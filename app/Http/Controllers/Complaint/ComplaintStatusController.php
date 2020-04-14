<?php

namespace App\Http\Controllers\Complaint;

use App\Model\Complaint\ComplaintStatus;
use App\Http\Controllers\Controller;

class ComplaintStatusController extends Controller
{
    public function search()
    {      
        
        $results = ComplaintStatus::orderBy('status_id')
            ->when(request('q'), function($query) {
                $query->where('status_name', 'like', '%'.request('q').'%'); 
                            
            })    
            ->whereIn('status_id' , [3,4,5] )        
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }
}
