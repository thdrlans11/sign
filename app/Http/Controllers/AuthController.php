<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $AuthService;

    public function __construct()
    {
        $this->AuthService = new AuthService();
    }

    public function loginProcess(Request $request)
    {
        return $this->AuthService->login($request);
    }

    public function logoutProcess()
    {
        return $this->AuthService->logout();
    }
}
