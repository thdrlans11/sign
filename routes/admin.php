<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function(){

    //회원 관리
    Route::prefix('member')->controller(\App\Http\Controllers\Admin\Member\MemberController::class)->group(function() {
        Route::get('/', 'list')->name('admin.member');
        Route::post('/dbChange', 'dbChange')->name('admin.member.dbChange');
        Route::get('/excel', 'excel')->name('admin.member.excel');

    });  
	
	//문자 발송
    Route::prefix('sms')->controller(\App\Http\Controllers\Admin\Sms\SmsController::class)->group(function () {
        Route::get('/send', 'index')->name('admin.sms.send.index');
        Route::post('/send', 'send')->name('admin.sms.send.send');
        Route::get('sample_bulk', 'sampleBulk')->name('admin.sms.send.sample_bulk');
        Route::post('bulk', 'bulk')->name('admin.sms.send.bulk');

        Route::prefix('result/{sms_type}/{sms_date}')->group(function () {
            Route::get('', 'result')->name('admin.sms.result.index');
            Route::get('show/', 'show')->name('admin.sms.result.show');
            Route::get('download', 'downloadList')->name('admin.sms.result.download.list');
            Route::get('show/download', 'downloadDetail')->name('admin.sms.result.download.detail');
        });

        Route::get('cost/{sms_date}', 'cost')->name('admin.sms.cost');
    });

}); 

require __DIR__.'/common.php';
?>