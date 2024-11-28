<?php

namespace App\Services\Admin\Member;

use App\Models\User;
use App\Services\dbService;
use App\Services\Util\ExcelService;
use Illuminate\Http\Request;

/**
 * Class MemberService
 * @package App\Services
 */
class MemberService extends dbService
{
    public function list(Request $request)
    {
        $lists = User::where('out_request','N')->whereIn('user_level',['1','4'])->orderByDesc('reg_date');

        foreach( $request->all() as $key => $val ){
            if( ( $key == 'user_id' || $key == 'name_kr' || $key == 'office_name' )  && $val ){
                $lists->where($key, 'LIKE', '%'.$val.'%');        
            }
        }

        if ($request->excel) {
            $cntQuery = clone $lists;
            return (new ExcelService())->memberExcel($lists, $cntQuery->count());
        }

        $lists = $lists->paginate('50')->appends($request->query());
        $lists = setListSeq($lists);
        $data['lists'] = $lists;

        return $data;
    }

    public function dbChange(Request $request)
    {   
        $this->transaction();

        try {
            $user = User::find(base64_decode($request->sid));            
            $user[$request->field] = $request->value;
            $user->save();

            $this->dbCommit($msg ?? '관리자 정회원 권한 변경'); 
            
            return 'success';

        } catch (\Exception $e) {

            return $this->dbRollback($e, 'ajax');

        }
    }
}
