<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;

class HomeController extends AdminController
{
    /**
     * Redirects to default Admin View
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        return redirect()->route('admin::dashboard');
    }
}
