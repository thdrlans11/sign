<?php

namespace App\Services\Util;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class SmsSendService
 * @package App\Services
 */
class SmsSendService
{
    private static $originator = '106003001000';

    public static function smsSend($message, $to, $from = null, $send_time = null, $group_seq = null, $reserve_flag = 'N', $reserve_date = '0000-00-00 00:00:00')
    {
        $sms = [
            'siteurl' => config('site.common')['sms']['domain'],
            'originator' => static::$originator,
            'reserve_flag' => $reserve_flag,
            'reserve_date' => $reserve_date,
            'destination' => str_replace('-', '', $to),
            'callback' => str_replace('-', '', $from ?: config('site.common.sms.number')),
            'body' => iconv('UTF-8', 'EUC-KR', $message),
            'user_id' => Auth::check() ? Auth::id() : 'guest',
            'sendtime' => is_null($send_time) ? now() : $send_time,
            'group_seq' => is_null($group_seq) ? str_replace('.', '', microtime(true)) : $group_seq
        ];

        DB::connection('sms2')->table('smscli_tbl')->insert($sms);

    }

    public static function mmsSend($message, $to, $from = null, $send_time = null, $group_seq = null, $reserve_flag = 'N', $reserve_date = null)
    {
        $mms = [
            'ETC2' => config('site.common')['sms']['domain'],
            'ETC1' => $reserve_flag,
            'PHONE' => $to,
            'CALLBACK' => str_replace('-', '', $from ?: config('site.common.sms.number')),
            'MSG' => iconv('UTF-8', 'EUC-KR', $message),
            'FILE_CNT' => 0,
            'FILE_PATH1' => null,
            'EXPIRETIME' => 60 * 60 * 12,
            'ID' => Auth::check() ? Auth::id() : 'guest',
            'STATUS' => 0,
            'REQDATE' => ($reserve_flag === 'Y') ? $reserve_date : ($send_time ?: now()),
            'ETC4' => is_null($group_seq) ? str_replace('.', '', microtime(true)) : $group_seq,
            'TYPE' => 0,
        ];

        DB::connection('sms2')->table('MMS_MSG')->insert($mms);
    }
}
