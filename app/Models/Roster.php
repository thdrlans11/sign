<?php

namespace App\Models;

use App\Services\CommonService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'sid';

    protected $guarded = [
        'sid'
    ];

    protected $dates = [
        'fee_upload_date',
        'agree_upload_date'
    ];      

    protected $casts = [
    ];

    public function setByPost($data)
    {
        if( !$data['sid'] ){
            $this->code = $data['code'];
        }
        $this->name = $data['name'];
        $this->affiliation = $data['affiliation'];
        $this->email = $data['email'];
    }

    protected static function booted(){
        static::deleting(function(Roster $roster){
            if( $roster->realfile_fee ){
                (new CommonService())->fileDeleteService($roster->realfile_fee);
            }
            if( $roster->realfile_agree ){
                (new CommonService())->fileDeleteService($roster->realfile_agree);
            }
        });
    }
}
