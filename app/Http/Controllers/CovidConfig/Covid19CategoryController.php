<?php

namespace App\Http\Controllers\CovidConfig;

use Illuminate\Http\Request;
use App\Model\CovidConfig\Covid19Category;
use App\Http\Controllers\Controller;

class Covid19CategoryController extends Controller
{
    public function search()
    {      
        
        $results = Covid19Category::orderBy('category_id')
            ->when(request('q'), function($query) {
                $query->where('category_name', 'like', '%'.request('q').'%');  
                $query;                 
            })            
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }
}
