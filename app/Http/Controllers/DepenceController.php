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

        $members = $colocation->members()->wherePivot('status','accepted')->wherePivot('left_at', null)->get();
        $this->authorizeColocationAccess($request->user(), $colocation);

        $month = $request->query('month');
        $year  = $request->query('year');

        $depencesQuery = $colocation->depences()
            ->with(['payer', 'category'])
            ->latest('date');

        if ($month && $year) {
            $depencesQuery->whereMonth('date', $month)->whereYear('date', $year);
        }

        $depences = $depencesQuery->get();

        $byMember = $depences->groupBy('payer_id')->map(fn($items) => $items->sum('amount'));
        $byCategory = $depences->groupBy('category_id')->map(fn($items) => $items->sum('amount'));

        [$balances, $settlements] = $this->computeBalancesAndSettlements($colocation, $depences);

        $categories = $colocation->categories()->orderBy('name')->get();

        $acceptedMembers = $colocation->members()
        ->wherePivot('status', 'accepted')
        ->wherePivot('left_at', null)
        ->get();

        $owner = $colocation->owner()->first();

        $members = $acceptedMembers
            ->push($owner)
            ->unique('id')
            ->values();

         $excludedIds = $members->pluck('id')->toArray(); // membres actuels + owner

        $availableUsers = User::whereNotIn('id', $excludedIds)
            ->orderBy('email')
            ->get(['id','email']);

        return view('depences.index', compact(
            'colocation','depences','categories','month','year','byMember','byCategory','balances','settlements','members','availableUsers',
        ));

    }



    public function store(StoreDepenceRequest $request, Colocation $colocation)
    {
        $this->authorizeColocationAccess($request->user(), $colocation);

        $categoryId = $request->validated()['category_id'];

        $belongs = $colocation->categories()->where('id', $categoryId)->exists();
        if (!$belongs) {
            return back()->with('error', "Catégorie invalide pour cette colocation.");
        }

        Depence::create([
            'title' => $request->validated()['title'],
            'amount' => $request->validated()['amount'],
            'date' => $request->validated()['date'],
            'payer_id' => $request->user()->id,
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

    private function computeBalancesAndSettlements(Colocation $colocation, $depences): array
    {
        $members = $colocation->members()
            ->wherePivot('status', 'accepted')
            ->wherePivot('left_at', null)
            ->get();

        $count = $members->count();
        if ($count === 0) return [collect(), []];

        $total = (float) $depences->sum('amount');
        $share = $total / $count;

        $paid = $depences->groupBy('payer_id')->map(fn($items) => (float) $items->sum('amount'));

        $balances = $members->mapWithKeys(function ($m) use ($paid, $share) {
            $p = (float) ($paid[$m->id] ?? 0);
            return [$m->id => round($p - $share, 2)];
        });

        $creditors = [];
        $debtors = [];

        foreach ($balances as $uid => $bal) {
            if ($bal > 0) $creditors[] = ['id' => $uid, 'amount' => $bal];
            if ($bal < 0) $debtors[] = ['id' => $uid, 'amount' => -$bal];
        }

        $settlements = [];
        $i = 0; $j = 0;

        while ($i < count($debtors) && $j < count($creditors)) {
            $pay = min($debtors[$i]['amount'], $creditors[$j]['amount']);
            $pay = round($pay, 2);

            if ($pay > 0) {
                $settlements[] = [
                    'from' => $debtors[$i]['id'],
                    'to' => $creditors[$j]['id'],
                    'amount' => $pay,
                ];
            }

            $debtors[$i]['amount'] = round($debtors[$i]['amount'] - $pay, 2);
            $creditors[$j]['amount'] = round($creditors[$j]['amount'] - $pay, 2);

            if ($debtors[$i]['amount'] <= 0.00001) $i++;
            if ($creditors[$j]['amount'] <= 0.00001) $j++;
        }

        return [$balances, $settlements];
    }
}