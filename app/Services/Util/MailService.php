<?php

namespace App\Services\Util;

use App\Services\dbService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class MailService
 * @package App\Services
 */
class MailService extends dbService
{
    public $receiver_name = "";
    public $receiver_email = "";
    public $sender_name = "";
    public $sender_email = "";
    public $subject = "";
    public $body = "";

    public function makeMail($data, $kind, $mode = null, $judgeGubun = null)
    {
        $this->sender_name = config('site.common.info.siteName');
        $this->sender_email = config('site.common.info.email');

        switch($kind){
            case 'applyComplete' :
                $this->receiver_name = $data->name_kr;
                if( $_SERVER['REMOTE_ADDR'] == "218.235.94.223" ){
                    $this->receiver_email = 'km@m2community.co.kr';     
                }else{
                    $this->receiver_email = $data->email;     
                }
                $this->subject = '['.config('site.common.info.siteName').'] 급성 뇌졸중 인증의 접수가 완료되었습니다.';        
                $this->body = view('templetes.applyMail', ['apply'=>$data, 'kind'=>$kind])->render();
                break;
            case 'applyPayComplete' :
                $this->receiver_name = $data->name_kr;
                $this->receiver_email = $data->email;     
                $this->subject = '['.config('site.common.info.siteName').'] 급성 뇌졸중 인증의 접수 비용 결제가 완료되었습니다.';        
                $this->body = view('templetes.applyMail', ['apply'=>$data, 'kind'=>$kind])->render();
                break;  
            case 'applyRefund' :
                $this->receiver_name = $data->name_kr;
                $this->receiver_email = $data->email;     
                $this->subject = '['.config('site.common.info.siteName').'] 급성 뇌졸중 인증의 접수가 취소되었습니다.';        
                $this->body = view('templetes.applyRefundMail', ['apply'=>$data, 'mode'=>'ing'])->render();
                break;      
            case 'applyRefundCom' :
                $this->receiver_name = $data->name_kr;
                $this->receiver_email = $data->email;     
                $this->subject = '['.config('site.common.info.siteName').'] 급성 뇌졸중 인증의 응시료 환불이 완료되었습니다.';        
                $this->body = view('templetes.applyRefundMail', ['apply'=>$data, 'mode'=>'com'])->render();
                break;
            case 'appointmentMail' :
                $this->receiver_name = $data->name;
                $this->receiver_email = $data->email ?? $data->user->email;     
                $this->subject = '['.config('site.common.info.siteName').'] 급성뇌졸중인증의 자격 심사 의뢰';        
                
                if( $mode == 'preview' ){
                    return view('templetes.appointmentMail', ['appointment'=>$data, 'judgeGubun'=>$judgeGubun])->render();
                }

                $this->body = view('templetes.appointmentMail', ['appointment'=>$data, 'judgeGubun'=>$judgeGubun])->render();
                break;        
        }

        $this->wiseuSend($this);
        
        if( $kind == 'applyRefund' || $kind == 'applyRefundCom' ){
            $this->receiver_name = config('site.common.info')['name'];
            $this->receiver_email = config('site.common.info')['email2'];
            $this->wiseuSend($this);
        }
    }

    private function wiseuSend($mailData)
    {   
        $now = Carbon::now();
        $seq = $now->timestamp . $now->micro;

        $NVREALTIMEACCEPT = [
            'ECARE_NO' => config('site.common.info.ecareNum'),
            'RECEIVER_ID' => $seq,
            'CHANNEL' => 'M',
            'SEQ' => $seq,
            'REQ_DT' => $now->format('Ymd'),
            'REQ_TM' => $now->format('His'),
            'TMPL_TYPE' => 'T',
            'RECEIVER_NM' => $mailData->receiver_name,
            'RECEIVER' => $mailData->receiver_email,
            'SENDER_NM' => $mailData->sender_name,
            'SENDER' => $mailData->sender_email,
            'SUBJECT' => $mailData->subject,
            'SEND_FG' => 'R',
            'DATA_CNT' => 1,
        ];

        $NVREALTIMEACCEPTDATA = [
            'SEQ' => $seq,
            'DATA_SEQ' => 1,
            'ATTACH_YN' => 'N',
            'DATA' => $mailData->body,
        ];
        
        DB::connection('wiseu')->table('NVREALTIMEACCEPT')->insert($NVREALTIMEACCEPT);
        DB::connection('wiseu')->table('NVREALTIMEACCEPTDATA')->insert($NVREALTIMEACCEPTDATA);
    }
}
