<?php

namespace App\Model\SMS;

use Illuminate\Database\Eloquent\Model;

class IncomingSMS extends Model
{
    protected $primaryKey = 'sms_id';
    protected $table = 'incoming_sms';
    public $timestamps = true;
    
    protected $fillable = [
        'sms_id','mobile_no','message','sms_type','created_at'
    ];
    
    protected $columns = [
        'sms_id','mobile_no','message','sms_type','created_at'
    ];

    protected $hidden = [
        'created_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['mobile_no'];
    }

    protected $appends = ['mobile_no'];
}
