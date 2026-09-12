<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JournalPageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $journalPages = JournalPage::query()
            ->orderBy('menu_group')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(20);

        return view(
            'admin.journal-pages.index',
            compact('journalPages')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.journal-pages.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated =
            $this->validatePage($request);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = filled(
            $validated['slug'] ?? null
        )
            ? Str::slug(
                $validated['slug']
            )
            : Str::slug(
                $validated['title']
            );


        if (
            JournalPage::where(
                'slug',
                $slug
            )->exists()
        ) {

            return back()
                ->withErrors([
                    'slug' =>
                        'This page slug is already being used.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        $featuredImage = null;


        if (
            $request->hasFile(
                'featured_image'
            )
        ) {

            $featuredImage =
                $request
                    ->file(
                        'featured_image'
                    )
                    ->store(
                        'journal-pages',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        JournalPage::create([

            'title' =>
                $validated['title'],

            'slug' =>
                $slug,

            'menu_group' =>
                $validated['menu_group'],

            /*
            |--------------------------------------------------------------------------
            | Content is stored directly
            |--------------------------------------------------------------------------
            */

            'content' =>
                $validated['content'],

            'content_mode' =>
                $validated['content_mode'],

            'short_description' =>
                $validated['short_description']
                ?? null,

            'featured_image' =>
                $featuredImage,

            'show_in_menu' =>
                $request->boolean(
                    'show_in_menu'
                ),

            'show_on_homepage' =>
                $request->boolean(
                    'show_on_homepage'
                ),

            'sort_order' =>
                $validated['sort_order']
                ?? 0,

            'status' =>
                $validated['status'],

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.journal-pages.index'
            )
            ->with(
                'success',
                'Journal website page created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        JournalPage $journalPage
    ) {

        return view(
            'admin.journal-pages.edit',
            compact('journalPage')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        JournalPage $journalPage
    ) {

        $validated =
            $this->validatePage($request);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = filled(
            $validated['slug'] ?? null
        )
            ? Str::slug(
                $validated['slug']
            )
            : Str::slug(
                $validated['title']
            );


        $slugExists =
            JournalPage::query()
                ->where(
                    'slug',
                    $slug
                )
                ->where(
                    'id',
                    '!=',
                    $journalPage->id
                )
                ->exists();


        if ($slugExists) {

            return back()
                ->withErrors([
                    'slug' =>
                        'This page slug is already being used.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        $featuredImage =
            $journalPage->featured_image;


        if (
            $request->hasFile(
                'featured_image'
            )
        ) {

            if ($featuredImage) {

                Storage::disk('public')
                    ->delete(
                        $featuredImage
                    );
            }


            $featuredImage =
                $request
                    ->file(
                        'featured_image'
                    )
                    ->store(
                        'journal-pages',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $journalPage->update([

            'title' =>
                $validated['title'],

            'slug' =>
                $slug,

            'menu_group' =>
                $validated['menu_group'],

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |
            | Raw HTML is saved directly.
            |--------------------------------------------------------------------------
            */

            'content' =>
                $validated['content'],

            'content_mode' =>
                $validated['content_mode'],

            'short_description' =>
                $validated['short_description']
                ?? null,

            'featured_image' =>
                $featuredImage,

            'show_in_menu' =>
                $request->boolean(
                    'show_in_menu'
                ),

            'show_on_homepage' =>
                $request->boolean(
                    'show_on_homepage'
                ),

            'sort_order' =>
                $validated['sort_order']
                ?? 0,

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.journal-pages.index'
            )
            ->with(
                'success',
                'Journal website page updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        JournalPage $journalPage
    ) {

        if (
            $journalPage->featured_image
        ) {

            Storage::disk('public')
                ->delete(
                    $journalPage
                        ->featured_image
                );
        }


        $journalPage->delete();


        return redirect()
            ->route(
                'admin.journal-pages.index'
            )
            ->with(
                'success',
                'Journal website page deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validatePage(
        Request $request
    ): array {

        return $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],


            'menu_group' => [
                'required',

                Rule::in([
                    'about',
                    'editorial_board',
                    'journal',
                    'authors',
                    'reviewers',
                ]),
            ],


            'content' => [
                'required',
                'string',
            ],


            'content_mode' => [
                'required',

                Rule::in([
                    'visual',
                    'html',
                ]),
            ],


            'short_description' => [
                'nullable',
                'string',
            ],


            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],


            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],


            'status' => [
                'required',

                Rule::in([
                    'draft',
                    'published',
                    'inactive',
                ]),
            ],

        ]);
    }
}