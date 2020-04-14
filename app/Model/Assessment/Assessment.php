<?php

namespace App\Model\Assessment;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;

class Assessment extends Model
{
    use Search;

    protected $table = 'assessment';
    public $timestamps = true;
    
    protected $search = [
        'assessment_id', 'assessment_no','owner_name','ward_no','street_no','door_no','mobile_no','mobile_no_2','mobile_no_3'
    ];

    protected $columns = [
        'assessment_id', 'assessment_no','owner_name','ward_no','street_no','door_no','mobile_no','mobile_no_2','mobile_no_3','created_at'
    ];

    protected $fillable = [
        'ward_no'
        
    ];

    protected $appends = [
        'text'
    ];
    

    public function getTextAttribute()
    {
        return $this->attributes['ward_no'];
    }
}
