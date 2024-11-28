<?php

namespace App\Http\Controllers;

use App\Services\MainService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    private $MainService;

    public function __construct()
    {        

        $this->MainService = new MainService();

        view()->share([
            'wrapClass' => 'main',
        ]);
    }

    public function main()
    {   
        return view('main');
        //return view('main')->with( $this->MainService->data() );
    }

    public function popup(Request $request)
    {
        return view('popup')->with( $this->MainService->popup($request) );
    }
}
