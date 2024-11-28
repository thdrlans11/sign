<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mms extends Model
{

    protected $connection = 'sms';

    protected $table = 'MMS_MSG';

    protected $primaryKey = 'MSGKEY';

    public $timestamps = false;

    protected $dates = [
        'REQDATE',
        'SENTDATE',
        'RSLTDATE',
        'REPORTDATE',
        'TERMINATEDDATE',
    ];

    public function setMsgAttribute($value)
    {
        $this->attributes['MSG'] = iconv('UTF-8', 'EUC-KR', $value);
        $this->attributes['SUBJECT'] = mb_substr($this->attributes['MSG'], 0, 10) . '...';
    }

    public function getSubjectAttribute()
    {
        return iconv('EUC-KR', 'UTF-8', $this->attributes['SUBJECT']);
    }

    public function getMsgAttribute()
    {
        return iconv('EUC-KR', 'UTF-8', $this->attributes['MSG']);
    }

    public function statusText()
    {
        $status = $this->STATUS;

        $result = '';

        if ($status === '0' && $this->ETC1 === 'Y' && $this->RSLT === '') {
            $result = '예약 전송대기';
        } elseif ($status === '0') {
            $result = '전송대기';
        } elseif ($status === '1') {
            $result = '송신중';
        } elseif ($status === '2' || $status === '3') {
            $result_status_texts = [
                '1000' => '성공',
                '2000' => '포맷 관련 알 수 없는 오류 발생',
                '2001' => '주소(포맷) 에러',
                '2002' => 'Content-length 오류',
                '2003' => '형식 오류',
                '2004' => 'Message ID 오류 (중복, 부재)',
                '2005' => 'Head 내 각 필드의 부적절',
                '2006' => 'Body 내 각 필드의 부적절',
                '2007' => '지원하지 않는 미디어 존재',
                '3000' => 'MMS를 미 지원 단말',
                '3001' => '단말 수신용량 초과',
                '3002' => '전송 시간 초과',
                '3003' => '읽기 확인 미 지원 단말',
                '3004' => '전원 꺼짐',
                '3005' => '음영지역',
                '3006' => '기타',
                '4000' => '서버실패(프로세스 또는 시스템 에러)',
                '4001' => '인증실패',
                '4002' => '네트워크 에러 발생',
                '4003' => '서비스의 일시적인 에러',
                '5000' => '번호이동에러',
                '5001' => '선불발급 발송건수 초과',
                '9001' => '유효시간 초과',
                '9002' => '폰 넘버 에러',
                '9003' => '스팸 번호(스팸 테이블 사용시)',
                '9004' => '이통사에서 응답 없음',
                '9005' => '파일크기 오류',
                '9006' => '지원되지 않는 파일',
                '9007' => '파일오류',
            ];

            $result = isset($result_status_texts[$this->RSLT]) ? $result_status_texts[$this->RSLT] : '';
        }

        return $result;
    }
}
