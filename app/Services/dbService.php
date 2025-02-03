<?php

namespace App\Services;

use App\Models\QueryLog;
use Illuminate\Support\Facades\DB;

/**
 * Class dbService
 * @package App\Services
 */
class dbService
{

    protected function enableQueryLog(): void
    {
        DB::enableQueryLog();
    }

    protected function getQuery(): array
    {
        foreach (DB::getQueryLog() ?? [] as $query) {
            $sql = $query['query'];
            $bindings = $query['bindings'];

            $queryResult[] = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        }

        return $queryResult ?? [];
    }

    protected function transaction(): void
    {
        DB::beginTransaction();
        $this->enableQueryLog();
    }

    protected function dbCommit(string $subject): void
    {
        DB::commit();

        QueryLog::create([
            'u_sid' => auth('admin')->check() ? auth('admin')->user()->sid : ( auth('client')->check() ? 'client_'.auth('client')->user()->sid : 'app' ),
            'subject' => $subject,
            'query' => $this->getQuery(),
            'ip' => request()->ip(),
        ]);
    }

    protected function dbRollback($error, $mode = '')
    {
        DB::rollback();

        $errorInfo = [
            'Message' => $error->getMessage(),
            'Code' => $error->getCode(),
            'File' => $error->getFile(),
            'Line' => $error->getLine(),
            'Query' => $this->getQuery(),
            'Trace' => $error->getTrace(),
        ];

        QueryLog::create([
            'u_sid' => auth('admin')->check() ? auth('admin')->user()->sid : ( auth('client')->check() ? 'client_'.auth('client')->user()->sid : 'app' ),
            'subject' => 'DB RollBack',
            'content' => $errorInfo,
            'query' => $this->getQuery(),
            'ip' => request()->ip()
        ]);

        if( $mode == 'ajax' ){
            return 'error';
        }else if( $mode == 'api' ){
            return $this->apiResponse(300, [ 'message' => 'dbError', 'data' => null] );
        }else{
            return redirect()->back()->with('alert','시스템 오류가 있습니다. 관리자에 문의해주세요.');
        }
    }

    protected function apiResponse($code, $data = [])
    {
        $response = [
            'code' => $code,
        ];

        if (!empty($data)) {
            $response = array_merge($response, $data);
        }
        
        return response()->json($response);
    }
}
