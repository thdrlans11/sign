<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Member\LoginService;

class LoginController extends Controller
{
    private $LoginService;

    public function __construct()
    {
        $this->LoginService = new LoginService();
        view()->share(['mainNum' => '6' ]);
    }

    public function loginShow()
    {
        return view('member.login');
    }

    public function loginProcess(Request $request)
    {
        return $this->LoginService->login($request);
    }

    public function logoutProcess()
    {
        return $this->LoginService->logout();
    }
}
