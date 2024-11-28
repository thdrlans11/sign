<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Services\Board\BoardService;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    private $BoardService;
    private $code;

    public function __construct()
    {
        $this->BoardService = new BoardService();
        $this->code = request()->code;

        view()->share([
            'mainNum' => config('site.board')[$this->code]['MAIN_NUM'],
            'subNum' => config('site.board')[$this->code]['SUB_NUM']
        ]);
    }

    public function list(Request $request)
    {
        return view('board.'.config('site.board')[$this->code]['Skin'].'.list')->with( $this->BoardService->list($request) );
    }

    public function calendar(Request $request)
    {
        return view('board.'.config('site.board')[$this->code]['Skin'].'.calendar')->with( $this->BoardService->calendar($request) );
    }

    public function form(Request $request)
    {
        return view('board.'.config('site.board')[$this->code]['Skin'].'.form')->with( $this->BoardService->form($request) );
    }

    public function upsert(Request $request)
    {
        return $this->BoardService->upsert($request);
    }

    public function view(Request $request)
    {   
        return view('board.'.config('site.board')[$this->code]['Skin'].'.view')->with( $this->BoardService->view($request) );
    }

    public function delete(Request $request)
    {
        return $this->BoardService->delete($request);
    }

    public function dbChange(Request $request)
    {
        return $this->BoardService->dbChange($request);
    }

    public function popupPreview(Request $request){        
        return view('board.popup')->with(['data' => $request->all()]);
    }
    
}
