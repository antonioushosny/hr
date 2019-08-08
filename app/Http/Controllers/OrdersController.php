<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\User;
use App\City;
use App\Area;
use App\Order;
use App\Container;
use App\CenterContainer;
use App\OrderCenter;
use App\OrderDriver;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Auth;
use App;
use DB;
use App\Notifications\Notifications;

class OrdersController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
    
        // if(Auth::user()->role != 'admin' ){
        //     return view('unauthorized',compact('role','admin'));
        // }
        
    }
    public function index()
    {
        $lang = App::getlocale();
        if((Auth::user()->role != 'center' )){
            $role = 'order';
            return view('unauthorized',compact('role','center'));
        }
        $title = 'orders';
        $orders = Order::where('center_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        
        // return $orders ; 
        return view('orders.index',compact('orders','title','lang'));

    }

    public function neworders()
    {
        $lang = App::getlocale();
        if((Auth::user()->role != 'center' )){
            $role = 'center';
            return view('unauthorized',compact('role','center'));
        }
        $title = 'neworders';
        $orders = Order::where('status','pending')->where('center_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        
        // return $orders ; 
        return view('orders.index',compact('orders','title','lang'));

    }
    public function noworders()
    {
        $lang = App::getlocale();
        if((Auth::user()->role != 'center' )){
            $role = 'center';
            return view('unauthorized',compact('role','center'));
        }
        $title = 'noworders';
        $orders = Order::where('center_id',Auth::user()->id)->where('status','accepted')->orWhere('status','assigned')->orderBy('id', 'DESC')->get();
        // return $orders ; 
        return view('orders.index',compact('orders','title','lang'));

    }
    public function lastorders()
    {
        $lang = App::getlocale();
        if((Auth::user()->role != 'center' )){
            $role = 'center';
            return view('unauthorized',compact('role','center'));
        }
        $title = 'lastorders';
        $orders = Order::where('center_id',Auth::user()->id)->where('status','delivered')->orWhere('status','canceled')->orderBy('id', 'DESC')->get();
        
        // return $orders ; 
        return view('orders.index',compact('orders','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'admin' ||  Auth::user()->role == 'provider')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'orders';
        $allcities = City::all();
        if($lang == 'ar'){
            $cities = array_pluck($allcities,'name_ar', 'id'); 
        }else{
            $cities = array_pluck($allcities,'name_en', 'id');
        }

        $allcontainers = Container::all();
        if($lang == 'ar'){
            $containers = array_pluck($allcontainers,'name_ar', 'id'); 
        }else{
            $containers = array_pluck($allcontainers,'name_en', 'id');
        }

        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id'); 
        
        return view('orders.add',compact('title','providers','containers','cities','lang'));
    }
    public function store(Request $request)
    {
       
        // return $request ;
        if($request->id ){
            $rules =
            [
                'provider_id'   =>'required', 
                'city_id'   =>'required', 
                'area_id'   =>'required', 
                'responsible_name'  =>'required|max:190',
                'email'  =>'required|email|max:190',            
                'status'  =>'required',   
                'lat' =>'required',
                'lng'    =>'required', 
            ];
            
        }     
    
        else{
            $rules =
            [
                'provider_id'   =>'required', 
                'city_id'   =>'required', 
                'area_id'   =>'required', 
                'responsible_name'  =>'required|max:190',
                'email'  =>'required|email|unique:users,email|max:190',            
                'status'  =>'required',       
                // 'password'  =>'required|min:6|max:190',     
                // 'image'  =>'required',      
                'lat' =>'required',
                'lng'    =>'required',   
                // 'mobile'     =>'required',   
            ];
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
        
         $user->name          = $request->responsible_name ;
         $user->lat           = $request->lat ;
         $user->lng           = $request->lng ;
         $user->email         = $request->email ;
         $user->status        = $request->status ;
         $user->mobile        = $request->mobile ;
         $user->city_id       = $request->city_id ;
         $user->area_id       = $request->area_id ;
         $user->provider_id       = $request->provider_id ;

         $user->role          = 'order';
         $user->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }

        $user->save();
        $containers  = CenterContainer::where('order_id',$user->id)->delete();
        $i = 0; 
        foreach($request->containers as $container_id){
          
            if($container_id != '' || $container_id != null){
                $container = CenterContainer::where('order_id',$user->id)->where('container_id',$container_id)->first();
                if(!$container){

                    $container = new CenterContainer ;
                    $container->container_id = $container_id ;
                    $container->order_id = $user->id ;
                    $container->price = $request->price[$i] ;
                    $container->save();
                }
                $i ++ ;
            }
        }

        return redirect()->route('orders');
        // return \Redirect::back();
        // return view('orders.index',compact('admins','title','lang'));

        return response()->json($user);

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'center')){
            $role = 'center';
            return view('unauthorized',compact('role','center'));
        }
        $title = 'orders';
        
        $alldrivers = User::where('role','driver')->where('available','1')->where('center_id',Auth::user()->id)->get();
        $drivers = array_pluck($alldrivers,'name', 'id');  

        $order = Order::where('id',$id)->with('centers')->with('drivers')->with('user')->orderBy('id', 'DESC')->first();
        // return $order ; 
        return view('orders.show',compact('order','drivers','title','lang'));
    }

    public function actionfororder(Request $request)
    {
        $rules =
            [
                'status'   =>'required', 
            ];

            // return $request ;
        if($request->status == 'accept' ){
            $rules =
            [
                'driver_id'   =>'required', 
            ];
            
        }     
    
        else if($request->status == 'decline' ) {
            $rules =
            [
                'reason'   =>'required',   
            ];
        }
        $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }

        $ordercenter = OrderCenter::where('order_id',$request->order_id)->where('center_id',Auth::user()->id)->first();
        $dt = Carbon::now();
        $date  = date('Y-m-d H:i:s', strtotime($dt));
         $order = Order::where('id',$request->order_id)->first();
        if($request->status == 'accept'){
            $order->status  = 'accepted' ;
            $order->driver_id  =  $request->driver_id ;
            $order->save();
            $ordercenter->status  = 'accept' ;
            $ordercenter->accept_date  = $date ;
            $ordercenter->save() ;
            $orderdriver = OrderDriver::where('center_id',$ordercenter->center_id)->where('order_id',$ordercenter->order_id)->where('driver_id',$request->driver_id)->first();
            if(!$orderdriver){
                $orderdriver = new OrderDriver ;
                $orderdriver->status  =  'pending' ; 
                $orderdriver->center_id  =   $ordercenter->center_id ; 
                $orderdriver->order_id  =   $ordercenter->order_id ; 
                $orderdriver->driver_id  =   $request->driver_id ; 
                $orderdriver->save();  
            }
            // $msg = "  تم اختيارك لتوصيل طلب جديد "  ;
            $type = "order";
            // $title = "  لديك طلب جديد من " ;

            $msg =  [
                'en' => " You have been selected to delivery a new order"  ,
                'ar' =>  " تم اختيارك لتوصيل طلب جديد  " ,
            ];
            
          
            
            $driver = User::where('id', $request->driver_id)->first(); 
            $driver->notify(new Notifications($msg,$type ));
            $device_token = $driver->device_token ;
            if($device_token){
                $this->notification($device_token,$msg,$msg);
                $this->webnotification($device_token,$msg,$msg,$type);
            }
 
            // $msg = "  تم  قبول طلبك "  ;
            $type = "accepted_order" ;
            // $title = "  تم  قبول طلبك " ;

            
            $msg =  [
                        
                'en' =>  "Your request ".$order->id."  has been accepted "  ,
                'ar' =>   " طلبك "  .$order->id . " تم قبوله",
            ];
            
          

            $user = User::where('id', $order->user_id)->first(); 
            $user->notify(new Notifications($msg,$type ));
            $device_token = $user->device_token ;
            if($device_token){
                $this->notification($device_token,$msg,$msg);
            }
            return \Response::json('accepted') ;

        }else if($request->status == 'decline'){
            $order->center_id  =  null ; 
            $order->driver_id  = null ; 
            $order->save();
            $ordercenter->status  = 'decline' ;
            $ordercenter->reason  = $request->reason ;
            $ordercenter->decline_date  = $date ;
            $ordercenter->save() ;

            $container = Container::where('id',$order->container_id)->with('centers')->first();
            $distancess = [] ;
            $i = 0;
            if(sizeof($container->centers) > 0){

                foreach ($container->centers as $center) {
                    $distance =  $this->GetDistance($request->lat, $center->lat, $request->lng, $center->lng, 'K');
                    $distancess[$center->id] = $distance  ;
                    $i++ ;
                }
                asort($distancess)  ;
                // reset($distancess);
   
                // return \Response::json( $distancess) ;
                foreach($distancess as $key => $distances) {
                    $ordercenter = OrderCenter::where('center_id',$key)->where('order_id',$request->order_id)->first();
                    // print ($ordercenter) ;
                    if($ordercenter){
                        unset($distancess[$key]);
                    }
                }
                // return \Response::json( $distancess) ;
                // asort($distancess)  ;
                $first_key = key($distancess);
                
                $CenterContainer = CenterContainer::where('center_id',$first_key)->where('container_id',$order->container_id)->with('center')->with('container')->first();
                if($CenterContainer){

                    $order->center_id = $CenterContainer->center->id ;
                    $order->provider_id = $CenterContainer->center->provider_id ;
                    $order->container_id = $CenterContainer->container->id ;
                    $order->price = $CenterContainer->price ;
                    $order->total = $CenterContainer->price * $order->no_container ;
                    $order->status = 'pending' ;
                    
                    $order->save();
    
                    $ordercenter = new OrderCenter ;
                    $ordercenter->order_id = $request->order_id ;
                    $ordercenter->center_id = $first_key ;
                    $ordercenter->status = 'pending' ;
                    $ordercenter->save();
    
                    // $msg = "  لديك طلب جديد من " . $order->user_name ;
                    $type = "order";
                    // $title = "  لديك طلب جديد من " . $order->user_name  ;
                    $msg =  [
                        'en' => "  You have a new request from " . $order->user_name ,
                        'ar' =>   "  لديك طلب جديد من " . $order->user_name  ,
                    ];
                    
                    $center = User::where('id', $CenterContainer->center->id)->first(); 
                    $center->notify(new Notifications($msg,$type ));
                    $device_token = $center->device_token ;
                    if($device_token){
                        $this->notification($device_token,$msg,$msg);
                    }
                    return \Response::json('canceled') ;
                }else{
                    $order->center_id = null ;
                    $order->provider_id = null ;
                    $order->container_id = $order->container_id ;
                    $order->price =  null ;
                    $order->total = null ;
                    $order->status = 'canceled' ;
                    $order->save();

                    // $msg = "  تم  رفض طلبك "  ;
                    $type = "canceled_order" ;
                    // $title = "  تم  رفض طلبك " ;

                    
                    $msg =  [
                        
                        'en' =>  "Your request ".$order->id." was declined "  ,
                        'ar' =>   " طلبك "  .$order->id . " تم رفضه",
                    ];
                    $user = User::where('id', $order->user_id)->first(); 
                    if($user){
                        $user->notify(new Notifications($msg,$type ));
                        $device_token = $user->device_token ;
                        if($device_token){
                            $this->notification($device_token,$msg,$msg);
                            $this->webnotification($device_token,$msg,$msg,$type);
                        }
                    }
                    return \Response::json('canceled') ;
                }
                

            }

            return 'success';
        }
         
    }

    public function assignDriver(Request $request)
    {

        if($request->type == 'reassign'){

            $rules =
            [
                'driver_id'   =>'required', 
                'order_id'   =>'required', 
            ];
        }else{

            $rules =
            [
                'reason'   =>'required', 
                'order_id'   =>'required', 
            ];
            
        }
    
        $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         $ordercenter = OrderCenter::where('order_id',$request->order_id)->where('center_id',Auth::user()->id)->first();
         $dt = Carbon::now();
         $date  = date('Y-m-d H:i:s', strtotime($dt));
          $order = Order::where('id',$request->order_id)->first();
        if($request->type == 'reassign'){
            $dt = Carbon::now();
            $date  = date('Y-m-d H:i:s', strtotime($dt));
            $order = Order::where('id',$request->order_id)->first();
            $order->driver_id =  $request->driver_id ; 
            $order->save();
            $orderdriver = new OrderDriver ;
            $orderdriver->status  =  'pending' ; 
            $orderdriver->center_id  =   $ordercenter->center_id ; 
            $orderdriver->order_id  =   $ordercenter->order_id ; 
            $orderdriver->driver_id  =   $request->driver_id ; 
            $orderdriver->save();  
            // $msg = "  تم اختيارك لتوصيل طلب جديد "  ;
            $type = "order";
            // $title = "  لديك طلب جديد من " ;
            $msg =  [
                'en' => "  You have been selected to delivery a new order "  ,
                'ar' =>   " تم اختيارك لتوصيل طلب جديد "   ,
            ];

            $title =  [
                
                'en' => "  You have a new request from " ,
                'ar' =>   "  لديك طلب جديد من "  ,
            ];
            $driver = User::where('id', $request->driver_id)->first(); 
            $driver->notify(new Notifications($msg,$type ));
            $device_token = $driver->device_token ;
            if($device_token){
                $this->notification($device_token,$title,$msg);
                $this->webnotification($device_token,$title,$msg,$type);
            }
        }else{
            $order->driver_id =  null ; 
            $order->save();
            $ordercenter->status  = 'decline' ;
            $ordercenter->reason  = $request->reason ;
            $ordercenter->decline_date  = $date ;
            $ordercenter->save() ;

            $container = Container::where('id',$order->container_id)->with('centers')->first();
            $distancess = [] ;
            $i = 0;
            if(sizeof($container->centers) > 0){

                foreach ($container->centers as $center) {
                    $distance =  $this->GetDistance($request->lat, $center->lat, $request->lng, $center->lng, 'K');
                    $distancess[$center->id] = $distance  ;
                    $i++ ;
                }
                asort($distancess)  ;
                // reset($distancess);
   
                // return \Response::json( $distancess) ;
                foreach($distancess as $key => $distances) {
                    $ordercenter = OrderCenter::where('center_id',$key)->where('order_id',$request->order_id)->first();
                    // print ($ordercenter) ;
                    if($ordercenter){
                        unset($distancess[$key]);
                    }
                }
                // return \Response::json( $distancess) ;
                // asort($distancess)  ;
                $first_key = key($distancess);
                
                $CenterContainer = CenterContainer::where('center_id',$first_key)->where('container_id',$order->container_id)->with('center')->with('container')->first();
                if($CenterContainer){

                    $order->center_id = $CenterContainer->center->id ;
                    $order->provider_id = $CenterContainer->center->provider_id ;
                    $order->container_id = $CenterContainer->container->id ;
                    $order->price = $CenterContainer->price ;
                    $order->total = $CenterContainer->price * $order->no_container ;
                    $order->status = 'pending' ;
                    $order->save();
    
                    $ordercenter = new OrderCenter ;
                    $ordercenter->order_id = $request->order_id ;
                    $ordercenter->center_id = $first_key ;
                    $ordercenter->status = 'pending' ;
                    $ordercenter->save();
    
                    // $msg = "  لديك طلب جديد من " . $order->user_name ;
                    $type = "order";
                    // $title = "  لديك طلب جديد من " . $order->user_name  ;

                    $msg =  [
                        'en' => "  You have a new request  ". $order->user_name ,
                        'ar' =>   "  لديك طلب جديد  " . $order->user_name ,
                    ];
        
                    $title =  [
                        
                        'en' => "  You have a new request  " ,
                        'ar' =>   "  لديك طلب جديد  "  ,
                    ];

                    $center = User::where('id', $CenterContainer->center->id)->first(); 
                    $center->notify(new Notifications($msg,$type ));
                    $device_token = $center->device_token ;
                    if($device_token){
                        $this->notification($device_token,$title,$msg);
                        $this->webnotification($device_token,$title,$msg,$type);
                    }
                    return \Response::json('canceled') ;
                }else{
                    $order->center_id = null ;
                    $order->provider_id = null ;
                    $order->driver_id = null ;
                    $order->container_id = $order->container_id ;
                    $order->price =  null ;
                    $order->total = null ;
                    $order->status = 'canceled' ;
                    $order->save(); 

                    // $msg = "  تم  رفض طلبك "  ;
                    $type = "canceled_order" ;
                    // $title = "  تم  رفض طلبك " ;
     
                    $title =  [
                        
                        'en' =>  "Your request ".$order->id." was declined "  ,
                        'ar' =>   " طلبك "  .$order->id . " تم رفضه",
                    ];
                    $msg =  [
                        
                        'en' =>  "Your request ".$order->id." was declined "  ,
                        'ar' =>   " طلبك "  .$order->id . " تم رفضه",
                    ];
                    $user = User::where('id', $order->user_id)->first(); 
                    if($user){
                        $user->notify(new Notifications($msg,$type ));
                        $device_token = $user->device_token ;
                        if($device_token){
                            $this->notification($device_token,$title,$msg);
                            $this->webnotification($device_token,$title,$msg,$type);
                        }
                    }
                    return \Response::json('canceled') ;
                }
                

            }
        }
        return \Response::json('accepted') ;

       
         
    }
    
    public function destroy($id)
    {
       
        if(!(Auth::user()->role == 'admin' ||  Auth::user()->role == 'provider')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $id = User::find( $id );
        $imageName =  $id->image; 
        \File::delete(public_path(). '/img/' . $imageName);
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        if($request->ids){
            foreach($request->ids as $id){
                $id = User::find($id);
                $imageName =  $id->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            $ids = User::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        // session()->flash('alert-danger', trans('admin.record_selected_deleted'));
        // return redirect()->route('admins');
      
    }
}
