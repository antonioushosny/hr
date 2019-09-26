<?php

namespace App\Http\Controllers;
use App\User;
use App\Doc;
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
            
            $hrs        = User::where('role','hr')->count('id');
            $admins    = User::where('role','admin')->count('id');
            $employees    = User::where('role','employee')->count('id');
      
            $title = 'home' ;

           
            return view('home',compact('lang','title'));
        
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
                'title_ar'  =>'required|arabic|max:190',           
                'title_en'  =>'required|english|max:190',           
                'type'  =>'required',           
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'title_ar'  =>'required|arabic|max:190',           
                'title_en'  =>'required|english|max:190', 
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

    public function messages()
    {
        $lang = App::getlocale();
        $title = 'messages';
        // $clients = User::where('role','client')->get();
        $clients = User::where('role','<>','admin')->get();
        $countries = Country::where('status','active')->get();
        $cities = City::where('status','active')->get();
        $fannies = User::where('role','fannie')->where('status','active')->get();
        $users = User::where('role','user')->where('status','active')->get();
        // $users = User::where('role','user')->get();
        return view('messages.index',compact('clients','users','cities','countries','title','lang'));
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
        else if($request->for == "all_users"){
            $clients =  User::where('role','user')->get();
        }
        else if($request->for == "all_fannies"){
            $clients =  User::where('role','fannie')->get();
        }
        else{
            $clients =  User::whereIn('id',$request->ids)->get();
        }
        if(sizeof($clients) > 0){
            
            foreach($clients as $client){
                if($client){
                    $msg =  [
                        'en' => $request->message ,
                        'ar' =>$request->message ,
                    ];
                    $title = [
                        'en' =>   $request->title  ,
                        'ar' => $request->title  ,  
                    ];
                    $type = "message";
                    // $msg =  $request->message ;
                    $client->notify(new Notifications($msg,$type ));
                    $device_id = $client->device_token;
                    // $title = $request->title ; 
                    if($device_id){
                        $this->notification($device_id,$title,$msg,$id);
                    }
                }
            }
        }
        
        return response()->json([ 'success' => trans('admin.successfully_send'), 'message' => trans('admin.successfully_send')], 200);
        // session()->flash('success', trans('admin.successfully_send'));
        // return redirect()->route('messages');
    }
   
}
