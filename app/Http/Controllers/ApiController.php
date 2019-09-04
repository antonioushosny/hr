<?php
use Illuminate\Support\Facades\Hash;
namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

use App\Area ;
use App\Advertisement ;
use App\AvailableDay;
use App\City;
use App\Contact;
use App\ContactUs;
use App\Favorite;
use App\Country ; 
use App\Doc;
use App\Order;
use App\Subscription ; 
use App\SubscriptionType ; 
use App\PasswordReset ; 
use App\User;
use App\Technician;
use App\Service;
use App\Rate;
use App\Nationality;

use Carbon\Carbon;
use App\Notifications\Notifications;
use App\Notifications\SendMessages;
use Validator;

use StreamLab\StreamLabProvider\Facades\StreamLabFacades;
use App\Notifications\verify_code;

class ApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
 
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Riyadh');
        $this->middleware('guest')->except('logout');
    }
    protected function SuccessResponse($message ,$data)
    {
        return response()->json([
            'success' => 1,
            'errors'=>[],
            'message' =>$message,
            'data' => $data,

        ]);
    }
    protected function FailedResponse($message ,$errors)
    {
       
        return response()->json([
            'success' => 0,
            'errors'=>$errors,
            'message' =>$message,
            'data' => null,

        ]);
    }

    protected function LoggedResponse($message )
    {
        return response()->json([
            'success' => -1,
            'errors'=>[],
            'message' =>$message,
            'data' => null,

        ]);
    }

//////////////////////////////////////////////
// IsRegistered function by Antonious hosny
    public function IsRegistered(Request $request){ 
            $rules=array(
                "mobile"=>"required",
                // "code"=>"required",
             );

            //check the validator true or not
            $validator  = \Validator::make($request->all(),$rules);
            if($validator->fails())
            {
                $messages = $validator->messages();
                $transformed = [];
                foreach ($messages->all() as $field => $message) {
                    $transformed[] = [
                        'message' => $message
                    ];
                }
                $message = trans('api.failed_login') ;
                return   $this->FailedResponse($message , $transformed) ;
                 
            }
            $user  = User::where('mobile',$request->mobile)->where('role','<>','admin')->orderBy('id', 'desc')->first();

            if($user){
                $code = rand(100000,999999);
                $UserCode = PasswordReset::where('email',$request->mobile)->first();
                if(!$UserCode){
                    $UserCode = new PasswordReset ;
                }
                $UserCode->email = $request->mobile ;
                $UserCode->token = $code ;
                $UserCode->save();
                
                $message = trans('api.send_code') ;
                return $this->SuccessResponse($message , $code) ;
               
                 
            }
            else
            {
                $code = rand(100000,999999);
                $UserCode = PasswordReset::where('email',$request->mobile)->first();
                if(!$UserCode){
                    $UserCode = new PasswordReset ;
                }
                $UserCode->email = $request->mobile ;
                $UserCode->token = $code ;
                $UserCode->save();

                $errors = [] ;
                $message = trans('api.mobile_notfound') ;
                // $response = $this->FailedResponse($message , $errors) ;
                return response()->json([
                    'success' => 0,
                    'errors'=>$errors,
                    'message' =>$message,
                    'data' => $code,
        
                ]);

                  
            }


    }
//////////////////////////////////////////////
// SendCode function by Antonious hosny
    public function SendCode(Request $request){ 
        $rules=array(
            "mobile"=>"required",
        );

        //check the validator true or not
        $validator  = \Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $messages = $validator->messages();
            $transformed = [];
            foreach ($messages->all() as $field => $message) {
                $transformed[] = [
                    'message' => $message
                ];
            }
            $message = trans('api.failed_login') ;
            return   $this->FailedResponse($message , $transformed) ;
            
        }
        $code = rand(100000,999999);
        $UserCode = PasswordReset::where('email',$request->mobile)->first();
        if(!$UserCode){
            $UserCode = new PasswordReset ;
        }
        $UserCode->email = $request->mobile ;
        $UserCode->token = $code ;
        $UserCode->save();
        
        $message = trans('api.send_code') ;
        return $this->SuccessResponse($message , $code) ;
          
        


    }
//////////////////////////////////////////////
// Countries function by Antonious hosny
    public function Countries(Request $request){ 
        $lang = $request->header('lang');
        if($lang == 'ar'){
            $countries  = Country::where('status','active')->with('cities')->orderBy('name_ar', 'asc')->get();
        }else{
            $countries  = Country::where('status','active')->with('cities')->orderBy('name_en', 'asc')->get();
        }
            if(sizeof($countries) > 0){
                $countriess =[];
                $i = 0 ;

                foreach($countries as $country){
                    if($country){
                        $countriess[$i]['country_id']   = $country->id;
                        if($lang == 'ar'){
                            $countriess[$i]['country_name']   = $country->name_ar;
                        }else{
                            $countriess[$i]['country_name']   =  $country->name_en;
                        }
                        if($country->image){
                            $countriess[$i]['logo'] = asset('img/').'/'. $country->image;
                        }else{
                            $countriess[$i]['logo'] = null ;
                        }
                        $citiess = [] ;
                        $n  = 0 ;
                        if(sizeOf($country->cities) > 0){

                            foreach($country->cities as $city){
                                $citiess[$n]['city_id']   = $city->id;
                                if($lang == 'ar'){
                                    $citiess[$n]['city_name']   = $city->name_ar;
                                }else{
                                    $citiess[$n]['city_name']   =  $city->name_en;
                                }
                                $areass = [] ;
                                $j  = 0 ;
                                if(sizeOf($city->areas) > 0){
        
                                    foreach($city->areas as $area){
                                        $areass[$j]['area_id']   = $area->id;
                                        if($lang == 'ar'){
                                            $areass[$j]['area_name']   = $area->name_ar;
                                        }else{
                                            $areass[$j]['area_name']   =  $area->name_en;
                                        }
                                        $j ++ ;
                                
                                    }
                                }
                                $citiess[$n]['areas'] = $areass ;
                                $n ++ ;

                            }
                        }
                        $countriess[$i]['cities'] = $citiess ;
                        $i ++ ;
                    
                    }
                }
                $message = trans('api.fetch') ;
                return $this->SuccessResponse($message , $countriess) ;
                
            }
            else
            {
                $errors=  [];
                $message = trans('api.notfound') ;
                return $this->FailedResponse($message , $errors) ;
            
                
            }

    }
