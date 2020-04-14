<?php

namespace App\Model\SMS;

use Illuminate\Database\Eloquent\Model;

class SMSHistory extends Model
{
    
    protected $primaryKey = 'sms_id';
    protected $table = 'sms_history';
    public $timestamps = true;

    protected $fillable = [
        'sms_id','category','mobile_no','message','user_type','complaint_no','created_at'
    ];
    
    protected $columns = [
        'sms_id','category','mobile_no','message','user_type','complaint_no','created_at'
    ];

    protected $hidden = [
         'updated_at'
    ];


}
