<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{

    protected $connection = 'sms';
    
    protected $table = 'smscli_tbl';

    protected $primaryKey = 'seq';
    
    public $timestamps = false;

    protected $dates = [
        'signdate',
        'sendtime',
        'changeime',
        'reserve_date',
    ];

    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = iconv('UTF-8', 'EUC-KR', $value);
    }

    public function getBodyAttribute()
    {
        return iconv('EUC-KR', 'UTF-8', $this->attributes['body']);
    }
    
    public function getReserveDateAttribute()
    {
        if ($this->attributes['reserve_date'] === '0000-00-00 00:00:00') {
            return null;
        }
        
        return $this->asDateTime($this->attributes['reserve_date']);
    }

    public function statusText()
    {
        if ($this->status === null && $this->changetime === null) {
            return '전송대기';
        } elseif ($this->status === 'delivered') {
            return '전송성공';
        } elseif ($this->status === 'rejected') {
            return '실패 - 결변';
        } elseif ($this->status === 'expired') {
            return '실패 - 타임아웃';
        } elseif ((int)$this->status === 28) {
            return '실패 - 사전 미등록 발신번호 사용';
        } elseif ((int)$this->status === 29) {
            return '실패 - 전화번호 세칙 미준수 발신번호 사용';
        } elseif ((int)$this->status === 30) {
            return '실패 - 발신번호 변작으로 등록된 발신번호 사용';
        } elseif ((int)$this->status === 31) {
            return '실패 - 번호도용문자차단서비스에 가입된 발신번호 사용';
        } else {
            return '실패 - 알 수 없는 에러';
        }
    }
}
