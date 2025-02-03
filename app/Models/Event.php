<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Event extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'sid';

    protected $guarded = [
        'sid'
    ];

    protected $dates = [
    ];      

    protected $casts = [
    ];

    public function rosters()
    {
        return $this->hasMany(Roster::class, 'code', 'code');
    }

    //비밀번호 암호화
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setByPost($data)
    {
        $this->company = $data['company'];
        $this->title = $data['title'];
        $this->sdate = $data['sdate'];
        $this->edate = $data['edate'];
        $this->code = $data['code'];
        if( $data['password'] ){
            $this->password = $data['password'];
        }
        $this->manager = $data['manager'];
    }
}
