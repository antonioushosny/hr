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
use App\CenterContainer;
use App\City;
use App\Contact;
use App\ContactUs;
use App\Container;
use App\Country ; 
use App\Doc;
use App\Order;
use App\OrderCenter ; 
use App\OrderDriver ; 
use App\PasswordReset ; 
use App\User;

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
        date_default_timezone_set('UTC');
        $this->middleware('guest')->except('logout');
    }
//////////////////////////////////////////////
// Advertisement function by Antonious hosny
    public function Advertisements(Request $request){ 
        $advertisements  = Advertisement::where('status','active')->orderBy('id', 'desc')->get();
            if(sizeof($advertisements) > 0){
                $advertisementss =[];
                $i = 0 ;

                foreach($advertisements as $advertisement){
                    if($advertisement){
                        // $advertisementss[$i]['link'] = $advertisement->link;
                        // $advertisementss[$i]['time'] = $advertisement->time;
                        if($advertisement->image != null){
                            $advertisementss[$i]['image']   = asset('img/').'/'. $advertisement->image;
                        }
                        else{
                            $advertisementss[$i]['image']   = null;
                        }
                        $i ++ ;
                       
                    }
                }
                return response()->json([
                    'success' => 'success',
                    'errors' => null,
                    'message' => trans('api.fetch'),
                    'data' => $advertisementss
                ]);
            }
            else
            {
                $errors=  trans('api.notfound');
                // $errors[] = trans('api.fail_login');
                return response()->json([
                    'success' => 'failed',
                    'errors' => $errors,
                    'message' => trans('api.notfound'),
                    'data' => null,

                ]);
            }


    }
//////////////////////////////////////////////
// Cities function by Antonious hosny
    public function Cities(Request $request){ 
        $lang = $request->header('lang');
        if($lang == 'ar'){
            $Cities  = City::where('status','active')->with('areas')->orderBy('name_ar', 'asc')->get();
        }else{
            $Cities  = City::where('status','active')->with('areas')->orderBy('name_en', 'asc')->get();
        }
            if(sizeof($Cities) > 0){
                $Citiess =[];
                $i = 0 ;

                foreach($Cities as $City){
                    if($City){
                        $Citiess[$i]['city_id']   = $City->id;
                        if($lang == 'ar'){
                            $Citiess[$i]['city_name']   = $City->name_ar;
                        }else{
                            $Citiess[$i]['city_name']   =  $City->name_en;
                        }
                        $areass = [] ;
                        $n  = 0 ;
                        if(sizeOf($City->areas) > 0){

                            foreach($City->areas as $area){
                                $areass[$n]['area_id']   = $area->id;
                                if($lang == 'ar'){
                                    $areass[$n]['area_name']   = $area->name_ar;
                                }else{
                                    $areass[$n]['area_name']   =  $area->name_en;
                                }
                                $n ++ ;
                        
                            }
                        }
                        $Citiess[$i]['areas'] = $areass ;
                        $i ++ ;
                    
                    }
                }
                return response()->json([
                    'success' => 'success',
                    'errors' => null,
                    'message' => trans('api.fetch'),
                    'data' => $Citiess
                ]);
            }
            else
            {
                $errors=  trans('api.notfound');
                return response()->json([
                    'success' => 'failed',
                    'errors' => $errors,
                    'message' => trans('api.notfound'),
                    'data' => null,

                ]);
            }

    }
//////////////////////////////////////////////
// Areas function by Antonious hosny
    public function Areas(Request $request){ 
        $lang = $request->header('lang');
        $rules=array(
            "city_id"=>"required",
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
            return response()->json([
                'success' => 'failed',
                'errors'=>$transformed,
                'message' => trans('api.failed_login'),
                'data' => null,

            ]);
        }
        if($lang == 'ar'){
            $areas  = Area::where('status','active')->where('city_id',$request->city_id)->orderBy('name_ar', 'asc')->get();
        }else{
            $areas  = Area::where('status','active')->where('city_id',$request->city_id)->orderBy('name_en', 'asc')->get();
        }
            if(sizeof($areas) > 0){
                $areass =[];
                $i = 0 ;

                foreach($areas as $area){
                    if($area){
                        $areass[$i]['area_id']   = $area->id;
                        if($lang == 'ar'){
                            $areass[$i]['area_name']   = $area->name_ar;
                        }else{
                            $areass[$i]['area_name']   =  $area->name_en;
                        }
                        $i ++ ;
                    
                    }
                }
                return response()->json([
                    'success' => 'success',
                    'errors' => null,
                    'message' => trans('api.fetch'),
                    'data' => $areass
                ]);
            }
            else
            {
                $errors=  trans('api.notfound');
                return response()->json([
                    'success' => 'failed',
                    'errors' => $errors,
                    'message' => trans('api.notfound'),
                    'data' => null,

                ]);
            }

    }
