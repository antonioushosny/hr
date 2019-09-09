<?php

namespace App\Http\Controllers;
use App\Order;
use App\User;
use App\Country;
use App\City;
use App\Doc;
use App\ContactUs;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Auth;
use App ;
use DB ;
use App\Notifications\Notifications;
use Notification;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
  
            $lang = App::getlocale();
            $dt = Carbon::now();
            $date = $dt->toDateString();
            $time  = date('H:i:s', strtotime($dt));
            
            $users        = User::where('role','user')->count('id');
            $providers    = User::where('role','provider')->count('id');
            $centers      = User::where('role','center')->count('id');
            $drivers      = User::where('role','driver')->count('id');

            $yesterday      = Carbon::now()->subDays(1)->toDateString();
            $one_week_ago   = Carbon::now()->subWeeks(1)->toDateString();
            $one_month_ago  = Carbon::now()->subMonths(1)->toDateString();
            $one_year_ago   = Carbon::now()->subYears(1)->toDateString();

            // return date('Y', strtotime($sex_year_ago)); 
           

            $last_sex_years = [] ;
            $sales_for_year = [] ;
            if(Auth::user()->role == 'admin'){
              
                $this_year = Order::where('status','delivered')->whereDate('created_at','>=',$one_year_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_month = Order::where('status','delivered')->whereDate('created_at','>=',$one_month_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_week = Order::where('status','delivered')->whereDate('created_at','>=',$one_week_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_day = Order::where('status','delivered')->whereDate('created_at','=',$date)->count('id');

                for($i=0 ;$i <=6 ; $i++){
                    $year = Carbon::now()->subYears($i+1)->toDateString();
                    $lastyear = Carbon::now()->subYears($i)->toDateString();
                    $last_sex_years[$i]['period'] = date('Y', strtotime($lastyear));
                    $last_sex_years[$i]['sales'] = Order::where('status','delivered')->whereDate('created_at','>=',$year)->whereDate('created_at','<=',$lastyear)->count('id');
                    $last_sex_years[$i]['orders'] = Order::where('status','delivered')->whereDate('created_at','>=',$year)->whereDate('created_at','<=',$lastyear)->count('id');
                }
                for($i=0 ;$i <=11 ; $i++){
                    $month = Carbon::now()->subMonths($i+1)->toDateString();
                    $lastmonth = Carbon::now()->subMonths($i)->toDateString();
                    $sales_for_year[$i]['period'] = date('Y-M-d', strtotime($lastmonth));
                    $sales_for_year[$i]['sales'] = Order::where('status','delivered')->whereDate('created_at','>=',$month)->whereDate('created_at','<=',$lastmonth)->count('id');
                    $sales_for_year[$i]['orders'] = Order::where('status','delivered')->whereDate('created_at','>=',$month)->whereDate('created_at','<=',$lastmonth)->count('id');
                }
    
                for($i=0 ;$i <=7 ; $i++){
                    $day = Carbon::now()->subDays($i+1)->toDateString();
                    $lastday = Carbon::now()->subDays($i)->toDateString();
                    $sales_for_week[$i]['period'] = date('D', strtotime($lastday));
                    $sales_for_week[$i]['sales'] = Order::where('status','delivered')->whereDate('created_at','>=',$day)->whereDate('created_at','<=',$lastday)->count('id');
                    $sales_for_week[$i]['orders'] = Order::where('status','delivered')->whereDate('created_at','>=',$day)->whereDate('created_at','<=',$lastday)->count('id');
                }
            }
            else if(Auth::user()->role == 'provider'){

                $drivers      = User::where('role','driver')->where('provider_id',Auth::user()->id)->count('id');
                $orders      = Order::where('provider_id',Auth::user()->id)->count('id');
                $sales      = Order::where('provider_id',Auth::user()->id)->count('id');

                $this_year = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$one_year_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_month = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$one_month_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_week = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$one_week_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_day = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','=',$date)->count('id');

                for($i=0 ;$i <=6 ; $i++){
                    $year = Carbon::now()->subYears($i+1)->toDateString();
                    $lastyear = Carbon::now()->subYears($i)->toDateString();
                    $last_sex_years[$i]['period'] = date('Y', strtotime($lastyear));
                    $last_sex_years[$i]['sales'] = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$year)->whereDate('created_at','<=',$lastyear)->count('id');
                    $last_sex_years[$i]['orders'] = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$year)->whereDate('created_at','<=',$lastyear)->count('id');
                }
                for($i=0 ;$i <=11 ; $i++){
                    $month = Carbon::now()->subMonths($i+1)->toDateString();
                    $lastmonth = Carbon::now()->subMonths($i)->toDateString();
                    $sales_for_year[$i]['period'] = date('Y-M-d', strtotime($lastmonth));
                    $sales_for_year[$i]['sales'] = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$month)->whereDate('created_at','<=',$lastmonth)->count('id');
                    $sales_for_year[$i]['orders'] = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$month)->whereDate('created_at','<=',$lastmonth)->count('id');
                }
    
                for($i=0 ;$i <=7 ; $i++){
                    $day = Carbon::now()->subDays($i+1)->toDateString();
                    $lastday = Carbon::now()->subDays($i)->toDateString();
                    $sales_for_week[$i]['period'] = date('D', strtotime($lastday));
                    $sales_for_week[$i]['sales'] = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$day)->whereDate('created_at','<=',$lastday)->count('id');
                    $sales_for_week[$i]['orders'] = Order::where('status','delivered')->where('provider_id',Auth::user()->id)->whereDate('created_at','>=',$day)->whereDate('created_at','<=',$lastday)->count('id');
                }
            }
            else{
                $drivers      = User::where('role','driver')->where('center_id',Auth::user()->id)->count('id');
                $orders      = Order::where('center_id',Auth::user()->id)->count('id');
                $sales      = Order::where('center_id',Auth::user()->id)->count('id');

                $this_year = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$one_year_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_month = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$one_month_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_week = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$one_week_ago)->whereDate('created_at','<=',$date)->count('id');
                $this_day = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','=',$date)->count('id');

                for($i=0 ;$i <=6 ; $i++){
                    $year = Carbon::now()->subYears($i+1)->toDateString();
                    $lastyear = Carbon::now()->subYears($i)->toDateString();
                    $last_sex_years[$i]['period'] = date('Y', strtotime($lastyear));
                    $last_sex_years[$i]['sales'] = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$year)->whereDate('created_at','<=',$lastyear)->count('id');
                    $last_sex_years[$i]['orders'] = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$year)->whereDate('created_at','<=',$lastyear)->count('id');
                }
                for($i=0 ;$i <=11 ; $i++){
                    $month = Carbon::now()->subMonths($i+1)->toDateString();
                    $lastmonth = Carbon::now()->subMonths($i)->toDateString();
                    $sales_for_year[$i]['period'] = date('Y-M-d', strtotime($lastmonth));
                    $sales_for_year[$i]['sales'] = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$month)->whereDate('created_at','<=',$lastmonth)->count('id');
                    $sales_for_year[$i]['orders'] = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$month)->whereDate('created_at','<=',$lastmonth)->count('id');
                }
    
                for($i=0 ;$i <=7 ; $i++){
                    $day = Carbon::now()->subDays($i+1)->toDateString();
                    $lastday = Carbon::now()->subDays($i)->toDateString();
                    $sales_for_week[$i]['period'] = date('D', strtotime($lastday));
                    $sales_for_week[$i]['sales'] = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$day)->whereDate('created_at','<=',$lastday)->count('id');
                    $sales_for_week[$i]['orders'] = Order::where('status','delivered')->where('center_id',Auth::user()->id)->whereDate('created_at','>=',$day)->whereDate('created_at','<=',$lastday)->count('id');
                }
            }
            
            // return  $sales_for_year ;
            $title = 'home' ;
            return view('home',compact('lang','title','providers','centers','drivers','users','sales','orders','this_day','this_week','this_month','this_year','last_sex_years','sales_for_year','sales_for_week'));
        
    }

    public function settings($type)
    {
        $lang = App::getlocale();
        if($type == 'about'){
            $title = "AboutUs" ;
            $type = "about" ;
        }else if($type == 'policy'){
            $title = "Policy" ;
            $type = "policy" ;
        }else if($type == 'bank'){
            $title = "bank" ;
            $type = "bank" ;
        }else{
            $title = "Terms" ;
            $type = "terms" ;
        }
        $data      = Doc::where('type',$type)->first();
        // return $about ;
        return view('settings.add',compact('lang','title','type','data')) ;
    }
    public function add($type){
        $lang = App::getlocale();
        if($type == 'about'){
            $title = "AboutUs" ;
            $type = "about" ;
        }else if($type == 'policy'){
            $title = "Policy" ;
            $type = "policy" ;
        }else{
            $title = "Terms" ;
            $type = "terms" ;
        }
      
        return view('settings.add',compact('title','lang','type')) ;
    }
    public function edit($type,$id){
        $lang = App::getlocale();
        if($type == 'about'){
            $title = "AboutUs" ;
            $type = "about" ;
        }else if($type == 'policy'){
            $title = "Policy" ;
            $type = "policy" ;
        }else{
            $title = "Terms" ;
            $type = "terms" ;
        }
      
        $data = Doc::find($id) ;
        return view('settings.add',compact('title','lang','type','data')) ;
    }
    public function store(Request $request)
    {
        $lang = App::getlocale();
        // return $request->desc_en;
        if($request->id ){
            $rules =
            [
                'title_ar'  =>'required|max:190',           
                'title_en'  =>'required|max:190',           
                'type'  =>'required',           
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'title_ar'  =>'required|max:190',           
                'title_en'  =>'required|max:190', 
                'type'  =>'required',                
                // 'country_id'  =>'required',     
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $doc = Doc::find( $request->id );
        }
        else{
            $doc = new Doc ;

        }

        $doc->title_ar          = $request->title_ar ;
        $doc->title_en         = $request->title_en ;
        $doc->status        = $request->status ;
        $doc->type        = $request->type ;
     
        $doc->disc_ar        = $request->desc_ar ;
        $doc->disc_en        = $request->desc_en ;
        $doc->save();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $doc->image   = $name;  
        }
        $doc->save();
        // return $doc ;
        $type = $doc->type   ;
        if($type == 'about'){
            $title = "AboutUs" ;
            $type = "about" ;
        }else if($type == 'policy'){
            $title = "Policy" ;
            $type = "policy" ;
        }else{
            $title = "Terms" ;
            $type = "terms" ;
        }
      
        $data =  $doc ;

        // return view('settings.add',compact('title','lang','type','data')) ;
        return response()->json($doc);

    }

    public function destroy($id)
    {
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $id = Doc::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        if($request->ids){
            $ids = Doc::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }

    public function profile($id)
    {

        $admin = User::where('id',$id)->first();
        $term  = Term::first();
        
        $title = "home" ;
        return view('profile',compact('admin','title','term'));
    }

    public function editprofile(Request $request)
    {
        // return $request ;
        if($request->id ){
            $rules =
            [
                'email'  =>'required|email|max:190',            
            ];
        }     
    
        else{
            $rules =
            [
                'email'  =>'required|email|unique:users,email|max:190',            
                'password'  =>'required|min:6|max:190',     
  
            ];
        }
        if($request->mobile){
            $rules['mobile'] = "between:8,11" ;
        }
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }

        // return $request ;
         if($request->id ){
            $user = User::find( $request->id );

            if($request->email != $user->email){
                $rules =
                [       
                    'email'  =>'required|email|unique:users,email',     
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
            }
            
            if ($request->hasFile('image')) {

                $imageName =  $user->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            if($request->password){
                $rules =
                [
                    'password'  =>'min:6',                    
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()){
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
                $password = \Hash::make($request->password);
                $user->password      = $password ;
            }
         }
         else{
            $user = new User ;
            $password = \Hash::make($request->password);
            $user->password      = $password ;
        }
        
        
         $user->email         = $request->email ;
         $user->mobile        = $request->mobile ;
         $user->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }

        $user->save();

        $lang = App::getlocale();
        return response()->json($user);

    }
    
    public function savetoken($token)
    {
        $user = Auth::user();
        $user->device_token = $token;
        $user->save();
        return response()->json([
            'msg' => $user
        ]);
    }

    public function messages()
    {
        $title = 'messages';
        // $clients = User::where('role','client')->get();
        $clients = User::where('role','<>','admin')->get();
        $countries = Country::where('id','<>','1')->get();
        $cities = City::where('id','<>','1')->get();
        // $users = User::where('role','user')->get();

        return view('messages.index',compact('clients','cities','countries','title'));
    }

    public function send(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'for' => 'required',
        ]);
        $user = User::where('role','admin')->first();
            if($user){
                $id = $user->id ;
            }
            else{
                $id ='1' ;
            }
        
        if($request->for == "all"){
            $clients =  User::where('role','<>','admin')->get();
        }
        else if($request->for == "for_country"){
            $clients =  User::whereIn('country_id',$request->countries)->get();
        }
        else if($request->for == "for_city"){
            $clients =  User::whereIn('city_id',$request->cities)->get();
        }
        else{
            $clients =  User::whereIn('id',$request->ids)->get();
        }
        if(sizeof($clients) > 0){

            foreach($clients as $client){
                if($client){
    
                    $type = "message";
                    $msg =  $request->message ;
                    $client->notify(new Notifications($msg,$type ));
                    $device_id = $client->device_token;
                    $title = $request->title ; 
                    if($device_id){
                        $this->notification($device_id,$title,$msg,$id);
                    }
                }
            }
        }
        if($request->send_points){
            if($request->send_points == 'send_points'){
                $validatedData = $request->validate([
                    'points' => 'required',
                    'coupons' => 'required',
                ]);
                if(sizeof($clients) > 0){
                    foreach($clients as $client){
                        if($client){
                            $client->points += $request->points ;
                            $client->coupons += $request->coupons ;
                            $client->save();
                        
                        }
                    }
                }
            }
        }
        session()->flash('alert-success', trans('admin.successfully_send'));
        return redirect()->route('messages');
    }
     

    public function contact_us(Request $request)
    {
        $contact = new ContactUs ;

        $contact->name = $request->name ;
        $contact->email = $request->email ;
        $contact->title = $request->title ;
        $contact->message = $request->message ;
        $contact->status = 'new' ;
        $contact->save();
        $type = "user";
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
        return view('landing');
    }
}
