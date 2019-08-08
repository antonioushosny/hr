<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDriver;
use App\OrderCenter;
use App\User;
use App\City;
use App\Area;
use Carbon\Carbon;
use Auth;
use App;
use DataTables;
class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'reports' ;
        $lang = App::getlocale();

        $allcities = City::all();
        if($lang == 'ar'){
            $cities = array_pluck($allcities,'name_ar', 'id'); 
        }else{
            $cities = array_pluck($allcities,'name_en', 'id');
        }
        
        $allareas = Area::all();
        if($lang == 'ar'){
            $areas = array_pluck($allareas,'name_ar', 'id'); 
        }else{
            $areas = array_pluck($allareas,'name_en', 'id');
        }

        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id');  

        $allcenters = User::where('role','center')->where('provider_id',Auth::user()->id)->get();
        $centers = array_pluck($allcenters,'name', 'id');

        $alldrivers = User::where('role','driver')->where('center_id',Auth::user()->id)->get();
        $drivers = array_pluck($alldrivers,'name', 'id');
        $status = [] ;
        $status['pending'] = __('admin.pending') ;
        $status['accepted'] = __('admin.accepted') ;
        $status['assigned'] = __('admin.assigned') ;
        $status['delivered'] = __('admin.delivered') ;
        $status['canceled'] = __('admin.canceled') ;
        // return $status;

        if(Auth::user()->role == 'admin' ){

            $reports = Order::latest()->get();
            return view('reports.index',compact('title','reports','cities','areas','providers','status','lang'));
        }
        else if(Auth::user()->role == 'provider' ){
            $reports = Order::where('provider_id',Auth::user()->id)->latest()->get();
            return view('reports.index',compact('title','reports','cities','areas','centers','status','lang'));
        }
        else{
            $reports = Order::where('center_id',Auth::user()->id)->latest()->get();
            return view('reports.index',compact('title','reports','cities','areas','drivers','status','lang'));
        }
        
        if(Auth::user()->role == 'admin' ){
            $dt = Carbon::now();
            $date = $dt->toDateString();
            // return $date ;
            list($year,$month,$day) = explode("-", $date);
            $wkday = date('l',mktime('0','0','0', $month, $day, $year));
    
            switch($wkday) {
            
                case 'Saturday': $numDaysToMon = 0; break;
                case 'Sunday': $numDaysToMon = 1; break; 
                case 'Monday': $numDaysToMon = 2; break;
                case 'Tuesday': $numDaysToMon = 3; break;
                case 'Wednesday': $numDaysToMon = 4; break;
                case 'Thursday': $numDaysToMon = 5; break;
                case 'Friday': $numDaysToMon = 6; break;  
            }

            // Timestamp of the monday for that week
            $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

            $seconds_in_a_day = 86400;

            // Get date for 7 days from Monday (inclusive)
            for($i=0; $i<7; $i++)
            {
                $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
                $saless[$i]       = Order::whereDate('created_at','=' ,$dates[$i])->count('id');
               
                // $dates[$i]= date('l, F jS, Y', strtotime($dates[$i]));
                $weeks[$i]= trans('admin.'.date('l', strtotime($dates[$i])));
                // $dates[$i] = $dates[$i]->format('dd/mm/yy');
            }

            $monthName = date('F',mktime('0','0','0', $month, $day, $year));
            
            switch($monthName) {
            
                case 'January': $numDaysToMon = 0; break;
                case 'February': $numDaysToMon = 1; break; 
                case 'March': $numDaysToMon = 2; break;
                case 'April': $numDaysToMon = 3; break;
                case 'May': $numDaysToMon = 4; break;
                case 'June': $numDaysToMon = 5; break;
                case 'July': $numDaysToMon = 6; break;  
                case 'August': $numDaysToMon = 7; break;  
                case 'September': $numDaysToMon = 8; break;  
                case 'October': $numDaysToMon = 9; break;  
                case 'November': $numDaysToMon = 10; break;  
                case 'December': $numDaysToMon = 11; break;  
            }

            // Timestamp of the monday for that week
            // return $numDaysToMon;
            $monday = mktime('0','0','0', $month-$numDaysToMon, $day, $year);

            $seconds_in_a_day = 2592000;

            // Get date for 7 days from Monday (inclusive)
            
            for($i=0; $i<12; $i++)
            {
                $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
                $year = date('Y',$monday+($seconds_in_a_day*$i));
                $month = date('m',$monday+($seconds_in_a_day*$i));
                
                $salessmonth[$i]  = Order::whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $month)->count('id');
                // $dates[$i]= date('l, F jS, Y', strtotime($dates[$i]));
                $months[$i]= date('j/F', strtotime($dates[$i]));
                
                // $dates[$i] = $dates[$i]->format('dd/mm/yy');
            }
            
            $clients      = User::where('role','client')->count('id');
            $departments  = Departement::count('id');
            $orders       = Order::count('id');
            $users        = User::where('role','user')->count('id');
            $allservices   = Service::with('offers')->get();
            $services = array_pluck($allservices,'name', 'id');
            $title = 'reports' ;
            $lang = App::getlocale();
            return view('reports.index',compact('title','salessmonth','saless','months','weeks','orders','clients','departments','users','services','lang'));



        }
    }
    public function ajax(){

        if ($request->ajax()) {

            $data = Order::all();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        }
        return view('reports.index',compact('reports'));
        // $data = Order::where('center_id',Auth::user()->id)->get();
        // return $data ;
        //  return response()->json(['data'=>$data]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // return $request;
        $title = 'reports' ;
        $lang = App::getlocale();

        $allcities = City::all();
        if($lang == 'ar'){
            $cities = array_pluck($allcities,'name_ar', 'id'); 
        }else{
            $cities = array_pluck($allcities,'name_en', 'id');
        }
        
        $allareas = Area::all();
        if($lang == 'ar'){
            $areas = array_pluck($allareas,'name_ar', 'id'); 
        }else{
            $areas = array_pluck($allareas,'name_en', 'id');
        }

        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id');  

        $allcenters = User::where('role','center')->where('provider_id',Auth::user()->id)->get();
        $centers = array_pluck($allcenters,'name', 'id');

        $alldrivers = User::where('role','driver')->where('center_id',Auth::user()->id)->get();
        $drivers = array_pluck($alldrivers,'name', 'id');

        $status = [] ;
        $status['pending'] = __('admin.pending') ;
        $status['accepted'] = __('admin.accepted') ;
        $status['assigned'] = __('admin.assigned') ;
        $status['delivered'] = __('admin.delivered') ;
        $status['canceled'] = __('admin.canceled') ;
        // return $status;

        if(Auth::user()->role == 'admin' ){
            $reports = Order::latest();
        }
        else if(Auth::user()->role == 'provider' ){
            $reports = Order::where('provider_id',Auth::user()->id)->latest();  
        }
        else{
            $reports = Order::where('center_id',Auth::user()->id)->latest();  
        }
        // return $request ;
        $provider_id = '' ;
        $center_id = '' ;
        $driver_id = '' ;
        $city_id = '' ;
        $area_id = '' ;
        $stat = '' ;
        $date_from = '' ;
        $date_to = '' ;

           
        if($request->provider_id){
            $reports = $reports->where('provider_id',$request->provider_id);
            $provider_id = $request->provider_id ;
        }
        if($request->center_id){
            $reports = $reports->where('center_id',$request->center_id);
            $center_id = $request->center_id ;
        }
        if($request->driver_id){
            $reports = $reports->where('driver_id',$request->driver_id);
            $driver_id = $request->driver_id ;
        }
        if($request->city_id){
            $reports = $reports->where('city_id',$request->city_id);
            $city_id = $request->city_id ;

        }
        if($request->area_id){
            $reports = $reports->where('area_id',$request->area_id);
            $area_id = $request->area_id ;

        }
        if($request->status){
            $reports = $reports->where('status',$request->status);
            $stat = $request->status ;

        }
        if($request->date_from){
            $reports = $reports->whereDate('created_at','>=',$request->date_from);
            $date_from = $request->date_from ;

        }
        if($request->date_to){
            $reports = $reports->whereDate('created_at','<=',$request->date_to);
            $date_to = $request->date_to ;

        }
        $reports = $reports->get();
        // return $area_id ;
        return view('reports.index',compact('title','reports','cities','areas','providers','centers','drivers','status','lang','provider_id','center_id','driver_id','city_id','area_id','stat','date_from','date_to'));
        

        if(Auth::user()->role == 'admin' ){
            $start = Carbon::parse($request->start)->toDateString() ;
            $end = Carbon::parse($request->end)->toDateString() ;
            $e = Carbon::parse($request->start)->toDateString() ;
            $orders = Order::where('service_id',$request->service_id)->whereDate('updated_at','>=' ,$start)->whereDate('updated_at','<=' ,$end)->count('id');
  
            return response()->json([ 
                'orders' => $orders,
            ]);
            return view('report.index',compact('clients','departments','sales','orders','users'));
        }
    }
    public function charges()
    {
            $title =  'reports' ;
            $charges = Charge::with('package')->get();
            return view('reports.charges',compact('charges','title'));
    }

    public function reportsdeals()
    {
            $title =  'reportsdeals' ;
            $deals = Ticket::where('status','1')->with('deal')->with('user')->get();
            // return $deals ;
            return view('reports.deals',compact('deals','title'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lang = App::getlocale();

        $title = 'reports';
        $order = Order::where('id',$id)->with('centers')->with('drivers')->with('user')->orderBy('id', 'DESC')->first();
        // return $order ; 
        return view('reports.show',compact('order','title','lang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
