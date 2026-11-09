@extends('layouts.admin')

@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Recently Loggon Users</h6>
                            <div class="text-center">
                                <h1 class="font-size-40 font-weight-bold mb-3">{{$active}} 
                                <small class="text-success font-weight-bold"> Recent Loggon Users </small></h1> 
                                <p>User that logged in for the past 24hrs</p>
                                <div class="row mb-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Active Users</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-uppercase font-size-11 text-muted">
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>IP </th>
                                        <th class="text-right">Location</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($users) > 0)
                                    @foreach ($users as $uu )
                                    <tr>
                                        <td>
                                            {{$uu->name}}
                                        </td>
                                        <td>{{$uu->email}}</td>
                                        <td>
                                            {{$uu->login_ip}}
                                        </td>
                                        <td class="text-right">    
                                        
                                        @php $details = json_decode(file_get_contents("http://ipinfo.io/197.210.65.32/json"));
                                        echo $details->city.", ".$details->country;
                                        @endphp
                                        
                                        </td>
                                    </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
            </div>

<div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Today's Users</h6>
                            <div class="text-center">
                                <h1 class="font-size-40 font-weight-bold mb-3">{{$today}} 
                                <small class="text-success font-weight-bold">Total New Users</small></h1> 
                                <p>Total new registered users since today  </p>
                                <div class="row mb-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Today Registered Users</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-uppercase font-size-11 text-muted">
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>IP </th>
                                        <th class="text-right">Location</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($new_users) > 0)
                                    @foreach ($new_users as $uu )
                                    <tr>
                                        <td>
                                            {{$uu->name}}
                                        </td>
                                        <td>{{$uu->email}}</td>
                                        <td>
                                            {{$uu->login_ip}}
                                        </td>
                                        <td class="text-right">    
                                        
                                        @php $details = json_decode(file_get_contents("http://ipinfo.io/197.210.65.32/json"));
                                        echo $details->city.", ".$details->country;
                                        @endphp
                                        
                                        </td>
                                    </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
            </div>


            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Logon Users for the past 7 days</h6>
                            <div class="text-center">
                                <h1 class="font-size-40 font-weight-bold mb-3">{{$recent}} 
                                <small class="text-success font-weight-bold">This week Active Users</small></h1> 
                                <p>Total User that have logon since the past 7 days  </p>
                                <div class="row mb-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Logon Users for the past 7 days</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-uppercase font-size-11 text-muted">
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>IP </th>
                                        <th class="text-right">Location</th>
                                        <th> Last Login </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($recentActive) > 0)
                                    @foreach ($recentActive as $uu )
                                    <tr>
                                        <td>
                                            {{$uu->name}}
                                        </td>
                                        <td>{{$uu->email}}</td>
                                        <td>
                                            {{$uu->login_ip}}
                                        </td>
                                        <td class="text-right">    
                                        
                                        @php $details = json_decode(file_get_contents("http://ipinfo.io/197.210.65.32/json"));
                                        echo $details->city.", ".$details->country;
                                        @endphp
                                        
                                        </td>
                                        <td>
                                            {{$uu->updated_at->DiffForHumans()}}
                                        </td>
                                    </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
    <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">This Week's Users</h6>
                            <div class="text-center">
                                <h1 class="font-size-40 font-weight-bold mb-3">{{$week}} 
                                <small class="text-success font-weight-bold">New this week</small></h1> 
                                <p>Total new registered users since 7 days ago  </p>
                                <div class="row mb-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">This week Registered Users</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-uppercase font-size-11 text-muted">
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>IP </th>
                                        <th>Location</th>
                                         <th class="text-right">Joined </th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($thisweek) > 0)
                                    @foreach ($thisweek as $uu )
                                    <tr>
                                        <td>
                                            {{$uu->name}}
                                        </td>
                                        <td>{{$uu->email}}</td>
                                        <td>
                                            {{$uu->login_ip}}
                                        </td>
                                        <td class="text-right">    
                                        
                                        @php $details = json_decode(file_get_contents("http://ipinfo.io/197.210.65.32/json"));
                                        echo $details->city.", ".$details->country;
                                        @endphp
                                        
                                        </td>
                                        <td> 
                                        {{$uu->created_at->DiffForHumans()}}
                                    </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
    </div>

 <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Orders Today</h6>
                            <div class="text-center">
                                <h1 class="font-size-40 font-weight-bold mb-3">{{$order}} 
                                <small class="text-success font-weight-bold">Orders Today</small></h1> 
                                <p>Total Orders for the past 24 hours </p>
                                <div class="row mb-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Orders Today</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-uppercase font-size-11 text-muted">
                                       <th class="text-left">S/N</th>
                                                <th>User Email</th>
                                                <th>Order No</th>
                                                <th>Payment Ref</th>
                                                  <th>Payment Method</th>
                                                <th>Amount</th>
                                                <th>Payment Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($orders) > 0)
                                        @foreach ($orders as  $sp)
                                            <tr>
                                            <td>{{$sp->id}}</td>
                                                <td>
                                                    <a href="#">{{substr($sp->user->email,0,15)}}..</a>
                                                </td> 
                                                <td>
                                                    <a href="#">{{$sp->order_No}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->payment_ref}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->payment_method}}</a>
                                                </td>
                                                 <td>
                                                    <a href="#">{{number_format($sp->amount,2)}}</a>
                                                </td>
                                                 <td>
                                                    @if($sp->is_paid == 1) <span  class="badge badge-success">Paid</span> @else <span type="span" class="badge badge-light">Pending</span> @endif</a>
                                                </td> 
                                    </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
    </div>


    <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">7 Days Orders</h6>
                            <div class="text-center">
                                <h1 class="font-size-40 font-weight-bold mb-3">{{$tt_order}} 
                                <small class="text-success font-weight-bold">7 Days Orders</small></h1> 
                                <p>Total Orders for the past 7 days </p>
                                <div class="row mb-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Orders for 7 Days</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-uppercase font-size-11 text-muted">
                                       <th class="text-left">S/N</th>
                                                <th>User Email</th>
                                                <th>Order No</th>
                                                <th>Payment Ref</th>
                                                  <th>Payment Method</th>
                                                <th>Amount</th>
                                                <th>Payment Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(count($av_orders) > 0)
                                        @foreach ($av_orders as  $sp)
                                            <tr>
                                            <td>{{$sp->id}}</td>
                                                <td>
                                                    <a href="#">{{substr($sp->user->email,0,15)}}..</a>
                                                </td> 
                                                <td>
                                                    <a href="#">{{$sp->order_No}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->payment_ref}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->payment_method}}</a>
                                                </td>
                                                 <td>
                                                    <a href="#">{{number_format($sp->amount,2)}}</a>
                                                </td>
                                                 <td>
                                                    @if($sp->is_paid == 1) <span  class="badge badge-success">Paid</span> @else <span type="span" class="badge badge-light">Pending</span> @endif</a>
                                                </td> 
                                    </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
    </div>

        </div>

        <!-- end::footer -->

    </main>
@endsection