<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailTemplateRequest;
use App\Models\EmailTemplate;
use App\Tables\EmailTemplates;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('templates.index', [
            'templates' => EmailTemplates::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTemplateRequest $request)
    {
        EmailTemplate::create($request->validated());

        Toast::title(__('New Template Created Successfully'))->autoDismiss(2);

        return to_route('templates.index');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $template)
    {
        if ($template->user_id != Auth::id()) {
            abort(404);
        }

        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTemplateRequest $request, EmailTemplate $template)
    {
        if ($template->user_id != Auth::id()) {
            abort(404);
        }

        $template->update($request->validated());

        Toast::title(__('Template Updated Successfully'))->autoDismiss(2);

        return to_route('templates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $template)
    {
        if ($template->user_id != Auth::id()) {
            abort(404);
        }

        $template->delete();

        Toast::title(__('Template Deleted Successfully'))->autoDismiss(2);

        return back();
    }

    public function getTemplates()
    {
        $templates = EmailTemplate::where('user_id', Auth::id())->pluck('text', 'name')->toArray();

        view()->share('templates', $templates);
    }

    public function getTemplateContent($templateId = null)
    {
        return $templateId ? EmailTemplate::findOrFail($templateId) : null;
    }
}
