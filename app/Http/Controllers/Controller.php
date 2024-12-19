<?php

namespace App\Http\Controllers;

use App\Services\CommonService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function tinyUpload(Request $request)
    {
        return [
            'location' => (new CommonService())->fileUploadService($request->file('file'), 'tinymce')['realfile'],
        ];
    }

    public function plUpload(Request $request)
    {
        return (new CommonService())->fileUploadService($request->file('file'), $request->directory);
    }

    public function fileDownload(Request $request)
    {
        return ($request->type === 'only')
            ? (new CommonService())->fileDownloadService($request)
            : (new CommonService())->zipDownloadService($request);
    }
}
