<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use Illuminate\Http\Request;

class ManuscriptController extends Controller
{
    /**
     * Manuscript List
     */
    public function index()
    {
        abort_unless(
            auth()->user()->can('manuscript.view'),
            403
        );

        $manuscripts = Manuscript::latest()
            ->paginate(20);

        return view(
            'admin.manuscripts.index',
            compact('manuscripts')
        );
    }


    /**
     * Show Single Manuscript
     */
    public function show(Manuscript $manuscript)
    {
        abort_unless(
            auth()->user()->can('manuscript.view'),
            403
        );

        return view(
            'admin.manuscripts.show',
            compact('manuscript')
        );
    }
}