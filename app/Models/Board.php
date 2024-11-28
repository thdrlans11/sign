<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'sid';

    //업데이트 불가능하게 한다.
    protected $guarded = [
        'sid'
    ];

    //여기에 필드를 넣으면 carbon 객체로 인식
    protected $dates = [
        'sdate',
        'edate'
    ];

    public function files(){
        return $this->hasMany(BoardFile::class, 'bsid');
    }
    
    public function popupTarget(){
        return $this->hasOne(BoardPopup::class, 'bsid');
    }

    public function setByPost($data){

        //수정이 아닐경우
        if( !$this->sid ){
            $this->code = $data['code'];        
            $this->id = auth('web')->user()->user_id;
            
        }

        //답글일경우
        if( $data->parent_sid ){
            $this->fid = $data->parent_sid;
            $this->thread = 'AA';
        }

        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->category = $data['category'];
        $this->subject = $data['subject'];
        $this->content = $data['content'];
        $this->linkurl = $data['linkurl'];

        //캘린더형
        if( $data['code'] == "schedule" ){
            
            $this->name = auth('web')->user()->name_kr;
            $this->email = auth('web')->user()->email;
            $this->date_type = $data['date_type'];
            $this->sdate = $data['sdate'];
            $this->edate = $data['edate'];
            $this->place = $data['place'];
            $this->sponsor = $data['sponsor'];
            $this->fee = $data['fee'];
            $this->inquiry = $data['inquiry'];

        }

        $this->notice = $data['notice'] ?? 'N';
        $this->popup = $data['popup'] ?? 'N';
        $this->main = $data['main'] ?? 'N';
        $this->hide = $data['hide'] ?? 'N';
        $this->ip = $data->getClientIp();
        
    }

    protected static function booted(){
        static::deleting(function(Board $board){
            foreach( $board->files ?? [] as $key => $file ){
                $file->delete();
            }
            $board->popupTarget()->delete();
        });
    }
}
