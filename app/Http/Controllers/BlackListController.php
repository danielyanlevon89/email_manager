<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlackListRequest;
use App\Models\BlackList;
use App\Tables\BlackLists;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;

class BlackListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('black_lists.index', [
            'blackLists' => BlackLists::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('black_lists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlackListRequest $request)
    {
        BlackList::create($request->validated());

        Toast::title(__('New Black List Item Created Successfully'))->autoDismiss(2);

        return to_route('black_lists.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlackList $blackList)
    {
        if ($blackList->user_id != Auth::id()) {
            abort(404);
        }

        return view('black_lists.edit', compact('blackList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlackListRequest $request, BlackList $blackList)
    {
        if ($blackList->user_id != Auth::id()) {
            abort(404);
        }

        $blackList->update($request->validated());

        Toast::title(__('Black List Item Updated Successfully'))->autoDismiss(2);

        return to_route('black_lists.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlackList $blackList)
    {
        if ($blackList->user_id != Auth::id()) {
            abort(404);
        }

        $blackList->delete();

        Toast::title(__('Black List Item Deleted Successfully'))->autoDismiss(2);

        return back();
    }
}