//////////////////////////////////////////////
//////////////////////////////////////////////
// login function by Antonious hosny
    public function Login(Request $request){
        // return $request;
        // print time();
        $lang = $request->header('lang');
        // $this->validateLogin($request);
        $rules=array(
            "mobile"=>"required",
            "code"=>"required",
            "device_id"=>"required",
            "role"=>"required",
            "device_type" => "required",  // 1 for ios , 0 for android  
        );

        //check the validator true or not
        $validator  = \Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $messages = $validator->messages();
            $transformed = [];
            foreach ($messages->all() as $field => $message) {
                $transformed[] = [
                     'message' => $message
                ];
            }
            $message = trans('api.failed_login') ;
            return  $this->FailedResponse($message , $transformed) ;
 
        }

        $user = User::where('mobile',$request->mobile)->where('role',$request->role)->first();
        // return $user;
        if(!$user){

            // $errors[] =[
            //     'message' => trans('api.mobile_notfound')
            // ];
            $errors = [] ;
            $message = trans('api.mobile_notfound') ;
            return  $this->FailedResponse($message , $errors) ;
 
        }
        else{
            $UserCode = PasswordReset::where('email',$request->mobile)->first();
            if (  $UserCode && $request->code == $UserCode->token) {
                if($user->status == 'not_active'|| $user->status == 'not_active' ||$user->role == 'admin'  ){
                    // $errors[] =[
                    //     'message' => trans('api.allowed')
                    // ];
                    $errors = [] ;
                    $message = trans('api.allowed') ;
                    return  $this->FailedResponse($message , $errors) ;
                   
                }
                $user->generateToken();
                $user->device_token = $request->device_id ;
                $user->type = $request->device_type ;
                $user->code = '' ;
                // $user->available = '1';

                $user->save();
                $UserCode->delete() ;
                $user =  User::where('id',$user->id)->with('technician')->first();
                $users = [] ;
                if($user){
                    $users['id'] = $user->id ;
                    $users['name'] = $user->name ;
                    $users['email'] = $user->email ;
                    $users['mobile'] = $user->mobile ;
                    $users['address'] = $user->address ;
                    if($user->technician){

                        if($user->technician->country){
                            
                            $users['country_id'] = $user->technician->country->id ;
                            if($lang == 'ar'){
                                $users['country_name']   = $user->technician->country->name_ar;
                            }else{
                                $users['country_name']   = $user->technician->country->name_en;
                            }
                        }else{
                            $users['country_id'] = null ;
                            $users['country_name']   =  null;
                        }
                        if($user->technician->city){
                            
                            $users['city_id'] = $user->technician->city->id ;
                            if($lang == 'ar'){
                                $users['city_name']   = $user->technician->city->name_ar;
                            }else{
                                $users['city_name']   = $user->technician->city->name_en;
                            }
                        }else{
                            $users['city_id'] = null ;
                            $users['city_name']   =  null;
                        }
                        if($user->technician->area){
                            
                            $users['area_id'] = $user->technician->area->id ;
                            if($lang == 'ar'){
                                $users['area_name']   = $user->technician->area->name_ar;
                            }else{
                                    $users['area_name']   = $user->technician->area->name_en;
                            }
                        }else{
                            $users['area_id'] = null ;
                            $users['area_name']   =  null;
                        }
                        if($user->technician->nationality){
                            
                            $users['nationality_id'] = $user->technician->nationality->id ;
                            if($lang == 'ar'){
                                $users['nationality_name']   = $user->technician->nationality->name_ar;
                            }else{
                                $users['nationality_name']   = $user->technician->nationality->name_en;
                            }
                        }else{
                            $users['nationality_id'] = null ;
                            $users['nationality_name']   =  null;
                        }
                        if($user->technician->service){
                            
                            $users['service_id'] = $user->technician->service->id ;
                            if($lang == 'ar'){
                                $users['service_name']   = $user->technician->service->name_ar;
                            }else{
                                $users['service_name']   = $user->technician->service->name_en;
                            }
                        }else{
                            $users['service_id'] = null ;
                            $users['service_name']   =  null;
                        }
                        $users['brief'] = $user->technician->brief ;
                        if($user->technician->identity_photo){
                            $users['identity_photo'] = asset('img/').'/'. $user->technician->identity_photo;
                        }
                        else {
                            $users['identity_photo'] = null;
                        }
                    }
                    $users['lat'] = $user->lat ;
                    $users['lng'] = $user->lng ;
                    $users['role'] = $user->role ;
                    $users['remember_token'] = $user->remember_token ;
                    if($user->image){
                        $users['image'] = asset('img/').'/'. $user->image;
                    }
                    else {
                        $users['image'] = null;
                    }

                    
                }
                $message = trans('api.login') ;
                return  $this->SuccessResponse($message , $users) ;
                  
               
            }
            else
            {
                // $errors[] =[
                //     'message' => trans('api.code_failed')
                // ];
                $errors = [] ;
                $message = trans('api.code_failed') ;
                return  $this->FailedResponse($message , $errors) ;
            }
                
            
        }

    }
