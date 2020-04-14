<?php

namespace App\Model\Complaint;

use Illuminate\Database\Eloquent\Model;

class ComplaintStatus extends Model
{

    protected $primaryKey = 'status_id';
    
    protected $fillable = [
        'status_id','status_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
       
        return $this->attributes['status_name'];
    }

    protected $appends = ['text'];
}
