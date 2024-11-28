<?php

namespace App\Services;

use App\Models\Board;
use Illuminate\Http\Request;

/**
 * Class MainService
 * @package App\Services
 */
class MainService
{
    public function data()
    {
        //팝업 공지사항
        $boardPopups = Board::where('code', 'notice')->where('hide','N')->where('popup','Y');
        $boardPopups->whereHas('popupTarget', function($query) {
            $query->where('startdate', '<=', date('Y-m-d'))->where('enddate', '>=', date('Y-m-d'));
        });
        $boardPopups = $boardPopups->orderByDesc('created_at')->get();

        //공지사항 리스트
        $notices = Board::where('code', 'notice')->where('hide','N')->where('main','Y')->orderByDesc('created_at')->get();

        $data['boardPopups'] = $boardPopups;
        $data['notices'] = $notices;

        return $data;
    }

    public function popup(Request $request)
    {
        $board = Board::find( base64_decode($request->sid) );

        $data['board'] = $board;
        $data['popup'] = $board->popupTarget;

        return $data;
    }
}
