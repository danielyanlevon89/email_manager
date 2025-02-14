<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkRequest;

use App\Models\Link;

use App\Services\BlackListService;
use App\Tables\Links;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('links.index', [
            'links' => Links::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LinkRequest $request)
    {
        Link::create($request->validated());

        Toast::title(__('New Link Created Successfully'))->autoDismiss(2);

        return to_route('links.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        if ($link->user_id != Auth::id()) {
            abort(404);
        }

        return view('links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LinkRequest $request, Link $link)
    {
        if ($link->user_id != Auth::id()) {
            abort(404);
        }

        $link->update($request->validated());

        Toast::title(__('Link Updated Successfully'))->autoDismiss(2);

        return to_route('links.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        if ($link->user_id != Auth::id()) {
            abort(404);
        }

        $link->delete();

        Toast::title(__('Link Deleted Successfully'))->autoDismiss(2);

        return back();
    }
}
