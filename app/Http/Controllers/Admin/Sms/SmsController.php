<?php

namespace App\Http\Controllers\Admin\Sms;

use App\Http\Controllers\Controller;
use App\Services\Admin\Sms\SmsService;
use App\Services\Util\ExcelService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    private $SmsService;

    public function __construct()
    {
        $this->SmsService = new SmsService();
        view()->share('mainNum', '7');
    }

    public function index(Request $request)
    {      
        return view('admin.sms.send')->with( $this->SmsService->index($request) );
    }

    public function sampleBulk(Request $request)
    {
        return (new ExcelService())->smsSampleBulkExcel($request->with_message);
    }

    public function bulk(Request $request)
    {
        return $this->SmsService->bulk($request);
    }

    public function send(Request $request)
    {
        return $this->SmsService->send($request);        
    }

    public function result(Request $request)
    {
        return view('admin.sms.result.index')->with( $this->SmsService->result($request) );        
    }

    public function downloadList(Request $request)
    {
        $request->merge(['excel' => true]);
        return $this->SmsService->result($request);
    }

    public function show(Request $request)
    {
        return view('admin.sms.result.show')->with( $this->SmsService->show($request) );        
    }

    public function downloadDetail(Request $request)
    {
        $request->merge(['excel' => true]);
        return $this->SmsService->show($request);
    }
    
    public function cost(Request $request)
    {
        return view('admin.sms.cost')->with( $this->SmsService->cost($request) );   
    }
}
