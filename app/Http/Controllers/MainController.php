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
        if( auth('admin')->check() ){
            return redirect()->route('event.list');
        }else if( auth('client')->check() ){
            return redirect()->route('roster.list', ['code'=>auth('client')->user()->code]);
        }
        return view('main');
    }

    public function popup(Request $request)
    {
        return view('popup')->with( $this->MainService->popup($request) );
    }
}