//////////////////////////////////////////////
// login function by Antonious hosny
    public function Login(Request $request){
        // return $request;
        // print time();
        $lang = $request->header('lang');
        // $this->validateLogin($request);
        $rules=array(
            "email"=>"required",
            "password"=>"required",
            "device_token"=>"required",
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
                    // 'field' => $field,
                    'message' => $message
                ];
            }
            return response()->json([
                'success' => 'failed',
                'errors'=>$transformed,
                'message' => trans('api.failed_login'),
                'data' => null,

            ]);
        }
        $user = User::where('email',$request->email)->where('role',$request->role)->first();
        // return $user;
        if(!$user){

            $errors =  trans('admin.email_notfound');
            return response()->json([
                'success' => 'failed',
                'errors' => $errors ,
                'message' => trans('admin.email_notfound'),
                'data' => null,

            ]);
        }
        else{
            if($user->password == null || $user->password == ''){
                return response()->json([
                    'success' => 'failed',
                    'errors' => trans('api.activate_account'),
                    'message' => trans('api.activate_account'),
                    'data' => null,
                ]);
            }
            if (\Hash::check( $request->password,$user->password)) {
                if($user->status == 'not_active'||$user->role == 'admin' ||$user->role == 'provider' ||$user->role == 'center'){
                    return response()->json([
                        'success' => 'failed',
                        'errors' => trans('api.allowed'),
                        'message' => trans('api.allowed'),
                        'data' => null,
                    ]);
                }
                $user->generateToken();
                $user->device_token = $request->device_token ;
                $user->type = $request->device_type ;
                $user->available = '1';

                $user->save();
                $user =  User::where('id',$user->id)->with('City')->with('Area')->first();
                $users = [] ;
                if($user){
                    $users['id'] = $user->id ;
                    $users['name'] = $user->name ;
                    $users['email'] = $user->email ;
                    $users['mobile'] = $user->mobile ;
                    if($user->City){
                        
                        if($lang == 'ar'){
                            $users['city_id'] = $user->City->id ;
                            $users['city_name']   = $user->City->name_ar;
                        }else{
                            $users['city_id'] = $user->City->id ;
                            $users['city_name']   = $user->City->name_en;
                        }
                    }else{
                        $users['city_id'] = null ;
                        $users['city_name']   =  null;
                    }
                    if($user->Area){
                        
                        if($lang == 'ar'){
                            $users['area_id'] = $user->Area->id ;
                            $users['area_name']   = $user->Area->name_ar;
                        }else{
                            $users['area_id'] = $user->Area->id ;
                            $users['area_name']   = $user->Area->name_en;
                        }
                    }else{
                        $users['area_id'] = null ;
                        $users['area_name']   =  null;
                    }
                    $users['lat'] = $user->lat ;
                    $users['lng'] = $user->lng ;
                    $users['role'] = $user->role ;
                    if($user->image){
                        $users['image'] = asset('img/').'/'. $user->image;
                    }
                    else {
                        $users['image'] = null;
                    }

                    $users['remember_token'] = $user->remember_token ;
                    
                }
                return response()->json([
                    'success' => 'success',
                    'errors' => null,
                    'message' => trans('api.login'),
                    'data' => $users
                ]);
            }
            else
            {
                $errors=  trans('api.password_failed');
                return response()->json([
                    'success' => 'failed',
                    'errors' => $errors,
                    'message' => trans('api.password_failed'),
                    'data' => null,

                ]);
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
            "password"=>"required|min:6",
            // "area_id"=>"required",
            // "city_id"=>"required",
            // "lat"=>"required",
            // "lng"=>"required",
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
            return response()->json([
                'success' => 'failed',
                'errors'  => $transformed,
                'message' => trans('api.failed_registered'),
                'data'    => null ,
            ]);
        }

            $user = new User;

            $password            = \Hash::make($request->password);
            $user->password      = $password ;
            $user->name          = $request->name ;
            $user->email         = $request->email ;
            $user->mobile        = $request->mobile ;
            $user->area_id        = $request->area_id ;
            $user->city_id        = $request->city_id ;
            $user->lat        = $request->lat ;
            $user->lng        = $request->lng ;
            $user->status        = 'active';
            $user->role          = 'user';
            $user->save();
            $user->generateToken();

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
            /////// this for verify email addreess/////////

            // $token = rand(100000,999999);
            // $PasswordReset = PasswordReset::where('email',$user->email)->first();
            // if(!$PasswordReset){
            //     $PasswordReset = new PasswordReset ;
            // }
            // $PasswordReset->email = $user->email ;
            // $PasswordReset->token = $token ;
            // $PasswordReset->save();
            // User::find($user->id)->notify(new verify_code($token));
            /////// end verify email addreess/////////
            $user =  User::where('id',$user->id)->with('country')->with('city')->first();
            if($user){
                $users = [] ;
                $users['id'] = $user->id ;
                $users['name'] = $user->name ;
                $users['email'] = $user->email ;
                $users['mobile'] = $user->mobile ;
                if($user->City){
                    if($lang == 'ar'){
                        $users['city_id'] = $user->City->id ;
                        $users['city_name']   = $user->City->name_ar;
                    }else{
                        $users['city_id'] = $user->City->id ;
                        $users['city_name']   = $user->City->name_en;
                    }
                }else{
                    $users['city_id'] = null ;
                    $users['city_name']   = null ;
                }
                if($user->Area){
                    if($lang == 'ar'){
                        $users['area_id'] = $user->Area->id ;
                        $users['area_name']   = $user->Area->name_ar;
                    }else{
                        $users['area_id'] = $user->Area->id ;
                        $users['area_name']   = $user->Area->name_en;
                    }
                }else{
                    $users['area_id'] = null ;
                    $users['area_name']   = null ;
                }
                $users['lat'] = $user->lat ;
                $users['lng'] = $user->lng ;
                $users['role'] = $user->role ;
                $users['remember_token'] = $user->remember_token ;
                
            }
            return response()->json([
                'success' => 'success',
                'errors'=> null ,
                'message' => trans('api.success_registered'),
                'data' => $users ,
            ]);
        
    }
