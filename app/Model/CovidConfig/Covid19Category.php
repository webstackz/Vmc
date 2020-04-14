<?php

namespace App\Model\CovidConfig;

use Illuminate\Database\Eloquent\Model;

class Covid19Category extends Model
{
    protected $primaryKey = 'category_id';
    
    protected $table = 'covid19_shop_category';

    protected $fillable = [
        'category_id', 'category_name'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['category_name'];
    }

    protected $appends = ['text'];
}
