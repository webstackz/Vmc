<?php

namespace App\Http\Controllers\SMS;

use DB;
use Illuminate\Http\Request;
use App\Model\Complaint\Complaint;
use App\Model\Complaint\ComplaintView;
use App\Model\SMS\SMSHistory;
use App\Model\SMS\IncomingSMS;
use App\Http\Controllers\Controller;
use App\Model\Assessment\Assessment;

class SmsController extends Controller
{

    public function incoming(Request $request){
        
        IncomingSMS::insert(['mobile_no' => mobile_no_prefix($request->mobile), 'message' => $request->message ,'created_at' => date('Y-m-d H:i:s')]);
      
        $keyword = explode(' ', $request->message);
        $keyword_size = sizeof($keyword);
          
        if ($keyword_size == 1) {
            SmsController::sendUserAckSMS($request);
        } elseif ($keyword_size == 2) {
            SmsController::sendUserReopenAckSMS($request);
        } 

    }
    
    public function rectify(Request $request){
        IncomingSMS::insert(['mobile_no' => mobile_no_prefix($request->mobile), 'message' => $request->message ,'created_at' => date('Y-m-d H:i:s')]);              
        SmsController::sendRectifySMS($request);
      
    }

    public function incomingCall(Request $request){
        
        IncomingSMS::insert(['mobile_no' => mobile_no_prefix($request->mobile), 'sms_type' => 'CALL' ,'created_at' => date('Y-m-d H:i:s')]);
   
        SmsController::sendUserAckSMS([
            'mobile' => $request->mobile,
            'message' => 'VMC'
            ]);

    }