//////////////////////////////////////////////
// editprofile function by Antonious hosny
    public function EditProfile(Request $request){
        // return $request ;
        $lang = $request->header('lang');
        $token = $request->token;
        if($token == ''){
            $errors[] = [
                'message' => trans('api.logged_out')
            ]; 
            return response()->json([
                'success' => 'failed',
                'errors' => $errors ,
                'message' => trans('api.logged_out'),
                'user' => null,

            ]);
        }  
        $user = User::where('remember_token',$token)->first();
        if($user){      
            $rules=array(  
                "name"=>"min:3",
                // 'profile_pic' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // "mobile"=>"digits:10",
                "password" => "min:6",
                "email"=> 'email'
            );
            $user = User::where('id',$user->id)->first();
            if($request->mobile){
                if( $request->mobile != $user->mobile){
                    $rules['mobile'] = 'unique:users,mobile';
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
                return response()->json([
                    'success' => 'failed',
                    'errors'=>$transformed,
                    'message' => trans('api.failed'),
                    'user' =>  null ,
                ]);
            }

            if($request->password){
                $password = \Hash::make($request->password);
                $user->password = $password ;
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
            if($request->city_id){
                $user->city_id           = $request->city_id ;
            }
            if($request->lat){
                $user->lat           = $request->lat ;
            }
            if($request->lng){
                $user->lng           = $request->lng ;
            }
            if($request->area_id){
                $user->area_id           = $request->area_id ;
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
            $user =  User::where('id',$user->id)->with('City')->with('Area')->first();
            $users = [] ;
            if($user){
                $users['id'] = $user->id ;
                $users['name'] = $user->name ;
                $users['email'] = $user->email ;
                $users['mobile'] = $user->mobile ;
                if($user->City){
                    
                    if($lang == 'ar'){
                        
                        $users['city_id'] = $user->City->id ;
                        $users['city_name']   = $user->City->name_ar;
                    }else{
                        $users['city_id'] = $user->City->id ;
                        $users['city_name']   = $user->City->name_en;
                    }
                }else{
                    $users['city_id'] = null ;
                    $users['city_name']   =  null;
                }
                if($user->Area){
                    
                    if($lang == 'ar'){
                        $users['area_id'] = $user->Area->id ;
                        $users['area_name']   = $user->Area->name_ar;
                    }else{
                        $users['area_id'] = $user->Area->id ;
                        $users['area_name']   = $user->Area->name_en;
                    }
                }else{
                    $users['area_id'] = null ;
                    $users['area_name']   =  null;
                }
                $users['lat'] = $user->lat ;
                $users['lng'] = $user->lng ;
                $users['role'] = $user->role ;
                $users['remember_token'] = $user->remember_token ;
                
            }
            return response()->json([
                'success' => 'success',
                'errors'=> null ,
                'message' => trans('api.save'),
                'user' => $users ,
            ]);
        }
        else{
            $errors[] = [
                'message' => trans('api.logged_out')
            ]; 
            return response()->json([
                'success' => 'failed',
                'errors' => $errors ,
                'message' => trans('api.logged_out'),
                'user' => null,

            ]);

        }

    }
///////////////////////////////////////////////////
// logout function by Antonious hosny
    public function Logout(Request $request){
        $token = $request->token;
        if($token == ''){
        }
        // $token = $request->header('access_token');
        $user = User::where('remember_token',$token)->first();
        if ($user) {
            $user->remember_token = null;
            $user->device_token = null;
            $user->available = '0';
            $user->save();
            return response()->json([
                'success' => 'success',
                'errors' => null,
                "message"=>trans('api.logout'),
                ]);
        }else{
            return response()->json([
                'success' => 'logged',
                'errors' => trans('api.logout'),
                "message"=>trans('api.logout'),
                ]);
        }

    }
//////////////////////////////////////////////////
// forget_password function by Antonious hosny
    public function ForgetPassword(Request $request){
        $rules['email'] = 'required';
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
                'errors'=>$transformed,
                'message' => trans('api.failed'), 
                'user' =>  null ,
            ]);
        }
        $email = $request->email;
        $user = User::where('email',$email)->first();
        if($user){
            $token = rand(100000,999999);
            $PasswordReset = PasswordReset::where('email',$user->email)->first();
            if(!$PasswordReset){
                $PasswordReset = new PasswordReset ;
            }
            $PasswordReset->email = $user->email ;
            $PasswordReset->token = $token ;
            $PasswordReset->save();
            User::find($user->id)->notify(new verify_code($token));
            return response()->json([
                'success' => 'success',
                'errors'  => null,
                "message"=>trans('api.send_token'), 
                ]);

        }
        $error = trans('api.user_notfound');
        return response()->json([
            'success' => 'failed',
            'errors'  =>  $error,
            "message"=>trans('api.user_notfound'),

            ]);
        
    }