//////////////////////////////////////////////
// register function by Antonious hosny
    public function Register(Request $request) {
        // return $request;
        $lang = $request->header('lang');
        // $this->validateLogin($request);
        $rules=array(   
            "name"=>"required",
            "email"=>"required|unique:users,email",
            "mobile"=>"required|between:8,11|unique:users,mobile", 
            "code"=>"required",
            "address"=>"required",
            "lat"=>"required",
            "lng"=>"required",
            "role"=>"required",
            "device_id"=>"required",
            "device_type"=>"required",
        );

        if($request->role == 'fannie'){
            $rules['country_id'] = "required" ;
            $rules['city_id'] = "required" ;
            $rules['area_id'] = "required" ;
            $rules['nationality_id'] = "required" ;
            $rules['service_id'] = "required" ;
            $rules['identity_photo'] = "required" ;
        }
        //check the validator true or not
        $validator  = \Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $messages = $validator->messages();
            $transformed = [];
 
            foreach ($messages->all() as $field => $message) {
                $transformed[] = [
                    'message' => $message
                ];
            }

            $errors = [] ;
            $message = trans('api.failed_registered') ;
            return  $this->FailedResponse($message , $transformed) ;
        
             
        }
        $UserCode = PasswordReset::where('email',$request->mobile)->first();
        if (  $UserCode && $request->code == $UserCode->token) {
            $user = new User;

            $user->name          = $request->name ;
            $user->email         = $request->email ;
            $user->mobile        = $request->mobile ;
            $user->address       = $request->address ;
            $user->lat           = $request->lat ;
            $user->lng           = $request->lng ;
            $user->status        = 'active';
            $user->role          = $request->role ;
            $user->device_token = $request->device_id ;
            $user->type = $request->device_type ;
            $user->save();
            $user->generateToken();
            if($user->role == 'fannie'){
                $fannie = new Technician ;
                $fannie->user_id = $user->id ;
                if(Country::find($request->country_id)){
                    $fannie->country_id = $request->country_id ;
                }
                if(City::find($request->city_id)){
                    $fannie->city_id = $request->city_id ;
                }
                if(Area::find($request->area_id)){
                    $fannie->area_id = $request->area_id ;
                }
                if(Nationality::find($request->nationality_id)){
                    $fannie->nationality_id = $request->nationality_id ;
                }
                if(Service::find($request->service_id)){
                    $fannie->service_id = $request->service_id ;
                }
 
                $fannie->brief = $request->brief ;

                if ($request->hasFile('identity_photo')) {
                    $image = $request->file('identity_photo');
                    $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/img');
                    $image->move($destinationPath, $name);
                    $fannie->identity_photo   = $name;  
                }
                $fannie->save(); 
            }
            $UserCode->delete() ;
            // $msg1 = "  مستخدم جديد قام بالتسجيل" ;
            $type = "user";
            // $title1 = "  مستخدم جديد قام بالتسجيل" ;
            $msg =  [
                'en' => "New user registered"  ,
                'ar' => "  مستخدم جديد قام بالتسجيل"  ,
            ];
            $title = [
                'en' =>  "New user registered"  ,
                'ar' => "  مستخدم جديد قام بالتسجيل"  ,  
            ];
            $admins = User::where('role', 'admin')->get(); 
            if(sizeof($admins) > 0){
                foreach($admins as $admin){
                    $admin->notify(new Notifications($msg,$type ));
                }
                $device_token = $admin->device_token ;
                if($device_token){
                    $this->notification($device_token,$title,$msg);
                    $this->webnotification($device_token,$title,$msg,$type);
                }
            }
            
            $user =  User::where('id',$user->id)->with('technician')->first();
            $users = [] ;
            if($user){
                $users['id'] = $user->id ;
                $users['name'] = $user->name ;
                $users['email'] = $user->email ;
                $users['mobile'] = $user->mobile ;
                $users['address'] = $user->address ;
                if($user->technician){

                    if($user->technician->country){
                        
                        $users['country_id'] = $user->technician->country->id ;
                        if($lang == 'ar'){
                            $users['country_name']   = $user->technician->country->name_ar;
                        }else{
                            $users['country_name']   = $user->technician->country->name_en;
                        }
                    }else{
                        $users['country_id'] = null ;
                        $users['country_name']   =  null;
                    }
                    if($user->technician->city){
                        
                        $users['city_id'] = $user->technician->city->id ;
                        if($lang == 'ar'){
                            $users['city_name']   = $user->technician->city->name_ar;
                        }else{
                            $users['city_name']   = $user->technician->city->name_en;
                        }
                    }else{
                        $users['city_id'] = null ;
                        $users['city_name']   =  null;
                    }
                    if($user->technician->area){
                        
                        $users['area_id'] = $user->technician->area->id ;
                        if($lang == 'ar'){
                            $users['area_name']   = $user->technician->area->name_ar;
                        }else{
                            $users['area_name']   = $user->technician->area->name_en;
                        }
                    }else{
                        $users['area_id'] = null ;
                        $users['area_name']   =  null;
                    }
                    if($user->technician->nationality){
                        
                        $users['nationality_id'] = $user->technician->nationality->id ;
                        if($lang == 'ar'){
                            $users['nationality_name']   = $user->technician->nationality->name_ar;
                        }else{
                            $users['nationality_name']   = $user->technician->nationality->name_en;
                        }
                    }else{
                        $users['nationality_id'] = null ;
                        $users['nationality_name']   =  null;
                    }
                    if($user->technician->service){
                        
                        $users['service_id'] = $user->technician->service->id ;
                        if($lang == 'ar'){
                            $users['service_name']   = $user->technician->service->name_ar;
                        }else{
                            $users['service_name']   = $user->technician->service->name_en;
                        }
                    }else{
                        $users['service_id'] = null ;
                        $users['service_name']   =  null;
                    }
                    $users['brief'] = $user->technician->brief ;
                    if($user->technician->identity_photo){
                        $users['identity_photo'] = asset('img/').'/'. $user->technician->identity_photo;
                    }
                    else {
                        $users['identity_photo'] = null;
                    }
                }
                $users['lat'] = $user->lat ;
                $users['lng'] = $user->lng ;
                $users['role'] = $user->role ;
                $users['remember_token'] = $user->remember_token ;
                if($user->image){
                    $users['image'] = asset('img/').'/'. $user->image;
                }
                else {
                    $users['image'] = null;
                }

                
            }
            $message = trans('api.success_registered') ;
            return  $this->SuccessResponse($message , $users) ;
           
        } 
        else
        {
            // $errors[] =[
            //     'message' => trans('api.code_failed')
            // ];
            $errors = [] ;
            $message = trans('api.code_failed') ;
            return  $this->FailedResponse($message , $errors) ;
        }
        
    }
//////////////////////////////////////////////
// editprofile function by Antonious hosny
    public function EditProfile(Request $request){
        // return $request ;
        $lang = $request->header('lang');
        $token = $request->header('token');
        if($token == ''){
 
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
           
        }  
        $user = User::where('remember_token',$token)->first();
        if($user){      
            $rules=array(  
                "name"=>"min:3",
                "email"=> 'email',
                "image" => 'file',
            );
            $user = User::where('id',$user->id)->first();
            if($request->mobile){
                if( $request->mobile != $user->mobile){
                    $rules['mobile'] = 'between:8,11|unique:users,mobile';
                }
            }
            if($request->email){
                if($request->email != $user->email){
                    $rules['email'] = 'email|unique:users,email';
                }
            }
            //check the validator true or not
            $validator  = \Validator::make($request->all(),$rules);
            if($validator->fails())
            {
                $messages = $validator->messages();
                $transformed = [];

                foreach ($messages->all() as $field => $message) {
                    $transformed[] = [
                        // 'field' => $field,
                        'message' => $message
                    ];
                }
                $message = trans('api.failed') ;
                return  $this->FailedResponse($message , $transformed) ;
            }
 
            if($request->name){
                $user->name          = $request->name ;
            }
            if($request->email){
                $user->email         = $request->email ;
            }
            if($request->mobile){
                $user->mobile        = $request->mobile ;
            }
            
            if($request->lat){
                $user->lat           = $request->lat ;
            }
            if($request->lng){
                $user->lng           = $request->lng ;
            }
            if($request->address){
                $user->address           = $request->address ;
            }
            
            // if ($request->profile_pic){
            //     $image = $request->input('profile_pic'); // image base64 encoded
            //     $image = str_replace('data:image/png;base64,', '', $image);
            //     $image = str_replace(' ', '+', $image);
            //     $imageName = str_random(10). time().'.'.'png';
            //     \File::put(public_path(). '/img/' . $imageName, base64_decode($image));
            //     $user->image = $imageName;
            // }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                $destinationPath = public_path('/img');
                $image->move($destinationPath, $name);
                $user->image   = $name;  
            }
            $user->save();
            if($user->role == 'fannie'){
                $fannie =  Technician::where('user_id',$user->id)->first() ;

                if(Country::find($request->country_id)){
                    $fannie->country_id = $request->country_id ;
                }
                if(City::find($request->city_id)){
                    $fannie->city_id = $request->city_id ;
                }
                if(Area::find($request->area_id)){
                    $fannie->area_id = $request->area_id ;
                }
                if(Nationality::find($request->nationality_id)){
                    $fannie->nationality_id = $request->nationality_id ;
                }
                if(Service::find($request->service_id)){
                    $fannie->service_id = $request->service_id ;
                }
 
                $fannie->brief = $request->brief ;

                if ($request->hasFile('identity_photo')) {
                    $image = $request->file('identity_photo');
                    $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/img');
                    $image->move($destinationPath, $name);
                    $fannie->identity_photo   = $name;  
                }
                $fannie->save(); 
            }
            $user =  User::where('id',$user->id)->with('technician')->first();
            $users = [] ;
            if($user){
                $users['id'] = $user->id ;
                $users['name'] = $user->name ;
                $users['email'] = $user->email ;
                $users['mobile'] = $user->mobile ;
                $users['address'] = $user->address ;
                if($user->technician){

                    if($user->technician->country){
                        
                        $users['country_id'] = $user->technician->country->id ;
                        if($lang == 'ar'){
                            $users['country_name']   = $user->technician->country->name_ar;
                        }else{
                            $users['country_name']   = $user->technician->country->name_en;
                        }
                    }else{
                        $users['country_id'] = null ;
                        $users['country_name']   =  null;
                    }
                    if($user->technician->city){
                        
                        $users['city_id'] = $user->technician->city->id ;
                        if($lang == 'ar'){
                            $users['city_name']   = $user->technician->city->name_ar;
                        }else{
                            $users['city_name']   = $user->technician->city->name_en;
                        }
                    }else{
                        $users['city_id'] = null ;
                        $users['city_name']   =  null;
                    }
                    if($user->technician->area){
                        
                        $users['area_id'] = $user->technician->area->id ;
                        if($lang == 'ar'){
                            $users['area_name']   = $user->technician->area->name_ar;
                        }else{
                                $users['area_name']   = $user->technician->area->name_en;
                        }
                    }else{
                        $users['area_id'] = null ;
                        $users['area_name']   =  null;
                    }
                    if($user->technician->nationality){
                        
                        $users['nationality_id'] = $user->technician->nationality->id ;
                        if($lang == 'ar'){
                            $users['nationality_name']   = $user->technician->nationality->name_ar;
                        }else{
                            $users['nationality_name']   = $user->technician->nationality->name_en;
                        }
                    }else{
                        $users['nationality_id'] = null ;
                        $users['nationality_name']   =  null;
                    }
                    if($user->technician->service){
                        
                        $users['service_id'] = $user->technician->service->id ;
                        if($lang == 'ar'){
                            $users['service_name']   = $user->technician->service->name_ar;
                        }else{
                            $users['service_name']   = $user->technician->service->name_en;
                        }
                    }else{
                        $users['service_id'] = null ;
                        $users['service_name']   =  null;
                    }
                    $users['brief'] = $user->technician->brief ;
                    if($user->technician->identity_photo){
                        $users['identity_photo'] = asset('img/').'/'. $user->technician->identity_photo;
                    }
                    else {
                        $users['identity_photo'] = null;
                    }
                }
                $users['lat'] = $user->lat ;
                $users['lng'] = $user->lng ;
                $users['role'] = $user->role ;
                $users['remember_token'] = $user->remember_token ;
                if($user->image){
                    $users['image'] = asset('img/').'/'. $user->image;
                }
                else {
                    $users['image'] = null;
                }

                
            }
            $message = trans('api.save') ;
            return  $this->SuccessResponse($message , $users) ;

        }
        else{
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }

    }