    public static function sendUserAckSMS($data){
        $cmp_count = Complaint::where('mobile_no', mobile_no_prefix($data['mobile']))
        ->whereIn('status_id',[1,2,5])->count();      

        if($cmp_count){            
            $re_send['mobile_no'] = $data ['mobile'];
            $re_send['msg'] = "உங்கள் புகார் ஏற்கனவே செயல்பாட்டில் உள்ளது.";
            SmsController::send($re_send);
        } else{
            
        $count = Assessment::
            where('mobile_no', mobile_no_prefix($data['mobile']))
            ->orWhere('mobile_no_2', mobile_no_prefix($data['mobile']))
            ->orWhere('mobile_no_3', mobile_no_prefix($data['mobile']))
            ->count();
        
        DB::transaction(function() use ($data,$count) {
            $complaint_no = counter()->next('covid19');
            Complaint::insert(['complaint_no' => $complaint_no,'mobile_no' => mobile_no_prefix($data ['mobile']) , 'SMS' => $data['message'] ,'created_at' => date('Y-m-d H:i:s')]);
            counter()->increment('covid19');

            $send['mobile_no'] = mobile_no_prefix($data['mobile']);
            
            if($count){               
                $send['msg'] = "வேதாரண்யம் நகராட்சி தானியங்கி சேவை மையம் அன்புடன் வரவேற்கிறது. உங்கள் தேவை அறிய நகராட்சி ஊழியர் 290452 என்ற எண்ணிலிருந்து விரைவில் தொடர்பு கொள்வார்.";
            }else{
               $send['msg'] = "வணக்கம்,இந்த சேவை வேதாரண்யம் நகராட்சி எல்லைக்கு உட்பட்ட பொதுமக்களுக்கு மட்டுமே. நீங்கள் வேதாரண்யம் நகராட்சிக்கு உட்பட்டவராக இருந்தால், உங்கள் விபரங்களை நகராட்சி அலுவலர் அழைப்பின்போது பதிவு செய்து கொண்டு இச்சேவையை விரைவாக பயன்படுத்தலாம்.";              
            }

            SmsController::send($send);
        
            SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => mobile_no_prefix($data['mobile']),'complaint_no' => $complaint_no, 'message'=> $send['msg'] ,'created_at' => date('Y-m-d H:i:s') ]);
        });
        }

    }

    public static function sendUserReopenAckSMS($data){
        $keyword = explode(' ', $data->message);
        $count = Complaint::where('complaint_no', $keyword[1])->count();

        if ($count) {
            $status = Complaint::where(['complaint_no' => $keyword[1],'status_id' => 4 ])->count();
            
            if ($status) {
                DB::transaction(function () use ($keyword) {
                    $rec = Complaint::where('complaint_no', $keyword[1])->first();
                    Complaint::where('complaint_no', $rec->complaint_no)->update(['status_id' => 5]);

                    $send['mobile_no'] = $rec->mobile_no;
                    $send['msg'] = "நகரட்சியை மீண்டும் தொடர்பு கொண்டமைக்கு நன்றி.உங்கள் புகார்/கோரிக்கை எண்:  $rec->complaint_no சரிவர பூர்த்திசெய்யத்தமைக்கு வருந்துகிறோம். மேலும் நகராட்சி ஆணையரின் நேரடி பார்வையில் தங்கள் புகார்/கோரிக்கை பூர்த்திசெய்யப்படும்.";
                    SmsController::send($send);
        
                    SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => 
                    mobile_no_prefix($rec->mobile_no),'complaint_no' => $rec->complaint_no, 'message'=> $send['msg'] ,'created_at' => date('Y-m-d H:i:s')]);
                });
            }
            else{
                $send['mobile_no'] = $data ['mobile'];
                $send['msg'] = "Your Complaint Under Process Kindly Wait";
                SmsController::send($send);
            }
        } else{
            $send['mobile_no'] = $data ['mobile'];
            $send['msg'] = "Invalid Complaint No";
            SmsController::send($send);
        } 

    }

    public static function sendShopSMS(Request $data){       

        $complaint = ComplaintView::where(['complaint_no' => $data['complaint_no']])->first();        
        $send['mobile_no'] = $data ['mobile_no'];
        $send['complaint_no'] = $data ['complaint_no'];
        $send['shop_id'] = $data ['shop_id'];
        $send['msg'] = "கோரிக்கை  எண் : " . $send['complaint_no'] . " ற்கு  தங்கள் நிறுவனத்தை நகராட்சி பரிந்துரை செய்துள்ளது. பொதுமக்கள் தேவையறிந்து சேவை செய்ய அன்புடன் கேட்டுக்கொள்கிறோம். சேவை கோரிய நபர் : " . $data['user_mobile_no'] . " முகவரி : " . $complaint->ward_no. " - ". $complaint->street_no. " - " . $complaint->door_no;
       
       
        SmsController::send($send);  

        $model = new SMSHistory;
        DB::transaction(function() use ($model,$send) {         
            $model->category = "covid19";
            $model->user_type = "shop";
            $model->mobile_no = $send['mobile_no'];
            $model->complaint_no = $send['complaint_no'];
            $model->message = $send['msg'];            
            $model->save();  

            Complaint::where('complaint_no',  $send['complaint_no'])
            ->update(['status_id' => 2,'shop_id' => $send['shop_id']]);
        });

        SmsController::sendUserVerifySMS($send);
    }

    public static function sendUserVerifySMS($data){
        $send['mobile_no'] = $data ['mobile_no'];
        $send['complaint_no'] = $data ['complaint_no'];
        $send['msg'] = "உங்கள் புகார்  வேதாரண்யம் நகராட்சி தானியங்கி சேவை மையத்தில் பதிவுசெய்யப்பட்டுவிட்டது. உங்கள் புகார்/கோரிக்கை  எண் : " . $send['complaint_no'];
        SmsController::send($send);  
        
        $model = new SMSHistory;

        DB::transaction(function() use ($model,$send) {         
            $model->category = "covid19";
            $model->user_type = "user";
            $model->mobile_no = $send['mobile_no'];
            $model->complaint_no = $send['complaint_no'];
            $model->message = $send['msg'];            
            $model->save();   
        });
    }

 
    public static function sendRectifySMS($data){        
        $keyword = explode(' ', $data->message);
       
         $count = ComplaintView::where(['complaint_no' => $keyword[2] , 'shop_no' => mobile_no_prefix($data->mobile)])
        ->whereNotIn('status_id',[4])->count();

        if ($count) {
            DB::transaction(function () use ($data,$keyword) {
                $rec = Complaint::where('complaint_no', $keyword[2])->first();
                Complaint::where('complaint_no', $rec->complaint_no)->update(['status_id' => 4]);

                $send['mobile_no'] = mobile_no_prefix($data->mobile);
                $send['msg'] = "கோரிக்கை/புகார்  எண் : $rec->complaint_no ஐ பூர்த்தி செய்து விட்டது.";
                SmsController::send($send);
        
                $user_send['mobile_no'] = $rec->mobile_no;
                $user_send['msg'] = "தங்கள் கோரிக்கை/புகார்  எண் $rec->complaint_no பூர்த்தி செய்யப்பட்டுவிட்டது. ஏதேனும் குறைகள் இருந்தால் VMC டைப் செய்து ஒரு space விட்டு உங்கள் கோரிக்கை/புகார் எண்ணையும் டைப் செய்து 56161 என்ற எண்ணிற்கு குறுந்செய்தி (SMS) அனுப்பினால் உங்கள் கோரிக்கை /புகார் மறுபரிசீலனை செய்யப்படும்.";
                SmsController::send($user_send);

                SMSHistory::insert(['category' => 'covid19','user_type' => 'shop','mobile_no' => 
                mobile_no_prefix($data->mobile),'complaint_no' => $rec->complaint_no, 'message'=> $send['msg'] ,'created_at' => date('Y-m-d H:i:s')]);
                SMSHistory::insert(['category' => 'covid19','user_type' => 'user','mobile_no' => 
                mobile_no_prefix($rec->mobile_no),'complaint_no' => $rec->complaint_no, 'message'=> $user_send['msg'] ,'created_at' => date('Y-m-d H:i:s')]);
            });
        } else{
            $send['mobile_no'] = $data['mobile'];
            $send['msg'] = "உங்கள் புகார் எண் தவறானது. VMC <space> REC <space> டைப் செய்து 56161 என்ற எண்ணிற்கு அனுப்பவும்.";
            SmsController::send($send);
        } 
    }

    public static function invalidUserSMS($data){        
        $send['mobile_no'] = $data ['mobile_no'];       
        $send['msg'] = "உங்கள் தொலைபேசி/ புகார் எண் தவறானது. REC <space> டைப் செய்து 56161 என்ற எண்ணிற்கு அனுப்பவும்.";
        SmsController::send($send);  
    }
    
    public static function invalidShopSMS($data){        
        $send['mobile_no'] = $data ['mobile_no'];       
        $send['msg'] = "உங்கள் தொலைபேசி/ புகார் எண் தவறானது. REC <space> டைப் செய்து 56161 என்ற எண்ணிற்கு அனுப்பவும்.";
        SmsController::send($send);  
    }  
    
    public static function invalidSMS($data){        
        $send['mobile_no'] = $data ['mobile_no'];       
        $send['msg'] = "உங்கள் தொலைபேசி/ புகார் எண் தவறானது. VMC <space> டைப் செய்து 56161 என்ற எண்ணிற்கு அனுப்பவும்.";
        SmsController::send($send);  
    }

    
    public static function send($data){
        //print_r($data); 
        //return;
        $client = new \GuzzleHttp\Client();
        // Create a request with auth credentials
        $url = 'http://bulk.sms-india.in/send.php?';
        $username = 'erpradhan1009@gmail.com';
        $password = 'admin123';
        $mobileno = $data['mobile_no'];
        //$mobileno = 8526737834;

        
        $msg =  urlencode($data['msg']);
        $request = $client->get("$url usr=$username&pwd=$password&ph=$mobileno&text=$msg");
        // Get the actual response without headers
        $response = $request->getBody();
        return $response;
    } 
}
//ஐயா, தங்கள் மேலான கவனத்திற்கு, புகார் எண்:xxx சரியாக பூர்த்திசெய்யப்பட வில்லை என்பதை தெரிவித்து கொள்கிறேன். (Assesse Name with mobile number)