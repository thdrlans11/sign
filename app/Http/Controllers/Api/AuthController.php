<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $AuthService;    

    public function __construct()
    {        
        $this->AuthService = new AuthService();
    }

    public function auth(Request $request)
    {   
        return $this->AuthService->auth($request);
    }

    public function logout(Request $request)
    {   
        return $this->AuthService->logout($request);
    }
}
