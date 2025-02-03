<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 메인 페이지
Route::controller(\App\Http\Controllers\MainController::class)->group(function() {
    Route::get('/', 'main')->name('main');
});;

// 로그인
Route::middleware('guest')->prefix('auth')->controller(\App\Http\Controllers\AuthController::class)->group(function() {
    Route::post('/', 'loginProcess')->name('loginProcess');
});

// 로그아웃
Route::middleware('auth:admin,client')->prefix('auth')->controller(\App\Http\Controllers\AuthController::class)->group(function() {
    Route::get('logout', 'logoutProcess')->name('logoutProcess');
});

//행사 관리
Route::middleware('authAdmin')->prefix('event')->controller(\App\Http\Controllers\Event\EventController::class)->group(function() {
    Route::get('/', 'list')->name('event.list');
    Route::get('/form/{sid?}', 'form')->name('event.form');
    Route::post('/upsert/{sid?}', 'upsert')->name('event.upsert');
    Route::post('/dbChange', 'dbChange')->name('event.dbChange');
});

//명단 관리
Route::middleware('authClient')->prefix('roster/{code}')->controller(\App\Http\Controllers\Roster\RosterController::class)->group(function() {
    Route::get('/', 'list')->name('roster.list');
    Route::get('/form/{sid?}', 'form')->name('roster.form');
    Route::post('/upsert/{sid?}', 'upsert')->name('roster.upsert');
    Route::get('/fileForm/{esid}', 'fileForm')->name('roster.fileForm');
    Route::post('/fileUpsert/{esid}', 'fileUpsert')->name('roster.fileUpsert');
    Route::post('/dbChange', 'dbChange')->name('roster.dbChange');
    Route::get('/memo/{sid}', 'memoForm')->name('roster.memoForm');
    Route::post('/memo/{sid}', 'memo')->name('roster.memo');
    Route::get('/excelUpload', 'excelUploadForm')->name('roster.excelUploadForm');
    Route::post('/excelUpload', 'excelUploadUpsert')->name('roster.excelUploadUpsert');
});

Route::controller(\App\Http\Controllers\Controller::class)->prefix('common')->group(function () {       
    /*
     * File Download URL
     * type => only: 단일, zip: 일괄다운(zip)
     * tbl => 테이블
     * sid => sid 값 enCryptString(sid) 로 암호화해서 전송
     */
    Route::get('fileDownload/{type}/{tbl}/{sid}', 'fileDownload')->where('type', 'only|zip')->name("download");
    Route::post('/tinyUpload', 'tinyUpload')->name("tinyUpload");
    Route::post('/plUpload', 'plUpload')->name("plUpload");
});