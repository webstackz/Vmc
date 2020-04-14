<?php

namespace App\Model\Complaint;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;

class Complaint extends Model
{
    use Search;
    protected $table = 'complaints';
    public $timestamps = true;
    protected $search = [
        'complaint_no', 'mobile_no'
    ];

    protected $columns = [
        'complaint_no','department_id', 'complaint_type_id', 'mobile_no','ward_no','street_no','door_no','description','comp_status',
        'created_at'
    ];

    protected $fillable = [
        'complaint_no','department_id', 'complaint_type_id', 'mobile_no','ward_no','street_no','door_no','description','comp_status','created_at'
    ];

}
