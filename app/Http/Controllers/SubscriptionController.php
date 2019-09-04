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
        $alluser= User::where('role','fannie')->orderBy('id', 'DESC')->get();
        $usernames = array_pluck($alluser,'name', 'id');
        $usernumber = array_pluck($alluser,'mobile', 'id');
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
        return view('subscriptions_tech.add',compact('usernames','usernumber','alluser','allsub','types','title','lang'));
    }
    public function store(Request $request)
    {
        //return $request;
        if($request->id ){
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190',  
                'no_month'=>'required',
                'cost' =>'required',        
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'sub_type'  =>'required',           
                'fannie'  =>'required',
                'deposit'=>'required||mimes:jpg,jpeg,png',      
            ];
        }
        $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         if($request->id ){
            $subscription = Subscription::find( $request->id );
        }
        else{
            $subscription = new Subscription ;

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
}