///////////////////////////////////////////////////
// logout function by Antonious hosny
    public function Logout(Request $request){
        $token = $request->header('token');
        
        $token = $request->header('token');
        if($token == ''){
 
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
           
        }  
        // $token = $request->header('access_token');
        $user = User::where('remember_token',$token)->first();
        if ($user) {
            $user->remember_token = null;
            $user->device_token = null;
             $user->save();

            $message = trans('api.logout') ;
            return  $this->SuccessResponse($message , $user) ;
          
        }else{
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }

    }
//////////////////////////////////////////////////
// Services function by Antonious hosny
    public function Services(Request $request){

        if($request->page && $request->page > 0 ){
            $page = $request->page * $request->skip ;
        }else{
            $page = 0 ;
        }
        $token = $request->header('token');
        $lang = $request->header('lang');
    
        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){

                $services = Service::where('status','active')->orderBy('id', 'desc')->skip($page)->limit($request->skip)->get();
                $services_count = Service::where('status','active')->count('id');
                // return $containers_count ;
                $servicess = [] ;
                $i =0 ;
                if(sizeof($services) > 0 ){
                    foreach($services as $service){
                        
                        $servicess[$i]['service_id'] = $service->id ;    
                        if($lang == 'ar'){
                            $servicess[$i]['service_name'] = $service->name_ar ; 
                         }else{
                            $servicess[$i]['service_name'] = $service->name_en ; 
                         }
                        if($service->image){
                            $servicess[$i]['image'] = asset('img/').'/'. $service->image;
                        }else{
                            $servicess[$i]['image'] = null ;
                        }
 
                        $i ++ ;                    
                        
                    }
                }
                $count = count($user->unreadnotifications) ;
                $data['services_count'] = $services_count ;
                $data['services'] = $servicess ;
                $data['count_notification'] = $count ;
                $message = trans('api.fetch') ;
                return  $this->SuccessResponse($message,$data ) ;
                 
            }else{
                $message = trans('api.logged_out') ;
                return  $this->LoggedResponse($message ) ;
            }
            
        }else{
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }


    }
//////////////////////////////////////////////////
// AllWorkers function by Antonious hosny
    public function AllWorkers(Request $request){
        $rules=array(
            "service_id"=>"required",
            "page"=>"required",
            "skip"=>"required",
        );
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        // return $date ;
        //check the validator true or not
        $validator  = \Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $messages = $validator->messages();
            $transformed = [];
            foreach ($messages->all() as $field => $message) {
                $transformed[] = [
                    'message' => $message
                ];
            }
            $message = trans('api.failed') ;
            return  $this->FailedResponse($message , $transformed) ;
 
        }
        if($request->page && $request->page > 0 ){
            $page = $request->page * $request->skip ;
        }else{
            $page = 0 ;
        }
        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                
                $technicians = Technician::whereDate('renewal_date','>=',$date)->where('service_id',$request->service_id)->whereHas('user')->orderBy('id', 'desc')->skip($page)->limit($request->skip)->get();
                $technicians_count = Technician::whereDate('renewal_date','>=',$date)->count('id');
                return $technicians ;
                $technicianss = [] ;
                $i =0 ;
                if(sizeof($technicians) > 0 ){
                    foreach($technicians as $technician){
                        if($technician->user && $technician->user->status == 'active')
                        $technicianss[$i]['service_id'] = $technician->id ;    
                        $technicianss[$i]['service_name'] = $technician->name_ar ; 
                        $technicianss[$i]['service_name'] = $technician->name_en ; 
                         if($technician->image){
                            $technicianss[$i]['image'] = asset('img/').'/'. $technician->image;
                        }else{
                            $technicianss[$i]['image'] = null ;
                        }

                        $i ++ ;                    
                        
                    }
                }
                $count = count($user->unreadnotifications) ;
                $data['services_count'] = $services_count ;
                $data['services'] = $servicess ;
                $data['count_notification'] = $count ;
                $message = trans('api.fetch') ;
                return  $this->SuccessResponse($message,$data ) ;
                
            }else{
                $message = trans('api.logged_out') ;
                return  $this->LoggedResponse($message ) ;
            }
            
        }else{
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }


    }
