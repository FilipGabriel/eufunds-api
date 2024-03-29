<?php

namespace Themes\Appfront\Http\Controllers\Admin;

use Modules\Admin\Ui\Facades\TabManager;
use Themes\Appfront\Http\Requests\SaveAppfrontRequest;

class AppfrontController
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $settings = setting()->all();
        $tabs = TabManager::get('appfront');

        return view('admin.appfront.edit', compact('settings', 'tabs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SaveAppfrontRequest $request)
    {
        setting($request->except('_token', '_method'));

        return back()->withSuccess(trans('admin::messages.resource_saved', ['resource' => trans('setting::settings.settings')]));
    }
}
