<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardCounter extends Model
{
    use HasFactory;

    protected $primaryKey = 'sid';

    protected $guarded = [
        'sid',
        'bsid',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function board(){
        return $this->belongsTo(Board::class,'bsid','sid');
    }

    public function setByData($data)
    {
        $this->bsid = base64_decode($data['sid']);
        $this->ip = request()->ip();
    }
}
