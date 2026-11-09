@extends('layouts.admin')
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                     <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Transactions</h6>
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
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="table-responsive">
                                        <table id="myTable" class="table table-striped table-bordered">
                                           <thead>
                                            <tr><th class="text-left">Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Last Login</th>
                                                <th>login Ip</th>
                                                 <th>Created At</th>
                                                 <th> </th>
                                               
                                            </thead>
                                            <tbody>
                                        @if(count($users) > 0)
                                        @foreach ($users as  $sp)
                                            <tr>
                                            
                                                <td>
                                                    <a href="#">{{$sp->name}} </a>
                                                </td> 
                                                <td>
                                                    <a href="#">{{$sp->email}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->phone}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->last_login}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->login_ip}}</a>
                                                </td> 
                                                <td>
                                                    <a href="#">{{$sp->created_at->format('d/M/y h:s')}}</a>
                                                </td>
                                               <td class="text-right">
                                                 @php
                                                        $id = $sp->id;
                                                        $parameter = encrypt($id);
                                                        @endphp
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="{{route('admin.user-orders', encrypt($sp->id))}}" class="dropdown-item">View Orders</a>
                                                            <a href="{{route('admin.user-transactions', encrypt($sp->id))}}" class="dropdown-item">View Transactions</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                              @endforeach
                                              @else 
                                              <tr>
                                              <td> No data available </td>
                                              </tr>
                                              @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                 </div>
                  </div>

@endsection