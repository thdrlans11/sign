<?php

namespace App\Services\Admin\Sms;

use App\Models\Appointment;
use App\Models\Mms;
use App\Models\Sms;
use App\Models\User;
use App\Services\CommonService;
use App\Services\Util\ExcelService;
use App\Services\Util\SmsSendService;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class SmsService
 * @package App\Services
 */
class SmsService
{
    public function index(Request $request)
    {
        $users = User::select(['user_id', 'name_kr', 'phone', 'user_level'])->orderBy('name_kr')->get();

        if( $request->listSid ){
            $appointments = Appointment::whereIn('sid', $request->listSid)->get();
        }
        
        $data['users'] = $users;
        $data['appointments'] = $appointments ?? [];
        $data['subNum'] = '1';

        return $data;
    }

    public function bulk(Request $request)
    {
        $with_message = (bool)$request->post('with_message');

        $file = (new CommonService())->fileUploadService($request->sms_file, 'sms');
        
        $excelRow = SimpleExcelReader::create(public_path($file['realfile']))->getRows();
        $recipients = $excelRow->toArray();  

        if ($with_message) {

            $group_seq = str_replace('.', '', microtime(true));
            $sms_date = now()->format('Ym');

            foreach ( $recipients as $key => $recipient ) {

                $recipient['message'] = trim($recipient['message']);
                $is_mms = mb_strlen($recipient['message'], 'euc-kr') > 80;

                if (!$is_mms) {
                    (new SmsSendService())->smsSend($recipient['message'], $recipient['mobile'], $recipient['sender'], now(), $group_seq);
                } else {
                    (new SmsSendService())->mmsSend($recipient['message'], $recipient['mobile'], $recipient['sender'], now(), $group_seq);
                }
            }

            return redirect()->route('admin.sms.send.index');

        } else {

            $recipients_mobile = [];

            foreach ( $recipients as $key => $recipient ) {
                $recipients_mobile[$recipient['mobile']] = $recipient['mobile'];
            }

            return redirect()->route('admin.sms.send.index')->with('recipients', $recipients_mobile);
        } 
    }

    public function send(Request $request)
    {
        $recipients = $request->post('recipients', []);
        $message = $request->post('message');
        $sender = $request->post('sender');
        $reserve_flag = $request->post('reserve_flag', 'N');
        $reserve_date = ($reserve_flag === 'Y') ? $request->post('reserve_date') . ':00' : '0000-00-00 00:00:00';
        $group_seq = str_replace('.', '', microtime(true));
        $is_mms = mb_strlen($message, 'euc-kr') > 80;

        if (empty($recipients)) {
            return redirect()->back()->with('alert', '받는 사람 목록을 추가하세요.');
        } elseif (($message === null || $message === '')) {
            return redirect()->back()->with('alert', '메시지 내용을 입력하세요.');
        } elseif (($sender === null || $sender === '')) {
            return redirect()->back()->with('alert', '보내는 사람을 입력하세요.');
        } elseif ($reserve_flag === 'Y' && $reserve_date === ':00') {
            return redirect()->back()->with('alert', '예약 발송일을 입력하세요.');
        }

        foreach ($recipients as $recipient) {
            if (!$is_mms) {
                (new SmsSendService())->smsSend($message, $recipient, $sender, now(), $group_seq, $reserve_flag, $reserve_date);
            } else {
                (new SmsSendService())->mmsSend($message, $recipient, $sender, now(), $group_seq, $reserve_flag, $reserve_date);
            }
        }

        return redirect()->route('admin.sms.result.show', ['sms_type' => $is_mms ? 'lms' : 'sms', 'sms_date' => now()->format('Ym'), 'group_seq' => $group_seq]);
    }

    public function result(Request $request)
    {
        $sms_type = $request->route()->parameter('sms_type');
        $sms_date = $request->route()->parameter('sms_date');
        $per_page = (int)$request->query('per_page', 15);

        if ($sms_type === 'sms') {
            $results = new Sms();
            $results = $results->setTable('smscli_tbl_' . $sms_date)
                ->select([
                    '*',
                    DB::raw('COUNT(seq) AS group_count'),
                    DB::raw('COUNT(IF(`status` = \'delivered\', `status`, NULL)) AS delivered_count')
                ])
                ->where('siteurl', config('site.common')['sms']['domain'])
                ->groupBy('group_seq')
                ->orderByDesc('seq');

            if ($sms_date === now()->format('Ym')) {
                $pending = new Sms();
                $pending = $pending->setTable('smscli_tbl')
                    ->select([
                        '*',
                        DB::raw('COUNT(seq) AS group_count'),
                        DB::raw('COUNT(IF(`status` = \'delivered\', `status`, NULL)) AS delivered_count')
                    ])
                    ->where('siteurl', config('site.common')['sms']['domain'])
                    ->groupBy('group_seq')
                    ->orderByDesc('seq');

                $results = $results->unionAll($pending);

                if ($request->excel) {
                    $results = $results->orderByDesc('seq');
                    $cntQuery = clone $results;
                    return (new ExcelService())->smsResultExcel($results, $cntQuery->count(), 'sms');
                }        

            }

            $results = $results->orderByDesc('seq')->paginate($per_page)->appends($request->query());

        } elseif ($sms_type === 'lms') {
            $results = new Mms();
            $results = $results->setTable('MMS_LOG_' . $sms_date)
                ->select([
                    '*',
                    DB::raw('COUNT(MSGKEY) AS etc4_count'),
                    DB::raw('COUNT(IF(`RSLT` = \'1000\' AND `status` IN(\'2\', \'3\'), `RSLT`, NULL)) AS sent_count')
                ])
                ->where('ETC2', config('site.common')['sms']['domain'])
                ->groupBy('ETC4')
                ->orderByDesc('MSGKEY');

            if ($sms_date === now()->format('Ym')) {
                $pending = new Mms();
                $pending = $pending->setTable('MMS_MSG')
                    ->select([
                        '*',
                        DB::raw('COUNT(MSGKEY) AS etc4_count'),
                        DB::raw('COUNT(IF(`RSLT` = \'1000\' AND `status` IN(\'2\', \'3\'), `RSLT`, NULL)) AS sent_count')
                    ])
                    ->where('ETC2', config('site.common')['sms']['domain'])
                    ->groupBy('ETC4')
                    ->orderByDesc('MSGKEY');

                $results = $results->unionAll($pending);

                if ($request->excel) {
                    $results = $results->orderByDesc('MSGKEY');
                    $cntQuery = clone $results;
                    return (new ExcelService())->smsResultExcel($results, $cntQuery->count(), 'lms');
                }
            }

            $results = $results->orderByDesc('MSGKEY')->paginate($per_page)->appends($request->query());
        }

        $data['results'] = $results;
        $data['subNum'] = '2';

        return $data;
    }

