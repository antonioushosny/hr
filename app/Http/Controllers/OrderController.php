<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Technician;
use App\Country;
use App\City; 
use App\Order; 

use Auth;
use App;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'orders';
 
        $orders = Order::orderBy('id', 'DESC')->with('user')->with('fannie')->get();
         //return $orders ; 
        return view('orders.index',compact('orders','title','lang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
        $lang = App::getlocale();

        $title = 'orders';
        $order = Order::where('id',$id)->with('user')->with('fannie')->orderBy('id', 'DESC')->first();
        if(!$order)
        {
            
                return redirect(url('error'));
            
        }
         //return $order ; 
        return view('orders.show',compact('order','title','lang'));
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
