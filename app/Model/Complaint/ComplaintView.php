<?php

namespace App\Model\Complaint;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;

class ComplaintView extends Model
{
    use Search;

    protected $table = 'complaints_view';

    protected $search = [
        'complaint_no', 'mobile_no'
    ];

    protected $columns = [
        'id','complaint_no','department_id', 'name','complaint_type_id', 'mobile_no','mobile_no_2','mobile_no_3','ward_no','street_no','door_no','description','comp_status',
        'created_at'
    ];

    protected $fillable = [
        'id','complaint_no','department_id','name', 'complaint_type_id', 'mobile_no','mobile_no_2','mobile_no_3','ward_no','street_no','door_no','description'
        
    ];

    protected $appends = [
        'text'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['complaint_no'] .' - '. $this->attributes['description'];
    }
    


}
