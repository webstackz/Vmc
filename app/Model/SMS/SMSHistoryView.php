<?php

namespace App\Model\SMS;

use Illuminate\Database\Eloquent\Model;

class SMSHistoryView extends Model
{
    
    protected $primaryKey = 'sms_id';
    protected $table = 'sms_history_view';


    protected $fillable = [
        'sms_id','category','mobile_no','message','user_type','complaint_no'
    ];
    
    protected $columns = [
        'sms_id','category','mobile_no','message','user_type','complaint_no'
    ];

    protected $hidden = [
         'updated_at'
    ];


}
