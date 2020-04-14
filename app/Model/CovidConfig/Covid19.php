<?php

namespace App\Model\CovidConfig;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;

class Covid19 extends Model
{
    use Search;

    protected $primaryKey = 'shop_id';

    protected $table = 'covid_shop';
    public $timestamps = true;
    
    protected $search = [
         'category_id','shop_name','address','shop_no','created_at'
    ];

    protected $columns = [
        'shop_id', 'category_id','shop_name','address','shop_no','created_at'
    ];

    protected $fillable = [
        'shop_id','ward_no', 'category_id','shop_name','address','shop_no','created_at'
        
    ];

    protected $appends = [
        'text'
    ];
    

    public function getTextAttribute()
    {
        return $this->attributes['shop_id'];
    }
}
