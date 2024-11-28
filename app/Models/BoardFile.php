<?php

namespace App\Models;

use App\Services\CommonService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'sid';

    //업데이트 불가능하게 한다.
    protected $guarded = [
        'sid'
    ];

    public function setByPost($data, $bsid){

        for( $i=0; $i<$data['plupload_count']; $i++ ){
            $file = new BoardFile();
            $file->code = $data['code'];
            $file->bsid = $bsid;
            $file->filename = $data['plupload_'.$i.'_name'];
            $file->realfile = $data['plupload_'.$i.'_stored_path'];
            $file->save();
        }

    }

    protected static function booted(){
        static::deleting(function(BoardFile $boardFile){
            (new CommonService())->fileDeleteService($boardFile->realfile);
        });
    }
}
