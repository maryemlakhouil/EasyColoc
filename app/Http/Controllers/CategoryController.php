<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Colocation;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request, Colocation $colocation)
    {
        // option: owner only
        if ($colocation->owner_id !== $request->user()->id) {
            abort(403);
        }

        Category::create([
            'name' => $request->validated()['name'],
            'colocation_id' => $colocation->id,
        ]);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function destroy(Category $category)
    {
        // option: owner only
        if ($category->colocation->owner_id !== auth()->id()) {
            abort(403);
        }

        $category->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