//////////////////////////////////////////////////
// verify_code function by Antonious hosny
    public function VerifyCode(Request $request){
        $code = $request->code;
        if($code){
            $PasswordReset = PasswordReset::where('token',$code)->first();
            // return $PasswordReset ;
            if ($PasswordReset) {
                $user = User::where('email',$PasswordReset->email)->first();
                if($user){
                    $user->status = 'active' ;
                    $user->save();
                }
                return response()->json([
                    'success' => 'success',
                    'errors'  => null,
                    "message"=>trans('api.success_code'),
                    'code' => $code 

                ]);
            }
            else{
                $error = trans('api.incorrect_code');
                return response()->json([
                    'success' => 'failed',
                    'errors'  =>  $error,
                    "message"=>trans('api.incorrect_code'),
                    'code' => null
                ]);
            }
            
        }
        $error = trans('api.code_requird');
        return response()->json([
            'success' => 'failed',
            'errors'  =>  $error,
            "message"=>trans('api.code_requird'),
            'code' => null
        ]);
        
    }
///////////////////////////////////////////////////
// reset_password function by Antonious hosny
    public function ResetPassword(Request $request){
        $code = $request->code;
        if($code){
            $PasswordReset = PasswordReset::where('token',$code)->first();
            if($PasswordReset){
                $user = User::where('email',$PasswordReset->email)->first();
            }
            
            else{
                $error = trans('api.code_notfound');
                return response()->json([
                    'success' => 'failed',
                    'errors'  => $error,
                    "message"=>trans('api.code_notfound'),

                ]);
            }
            if ($user) {
                if($request->password){
                    $password = \Hash::make($request->password);
                    $user->password = $password ;
                    // $user->generateToken();
                    $user->save();
                    
                    return response()->json([
                        'success' => 'success',
                        'errors'  => null,
                        "message"=>trans('api.passwordsucces')
                    ]);
                }
                else{
                    $error = trans('api.password_requird');
                    return response()->json([
                        'success' => 'failed',
                        'errors'  => $error,
                        "message"=>trans('api.password_requird'),
                
                    ]);
                }
            
                // User::find($user->id)->notify(new verify_code($user->verify_code ));
                // return $user;
                
            }
            $error = trans('api.code_notfound');
            return response()->json([
                'success' => 'failed',
                'errors'  => $error,
                "message"=>trans('api.code_notfound'),
                

            ]);
        }
        $error = trans('api.code_required');
        return response()->json([
            'success' => 'failed',
            'errors'  => $error,
            "message"=>trans('api.code_required'),
        

        ]);
        
    }