//////////////////////////////////////////////////
// MakeOrder function by Antonious hosny
    public function MakeOrder(Request $request){
        $token = $request->header('token');
        $lang = $request->header('lang');
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $time  = date('H:i:s', strtotime($dt));
        if($token){

            $user = User::where('remember_token',$token)->first();
            if($user){
                $rules=array(
                    'container_id'      =>'required',
                    'num_containers'    => 'required',
                    'lat'    => 'required',
                    'lng'    => 'required',
                    // 'city_id'    => 'required',
                    // 'area_id'    => 'required',
                );
                $validator  = \Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    $messages = $validator->messages();
                    $transformed = [];
        
                    foreach ($messages->all() as $field => $message) {
                        $transformed[] = [
                            'message' => $message
                        ];
                    }
                    return response()->json([
                        'success' => 'failed',
                        'errors'  => $transformed,
                        'message' => trans('api.validation_error'),
                        'data'    => null ,
                    ]);
                }
                $container = Container::where('id',$request->container_id)->with('centers')->first();
                $distancess = [] ;
                $i = 0;
                if(sizeof($container->centers) > 0){

                    foreach ($container->centers as $center) {
                       $distance =  $this->GetDistance($request->lat, $center->lat, $request->lng, $center->lng, 'K');
                       $distancess[$center->id] = $distance  ;
                       $i++ ;
                        // print   $distance.' KM ' .'</br>';
                    }
                    asort($distancess)  ;
                    // reset($distancess);
                    $first_key = key($distancess);

                    $CenterContainer = CenterContainer::where('center_id',$first_key)->where('container_id',$request->container_id)->with('center')->with('container')->first();
                    //    return $CenterContainer;
                    $user->city_id = $request->city_id ;
                    $user->area_id = $request->area_id ;
                    $user->save();
                    $order = new Order ;
                    $order->user_name = $user->name ;
                    $order->user_mobile = $user->mobile ;
                    if($user->City)
                    $order->city = $user->City->name_ar ;
                    if($user->Area)
                    $order->area = $user->Area->name_ar ;
                    $order->lat = $request->lat ;
                    $order->lng = $request->lng ;
                    $order->container_name_ar = $CenterContainer->container->name_ar ;
                    $order->container_name_en = $CenterContainer->container->name_en ;
                    $order->container_size = $CenterContainer->container->size ;
                    $order->no_container = $request->num_containers ;
                    $order->notes = $request->notes ;
                    $order->user_id = $user->id ;
                    $order->center_id = $CenterContainer->center->id ;
                    $order->provider_id = $CenterContainer->center->provider_id ;
                    $order->container_id = $CenterContainer->container->id ;
                    $order->price = $CenterContainer->price ;
                    $order->total = $CenterContainer->price * $request->num_containers ;
                    $order->status = 'pending' ;
                    
                    $order->save();
                    $ordercenter = new OrderCenter ;
                    $ordercenter->order_id = $order->id ;
                    $ordercenter->center_id = $order->center_id ;
                    $ordercenter->status = 'pending' ;
                    $ordercenter->save();

                    // $msg = "  لديك طلب جديد من " . $user->name ;
                    // $title = "  لديك طلب جديد من " . $user->name ;
                    
                    $type = "order";
                    $msg =  [
                        'en' => "  You have a new request from" . $user->name ." Order number ". $order->id  , 
                        'ar' =>  "  لديك طلب جديد من " . $user->name ."  رقم الطلب ". $order->id,
                    ];
                    $title = [
                        'en' =>  "  You have a new request from " . $user->name ,
                        'ar' =>  "  لديك طلب جديد من " . $user->name ,  
                    ];
                    $center = User::where('id', $CenterContainer->center->id)->first(); 
                    $center->notify(new Notifications($msg,$type ));
                    $device_token = $center->device_token ;
                    if($device_token){
                        $this->notification($device_token,$msg,$msg);
                        $this->webnotification($device_token,$msg,$msg,$type);
                    }
                    
                    $order = Order::where('id',$order->id)->with('center')->with('container')->first();
                    $orders = [];
                    if($order){
                        $orders['container_id'] =   $order->container->id ;
                        $orders['center_id'] =   $order->center->id ;
                        $orders['center_name'] =   $order->center->name ;
                        if($lang == 'ar'){
                            $orders['container_name'] =   $order->container->name_ar ;
                        }else{
                            $orders['container_name'] =   $order->container->name_en ;
                        }
                        $orders['container_size'] =   $order->container->size ;
                        $orders['num_containers'] =   $order->no_container;
                        $orders['container_price'] =   $order->price ;
                        $orders['total'] =   $order->total ;
                        $orders['status'] =   trans('api.'.$order->status) ;
                    }
                    return response()->json([
                        'success' => 'success',
                        'errors' => null ,
                        'message' => trans('api.save'),
                        'data' => $orders ,
                    ]);
                }
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.notfoundcenter'),
                    "message"=>trans('api.notfoundcenter'),
                    ]);
                
            }else{
                return response()->json([
                    'success' => 'logged',
                    'errors' => trans('api.logout'),
                    "message"=>trans('api.logout'),
                    ]);
            }
        }else{
            return response()->json([
                'success' => 'logged',
                'errors' => trans('api.logout'),
                "message"=>trans('api.logout'),
                ]);
        }


    }
