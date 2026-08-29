<?php

namespace App\Http\Controllers;

use App\Models\ArticleType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleTypeController extends Controller
{
    /**
     * Display a listing of article types.
     */
    public function index()
    {
        $articleTypes = ArticleType::orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.article-types.index', compact('articleTypes'));
    }

    /**
     * Show the form for creating a new article type.
     */
    public function create()
    {
        return view('admin.article-types.create');
    }

    /**
     * Store a newly created article type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:article_types,name',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:article_types,code',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ArticleType::create($validated);

        return redirect()
            ->route('admin.article-types.index')
            ->with('success', 'Article type created successfully.');
    }

    /**
     * Display the specified article type.
     */
    public function show(ArticleType $articleType)
    {
        return view('admin.article-types.show', compact('articleType'));
    }

    /**
     * Show the form for editing an article type.
     */
    public function edit(ArticleType $articleType)
    {
        return view('admin.article-types.edit', compact('articleType'));
    }

    /**
     * Update the specified article type.
     */
    public function update(Request $request, ArticleType $articleType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('article_types', 'name')
                    ->ignore($articleType->id),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('article_types', 'code')
                    ->ignore($articleType->id),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $articleType->update($validated);

        return redirect()
            ->route('admin.article-types.index')
            ->with('success', 'Article type updated successfully.');
    }

    /**
     * Remove the specified article type.
     */
    public function destroy(ArticleType $articleType)
    {
        if ($articleType->manuscripts()->exists()) {
            return redirect()
                ->route('admin.article-types.index')
                ->with('error', 'This article type cannot be deleted because it is already used by manuscripts.');
        }

        $articleType->delete();

        return redirect()
            ->route('admin.article-types.index')
            ->with('success', 'Article type deleted successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(ArticleType $articleType)
    {
        $articleType->update([
            'is_active' => !$articleType->is_active,
        ]);

        return redirect()
            ->route('admin.article-types.index')
            ->with('success', 'Article type status updated successfully.');
    }
}