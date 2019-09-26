@extends('layouts.index')
@section('style')
    <style> 
        .hidden{
            display:none ;
        }
    </style>
@endsection
 @section('content')
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>{{__('admin.dashboard')}}
                <small>{{__('admin.Welcome to hr')}}</small>
                </h2>
            </div>            
                @if($lang =='ar')
                <div class="col-lg-7 col-md-7 col-sm-12 text-left">
                <ul class="breadcrumb float-md-left" style=" padding: 0.6rem; direction: ltr; ">
                @else 
                <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <ul class="breadcrumb float-md-right">
                @endif
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="zmdi zmdi-home"></i> {{__('admin.dashboard')}}</a></li>
                    <!-- <li class="breadcrumb-item active">{{__('admin.dashboard')}}</li> -->
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        <!-- for statics -->
        <div class="row clearfix">
            <div class="col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                                    <div class="body">
                                        <a href="{{ route('users') }}">
                                        <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{$users}}" data-speed="1000" data-fresh-interval="700">{{$users}}</h2></a>
                                        <p class="text-muted">{{trans('admin.users')}}</p>
                                        <span id="linecustom1">1,4,2,6,5,2,3,8,5,2</span>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                                    <div class="body">
                                        <a href="{{ route('technicians') }}"><h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{$technicians}}" data-speed="2000" data-fresh-interval="700">{{$technicians}}</h2></a>
                                        <p class="text-muted ">{{trans('admin.technicians')}}</p>
                                        <span id="linecustom2">2,9,5,5,8,5,4,2,6</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                                    <div class="body">
                                        <a href="{{ route('services') }}"><h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{$services}}" data-speed="2000" data-fresh-interval="700">{{$services}}</h2></a>
                                        <p class="text-muted">{{trans('admin.services')}}</p>
                                        <span id="linecustom3">1,5,3,6,6,3,6,8,4,2</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                                    <div class="body">
                                        <a href="{{ route('orders') }}"><h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{$orders}}" data-speed="1000" data-fresh-interval="700">{{$orders}}</h2></a>
                                        <p class="text-muted">{{trans('admin.orders')}}</p>
                                        <span id="linecustom4">1,4,2,6,5,2,3,8,5,2</span>
                                    
                                    </div>
                                </div>
                         
                                 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- for statics map -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        @if($lang == 'ar')
                            <h2>{{ __('admin.report') }} <strong> {{ __('admin.orders') }}</strong> </h2>
                        @else 
                            <h2><strong>{{ __('admin.orders') }}</strong> {{ __('admin.report') }}  </h2>
                        @endif
                        <ul class="header-dropdown">

                        </ul>
                    </div>
                    <div class="body">
                        <div class="row text-center">
                            <div class="col-sm-3 col-6">
                                <h3 class="margin-0"> {{$this_day}}  <i class="zmdi zmdi-trending-up col-green"></i></h3>
                                <p class="text-muted"> {{__('admin.Today_orders')}}</p>
                            </div>
                            <div class="col-sm-3 col-6">
                                <h3 class="margin-0"> {{$this_week}}   <i class="zmdi zmdi-trending-up col-green"></i></h3>
                                <p class="text-muted">{{__('admin.This_Week_orders')}}</p>
                            </div>
                            <div class="col-sm-3 col-6">
                                <h3 class="margin-0"> {{$this_month}}  <i class="zmdi zmdi-trending-up col-green"></i></h3>
                                <p class="text-muted">{{__('admin.This_Month_orders')}}</p>
                            </div>
                            <div class="col-sm-3 col-6">
                                <h3 class="margin-0"> {{$this_year}}  <i class="zmdi zmdi-trending-up col-green"></i></h3>
                                <p class="text-muted">{{__('admin.This_Year_orders')}}</p>
                            </div>
                        </div>
                        <div id="area_chart" class="graph"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>{{ __('admin.This_Week_orders') }}</strong> </h2>
                        <ul class="header-dropdown">
                            
                        </ul>
                    </div>
                    <div class="body">
                        <canvas id="line_chart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>{{ __('admin.This_Year_orders') }}</strong> </h2>
                        <ul class="header-dropdown">
                           
                        </ul>
                    </div>
                    <div class="body">
                        <canvas id="bar_chart" height="150"></canvas>
                    </div>
                </div>
            </div>            
        </div>
    </div>
   
</section>
  
@endsection 

