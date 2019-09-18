<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Technician;
use App\SubscriptionType;
use App\Subscription;
use Auth;
use App;
use DB;
use Carbon\Carbon;
use App\Notifications\Notifications;

use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    //
    public function __construct()
    {
     
    }
    public function index()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions_tech';
 
        $subscriptions = Subscription::with('user')->with('subscription_type')->orderBy('id', 'DESC')->get();
         //return $subscriptions ; 
        return view('subscriptions_tech.index',compact('subscriptions','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions_tech';
        $alluser= User::where('role','fannie')->select('id',DB::raw('CONCAT(name, " - ", mobile) AS user_mobile'))->orderBy('id', 'DESC')->get();
        $usernames = array_pluck($alluser,'user_mobile', 'id');
        //$usernumber = array_pluck($alluser,'mobile', 'id');
        if($lang=='ar')
        {
            $allsub = SubscriptionType::select('id',DB::raw('name_ar AS name'))->get();
        }
        else
        {
            $allsub = SubscriptionType::select('id',DB::raw('name_en AS name'))->get();
        }
        $types = array_pluck($allsub,'name', 'id');
        //return $usernumber;
        return view('subscriptions_tech.add',compact('usernames','alluser','allsub','types','title','lang'));
    }
    public function store(Request $request)
    {
        // return $request;
        if($request->id ){
            $rules =
            [
                'sub_type'  =>'required',           
                'fannie'  =>'required',
                //'deposit'=>'required|mimes:jpg,jpeg,png',   
                'date_exp'=>'required', 
            ];
            
        }     
    
        else{
            $rules =
            [
                'sub_type'  =>'required',           
                'fannie'  =>'required',
                'deposit'=>'required|mimes:jpg,jpeg,png',      
            ];
        }
        $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         if($request->id ){
            $subscription = Subscription::find( $request->id );
            $subscription->date          = $request->date_exp;
            if ($request->hasFile('deposit')) {
                $image = $request->file('deposit');
                if($subscription->image)
                {
                    \File::delete(public_path(). '/img/' . $subscription->image);

                }
            }
            $techican= Technician::where('user_id',$request->fannie)->first();
            if($request->date_exp > $techican->renewal_date)
            {
                $techican->renewal_date=$request->date_exp;
                $techican->save();
            }
        }
        else{
            $subscription = new Subscription ;
            $subscription->status = "accepted" ;

        }

        $subscription->subscription_id         = $request->sub_type ;
        $subscription->fannie_id          = $request->fannie ;
       
        if ($request->hasFile('deposit')) {
    
            $image = $request->file('deposit');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $subscription->image   = $name;  
        }
        $subscription->save();
        $subscription = Subscription::where('id',$subscription->id)->first();
        return response()->json($subscription);
        
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions_tech';
        $subscription = Subscription::where('id',$id)->with('user')->with('subscription_type')->orderBy('id', 'DESC')->first();
        if($subscription)
        {
            if($subscription->subscription_type)
            {
                $number=$subscription->subscription_type->no_month;
                $new_date = strtotime($number."month", strtotime($subscription->created_at));
                $new_date=date("Y-m-d", $new_date);

            }
            else
            {
                $new_date = "";
               
            }

            if($lang=='ar')
            {
                $allsub = SubscriptionType::select('id',DB::raw('name_ar AS name'))->get();
            }
            else
            {
                $allsub = SubscriptionType::select('id',DB::raw('name_en AS name'))->get();
            }
            $types = array_pluck($allsub,'name', 'id');
              //return $new_date; 
            return view('subscriptions_tech.edit',compact('subscription','title','lang','new_date','types'));
            
        }
        else
        {
            return redirect(url('error'));
        }
        
    }

    public function accept($id)
    {
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions_tech';
        $subscription = Subscription::where('id',$id)->with('user')->with('subscription_type')->orderBy('id', 'DESC')->first();
       
        if($subscription)
        {
            if($subscription->subscription_type)
            { 
                $number=$subscription->subscription_type->no_month;
                if($subscription->user->technician->renewal_date <= $date ){
                    $new_date = strtotime($number."month", strtotime($date));
                }else{
                    $new_date = strtotime($number."month", strtotime($subscription->user->technician->renewal_date));
                }
                $new_date=date("Y-m-d", $new_date);
                $techican = Technician::where('user_id',$subscription->user->id)->first() ;
                $techican->renewal_date =   $new_date ;
                $techican->save() ;
                $subscription->status = "accepted" ;
                $subscription->save() ;

                $type = "subscription";
                $msg =  [
                    'en' =>  " The renewal application number " . $subscription->id . " was approved, The next renewal date is " .  $techican->renewal_date, 
                    'ar' =>  " تم الموافقة علي طلب تجديد الاشتراك رقم  " . $subscription->id . " واصبح ميعاد التجديد القادم " .  $techican->renewal_date, 
                ];
                
                $fannie = User::where('id', $subscription->user->id)->first(); 
                if($fannie){

                    $fannie->notify(new Notifications($msg,$type ));
                    $device_token = $fannie->device_token ;
                    if($device_token){
                        $this->notification($device_token,$msg,$msg);
                    }
                }
                return redirect()->route('techsubscriptions');
                // return Redirect::route('techsubscriptions') ;
            }
            
            return redirect(url('error'));
            
        }
        else
        {
            return redirect(url('error'));
        }
        
    }

    public function reject($id)
    {
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions_tech';
        $subscription = Subscription::where('id',$id)->with('user')->with('subscription_type')->orderBy('id', 'DESC')->first();
       
        if($subscription)
        {
            $subscription->status = "rejected" ;
            $subscription->save() ;
            $type = "subscription";
            $msg =  [
                'en' =>  " The renewal application number " . $subscription->id . " was rejected, Please communicate with the application management "  , 
                'ar' =>  " تم رفض طلب تجديد الاشتراك رقم  " . $subscription->id . " نرجو التواصل مع ادراة التطبيق "  , 
            ];
            
            $fannie = User::where('id', $subscription->user->id)->first(); 
            if($fannie){

                $fannie->notify(new Notifications($msg,$type ));
                $device_token = $fannie->device_token ;
                if($device_token){
                    $this->notification($device_token,$msg,$msg);
                }
            }
            return redirect()->route('techsubscriptions');
            
        }
        else
        {
            return redirect(url('error'));
        }
        
    }


}
