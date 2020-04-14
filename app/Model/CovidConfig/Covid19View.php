<?php

namespace App\Model\CovidConfig;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;

class Covid19View extends Model
{
    use Search;

    protected $table = 'covid_config_view';

    protected $search = [
        'ward_no', 'category_id','shop_name','address','shop_no'
    ];

    protected $columns = [
        'shop_id','ward_no', 'category_id','shop_name','address','shop_no','category_name'
    ];

    protected $fillable = [
        'shop_id','ward_no', 'category_id','shop_name','address','shop_no','category_name'
        
    ];

    protected $appends = [
        'text'
    ];
    

    public function getTextAttribute()
    {
        return $this->attributes['shop_id'];
    }
}
