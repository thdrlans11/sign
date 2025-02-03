<?php

namespace App\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use App\Services\Roster\RosterService;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    private $RosterService;

    public function __construct()
    {
        $this->RosterService = new RosterService();
        view()->share(['mainNum' => '1']);
    }

    public function list(Request $request)
    {
        return view('roster.list')->with( $this->RosterService->list($request) );
    }

    public function form(Request $request)
    {
        return view('roster.form')->with( $this->RosterService->form($request) );
    }

    public function upsert(Request $request)
    {
        return $this->RosterService->upsert($request);
    }

    public function fileForm(Request $request)
    {
        return view('roster.fileForm')->with( $this->RosterService->fileForm($request) );
    }

    public function fileUpsert(Request $request)
    {
        return $this->RosterService->fileUpsert($request);
    }

    public function dbChange(Request $request)
    {
        return $this->RosterService->dbChange($request);
    }

    public function memoForm(Request $request)
    {
        return view('roster.memo')->with( $this->RosterService->memoForm($request) );
    }

    public function memo(Request $request)
    {
        return $this->RosterService->memo($request);
    }

    public function excelUploadForm(Request $request)
    {
        return view('roster.excelForm')->with( $this->RosterService->excelUploadForm($request) );
    }

    public function excelUploadUpsert(Request $request)
    {
        return $this->RosterService->excelUploadUpsert($request);
    }
}
