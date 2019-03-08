<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function root()
    {
        return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
    }

    public function dashboard()
    {
        return view('jarboe::layouts.main');
    }
}
