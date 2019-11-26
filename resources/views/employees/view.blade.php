@extends('layouts.index')
@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<style>
.select2-selection.select2-selection--multiple {
    display: block;
    width: 100% !important;
    background-color: transparent;
    border: 1px solid #888888;
    border-radius: 30px;
    color: #2c2c2c;
    line-height: normal;
    font-size: 1.1em;
 
    -webkit-box-shadow: none;
    box-shadow: none;
    
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
                    <li class="breadcrumb-item active"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i>{{__('admin.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('employees')}}"><i class="zmdi zmdi-face"></i> {{__('admin.employees')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">Details</a></li>
                    
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
               

                        <div class="header">
                            <h2><strong>{{trans('admin.'.$title)}}</strong> Details for {{ $employee->name }} </h2>
                            
                        </div>
                        <div class="body row">
                            <div class="col-lg-10">
                                   
                                    <h3>Main Data : </h3>
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{$employee->name}}</td>
                                            <th>mobile</th>
                                            <td>{{$employee->mobile}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('admin.national_id')}}</th>
                                            <td>{{$employee->national_id}}</td>
                                            <th>{{__('admin.mac_address')}}</th>
                                            <td>{{$employee->mac_address}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('admin.net_salary')}}</th>
                                            <td>{{$employee->net_salary}}</td>
                                            <th>{{__('admin.cross_salary')}}</th>
                                            <td>{{$employee->cross_salary}}</td>
                                        </tr>
                                        <tr>
                                            <th>Rewards This Month </th>
                                            <td>{{  $data['sumrewards']}}</td>
                                            <th>Discount This Month</th>
                                            <td>{{   $data['sumdiscounts']}}</td>
                                        </tr>
                                        <tr>
                                            <th> Total Salary </th>
                                            <td>{{  $data['total_salary']}}</td>
                                            <th>{{__('admin.email')}}</th>
                                            <td>{{$employee->email}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('admin.status')}}</th>
                                            <td>{{$employee->status}}</td>
                                            <th>{{__('admin.department')}}</th>
                                            <td>{{$employee->department->title}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('admin.insurance')}}</th>
                                            <td>{{$employee->insurance}}</td>
                                            <th>{{__('admin.annual_vacations')}}</td>
                                            <td>{{$employee->annual_vacations}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('admin.accidental_vacations')}}</th>
                                            <td>{{$employee->accidental_vacations}}</td>
                                            
                                        </tr>
                                    </table> 

                                    <h3>Tasks For This Month : </h3>
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Title</th>
                                            <th>Project Name</th>
                                            <th> Status</th>
                                        </tr>
                                        @foreach($data['tasks'] as $task)
                                            <tr>
                                                <td>{{$task['date']}}</td>
                                                <td>{{$task['time']}}</td>
                                                <td>{{$task['title']}}</td>
                                                <td>{{$task['project_name']}}</td>
                                                <td>{{$task['status']}}</td>

                                            </tr>
                                        @endforeach
                                    </table>

                                    <h3>Attendances For This Month : </h3>
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th>Date</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            
                                        </tr>
                                        @foreach($data['attendences'] as $attendence)
                                            <tr>
                                                <td>{{$attendence['date']}}</td>
                                                <td>{{$attendence['check_in']}}</td>
                                                <td>{{$attendence['check_out']}}</td>
                                              

                                            </tr>
                                        @endforeach
                                    </table>

                                    <h3>Vacations For This Month : </h3>
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Days</th>
                                            <th> Title</th>
                                            <th> Type</th>
                                            <th> Status</th>
                                        </tr>
                                        @foreach($data['vacations'] as $vacation)
                                            <tr>
                                                <td>{{$vacation['from']}}</td>
                                                <td>{{$vacation['to']}}</td>
                                                <td>{{$vacation['days']}}</td>
                                                <td>{{$vacation['title']}}</td>
                                                <td>{{$vacation['type']}}</td>
                                                <td>{{$vacation['status']}}</td>

                                            </tr>
                                        @endforeach
                                    </table>


                                    <h3>Discounts For This Month : </h3>
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th> Reason</th>
                                        </tr>
                                        @foreach($data['discounts'] as $discount)
                                            <tr>
                                                <td>{{$discount['created_at']}}</td>
                                                <td>{{$discount['amount']}}</td>
                                                <td>{{$discount['reason']}}</td>

                                            </tr>
                                        @endforeach
                                    </table>

                                    <h3>Rewards For This Month : </h3>
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th> Reason</th>
                                        </tr>
                                        @foreach($data['rewards'] as $discount)
                                            <tr>
                                                <td>{{$discount['created_at']}}</td>
                                                <td>{{$discount['amount']}}</td>
                                                <td>{{$discount['reason']}}</td>

                                            </tr>
                                        @endforeach
                                    </table>

                              </div>
                        </div>
                </div>
            </div>
        </div>
  
    </div>

</section>
  
@endsection 

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script>
     //this for add new record
     
$('.select2').select2({
    placeholder: "{{trans('admin.choose')}}"
});
</script>
    
@endsection