/////////////////////////////////////////////////
// Containers function by Antonious hosny
    public function Containers(Request $request){
        if($request->page && $request->page > 0 ){
            $skip = $request->page.'0' ;
        }else{
            $skip = 0 ;
        }
        $token = $request->token;
        $lang = $request->header('lang');
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $time  = date('H:i:s', strtotime($dt));
        if($token){
            $user = User::where('remember_token',$token)->first();
            if($user){

                $containers = Container::where('status','active')->orderBy('id', 'desc')->skip($skip)->limit(10)->get();
                $containers_count = Container::where('status','active')->count('id');
                // return $containers_count ;
                $containerss = [] ;
                $i =0 ;
                if(sizeof($containers) > 0 ){
                    foreach($containers as $container){
                        
                        $containerss[$i]['container_id'] = $container->id ;    
                        if($lang == 'ar'){
                            $containerss[$i]['container_name'] = $container->name_ar ; 
                            $containerss[$i]['container_desc'] = $container->desc_ar ; 
                        }else{
                            $containerss[$i]['container_name'] = $container->name_en ; 
                            $containerss[$i]['container_desc'] = $container->desc_en ; 
                        }
                        if($container->image){
                            $containerss[$i]['image'] = asset('img/').'/'. $container->image;
                        }else{
                            $containerss[$i]['image'] = null ;
                        }
                        $containerss[$i]['size'] = $container->size ; 

                        $i ++ ;                    
                        
                    }
                }
                return response()->json([
                    'success' => 'success',
                    'errors' => null ,
                    'message' => trans('api.fetch'),
                    'data' => [
                        'containers' => $containerss,
                        'containers_count' => $containers_count,
                        ],
    
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
// MakeOrder function by Antonious hosny
    public function MakeOrder(Request $request){
        $token = $request->token;
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
                    'city_id'    => 'required',
                    'area_id'    => 'required',
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
                        'en' => "  You have a new request from" . $user->name ,
                        'ar' =>  "  لديك طلب جديد من " . $user->name ,
                    ];
                    $title = [
                        'en' =>  "  You have a new request from " . $user->name ,
                        'ar' =>  "  لديك طلب جديد من " . $user->name ,  
                    ];
                    $center = User::where('id', $CenterContainer->center->id)->first(); 
                    $center->notify(new Notifications($msg,$type ));
                    $device_token = $center->device_token ;
                    if($device_token){
                        $this->notification($device_token,$title,$msg);
                        $this->webnotification($device_token,$title,$msg,$type);
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
        $token = $request->token;
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
                $orderss = Order::where('user_id',$user->id)->with('center')->with('container')->skip($skip)->limit(10)->get();
                $count_orders = Order::where('user_id',$user->id)->with('center')->with('container')->count('id');
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
                        if($order->container){
                            if($order->container->image){
                                $orders[$i]['image'] = asset('img/').'/'. $order->container->image;
                            }else{
                                $orders[$i]['image'] = null ;
                            }
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
                            'count_orders' => $count_orders
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
                $orderss = Order::where('driver_id',$user->id)->with('center')->with('container')->skip($skip)->limit(10)->get();
                $count_orders = Order::where('driver_id',$user->id)->with('center')->with('container')->count('id');
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
                        if($order->container){
                            if($order->container->image){
                                $orders[$i]['image'] = asset('img/').'/'. $order->container->image;
                            }else{
                                $orders[$i]['image'] = null ;
                            }
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
                            'count_orders' => $count_orders
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
        $token = $request->token;
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
                        'en' =>  $user->name ."  canceled the order"  ,
                        'ar' =>   $user->name ."  قام بالغاء الطلب"  ,
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
                            $this->notification($device_token,$title,$msg);
                            $this->webnotification($device_token,$title,$msg,$type);
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
        $token = $request->token;
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
        $token = $request->token;
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
                        $user->available = 0 ;
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
                            'en' =>  $user->name ."  agreed to deliver the request"  ,
                            'ar' =>   $user->name ."  قام بالموافقة علي توصيل الطلب"  ,
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
                                $this->notification($device_token,$title,$msg);
                                $this->webnotification($device_token,$title,$msg,$type);
                            }
                        }
                        $msg =  [
                            'en' =>  "  Your order status has changed"  ,
                            'ar' =>   " تم تغيير حالة الطلب الخاص بك "  ,
                        ];
                        $title = [
                            'en' =>  "  Your order status has changed"  ,
                            'ar' =>   " تم تغيير حالة الطلب الخاص بك "  ,
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
                            'en' =>  $user->name ."  declined  to deliver the request"  ,
                            'ar' =>   $user->name ."  رفض تسليم الطلب"  ,
                        ];
                        $title = [
                            'en' =>  $user->name ."  declined  to deliver the request"  ,
                            'ar' =>   $user->name ."  رفض تسليم الطلب"  ,
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
                        $type = "order";
                        $msg =  [
                            'en' =>  "  The request has been delivered"  ,
                            'ar' =>   "  تم توصيل الطلب"  ,
                        ];
                        $title = [
                            'en' =>  "  The request has been delivered"  ,
                            'ar' =>   "  تم توصيل الطلب"  ,
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

        $rules=array(   
            "message"=>"required",
            "title"=>"required",
            "email"=>"required|email",
            "name"=>"required",
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
                'message' => trans('api.failed'),
                'user'    => null ,
            ]);
        }
        else{
            $contact = new ContactUs ;

            $contact->name = $request->name ;
            $contact->email = $request->email ;
            $contact->title = $request->title ;
            $contact->message = $request->message ;
            $contact->status = 'new' ;
            $contact->save();

            return response()->json([
                'success' => 'success',
                'errors' => null ,
                'message' => trans('api.save'),
                'data' => [
                    "contact" => $contact,
                    ],
            ]);
        }
        

    }
/////////////////////////////////////////////////////
// Terms and Conditions function by Antonious hosny
    public function TermsConditions(Request $request){
        $lang = $request->header('lang');
        $doc = Doc::where('type','terms')->first();
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
        return response()->json([
            'success' => 'success',
            'errors' => null ,
            'message' => trans('api.fetch'),
            'data' =>  $docss,
                
        ]);
    

    }
///////////////////////////////////////////////////
// Policy function by Antonious hosny
    public function Policy(Request $request){
        $lang = $request->header('lang');
        $doc = Doc::where('type','policy')->first();
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
        return response()->json([
            'success' => 'success',
            'errors' => null ,
            'message' => trans('api.fetch'),
            'data' =>  $docss,
                
        ]);


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
        return response()->json([
            'success' => 'success',
            'errors' => null ,
            'message' => trans('api.fetch'),
            'data' =>  $docss,
                
        ]);


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
        date_default_timezone_set('Africa/Cairo');
        $token = $request->token;
        // return $token ;
        if($token == ''){
            $errors =  trans('api.logged_out');
            
            return response()->json([
                'success' => 'logged',
                'errors' => $errors ,
                'message' => trans('api.logged_out'),
                'count' => null,
            ]);
        }
        $user = User::where('remember_token',$token)->first();
        // $user->notify(new Notifications());
        // return $user ;
        if($user){
            $user->lang  = $lang ;
            $user->save();
            $count = count($user->unreadnotifications) ;
            // return $count ;
            return response()->json([
                'success' => 'success',
                'errors' => null ,
                'message' => trans('api.fetch'),
                'count' => $count ,
            ]);
        }
        else{
            $errors[] = [
                'message' => trans('api.logged_out')
            ]; 
            return response()->json([
                'success' => 'logged',
                'errors' => $errors ,
                'message' => trans('api.logged_out'),
                'count' => null,
            ]);
        }

    }
/////////////////////////////////////////////////////////
// get_notification function by Antonious hosny
    public function get_notification(Request $request){
        date_default_timezone_set('Africa/Cairo');
        $token = $request->token;
        if($token == ''){
            $errors = trans('api.logged_out');
        
            return response()->json([
                'success' => 'failed',
                'errors' => $errors ,
                'message' => trans('api.logged_out'),
                'notifications' => null,
            ]);
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
    
            return response()->json([
                'success' => 'success',
                'errors' => null ,
                'message' => trans('api.fetch'),
                'notifications' => $notifications ,
            ]);
        }
        else{
            $errors = trans('api.logged_out');
        
            return response()->json([
                'success' => 'failed',
                'errors' => $errors ,
                'message' => trans('api.logged_out'),
                'notifications' => null,
            ]);
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