@section('script')
<script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script><!-- Morris Plugin Js -->
<script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> <!-- JVectorMap Plugin Js -->
<script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script> <!-- Jquery Knob, Count To, Sparkline Js -->
<script src="{{ asset('assets/plugins/chartjs/Chart.bundle.min.js') }}"></script> <!-- Chart Plugins Js -->
<script src="{{ asset('assets/js/pages/index.js') }}"></script> 

<script> 


    last_sex_years = [] ;
    months = [] ;
    sales = [] ;
    orders = [] ;
    days = [] ;
    saless = [] ;
    orderss = [] ;
    yk = [] ;
    lab = [] ;
    i = 0 ;
    @foreach($last_sex_years as $year)
            last_sex_years[i] ={
                period: "{{$year['period']}}",
                sales: "{{$year['sales']}}",
                orders: "{{$year['orders']}}"
            }; 
        i ++ ;
        {{--  console.log(last_sex_years);  --}}
    @endforeach
    i = 11 ;
    @foreach($sales_for_year as $month)
        months[i] ="{{$month['period']}}" ;
        sales[i] ="{{$month['sales']}}" ;
        orders[i] ="{{$month['orders']}}" ;
            
        i -- ;
    @endforeach

    i = 6 ;
    @foreach($sales_for_week as $day)
        days[i] ="{{$day['period']}}" ;
        saless[i] ="{{$day['sales']}}" ;
        orderss[i] ="{{$day['orders']}}" ;
            
        i -- ;
    @endforeach
    //======

    xk = "{{__('admin.period')}}" ;
    yk[0] = "{{__('admin.sales')}}" ;
    yk[1] = "{{__('admin.orders')}}" ;
    lab[0] = "{{__('admin.sales')}}" ;
    lab[1] = "{{__('admin.orders')}}" ;

    function MorrisArea() {
        Morris.Area({
            element: 'area_chart',
            data: last_sex_years,
        lineColors: [ '#00ced1', '#ff758e'],
        xkey: 'period',
        ykeys: [ 'orders'],
        labels: [ "{{__('admin.orders')}}"],
        pointSize: 0,
        lineWidth: 0,
        resize: true,
        fillOpacity: 0.8,
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        hideHover: 'auto'
        });
    }
    //======
    $(function () {
        new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
        new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
    });

    function getChartJs(type) {
        var config = null;

        if (type === 'line') {
            config = {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [  {
                        label: "{{__('admin.orders')}}",
                        data: orderss,                    
                        borderColor: 'rgba(140,147,154, 0.2)',
                        backgroundColor: 'rgba(140,147,154, 0.2)',
                        pointBorderColor: 'rgba(140,147,154, 0)',
                        pointBackgroundColor: 'rgba(140,147,154, 0.9)',
                        pointBorderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: false,
                    
                }
            }
        }
        else if (type === 'bar') {
            config = {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                       {
                            label: "{{__('admin.orders')}}",
                            data: orders,
                            backgroundColor: '#8a8a8b',
                            strokeColor: "rgba(255,118,118,0.1)",
                        }]
                },
                options: {
                    responsive: true,
                    legend: false
                }
            }
        }
        else if (type === 'radar') {
            config = {
                type: 'radar',
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July"],
                    datasets: [{
                        label: "My First dataset",
                        data: [65, 25, 90, 81, 56, 55, 40],
                        borderColor: 'rgba(241,95,121, 0.8)',
                        backgroundColor: 'rgba(241,95,121, 0.5)',
                        pointBorderColor: 'rgba(241,95,121, 0)',
                        pointBackgroundColor: 'rgba(241,95,121, 0.8)',
                        pointBorderWidth: 1
                    }, {
                            label: "My Second dataset",
                            data: [72, 48, 40, 19, 96, 27, 100],
                            borderColor: 'rgba(140,147,154, 0.8)',
                            backgroundColor: 'rgba(140,147,154, 0.5)',
                            pointBorderColor: 'rgba(140,147,154, 0)',
                            pointBackgroundColor: 'rgba(140,147,154, 0.8)',
                            pointBorderWidth: 1
                        }]
                },
                options: {
                    responsive: true,
                    legend: false
                }
            }
        }
        else if (type === 'pie') {
            config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [150, 53, 121, 87, 45],
                        backgroundColor: [
                            "#2a8ceb",
                            "#58a3eb",
                            "#6fa6db",
                            "#86b8e8",
                            "#9dc7f0"
                        ],
                    }],
                    labels: [
                        "Pia A",
                        "Pia B",
                        "Pia C",
                        "Pia D",
                        "Pia E"
                    ]
                },
                options: {
                    responsive: true,
                    legend: false
                }
            }
        }   
        return config;
    }
</script>

@endsection