//////////////////////////////////////////////////
// MyOrders function by Antonious hosny
    public function MyOrders(Request $request){
        $token = $request->header('token');
        $lang = $request->header('lang');
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $time  = date('H:i:s', strtotime($dt));
        if($request->page && $request->page >= 1 ){
            $skip = $request->page.'0' ;
            // return $skip ;
        }else{
            $skip = 0 ;
        }
        
        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user && $user->role == 'user'){
                // $orderss = Order::where('user_id',$user->id)->with('center')->where('status','<>','delivered')->Where('status','<>','canceled')->with('container')->get();
                // $orderss = Order::where('user_id',$user->id)->with('center')->with('container')->skip($skip)->limit(10)->get();
                $orderss = Order::where('user_id',$user->id)->with('center')->with('container')->get();
                $count_orders = Order::where('user_id',$user->id)->with('center')->with('container')->count('id');
                $count = count($user->unreadnotifications) ;
                if(sizeof($orderss) > 0){
                    $orders = [];
                    $i = 0 ;
                    foreach($orderss as $order){
                        $orders[$i]['order_id'] =   $order->id ;
                        
                        if($order->center){
                            
                            $orders[$i]['center_id'] =   $order->center->id ;
                            $orders[$i]['center_name'] =   $order->center->name ;
                        }else{
                             $orders[$i]['center_id'] =   ' '  ;
                            $orders[$i]['center_name'] =  ' ' ;
                        }
                         
                        $orders[$i]['num_containers'] =   $order->no_container;
                        $orders[$i]['container_price'] =   $order->price ;
                        if($order->container){
                            $orders[$i]['container_id'] =   $order->container->id ;
                            if($lang == 'ar'){
                                $orders[$i]['container_name'] =   $order->container->name_ar ;
                            }else{
                                $orders[$i]['container_name'] =   $order->container->name_en ;
                            }
                            $orders[$i]['container_size'] =   $order->container->size ;
                            if($order->container->image){
                                $orders[$i]['image'] = asset('img/').'/'. $order->container->image;
                            }else{
                                $orders[$i]['image'] = null ;
                            }
                        }else{
                            if($lang == 'ar'){
                                $orders[$i]['container_name'] =   $order->container_name_ar ;
                            }else{
                                $orders[$i]['container_name'] =   $order->container_name_en ;
                            }
                            $orders[$i]['container_size'] =   $order->container_size ;
                             $orders[$i]['image'] = null ;
                        }
                        $orders[$i]['total'] =   $order->total ;
                        // $orders[$i]['status'] =   trans('api.'.$order->status) ;
                        $orders[$i]['status'] =   $order->status;
                        $orders[$i]['created_at'] =   $order->created_at;
                        $i++;
                    }
                    return response()->json([
                        'success' => 'success',
                        'errors' => null ,
                        'message' => trans('api.fetch'),
                        'data' => [
                            'order' => $orders  , 
                            'count_orders' => $count_orders,
                            'count_notifications' => $count,
                        ]
                    ]);
                }
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.notfound'),
                    "message"=>trans('api.notfound'),
                    ]);
                
            }
            else if($user && $user->role == 'driver'){
                // $orderss = Order::where('user_id',$user->id)->with('center')->where('status','<>','delivered')->Where('status','<>','canceled')->with('container')->get();
                // $orderss = Order::where('driver_id',$user->id)->with('center')->with('container')->skip($skip)->limit(10)->get();
                $orderss = Order::where('driver_id',$user->id)->with('center')->with('container')->get();
                $count_orders = Order::where('driver_id',$user->id)->with('center')->with('container')->count('id');
                 $count = count($user->unreadnotifications) ;
                if(sizeof($orderss) > 0){
                    $orders = [];
                    $i = 0 ;
                    foreach($orderss as $order){
                        $orders[$i]['order_id'] =   $order->id ;
                        $orders[$i]['user_id'] =   $order->user_id ;
                        $orders[$i]['user_name'] =   $order->user_name ;
                        $orders[$i]['mobile'] =   $order->user_mobile ;
                        $orders[$i]['lat'] =   $order->lat ;
                        $orders[$i]['lng'] =   $order->lng ;
                        $orders[$i]['city'] =   $order->city ;
                        $orders[$i]['area'] =   $order->area ;
                        
                        if($order->center){
                            
                            $orders[$i]['center_id'] =   $order->center->id ;
                            $orders[$i]['center_name'] =   $order->center->name ;
                        }else{
                             $orders[$i]['center_id'] =   ' '  ;
                            $orders[$i]['center_name'] =  ' ' ;
                        }
                       
                       
                        $orders[$i]['num_containers'] =   $order->no_container;
                        $orders[$i]['container_price'] =   $order->price ;
                        if($order->container){
                            $orders[$i]['container_id'] =   $order->container->id ;
                            if($lang == 'ar'){
                                $orders[$i]['container_name'] =   $order->container->name_ar ;
                            }else{
                                $orders[$i]['container_name'] =   $order->container->name_en ;
                            }
                            $orders[$i]['container_size'] =   $order->container->size ;
                            if($order->container->image){
                                $orders[$i]['image'] = asset('img/').'/'. $order->container->image;
                            }else{
                                $orders[$i]['image'] = null ;
                            }
                        }
                        else{
                            if($lang == 'ar'){
                                $orders[$i]['container_name'] =   $order->container_name_ar ;
                            }else{
                                $orders[$i]['container_name'] =   $order->container_name_en ;
                            }
                            $orders[$i]['container_size'] =   $order->container_size ;
                             $orders[$i]['image'] = null ;
                        }
                        $orders[$i]['total'] =   $order->total ;
                        // $orders[$i]['status'] =   trans('api.'.$order->status) ;
                        $orders[$i]['status'] =   $order->status;
                        $orders[$i]['created_at'] =   $order->created_at;
                        $i++;
                    }
                    return response()->json([
                        'success' => 'success',
                        'errors' => null ,
                        'message' => trans('api.fetch'),
                        'data' => [
                            'order' => $orders  , 
                            'count_orders' => $count_orders,
                            'count_notifications' => $count,
                        ]
                    ]);
                }
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.notfound'),
                    "message"=>trans('api.notfound'),
                    ]);
                
            }
            else{
                return response()->json([
                    'success' => 'logged',
                    'errors' => trans('api.logout'),
                    "message"=>trans('api.logout'),
                    ]);
            }
        }else{
            return response()->json([
                'success' => 'logged',
                'errors' => trans('api.logout'),
                "message"=>trans('api.logout'),
                ]);
        }
    }
//////////////////////////////////////////////////
// CanceledOrders function by Antonious hosny
    public function CanceleOrder(Request $request){
        $token = $request->header('token');
        $lang = $request->header('lang');
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $time  = date('H:i:s', strtotime($dt));
        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user && $user->role == 'user'){
                $rules=array(
                    'order_id'      =>'required',
                );
                $validator  = \Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    $messages = $validator->messages();
                    $transformed = [];
        
                    foreach ($messages->all() as $field => $message) {
                        $transformed[] = [
                            'message' => $message
                        ];
                    }
                    return response()->json([
                        'success' => 'failed',
                        'errors'  => $transformed,
                        'message' => trans('api.validation_error'),
                        'data'    => null ,
                    ]);
                }
                $order = Order::where('id',$request->order_id)->first();
                if($order){
                    $order->status = 'canceled' ;
                    $order->save();
                    $type = "order";
                    $msg =  [
                        'en' =>  $user->name ."  canceled the order"  ." number ". $order->id  , 
                        'ar' =>   $user->name ."  قام بالغاء الطلب"   ." رقم ". $order->id  , 
                    ];
                    $title = [
                        'en' =>  $user->name ."  canceled the order"  ,
                        'ar' =>   $user->name ."  قام بالغاء الطلب"  , 
                    ];
                    $center = User::where('id', $order->center_id)->first(); 
                    if($center){

                        $center->notify(new Notifications($msg,$type ));
                        $device_token = $center->device_token ;
                        if($device_token){
                            $this->notification($device_token,$msg,$msg);
                            $this->webnotification($device_token,$msg,$msg,$type);
                        }
                    }
                    return response()->json([
                        'success' => 'success',
                        'errors' => null ,
                        'message' => trans('api.canceled'),
                        'data' => null ,
                    ]);
                }
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.notfound'),
                    "message"=>trans('api.notfound'),
                    ]);
                
            }else{
                return response()->json([
                    'success' => 'logged',
                    'errors' => trans('api.logout'),
                    "message"=>trans('api.logout'),
                    ]);
            }
        }else{
            return response()->json([
                'success' => 'logged',
                'errors' => trans('api.logout'),
                "message"=>trans('api.logout'),
                ]);
        }
    }
//////////////////////////////////////////////////
// OrdersHistory function by Antonious hosny
    public function OrdersHistory(Request $request){
        $token = $request->header('token');
        $lang = $request->header('lang');
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $time  = date('H:i:s', strtotime($dt));
        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user && $user->role == 'user'){
                $orderss = Order::where('user_id',$user->id)->with('center')->where('status','delivered')->Orwhere('status','canceled')->with('container')->get();
                if(sizeof($orderss) > 0){
                    $orders = [];
                    $i = 0 ;
                    foreach($orderss as $order){
                        $orders[$i]['order_id'] =   $order->id ;
                        $orders[$i]['container_id'] =   $order->container->id ;
                        $orders[$i]['center_id'] =   $order->center->id ;
                        $orders[$i]['center_name'] =   $order->center->name ;
                        if($lang == 'ar'){
                            $orders[$i]['container_name'] =   $order->container->name_ar ;
                        }else{
                            $orders[$i]['container_name'] =   $order->container->name_en ;
                        }
                        $orders[$i]['container_size'] =   $order->container->size ;
                        $orders[$i]['num_containers'] =   $order->no_container;
                        $orders[$i]['container_price'] =   $order->price ;
                        $orders[$i]['total'] =   $order->total ;
                        $orders[$i]['status'] =   trans('api.'.$order->status) ;
                        $i++;
                    }
                    return response()->json([
                        'success' => 'success',
                        'errors' => null ,
                        'message' => trans('api.fetch'),
                        'data' => $orders ,
                    ]);
                }
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.notfound'),
                    "message"=>trans('api.notfound'),
                    ]);
                
            }else{
                return response()->json([
                    'success' => 'logged',
                    'errors' => trans('api.logout'),
                    "message"=>trans('api.logout'),
                    ]);
            }
        }else{
            return response()->json([
                'success' => 'logged',
                'errors' => trans('api.logout'),
                "message"=>trans('api.logout'),
                ]);
        }
    }
