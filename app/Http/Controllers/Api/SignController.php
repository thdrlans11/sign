<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\SignService;
use Illuminate\Http\Request;

class SignController extends Controller
{
    private $SignService; 
    
    public function __construct()
    {        
        $this->SignService = new SignService();
    }

    public function list(Request $request)
    {   
        return $this->SignService->list($request);
    }

    public function file(Request $request)
    {   
        return $this->SignService->file($request);
    }

    public function fileDel(Request $request)
    {
        return $this->SignService->fileDel($request);
    }

    public function signView(Request $request)
    {
        return $this->SignService->signView($request);
    }
}
