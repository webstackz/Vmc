<?php

namespace App\Http\Controllers\Complaint;

use Illuminate\Http\Request;
use App\Model\Complaint\Complaint;
use App\Model\CovidConfig\Covid19Category;
use App\Model\Complaint\ComplaintStatus;
use App\Model\Complaint\ComplaintView;
use App\Model\Complaint\ComplaintTableView;
use DB;
use App\Http\Controllers\SMS\SmsController;
use App\Model\SMS\SMSHistory;
use App\Model\SMS\SMSHistoryView;
use App\Http\Controllers\Controller;
use App\Model\Assessment\Assessment;

class CovidComplaintController extends Controller
{
    public function index()
    {
        return api([
            'data' => ComplaintTableView::search()
        ]);
    }   

    public function create()
    {
        $form = ['complaint_no' => counter()->next('covid19'),'assess_list' => []];
        
        return api([
            'form' => $form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([      
            'type' => 'required|array',
            'mobile_no' => 'required|max:255',
            'name' => 'required|max:255',
            'ward_no' => 'required|max:255',
            'street_no' => 'required|max:255',
            'door_no' => 'required|max:255'           
        ]);
        
        $count = Assessment::where('assessment_no', $request->assessment_no)->count();
        
        if($count == 0){
            $model = new Assessment;

            DB::transaction(function() use ($model, $request) {
                $model->assessment_no = $request->assessment_no;          
                $model->owner_name = $request->name;           
                $model->ward_no = $request->ward_no;           
                $model->street_no = $request->street_no;           
                $model->door_no = $request->door_no;          
                $model->mobile_no = $request->mobile_no;
                $model->created_at = date('Y-m-d H:i:s');            
                $model->save();
                return $model;
            });
            
        
        } else if($request->assessment_no && $count != 0){ 
            
            $assess = Assessment::where('assessment_no', $request->assessment_no)->first();
            $mobile_no_arr = array($assess->mobile_no, $assess->mobile_no_2, $assess->mobile_no_3);
            
            if (!in_array($request->mobile_no, $mobile_no_arr)){          

                if(!$assess->mobile_no){
                    $update_data = ['mobile_no' => $request->mobile_no];
                } else if(!$assess->mobile_no_2){
                    $update_data = ['mobile_no_2' => $request->mobile_no];
                } else{
                    $update_data = ['mobile_no_3' => $request->mobile_no];
                }

                Assessment::where('assessment_no',  $request->assessment_no)->update($update_data);                
                
            }
        }
        
        $cmp_no = counter()->next('covid19');
        $msg = "வேதாரண்யம் நகராட்சி தானியங்கி சேவை மையம் அன்புடன் வரவேற்கிறது. உங்கள் தேவை அறிய நகராட்சி ஊழியர் 290452 என்ற எண்ணிலிருந்து விரைவில் தொடர்பு கொள்வார்.";
        
        $model = new Complaint;

        $result = DB::transaction(function() use ($model, $request,$cmp_no) {
            
            $model->complaint_no = $cmp_no;
            $model->mobile_no = $request->mobile_no;
            $model->name = $request->name;           
            $model->ward_no = $request->ward_no;           
            $model->street_no = $request->street_no;           
            $model->door_no = $request->door_no;           
            $model->complaint_type_id = $request->type['category_id'];
            // $model->department_id = $request->dept['department_id'];
            $model->description = $request->description;
            $model->created_at = date('Y-m-d H:i:s');
            counter()->increment('covid19');
            $model->save();
            return $model;
        });

        SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => $request->mobile_no,'complaint_no' => $cmp_no, 'message'=> $msg ,'created_at' => date('Y-m-d H:i:s')]);
    
        SmsController::send(array('mobile_no' => $request->mobile_no, 'msg' => $msg));

        return api([
            'saved' => true,
            'id' => $result->id
        ]);
    }

    public function show($id)
    {
        $data = ComplaintView::findOrFail($id);
        
        $data['sms_list'] = SMSHistoryView::where('complaint_no' , $data->toArray()['complaint_no'])->get();  

        return api([
            'data' => $data
        ]);
    }

    public function edit($id)
    {
        $form = ComplaintView::findOrFail($id);

        if ($form->toArray()['complaint_type_id']) {
            $form['type'] = Covid19Category::findOrFail($form->toArray()['complaint_type_id']);
        }

        if ($form->toArray()['status_id']) {
            $form['status'] = ComplaintStatus::findOrFail($form->toArray()['status_id']);
        }      
        
        $mob = $form->toArray()['mobile_no'];
        if ($mob) {
            $form['assess_list'] = Assessment::
                where('mobile_no',$mob)
                ->orWhere('mobile_no_2', $mob)
                ->orWhere('mobile_no_3', $mob)
                ->get();
        }    
        
        return api([
            'form' => $form            
        ]);
    }


