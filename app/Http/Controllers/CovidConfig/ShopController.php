<?php

namespace App\Http\Controllers\CovidConfig;

use Illuminate\Http\Request;
use App\Model\CovidConfig\Covid19View;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
   
    public function shopData(Request $request)
    {        
        $form = Covid19View::where('category_name',  $request->complaint_name)->get();
        return api([
            'form' => $form            
        ]);
    }
}
