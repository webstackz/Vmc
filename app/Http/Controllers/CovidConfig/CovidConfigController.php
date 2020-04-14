<?php

namespace App\Http\Controllers\CovidConfig;

use Illuminate\Http\Request;
use App\Model\CovidConfig\Covid19;
use App\Model\CovidConfig\Covid19View;
use DB;
use App\Http\Controllers\Controller;

class CovidConfigController extends Controller
{
    public function index()
    {
        return api([
            'data' => Covid19View::search()
        ]);
    }

    public function create(Request $request)
    {      

        $form = [
            'items' => [
                [
                    'wardno' => null,
                    'covid19category' => null
                ]
            ]
        ];

        return api([
            'form' => $form
        ]);
    }

    public function store(Request $request)
    {
         $request->validate([
            'items' => 'required|array|min:1',           
            'items.*.covid19_category' => 'required|array',
            'items.*.shop_name' => 'required|max:255',
            'items.*.address' => 'required|max:255',
            'items.*.mobile_no' => 'required|max:255',

        ]);
      
	
        $model = DB::transaction(function() use ($request) {          
           
            foreach($request->items as $item){               
                $insert_array['category_id'] = $item['covid19_category']['category_id'];
                $insert_array['shop_name'] = $item['shop_name'];
                $insert_array['address'] = $item['address'];
                $insert_array['shop_no'] = $item['mobile_no'];
                $insert_array['created_at'] = date('Y-m-d H:i:s');
                Covid19::insert($insert_array);
            }
        });
        
        return api([
            'saved' => true,
            'id' => null
        ]);
    }

   
}