//////////////////////////////////////////////////
// ChangeStatusOrders function by Antonious hosny
    public function ChangeStatusOrders(Request $request){
        $token = $request->header('token');
        $lang = $request->header('lang');
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $time  = date('H:i:s', strtotime($dt));
        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user && $user->role == 'driver'){
                $rules=array(
                    'status'      =>'required',
                    'order_id'      =>'required',
                );
                $validator  = \Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    $messages = $validator->messages();
                    $transformed = [];
        
                    foreach ($messages->all() as $field => $message) {
                        $transformed[] = [
                            'message' => $message
                        ];
                    }
                    return response()->json([
                        'success' => 'failed',
                        'errors'  => $transformed,
                        'message' => trans('api.validation_error'),
                        'data'    => null ,
                    ]);
                }
                $order = Order::where('id',$request->order_id)->first();
                $dt = Carbon::now();
                $date  = date('Y-m-d H:i:s', strtotime($dt));
                if($order){
                    if($request->status == 'accept'){
                        $user->available = 2 ;
                        $user->save();
                        $order->status = 'assigned' ;
                        $order->save();
                        $orderdriver = OrderDriver::where('order_id',$order->id)->where('driver_id',$user->id)->orderBy('id',"Desc")->first();
                        if($orderdriver){
                            $orderdriver->status = 'accept' ;
                            $orderdriver->accept_date  = $date ;
                            $orderdriver->save(); 
                        }
                        $type = "order";
                        $msg =  [
                            'en' =>  $user->name ."  agreed to deliver the request "  ." number ". $order->id  , 
                            'ar' =>   $user->name ."  قام بالموافقة علي توصيل الطلب" ." رقم ". $order->id  ,  
                        ];
                        $title = [
                            'en' =>  $user->name ."  agreed to deliver the request"  ,
                            'ar' =>   $user->name ."  قام بالموافقة علي توصيل الطلب"  ,
                        ];
                        $center = User::where('id', $order->center_id)->first(); 
                        if($center){

                            $center->notify(new Notifications($msg,$type ));
                            $device_token = $center->device_token ;
                            if($device_token){
                                $this->notification($device_token,$msg,$msg);
                                $this->webnotification($device_token,$title,$msg,$type);
                            }
                        }
                        $msg =  [
                            'en' =>  "  Your order status has changed"  ." number ". $order->id  , 
                            'ar' =>   " تم تغيير حالة الطلب الخاص بك "  ." رقم ". $order->id  , 
                        ];
                        $title = [
                            'en' =>  "  Your order status has changed" ." number ". $order->id   ,
                            'ar' =>   " تم تغيير حالة الطلب الخاص بك "  ." رقم ". $order->id  , 
                        ];
                        $center = User::where('id', $order->user_id)->first(); 
                        if($center){

                            $center->notify(new Notifications($msg,$type ));
                            $device_token = $center->device_token ;
                            if($device_token){
                                $this->notification($device_token,$title,$msg);
                                $this->webnotification($device_token,$title,$msg,$type);
                            }
                        }
                        return response()->json([
                            'success' => 'success',
                            'errors' => null ,
                            'message' => trans('api.success'),
                            'data' => null ,
                        ]);
                    }
                    else if($request->status == 'decline'){
                        $user->available = 1 ;
                        $user->save();
                        $order->status = 'accepted' ;
                        $order->driver_id = null ;
                        $order->save();
                        $orderdriver = OrderDriver::where('order_id',$order->id)->where('driver_id',$user->id)->orderBy('id',"Desc")->first();
                        if($orderdriver){
                            $orderdriver->status = 'decline' ;
                            $orderdriver->reason = $request->reason ;
                            $orderdriver->decline_date  = $date ;
                            $orderdriver->save(); 
                        }
                        $type = "order";
                        $msg =  [
                            'en' =>  $user->name ."  declined  to deliver the request"  ." number ". $order->id   ,
                            'ar' =>   $user->name ."  رفض تسليم الطلب"   ." رقم ". $order->id   ,
                        ];
                        $title = [
                            'en' =>  $user->name ."  declined  to deliver the request"   ." number ". $order->id   ,
                            'ar' =>   $user->name ."  رفض تسليم الطلب"   ." رقم ". $order->id   ,
                        ];
                        $center = User::where('id', $order->center_id)->first(); 
                        if($center){

                            $center->notify(new Notifications($msg,$type ));
                            $device_token = $center->device_token ;
                            if($device_token){
                                $this->notification($device_token,$title,$msg);
                                $this->webnotification($device_token,$title,$msg,$type);
                            }
                        }

                        return response()->json([
                            'success' => 'success',
                            'errors' => null ,
                            'message' => trans('api.success'),
                            'data' => null ,
                        ]);
                    }
                    else if($request->status == 'delivered'){
                        $user->available = 1 ;
                        $user->save();
                        $order->status = 'delivered' ;
                        $order->save();
                        $orderdriver = OrderDriver::where('order_id',$order->id)->where('driver_id',$user->id)->orderBy('id',"Desc")->first();
                        if($orderdriver){
                            $orderdriver->status = 'accept' ;
                            $orderdriver->accept_date  = $date ;
                            $orderdriver->save(); 
                        }
                        $type = "order";
                        $msg =  [
                            'en' =>  "  The request has been delivered"  ." number ". $order->id   ,
                            'ar' =>   "  تم توصيل الطلب"  ." رقم ". $order->id   ,
                        ];
                        $title = [
                            'en' =>  "  The request has been delivered" ." number ". $order->id   ,
                            'ar' =>   "  تم توصيل الطلب"  ." رقم ". $order->id   ,
                        ];
                        $center = User::where('id', $order->center_id)->first(); 
                        if($center){

                            $center->notify(new Notifications($msg,$type ));
                            $device_token = $center->device_token ;
                            if($device_token){
                                $this->notification($device_token,$title,$msg);
                                $this->webnotification($device_token,$title,$msg,$type);
                            }
                        }
                        $center = User::where('id', $order->user_id)->first(); 
                        if($center){

                            $center->notify(new Notifications($msg,$type ));
                            $device_token = $center->device_token ;
                            if($device_token){
                                $this->notification($device_token,$title,$msg);
                                $this->webnotification($device_token,$title,$msg,$type);
                            }
                        }
                        return response()->json([
                            'success' => 'success',
                            'errors' => null ,
                            'message' => trans('api.success'),
                            'data' => null ,
                        ]);
                    }

                    return response()->json([
                        'success' => 'failed',
                        'errors' => null ,
                        'message' => trans('api.notfound'),
                        'data' => null ,
                    ]);
                    
                }
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.notfound'),
                    "message"=>trans('api.notfound'),
                    ]);
                
            }else{
                return response()->json([
                    'success' => 'logged',
                    'errors' => trans('api.logout'),
                    "message"=>trans('api.logout'),
                    ]);
            }
        }else{
            return response()->json([
                'success' => 'logged',
                'errors' => trans('api.logout'),
                "message"=>trans('api.logout'),
                ]);
        }
    }
//////////////////////////////////////////////////



