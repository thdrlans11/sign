<?php

namespace App\Services;

use App\Models\BoardFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class CommonService
 * @package App\Services
 */
class CommonService
{
    private function filenameRegx(string $filename): string
    {
        // 파일명에 허용되지않는 특수문자 제거
        return preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", ' ', $filename);
    }

    public function fileUploadService($file, string $folder)
    {
        $directory = "uploads/" . $folder;

        $ext = $file->getClientOriginalExtension();
        $save_name = (now()->timestamp . '_' . Str::random(10) . '.' . $ext);

        return [
            'filename' => $this->filenameRegx($file->getClientOriginalName()),
            'realfile' => '/storage/' . $file->storeAs($directory, $save_name, 'public')
        ];
    }

    public function fileDeleteService(string $realfile)
    {
        if (File::exists(public_path($realfile))) {
            File::delete(public_path($realfile));
        }
    }

    public function fileDownloadService(Request $request)
    {
        $tbl = $request->tbl;
        $sid = $request->sid;

        switch ($tbl) {
            case 'boardFile':
                $boardFile = BoardFile::findOrFail(base64_decode($sid));
                $boardFile->increment('download');

                $data = ['realfile' => $boardFile->realfile, 'filename' => $boardFile->filename];
                break;
            default:
                return redirect()->back()->with('alert','잘못된 접근입니다.');
        }

        return (File::exists(public_path($data['realfile'])))
            ? response()->download(public_path($data['realfile']), $data['filename'])
            : redirect()->back()->with('alert','파일을 찾을 수 없습니다.');; // 파일 데이터가 없을경우
    }

    // public function zipDownloadService(Request $request)
    // {
    //     $tbl = $request->tbl;
    //     $sid = deCryptString($request->sid);

    //     switch ($tbl) {
    //         case 'board': // 게시판 pluplad 파일 일괄 다운로드
    //             $board = Board::findOrFail($sid);
    //             $board->files()->increment('download');

    //             $this->data = $this->makeZip("{$board->subject}.zip", $board->files, $request->password ?? null);
    //             break;

    //         default:
    //             return notFoundRedirect();
    //     }


    //     return (File::exists($this->data['realfile']))
    //         ? response()->download($this->data['realfile'], $this->data ['filename'])->deleteFileAfterSend(true)
    //         : errorRedirect('back', errorMsg('nFile')); // 파일 데이터가 없을경우
    // }
    
    // public function makeZip($filename, $fileData, $password = null)
    // {
    //     // Zip 파일을 저장할 디렉터리 경로
    //     $zipDirectory = storage_path('app/zipArchive');

    //     // 비밀번호가 있을경우
    //     if ($password) {
    //         $password = deCryptString($password);
    //     }

    //     // 폴더가 없을경우 생성
    //     if (!File::exists($zipDirectory)) {
    //         File::makeDirectory($zipDirectory, 0755, true);
    //     }

    //     // 특수문자 제거한 압축 파일명
    //     $zipFile['filename'] = $this->filenameRegx($filename);

    //     // 압축 파일 경로
    //     $zipFile['realfile'] = "{$zipDirectory}/{$zipFile['filename']}";

    //     // ZipArchive 인스턴스 생성
    //     $zip = new \ZipArchive();

    //     // zip 아카이브 생성 여부 확인
    //     if ($zip->open($zipFile['realfile'], \ZipArchive::CREATE) !== true) {
    //         return serverRedirect();
    //     }

    //     // 비밀 번호 있을경우 암호 설정
    //     if ($password) {
    //         $zip->setPassword($this->zipPassword());
    //     }

    //     // addFile ( 파일이 존재하는 경로, 저장될 이름 )
    //     foreach ($fileData ?? [] as $row) {
    //         $path = public_path($row->realfile);

    //         // 파일 있다면 추가
    //         if (File::exists($path)) {
    //             $zip->addFile($path, $row->filename);

    //             // 비밀번호 있을경우 암호화
    //             if ($password) {
    //                 $zip->setEncryptionName($path, \ZipArchive::EM_AES_256);
    //             }
    //         }
    //     }

    //     $zip->close();

    //     return $zipFile;
    // }
}