    public function show(Request $request)
    {
        $sms_type = $request->route()->parameter('sms_type');
        $sms_date = $request->route()->parameter('sms_date');
        $group_seq = $request->query('group_seq');
        $per_page = (int)$request->query('per_page', 15);

        if ($sms_type === 'sms') {
            $results = new Sms();
            $results = $results->setTable('smscli_tbl_' . $sms_date)
                ->where('siteurl', config('site.common')['sms']['domain'])
                ->where('group_seq', $group_seq);

            if ($sms_date === now()->format('Ym')) {
                $pending = new Sms();
                $pending = $pending->setTable('smscli_tbl')
                    ->where('siteurl', config('site.common')['sms']['domain'])
                    ->where('group_seq', $group_seq);

                $results = $results->unionAll($pending);

                if ($request->excel) {
                    $results = $results->orderByDesc('seq');
                    $cntQuery = clone $results;
                    return (new ExcelService())->smsDetailExcel($results, $cntQuery->count(), 'sms');
                }
            }

            $results = $results->orderByDesc('seq')->paginate($per_page)->appends($request->query());

        } elseif ($sms_type === 'lms') {
            $results = new Mms();
            $results = $results->setTable('MMS_LOG_' . $sms_date)
                ->where('ETC2', config('site.common')['sms']['domain'])
                ->where('ETC4', $group_seq);

            if ($sms_date === now()->format('Ym')) {
                $pending = new Mms();
                $pending = $pending->setTable('MMS_MSG')
                    ->where('ETC2', config('site.common')['sms']['domain'])
                    ->where('ETC4', $group_seq);

                $results = $results->unionAll($pending);

                if ($request->excel) {
                    $results = $results->orderByDesc('MSGKEY');
                    $cntQuery = clone $results;
                    return (new ExcelService())->smsDetailExcel($results, $cntQuery->count(), 'lms');
                }
            }

            $results = $results->orderByDesc('MSGKEY')->paginate($per_page)->appends($request->query());
        }

        $data['group_seq'] = $group_seq;
        $data['results'] = $results;
        $data['subNum'] = '2';

        return $data;
    }

    public function cost(Request $request)
    {
        $sms_date = $request->route()->parameter('sms_date');

        $sms = new Sms();
        $sms = $sms->setTable('smscli_tbl' . ($sms_date ? '_' . $sms_date : ''))
            ->select([
                DB::raw('COUNT(seq) AS all_count'),
                DB::raw('COUNT(IF(`status` = \'delivered\', seq, null)) AS delivered_count'),
            ])
            ->where('siteurl', config('site.common')['sms']['domain'])
            ->first();

        $sms->all_count = (int)$sms->all_count;
        $sms->delivered_count = (int)$sms->delivered_count;

        if ($sms_date === Carbon::now()->format('Ym')) {
            $pending = new Sms();
            $pending = $pending->setTable('smscli_tbl')
                ->select([
                    DB::raw('COUNT(seq) AS all_count'),
                    DB::raw('COUNT(IF(`status` = \'delivered\', seq, null)) AS delivered_count'),
                ])
                ->where('siteurl', config('site.common')['sms']['domain'])
                ->first();

            $sms->all_count = $sms->all_count + (int)$pending->all_count;
            $sms->delivered_count = $sms->delivered_count + (int)$pending->delivered_count;
        }

        $lms = new Mms();
        $lms = $lms->setTable('MMS_LOG_' . $sms_date)
            ->select([
                DB::raw('COUNT(MSGKEY) AS all_count'),
                DB::raw('COUNT(IF(`RSLT` = \'1000\' AND `status` IN(\'2\', \'3\'), `RSLT`, NULL)) AS sent_count')
            ])
            ->where('ETC2', config('site.common')['sms']['domain'])
            ->first();

        $sms->all_count = (int)$sms->all_count;
        $sms->sent_count = (int)$sms->sent_count;

        if ($sms_date === Carbon::now()->format('Ym')) {
            $pending = new Sms();
            $pending = $pending->setTable('MMS_MSG')
                ->select([
                    DB::raw('COUNT(MSGKEY) AS all_count'),
                    DB::raw('COUNT(IF(`RSLT` = \'1000\' AND `status` IN(\'2\', \'3\'), `RSLT`, NULL)) AS sent_count')
                ])
                ->where('ETC2', config('site.common')['sms']['domain'])
                ->first();

            $sms->all_count = $sms->all_count + (int)$pending->all_count;
            $sms->sent_count = $sms->sent_count + (int)$pending->sent_count;
        }

        $data['sms_date'] = $sms_date;
        $data['sms'] = $sms;
        $data['lms'] = $lms;
        $data['subNum'] = '3';
        
        return $data;
    }
}
