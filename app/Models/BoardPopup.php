<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardPopup extends Model
{
    use HasFactory;

    protected $primaryKey = 'sid';
    
    //업데이트 불가능하게 한다.
    protected $guarded = [
        'sid'
    ];

    //여기에 필드를 넣으면 carbon 객체로 인식
    protected $dates = [
    ];

    public function setByPost($data, $bsid){
        
        if( !$this->sid ){
            $this->code = $data['code'];
            $this->bsid = $bsid;
        }

        $this->skin = $data['popup_skin'];
        $this->popup_select = $data['popup_select'];
        $this->popup_content = $data['popup_content'];
        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->position_x = $data['position_x'];
        $this->position_y = $data['position_y'];
        $this->popup_detail = $data['popup_detail'];
        $this->popup_linkurl = $data['popup_linkurl'];
        $this->startdate = $data['popup_sdate'];
        $this->enddate = $data['popup_edate'];

    }
}
