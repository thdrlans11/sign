<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {        
        view()->share([
            'wrapClass' => 'intro',
        ]);
    }

    public function main()
    {   
        return view('admin.main');
    }
}
