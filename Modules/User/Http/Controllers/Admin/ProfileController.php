<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Admin\Ui\Facades\TabManager;

class ProfileController
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $tabs = TabManager::get('profile');

        return view('user::admin.profile.edit', compact('tabs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->bcryptPassword($request);

        auth()->user()->update($request->all());

        return back()->withSuccess(trans('admin::messages.resource_saved', [
            'resource' => trans('user::users.profile'),
        ]));
    }

    /**
     * Bcrypt user password.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function bcryptPassword($request)
    {
        if ($request->filled('password')) {
            return $request->merge(['password' => bcrypt($request->password)]);
        }

        unset($request['password']);
    }
}
