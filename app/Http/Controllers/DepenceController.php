<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepenceRequest;
use App\Models\Colocation;
use App\Models\Depence;
use Illuminate\Http\Request;
use App\Models\User;


class DepenceController extends Controller
{

public function index(Request $request, Colocation $colocation)
{
    // 1- accès colocation
    $this->authorizeColocationAccess($request->user(), $colocation);

    // 2- Membres actifs + owner 
    $acceptedMembers = $colocation->members()
        ->wherePivot('status', 'accepted')
        ->wherePivot('left_at', null)
        ->get();

    $owner = $colocation->owner()->first();

    $members = $acceptedMembers
        ->push($owner)
        ->filter()           
        ->unique('id')
        ->values();

    // 3 - Filtres mois 
    $month = $request->query('month');
    $year  = $request->query('year');

    // 4 - Charger les dépenses

    $depencesQuery = $colocation->depences()
        ->with(['payer', 'category'])
        ->latest('date');

    if ($month && $year) {
        $depencesQuery->whereMonth('date', $month)->whereYear('date', $year);
    }

    $depences = $depencesQuery->get();

    // 5 - Catégories 
    $categories = $colocation->categories()->orderBy('name')->get();

    // 6- Users disponibles pour invitation 

    $excludedIds = $members->pluck('id')->toArray();

    $availableUsers = User::whereNotIn('id', $excludedIds)
        ->orderBy('email')
        ->get(['id', 'email']);

    // 7 - Dépenses par membre
    $byMember = $depences->groupBy('payer_id')->map(fn ($items) => round((float) $items->sum('amount'), 2));
    $byCategory = $depences->groupBy('category_id')->map(fn ($items) => round((float) $items->sum('amount'), 2));

    // 8-  Total / part individuelle
    $total = round((float) $depences->sum('amount'), 2);
    $memberCount = $members->count();
    $share = $memberCount > 0 ? round($total / $memberCount, 2) : 0.0;

    // 9 -Total payé par membre
    $paidByMember = $depences->groupBy('payer_id')
        ->map(fn ($items) => round((float) $items->sum('amount'), 2));

    // 10 Balance = payé - part
    $balances = $members->mapWithKeys(function ($m) use ($paidByMember, $share) {
        $paid = (float) ($paidByMember[$m->id] ?? 0);
        return [$m->id => round($paid - $share, 2)];
    });

    // 11- Qui doit à qui 
    $creditors = [];
    $debtors = [];

    foreach ($balances as $userId => $bal) {
        if ($bal > 0) $creditors[] = ['id' => $userId, 'montant' => $bal];
        if ($bal < 0) $debtors[] = ['id' => $userId, 'montant' => -$bal];
    }

    $settlements = [];
    $i = 0;
    $j = 0;

    while ($i < count($debtors) && $j < count($creditors)) {
        $pay = min($debtors[$i]['montant'], $creditors[$j]['montant']);
        $pay = round($pay, 2);

        if ($pay > 0) {
            $settlements[] = [
                'from' => $debtors[$i]['id'],
                'to' => $creditors[$j]['id'],
                'montant' => $pay,
            ];
        }

        $debtors[$i]['montant'] = round($debtors[$i]['montant'] - $pay, 2);
        $creditors[$j]['montant'] = round($creditors[$j]['montant'] - $pay, 2);

        if ($debtors[$i]['montant'] <= 0.00001) $i++;
        if ($creditors[$j]['montant'] <= 0.00001) $j++;
    }
    $openSettlements = \App\Models\Regle::where('colocation_id', $colocation->id)
        ->whereNull('paid_at')
        ->with(['fromUser','toUser'])
        ->get();
    
    return view('depences.index', compact(
        'colocation',
        'depences',
        'categories',
        'month',
        'year',
        'byMember',
        'byCategory',
        'members',
        'availableUsers',
        'total',
        'share',
        'paidByMember',
        'balances',
        'settlements',
        'openSettlements'
    ));
}


    public function store(StoreDepenceRequest $request, Colocation $colocation)
    {
        $this->authorizeColocationAccess($request->user(), $colocation);

        $categoryId = $request->validated()['category_id'];
        $payerId = $request->validated()['payer_id'];

        // Catégorie appartient à la colocation
        $belongs = $colocation->categories()->where('id', $categoryId)->exists();
        if (!$belongs) {
            return back()->with('error', "Catégorie invalide pour cette colocation.");
        }

        // Payeur doit être membre accepté OU owner
        $isMember = $colocation->members()
            ->where('users.id', $payerId)
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->exists()
            || $payerId === $colocation->owner_id;

        if (!$isMember) {
            return back()->with('error', "Le payeur doit être un membre accepté ou l'owner.");
        }

        Depence::create([
            'title' => $request->validated()['title'],
            'amount' => $request->validated()['amount'], 
            'date' => $request->validated()['date'],
            'payer_id' => $payerId,
            'colocation_id' => $colocation->id,
            'category_id' => $categoryId,
        ]);

        return back()->with('success', 'Dépense ajoutée.');
    }

    public function destroy(Request $request, Depence $depence)
    {
        $this->authorizeColocationAccess($request->user(), $depence->colocation);

        if ($depence->payer_id !== $request->user()->id && $depence->colocation->owner_id !== $request->user()->id) {
            abort(403);
        }

        $depence->delete();

        return back()->with('success', 'Dépense supprimée.');
    }

    private function authorizeColocationAccess($user, Colocation $colocation): void
    {
        if ($colocation->owner_id === $user->id) {
            return;
        }

        $isMember = $colocation->members()
            ->where('users.id', $user->id)
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->exists();

        if (!$isMember) {
            abort(403);
        }
    }

    
    private function syncSettlements(Colocation $colocation, array $settlements): void
    {
        // on garde l'historique payé
        \App\Models\Regle::where('colocation_id', $colocation->id)
            ->whereNull('paid_at')
            ->delete();

        foreach ($settlements as $s) {
            \App\Models\Regle::create([
                'colocation_id' => $colocation->id,
                'from_user_id' => $s['from'],
                'to_user_id' => $s['to'],
                'montant' => $s['montant'],
            ]); 
        }
    }
}