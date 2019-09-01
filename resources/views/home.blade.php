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
                <small>{{__('admin.Welcome to fannie')}}</small>
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
    
       
    </div>
   
</section>
  
@endsection 

@section('script')
<script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script><!-- Morris Plugin Js -->
<script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> <!-- JVectorMap Plugin Js -->
<script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script> <!-- Jquery Knob, Count To, Sparkline Js -->
<script src="{{ asset('assets/plugins/chartjs/Chart.bundle.min.js') }}"></script> <!-- Chart Plugins Js -->
<script src="{{ asset('assets/js/pages/index.js') }}"></script>



{{--  <script src="{{ asset('assets/plugins/chartjs/polar_area_chart.js') }}"></script><!-- Polar Area Chart Js -->   --}}
{{-- <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script><!-- Custom Js -->  --}}
 {{-- <script src="{{ asset('assets/js/pages/charts/chartjs.js') }}"></script>  --}}
{{--  <script src="{{ asset('assets/js/pages/charts/polar_area_chart.js') }}"></script>  --}}

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
        ykeys: [ 'sales', 'orders'],
        labels: [ "{{__('admin.sales')}}", "{{__('admin.orders')}}"],
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
                    datasets: [{
                        label: "{{__('admin.sales')}}",
                        data: saless,
                        borderColor: 'rgba(241,95,121, 0.2)',
                        backgroundColor: 'rgba(241,95,121, 0.5)',
                        pointBorderColor: 'rgba(241,95,121, 0.3)',
                        pointBackgroundColor: 'rgba(241,95,121, 0.2)',
                        pointBorderWidth: 1
                    }, {
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
                    datasets: [{
                        label: "{{__('admin.sales')}}",
                        data: sales,
                        backgroundColor: '#26c6da',
                        strokeColor: "rgba(255,118,118,0.1)",
                    }, {
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
