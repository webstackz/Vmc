<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    protected $primaryKey = 'complaint_type_id';
    protected $fillable = [
        'complaint_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
       
        return $this->attributes['complaint_name'];
    }

    protected $appends = ['text'];
}
