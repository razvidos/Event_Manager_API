<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class SinglePageController extends Controller
{
    public const INVALID_LOCALE_CODE = 400;

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('spa', [
            'auth_user' => Auth::user()
        ]);
    }
}
