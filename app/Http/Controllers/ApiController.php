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
use App\Reason;

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
    private $objuser;
    

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
                $data['code'] = $code ;
                $data['IsRegistered'] = 1 ;
                $message = trans('api.send_code') ;
                return $this->SuccessResponse($message , $data) ;
               
                 
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
                $data['code'] = $code ;
                $data['IsRegistered'] = 0 ;

                $message = trans('api.mobile_notfound') ;
                return $this->SuccessResponse($message , $data) ;
    
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
                // return $user ;
                $technicians = Technician::whereDate('renewal_date','>=',$date)->where('service_id',$request->service_id)->whereHas('hasuser')->with('hasuser')->with('nationality')->orderBy('id', 'desc')->skip($page)->limit($request->skip)->get();
                $technicians_count = Technician::whereDate('renewal_date','>=',$date)->where('service_id',$request->service_id)->whereHas('hasuser')->with('hasuser')->count('id');
                // return $technicians ;
                $technicianss = [] ;
                $i =0 ;
                if(sizeof($technicians) > 0 ){
                    foreach($technicians as $technician){
                        $ratecount = Rate::where('evaluator_to',$technician->hasuser->id)->count('id');
                        $sumrates = Rate::where('evaluator_to',$technician->hasuser->id)->sum('rate');
                        if($ratecount != 0){
                            $rate =  $sumrates / $ratecount ;
                        }else{
                            $rate = 0 ;
                        }
                        $favorite = Favorite::where('user_id',$user->id)->where('fannie_id',$technician->hasuser->id)->first();
                        if($favorite){
                            $isFavorate = 1 ;
                        }else{
                            $isFavorate = 0 ;
                        }

                        $technicianss[$i]['worker_id'] = $technician->hasuser->id ;    
                        $technicianss[$i]['worker_name'] = $technician->hasuser->name ; 
                        $technicianss[$i]['lat'] = $technician->hasuser->lat ; 
                        $technicianss[$i]['lng'] = $technician->hasuser->lng ; 
                        $technicianss[$i]['rate'] =   $rate ; 
                        $technicianss[$i]['isFavorite'] =  $isFavorate; 
                        if($technician->nationality){
                            if($lang == 'ar'){
                                $technicianss[$i]['nationality'] = $technician->nationality->name_ar ; 
                            }else{
                                $technicianss[$i]['nationality'] = $technician->nationality->name_en ; 
                            }
                        }
                        $technicianss[$i]['available'] = $technician->available ; 
                        if($technician->hasuser->image){
                            $technicianss[$i]['image'] = asset('img/').'/'. $technician->hasuser->image;
                        }else{
                            $technicianss[$i]['image'] = null ;
                        }

                        $i ++ ;                    
                        
                    }
                }
                $data['technicians'] = $technicianss ;
                $data['count_technicians'] = $technicians_count ;

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
// NearstWorkers function by Antonious hosny
    public function NearstWorkers(Request $request){
        $rules=array(
            "service_id"=>"required",
            "lat"=>"required",
            "lng"=>"required",
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
        
        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                // return $user ;
                $technicians = Technician::whereDate('renewal_date','>=',$date)->where('service_id',$request->service_id)->whereHas('hasuser')->with('hasuser')->with('nationality')->orderBy('id', 'desc')->get();
                 // return $technicians ;
                $technicianss = [] ;
                $i =0 ;
                if(sizeof($technicians) > 0 ){
                    foreach($technicians as $technician){
                        $distance = $this->GetDistance($request->lat,$technician->hasuser->lat,$request->lng,$technician->hasuser->lng,'k');
                        // return $distance ;
                        if($distance <= 20){

                            $ratecount = Rate::where('evaluator_to',$technician->hasuser->id)->count('id');
                            $sumrates = Rate::where('evaluator_to',$technician->hasuser->id)->sum('rate');
                            if($ratecount != 0){
                                $rate =  $sumrates / $ratecount ;
                            }else{
                                $rate = 0 ;
                            }
                            $favorite = Favorite::where('user_id',$user->id)->where('fannie_id',$technician->hasuser->id)->first();
                            if($favorite){
                                $isFavorate = 1 ;
                            }else{
                                $isFavorate = 0 ;
                            }
    
                            $technicianss[$i]['worker_id'] = $technician->hasuser->id ;    
                            $technicianss[$i]['worker_name'] = $technician->hasuser->name ; 
                            $technicianss[$i]['lat'] = $technician->hasuser->lat ; 
                            $technicianss[$i]['lng'] = $technician->hasuser->lng ; 
                            $technicianss[$i]['rate'] =   $rate ; 
                            $technicianss[$i]['isFavorite'] =  $isFavorate; 
                            $technicianss[$i]['distance'] =  round($distance,2). __('api.km'); 
                            if($technician->nationality){
                                if($lang == 'ar'){
                                    $technicianss[$i]['nationality'] = $technician->nationality->name_ar ; 
                                }else{
                                    $technicianss[$i]['nationality'] = $technician->nationality->name_en ; 
                                }
                            }
                            $technicianss[$i]['available'] = $technician->available ; 
                            if($technician->hasuser->image){
                                $technicianss[$i]['image'] = asset('img/').'/'. $technician->hasuser->image;
                            }else{
                                $technicianss[$i]['image'] = null ;
                            }
    
                            $i ++ ;                    
                        }
                        
                    }
                }
                $data['technicians'] = $technicianss ;
 
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
// AvailableWorkers function by Antonious hosny
    public function AvailableWorkers(Request $request){

        $rules=array(
            "service_id"=>"required",
            "time"=>"required",
            "date"=>"required",
            "skip"=>"required",
            "page"=>"required",
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

        $day  = date('D', strtotime($request->date));
        $time  =  $request->time;
        $service =  $request->service_id;
        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                // return $user ;
                $technicians = User::where('status','active')->whereHas('availabledate', function ($query) use ($day,$time) {
                    $query->where('day', $day)->where('from','<',$time)->where('to','>',$time);
                })->with('availabledate')->whereHas('technician', function ($query) use ($service) {
                    $query->where('service_id', $service);
                })->with('technician')->skip($page)->limit($request->skip)->get();

                $count_technicians = User::where('status','active')->whereHas('availabledate', function ($query) use ($day,$time) {
                    $query->where('day', $day)->where('from','<',$time)->where('to','>',$time);
                })->with('availabledate')->whereHas('technician', function ($query) use ($service) {
                    $query->where('service_id', $service);
                })->with('technician')->skip($page)->limit($request->skip)->count('id');
                // return $technicians ;
                $technicianss = [] ;
                $i =0 ;
                if(sizeof($technicians) > 0 ){
                    foreach($technicians as $technician){
                        

                        $ratecount = Rate::where('evaluator_to',$technician->id)->count('id');
                        $sumrates = Rate::where('evaluator_to',$technician->id)->sum('rate');
                        if($ratecount != 0){
                            $rate =  $sumrates / $ratecount ;
                        }else{
                            $rate = 0 ;
                        }
                        $favorite = Favorite::where('user_id',$user->id)->where('fannie_id',$technician->id)->first();
                        if($favorite){
                            $isFavorate = 1 ;
                        }else{
                            $isFavorate = 0 ;
                        }

                        $technicianss[$i]['worker_id'] = $technician->id ;    
                        $technicianss[$i]['worker_name'] = $technician->name ; 
                        $technicianss[$i]['lat'] = $technician->lat ; 
                        $technicianss[$i]['lng'] = $technician->lng ; 
                        $technicianss[$i]['rate'] =   $rate ; 
                        $technicianss[$i]['isFavorite'] =  $isFavorate; 
                        if($technician->technician->nationality){
                            if($lang == 'ar'){
                                $technicianss[$i]['nationality'] = $technician->technician->nationality->name_ar ; 
                            }else{
                                $technicianss[$i]['nationality'] = $technician->technician->nationality->name_en ; 
                            }
                        }
                        $technicianss[$i]['available'] = $technician->technician->available ; 
                        if($technician->image){
                            $technicianss[$i]['image'] = asset('img/').'/'. $technician->image;
                        }else{
                            $technicianss[$i]['image'] = null ;
                        }

                        $i ++ ;                    
                      
                        
                    }
                }
                $data['technicians'] = $technicianss ;
                $data['count_technicians'] = $count_technicians ;
                
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
// WorkerDetail function by Antonious hosny
    public function WorkerDetail(Request $request){

        $rules=array(
            "fannie_id"=>"required",
            "lat"=>"required",
            "lng"=>"required",
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

        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                // return $user ;
                $technician = User::where('id',$request->fannie_id)->with('technician')->with('rates')->with('fannieorders')->first();
                // return $technician ;
                $technicianss = [] ;
                if($technician ){
                    $distance = $this->GetDistance($request->lat,$technician->lat,$request->lng,$technician->lng,'k');


                    $ratecount = Rate::where('evaluator_to',$technician->id)->count('id');
                    $sumrates = Rate::where('evaluator_to',$technician->id)->sum('rate');
                    if($ratecount != 0){
                        $rate =  $sumrates / $ratecount ;
                    }else{
                        $rate = 0 ;
                    }
                    $favorite = Favorite::where('user_id',$user->id)->where('fannie_id',$technician->id)->first();
                    if($favorite){
                        $isFavorate = 1 ;
                    }else{
                        $isFavorate = 0 ;
                    }

                    $technicianss['worker_id'] = $technician->id ;    
                    $technicianss['worker_name'] = $technician->name ; 
                    $technicianss['lat'] = $technician->lat ; 
                    $technicianss['lng'] = $technician->lng ; 
                    $technicianss['rate'] =   $rate ; 
                    $technicianss['distance'] =   round($distance,2). __('api.km'); 
                    $technicianss['isFavorite'] =  $isFavorate; 
                    $technicianss['count_orders'] = count( $technician->fannieorders ) ;
                    if($technician->technician->nationality){
                        if($lang == 'ar'){
                            $technicianss['nationality'] = $technician->technician->nationality->name_ar ; 
                        }else{
                            $technicianss['nationality'] = $technician->technician->nationality->name_en ; 
                        }
                    }
                    if($technician->technician->service){
                        $technicianss['service_id'] = $technician->technician->service->id ; 
                        if($lang == 'ar'){
                            $technicianss['service'] = $technician->technician->service->name_ar ; 
                        }else{
                            $technicianss['service'] = $technician->technician->service->name_en ; 
                        }
                    }
                    $technicianss['available'] = $technician->technician->available ; 
                    $technicianss['brief'] = $technician->technician->brief ; 
                    if($technician->image){
                        $technicianss['image'] = asset('img/').'/'. $technician->image;
                    }else{
                        $technicianss['image'] = null ;
                    }
                    $ratess = [] ;
                    $i = 0 ;
                    if(sizeof($technician->rates) > 0){
                        foreach($technician->rates as $rate){
                            // return $rate->evaluatorfrom ;
                            if( $rate->evaluatorfrom){
                                $ratess[$i]['user_name'] = $rate->evaluatorfrom->name ;
                                if($rate->evaluatorfrom->image){
                                    $ratess[$i]['image'] = asset('img/').'/'. $rate->evaluatorfrom->image;
                                }else{
                                    $ratess[$i]['image'] = null ;
                                }
                            }else{
                                $ratess[$i]['user_name'] = '' ;
                                $ratess[$i]['image'] = null ;
                            }
                            $ratess[$i]['rate'] = $rate->rate ;
                            $ratess[$i]['notes'] = $rate->notes ;
                            $ratess[$i]['date'] = $rate->created_at ;
                            $i++;
                        }
                    }
                    $technicianss['rates'] = $ratess ;
                    
                        
                }
                $data['technician'] = $technicianss ;
                 
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
// Favorite function by Antonious hosny
    public function Favorite(Request $request){

        $rules=array(
            "fannie_id"=>"required"
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

        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                // return $user ;
                $data['isFavorite'] = 0;
                $technician = User::where('id',$request->fannie_id)->first();
                $favorite = Favorite::where('user_id',$user->id)->where('fannie_id',$technician->id)->first();
                if($favorite){
                     $favorite->delete() ;
                    $data['isFavorite'] = 0 ;

                }else{

                    $favorite = new Favorite;
                    $favorite->user_id = $user->id ;
                    $favorite->fannie_id = $technician->id ;
                    $favorite->save() ;
                    $data['isFavorite'] = 1 ;
 
                }
                $message = trans('api.save') ;
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
// MyFavorites function by Antonious hosny
    public function MyFavorites(Request $request){

        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                // return $user ;
                $favoritess = [] ;
                $i = 0 ;
                $favorites = Favorite::where('user_id',$user->id)->with('fannie')->get();
                if(sizeOf($favorites) > 0){
                    foreach($favorites as $favorite){
                        if($favorite->fannie){
                            
                            $ratecount = Rate::where('evaluator_to',$favorite->fannie->id)->count('id');
                            $sumrates = Rate::where('evaluator_to',$favorite->fannie->id)->sum('rate');
                            if($ratecount != 0){
                                $rate =  $sumrates / $ratecount ;
                            }else{
                                $rate = 0 ;
                            }
                            $favoritess[$i]['worker_id']  =  $favorite->fannie->id;
                            $favoritess[$i]['worker_name']  =  $favorite->fannie->name;
                            if( $favorite->fannie->technician){
                                $favoritess[$i]['available']  = $favorite->fannie->technician->available ; 
                                if( $favorite->fannie->technician->nationality){
                                    if($lang == 'ar'){
                                        $favoritess[$i]['nationality']  =  $favorite->fannie->technician->nationality->name_ar;
                                    }else{
                                        $favoritess[$i]['nationality']  =  $favorite->fannie->technician->nationality->name_en;
                                    }
                                }else{
                                    $favoritess[$i]['nationality']  = ' '; 
                                }
                            }else{
                                $favoritess[$i]['nationality']  = ' '; 
                                $favoritess[$i]['available']  = 0 ;
                            }
                            
                            if($favorite->fannie->image){
                                $favoritess[$i]['image'] = asset('img/').'/'. $favorite->fannie->image;
                            }else{
                                $favoritess[$i]['image'] = null ;
                            }

                            $favoritess[$i]['rate']  =  $rate ;
                            if($request->lat && $request->lng){
                                $distance = $this->GetDistance($request->lat,$favorite->fannie->lat,$request->lng,$favorite->fannie->lng,'k');
                                $favoritess[$i]['distance'] = round($distance,2). __('api.km'); 
                            }
                            $i ++ ;
                        }
                    }
                }

                $data['favorites'] = $favoritess ;
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

// RequestOrder function by Antonious hosny
    public function RequestOrder(Request $request){

        $rules=array(
            "service_id"=>"required",
            "fannie_id"=>"required",
            "lat"=>"required",
            "lng"=>"required",
            "address"=>"required",
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

        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                // return $user ;
                
                $order = New Order ;
                $order->user_id = $user->id ;
                $order->fannie_id = $request->fannie_id ;
                $order->service_id = $request->service_id ;
                $order->lat = $request->lat ;
                $order->lng = $request->lng ;
                $order->address = $request->address ;
                $order->notes = $request->notes ;
                $order->date = $request->date ;
                $order->time = $request->time ;
                $order->status  = 'pending' ;
                $order->save() ;

                $type = "order";
                $msg =  [
                    'en' => "  You have a new request from " . $user->name ." Order number ". $order->id  , 
                    'ar' =>  "  لديك طلب جديد من " . $user->name ."  رقم الطلب ". $order->id,
                ];
                $title = [
                    'en' =>  "  You have a new request from  " . $user->name ,
                    'ar' =>  "  لديك طلب جديد من " . $user->name ,  
                ];
                $fannie = User::where('id', $request->fannie_id)->first(); 
                $fannie->notify(new Notifications($msg,$type ));
                $device_token = $fannie->device_token ;
                if($device_token){
                    $this->notification($device_token,$msg,$msg);
                    $this->webnotification($device_token,$msg,$msg,$type);
                }
                
                $data['order'] = $order ;

                $message = trans('api.save') ;
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
// MyOrders function by Antonious hosny
    public function MyOrders(Request $request){
        $rules=array(
            "lat"=>"required",
            "lng"=>"required",
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
        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                if($user->role == 'user'){
                    $orders = Order::where('user_id',$user->id)->with('fannie')->with('user')->with('service')->get();
                }else{
                    $orders = Order::where('fannie_id',$user->id)->with('fannie')->with('user')->with('service')->get();
                }
                // return $orders ;
                
                $orderss = [];
                $i = 0; 
                if(sizeof($orders) > 0){
                    foreach($orders as $order){
                        $orderss[$i]['order_id'] = $order->id ;
                        $orderss[$i]['status'] = $order->status ;
                        $orderss[$i]['notes'] = $order->notes ;
                        $orderss[$i]['created_at'] = $order->created_at->format('Y-d-m H:i:s') ;
                        if($order->fannie){
                            $distance = $this->GetDistance($request->lat,$order->fannie->lat,$request->lng,$order->fannie->lng,'k');
                            
                            $ratecount = Rate::where('evaluator_to',$order->fannie->id)->count('id');
                            $sumrates = Rate::where('evaluator_to',$order->fannie->id)->sum('rate');
                            if($ratecount != 0){
                                $rate =  $sumrates / $ratecount ;
                            }else{
                                $rate = 0 ;
                            }

                            $favorite = Favorite::where('user_id',$user->id)->where('fannie_id',$order->fannie->id)->first();
                            if($favorite){
                                $isFavorite = 1 ;
                            }else{
                                $isFavorite = 0 ;
                            }
                            

                            $orderss[$i]['fannie_id']    =  $order->fannie->id;
                            $orderss[$i]['fannie_name']  = $order->fannie->name;
                            $orderss[$i]['fannie_mobile']  = $order->fannie->mobile;
                            $orderss[$i]['rate']  = $rate;
                            $orderss[$i]['isFavorite']  = $isFavorite;
                            $orderss[$i]['distance']     =  round($distance,2). __('api.km'); 
                            $orderss[$i]['fannie_image'] = asset('img/').'/'.$order->fannie->image;
                        }else{
                            $orderss[$i]['fannie_id']    = null;
                            $orderss[$i]['fannie_name']  = null;
                            $orderss[$i]['fannie_mobile']  = null;
                            $orderss[$i]['rate']  = 0 ;
                            $orderss[$i]['isFavorite']  = 0 ;
                            $orderss[$i]['distance']     = null ;
                            $orderss[$i]['fannie_image'] = null;
                        }
                        if($order->service){
                            $orderss[$i]['service_id']   = $order->service->id;
                            if($lang == 'ar')
                            $orderss[$i]['service_name'] = $order->service->name_ar;
                            else
                            $orderss[$i]['service_name'] = $order->service->name_en;
    
                            $orderss[$i]['service_image']= asset('img/').'/'.$order->service->image;
                        }else{
                            $orderss[$i]['service_id']   = null;
                            $orderss[$i]['service_name'] = null;
                            $orderss[$i]['service_image']= null;
                        }
                        if($order->user){
                            $orderss[$i]['user__id']   = $order->user->id;
                            $orderss[$i]['user_name'] = $order->user->name;
                            $orderss[$i]['user_mobile'] = $order->user->mobile;
                            $orderss[$i]['user_image']= asset('img/').'/'.$order->user->image;
                        }else{
                            $orderss[$i]['user__id']   = null;
                            $orderss[$i]['user_name'] = null;
                            $orderss[$i]['user_mobile']= null;
                            $orderss[$i]['user_image']= null;
                        }
                        $i ++ ;
                    }
                }
                
                $data['orders'] = $orderss ;

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
// CancelationReason function by Antonious hosny
    public function CancelationReason(Request $request){
       
        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                $reasons = Reason::where('type','reason')->get();
                // return $orders ;
                
                $reasonss = [];
                $i = 0; 
                if(sizeof($reasons) > 0){
                    foreach($reasons as $reason){
                        if($lang == 'ar'){

                            $reasonss[$i]['title'] = $reason->title_ar;
                        }
                        else{

                            $reasonss[$i]['title'] = $reason->title_en;
                        }
                        $i ++ ;
                    }
                }
                
                $data['reasons'] = $reasonss ;

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
// CanceleOrder function by Antonious hosny
    public function CanceleOrder(Request $request){
        $rules=array(
            "order_id"=>"required",
            "reason"=>"required",
        );
        $dt = Carbon::now();
        $date  = date('Y-m-d H:i:s', strtotime($dt));
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
        $token = $request->header('token');
        $lang = $request->header('lang');

        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){
                $order = Order::where('id',$request->order_id)->first();
                if($order){
                    $order->status = 'canceled' ;
                    $order->rejected_reason = $request->reason ;
                    $order->rejected_date = $date ;
                    
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
                    $fannie = User::where('id', $order->fannie_id)->first(); 
                    if($fannie){

                        $fannie->notify(new Notifications($msg,$type ));
                        $device_token = $fannie->device_token ;
                        if($device_token){
                            $this->notification($device_token,$msg,$msg);
                            $this->webnotification($device_token,$msg,$msg,$type);
                        }
                    }
                }
                
                $data['order'] = $order ;

                $message = trans('api.save') ;
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
                        'en' => "you have new message from ".  $user->name   ,
                        'ar' => "  لديك رسالة جديدة من " . $user->name   ,
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
        $message = trans('api.logout') ;
        return   $this->LoggedResponse($message ) ;

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