    public function update($id, Request $request)
    {
        $compliant_model = Complaint::findOrFail($id);

        $request->validate([      
            'type' => 'required|array',
            'mobile_no' => 'required|max:255',
            'name' => 'required|max:255',
            'ward_no' => 'required|max:255',
            'street_no' => 'required|max:255',
            'door_no' => 'required|max:255'            
        ]);
    
        Complaint::where('complaint_no',  $compliant_model->complaint_no)
        ->update(['mobile_no' => $request->mobile_no, 'name' => $request->name,
        'ward_no' => $request->ward_no, 
        'street_no' => $request->street_no, 
        'door_no' => $request->door_no, 
        'complaint_type_id' => $request->type['category_id'],      
        'status_id' => $request->status['status_id'],
        'description' => $request->description]);
       
        $count = Assessment::where('assessment_no', $request->assessment_no)->count();
        
        // if($request->assessment_no && $count == 0){
        //     $model = new Assessment;

        //     DB::transaction(function() use ($model, $request) {
        //         $model->assessment_no = $request->assessment_no;          
        //         $model->owner_name = $request->name;           
        //         $model->ward_no = $request->ward_no;           
        //         $model->street_no = $request->street_no;           
        //         $model->door_no = $request->door_no;          
        //         $model->mobile_no = $request->mobile_no;
        //         $model->created_at = date('Y-m-d H:i:s');            
        //         $model->save();
        //         return $model;
        //     });
            
        //     SmsController::send(array('mobile_no' => $model->mobile_no, 'msg' => "நமது நகராட்சி தானியங்கி மையத்தில் உங்கள் விபரங்கள் பதிவு செய்யப்பட்டு, உங்கள் புகார்/கோரிக்கை ஏற்கப்பட்டது. உங்கள் கோரிக்கை/புகார் எண் : " . $compliant_model->complaint_no));
     
        // } 

        if($request->assessment_no && $count != 0){                      

                $update_data = ['mobile_no' => $request->mobile_no,'mobile_no_2' => $request->mobile_no_2,'mobile_no_3' => $request->mobile_no_3];
                Assessment::where('assessment_no',  $request->assessment_no)->update($update_data);                
                
            
        }
        
        $this->sendSms(array('status' => $request->status['status_id'] , 'mobile_no' => $request->mobile_no , 'complaint_no' => $compliant_model->complaint_no));

        return api([
            'saved' => true,
            'id' => $compliant_model->id
        ]);
    }

    public function sendSms($data)
    {
       if($data['status'] == 4){
            $msg = "தங்கள் கோரிக்கை/புகார்  எண் ". $data['complaint_no'] ." பூர்த்தி செய்யப்பட்டுவிட்டது. ஏதேனும் குறைகள் இருந்தால் VMC டைப் செய்து ஒரு space விட்டு உங்கள் கோரிக்கை/புகார் எண்ணையும் டைப் செய்து 56161 என்ற எண்ணிற்கு குறுந்செய்தி (SMS) அனுப்பினால் உங்கள் கோரிக்கை /புகார் மறுபரிசீலனை செய்யப்படும்.";
            SmsController::send(array('mobile_no' => $data['mobile_no'], 'msg' => $msg));   
            SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => $data['mobile_no'],'complaint_no' => $data['complaint_no'], 'message'=>  $msg ,'created_at' => date('Y-m-d H:i:s') ]);
                
        } else if($data['status'] == 5){
            $msg = "நகரட்சியை மீண்டும் தொடர்பு கொண்டமைக்கு நன்றி.உங்கள் புகார்/கோரிக்கை எண்: ". $data['complaint_no'] ." சரிவர பூர்த்திசெய்யத்தமைக்கு வருந்துகிறோம். மேலும் நகராட்சி ஆணையரின் நேரடி பார்வையில் தங்கள் புகார்/கோரிக்கை பூர்த்திசெய்யப்படும்.";
            SmsController::send(array('mobile_no' => $data['mobile_no'], 'msg' => $msg));   
            SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => $data['mobile_no'],'complaint_no' => $data['complaint_no'], 'message'=> $msg ,'created_at' => date('Y-m-d H:i:s')]);
        } else if($data['status'] == 5){
            $msg = "மன்னிக்கவும் இந்த சேவை வேதாரண்யம் நகராட்சி எல்லைக்கு உட்பட்ட பொதுமக்களுக்கு மட்டுமே. நீங்கள் வேதாரண்யம் நகராட்சிக்கு உட்பட்டவராக இருந்தால், உங்கள் விபரங்களை நகராட்சி அலுவலர் அழைப்பின்போது பதிவு செய்து கொண்டு இச்சேவையை பயன்படுத்தலாம்.";
            SmsController::send(array('mobile_no' => $data['mobile_no'], 'msg' => $msg));   
            SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => $data['mobile_no'],'complaint_no' => $data['complaint_no'], 'message'=> $msg ,'created_at' => date('Y-m-d H:i:s')]);
        }
    }

    public function destroy($id)
    {
        $model = Complaint::findOrFail($id);
        $model->delete();

        return api([
            'deleted' => true
        ]);
    }
}
