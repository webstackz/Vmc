<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ComplaintType;

class ComplaintTypeController extends Controller
{
    public function search()
    {
        $arr = request('department_id');
        
        $results = ComplaintType::orderBy('complaint_name')
            ->when(request('q'), function($query) {
                $query->where('complaint_name', 'like', '%'.request('q').'%');  
                
                                
            })     
            ->where(function($query) use ($arr)  {
                if(isset($arr)) {
                    $query->where('department_id', json_decode($arr)->department_id );  
                }
             })       
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }
}