/////////////////////////////////////////////////////
// ContactUs function by Antonious hosny
    public function ContactUs(Request $request){
        $lang = $request->header('lang');
        $token = $request->header('token');
        if($token){
            $user =User::where('remember_token',$token)->first();
            if($user){
                $rules=array(   
                    "message"=>"required",
                    "title"=>"required",
                );
        
                $validator  = \Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    $messages = $validator->messages();
                    $transformed = [];
        
                    foreach ($messages->all() as $field => $message) {
                        $transformed[] = [
                            'message' => $message
                        ];
                    }
                    $message = trans('api.failed') ;
                    return   $this->FailedResponse($message , $transformed) ;
                     
                }
                else{
                    $contact = new ContactUs ;
        
                    $contact->name = $user->name ;
                    $contact->email = $user->email ;
                    $contact->title = $request->title ;
                    $contact->message = $request->message ;
                    $contact->status = 'new' ;
                    $contact->save();
                    $type = "contact";
                    // $title1 = "  مستخدم جديد قام بالتسجيل" ;
                    $msg =  [
                        'en' => "you have new message from ".  $request->name   ,
                        'ar' => "  لديك رسالة جديدة من " . $request->name   ,
                    ];
                    
                    $admins = User::where('role', 'admin')->get(); 
                    if(sizeof($admins) > 0){
                        foreach($admins as $admin){
                            $admin->notify(new Notifications($msg,$type ));
                        }
                        $device_token = $admin->device_token ;
                        if($device_token){
                            $this->notification($device_token,$msg,$msg);
                            $this->webnotification($device_token,$msg,$msg,$type);
                        }
                    }
                    $message = trans('api.send') ;
                    return $this->SuccessResponse($message , $contact) ;
        
                
                }
                
            }
            $message = trans('api.logout') ;
            return   $this->LoggedResponse($message ) ;
        }
        

    }
/////////////////////////////////////////////////////
// Terms and Conditions function by Antonious hosny
    public function TermsConditions(Request $request){
        $lang = $request->header('lang');
        $term = Doc::where('type','terms')->first();
        $terms =[] ;
        if($term){
            if($lang == 'ar'){
                $terms['title'] = $term->title_ar ; 
                $terms['disc'] = $term->disc_ar ; 
            }else{
                $terms['title'] = $term->title_en ;      
                $terms['disc'] = $term->disc_en ;      
            }    
        }

        $polcy = Doc::where('type','policy')->first();
        $policies =[] ;
        if($polcy){
            if($lang == 'ar'){
                $policies['title'] = $polcy->title_ar ; 
                $policies['disc'] = $polcy->disc_ar ; 
            }else{
                $policies['title'] = $polcy->title_en ;      
                $policies['disc'] = $polcy->disc_en ;      
            }    
        }
        
        $data['terms'] = $terms ;
        $data['policies'] = $policies ;

        $message = trans('api.fetch') ;
        return $this->SuccessResponse($message , $data) ;
           
                
   
    

    }
///////////////////////////////////////////////////
// AboutUs function by Antonious hosny
    public function AboutUs(Request $request){
        $lang = $request->header('lang');
        $doc = Doc::where('type','about')->first();
        $docss =[] ;
        if($doc){
            if($lang == 'ar'){
                $docss['title'] = $doc->title_ar ; 
                $docss['disc'] = $doc->disc_ar ; 
            }else{
                $docss['title'] = $doc->title_en ;      
                $docss['disc'] = $doc->disc_en ;      
            }    
        }
        $data['about'] = $docss ;
         
        $message = trans('api.fetch') ;
        return $this->SuccessResponse($message , $data) ;
       


    }
///////////////////////////////////////////////////
// AboutUs function by Antonious hosny
    public function SocialContacts(Request $request){
        $lang = $request->header('lang');
        $contacts= Contact::first();
        if($contacts){
            return response()->json([
                'success' => 'success',
                'errors' => null ,
                'message' => trans('api.fetch'),
                'data' =>  $contacts,
                    
            ]);
        }else{
            return response()->json([
                'success' => 'failed',
                'errors' => null ,
                'message' => trans('api.notfound'),
                'data' =>  null,
                    
            ]);
        }
        


    }
///////////////////////////////////////////////////
// count_notification function by Antonious hosny
    public function count_notification(Request $request){
        $lang = $request->header('lang');
        $token = $request->header('token');
        date_default_timezone_set('Africa/Cairo');
         // return $token ;
        if($token == ''){
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }
        $user = User::where('remember_token',$token)->first();
        // $user->notify(new Notifications());
        // return $user ;
        if($user){
            $user->lang  = $lang ;
            $user->save();
            $count = count($user->unreadnotifications) ;
            // return $count ;

            $message = trans('api.fetch') ;
            return  $this->SuccessResponse($message , $count) ;
             
        }
        else{
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }

    }
/////////////////////////////////////////////////////////
// get_notification function by Antonious hosny
    public function get_notification(Request $request){
        date_default_timezone_set('Africa/Cairo');
        $token = $request->header('token');
        if($token == ''){
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }
        $user = User::where('remember_token',$token)->first();
        // $user->notify(new Notifications());
        // return $user ;
        if($user){
            $notifications = $user->notifications->take(25)  ;
            foreach($user->unreadnotifications as $note){
                $note->markAsRead();
            }
            // return $count ;
            $message = trans('api.fetch') ;
            return  $this->SuccessResponse($message , $notifications) ;
            
        }
        else{
            $message = trans('api.logged_out') ;
            return  $this->LoggedResponse($message ) ;
        }

    }
/////////////////////////////////////////////////////////




////////////////////////////////////////////for test only //////////////////////////////////
// for test send_notifications
    public function send_notifications(Request $request){
        // $request->device_id;
            $rules=array(
                        'device_id'          => 'required',
                    );
                        $Messages = [
                    ];
                    //check the validator true or not
                    $validator  = Validator::make($request->all(),$rules,$Messages);
        $device_id = $request->device_id;
        // $msg = "you have message from backend";
        // $title = "test";
         
            
        
        $msg =  [
            'en' =>  "  agreed to deliver the request"  ,
            'ar' =>   "  قام بالموافقة علي توصيل الطلب"  ,
        ];
        $title = [
            'en' =>  "  agreed to deliver the request"  ,
            'ar' =>   "  قام بالموافقة علي توصيل الطلب"  ,
        ];
        $this->notification($device_id,$title,$msg);
        
        return response()->json([
            'message' => 'done'
        ]);

    }
////////////////////////////////////////////////////////
// for test send_notifications
    public function webnotifications(Request $request){
        // $request->device_id;
            $rules=array(
                        'device_id'          => 'required',
                    );
            $Messages = [
                    ];
                    //check the validator true or not
        $validator  = Validator::make($request->all(),$rules,$Messages);
        $device_id = $request->device_id;
        // $msg = "you have message from backend";
        // $title = "test";
        
        // $msg =  'لديك طلب جديد من '  ;

        // $title = 'طلب جديد';
        $type = "order" ;

        $msg =  [
            'en' => "New user registered"  ,
            'ar' => "  مستخدم جديد قام بالتسجيل"  ,
        ];
        $title = [
            'en' =>  "New user registered"  ,
            'ar' => "  مستخدم جديد قام بالتسجيل"  ,  
        ];
        $this->webnotification($device_id,$title,$msg, $type);
        
        return response()->json([
            'message' => 'done'
        ]);

    }

////////////////////////////////////////////////////////
   
}
