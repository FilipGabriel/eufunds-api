<?php

namespace Themes\Appfront\Http\Controllers;

class CookieBarController
{
    public function destroy()
    {
        $cookie = cookie()->forever('show_cookie_bar', false);

        return response('')->withCookie($cookie);
    }
}
