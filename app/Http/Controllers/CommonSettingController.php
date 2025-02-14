<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonSettingRequest;
use App\Models\CommonSetting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;

class CommonSettingController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        $commonSetting = CommonSetting::where('user_id',Auth::id())->first();

        return view('common_settings.index', compact('commonSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function store(CommonSettingRequest $request)
    {

        $commonSetting = CommonSetting::where('user_id', Auth::id())->first();

        if ($commonSetting !== null) {
            $commonSetting->update($request->validated());
        } else {
            $request->request->add(['user_id' => Auth::id()]);
            $commonSetting = CommonSetting::create($request->validated());
        }

        Toast::title(__('Settings Updated Successfully'))->autoDismiss(2);

        return view('common_settings.index', compact('commonSetting'));
    }

    public function getCommonSettings($account)
    {

        return CommonSetting::where('user_id', $account->user_id)->first();

    }
}
