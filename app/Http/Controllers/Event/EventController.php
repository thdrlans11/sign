<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Services\Event\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $EventService;

    public function __construct()
    {
        $this->EventService = new EventService();
        view()->share(['mainNum' => '1']);
    }

    public function list(Request $request)
    {
        return view('event.list')->with( $this->EventService->list($request) );
    }

    public function form(Request $request)
    {
        return view('event.form')->with( $this->EventService->form($request) );
    }

    public function upsert(Request $request)
    {
        return $this->EventService->upsert($request);
    }

    public function dbChange(Request $request)
    {
        return $this->EventService->dbChange($request);
    }
}
