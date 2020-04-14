<?php

namespace App\Http\Controllers\Assessment;

use Illuminate\Http\Request;
use App\Model\Assessment\Assessment;
use App\Http\Controllers\Controller;

class AssessmentController extends Controller
{
   
    public function index()
    {
        return api([
            'data' => Assessment::search()
        ]);
    }

    public function assessData(Request $request)
    {
        
        $form = Assessment::
            where('mobile_no', $request->mobile_no)
            ->orWhere('mobile_no_2', $request->mobile_no)
            ->orWhere('mobile_no_3', $request->mobile_no)
            ->get();

        return api([
            'form' => $form            
        ]);
    }
}
