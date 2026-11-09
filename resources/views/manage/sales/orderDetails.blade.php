@extends('layouts.admin')
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                     <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Orders Details</h6>
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
                                            <tr>
                                                <th></th>
                                                <th>Order No</th>
                                                <th>Product Name</th>
                                                  <th>Price</th>
                                                <th>Quantity</th>
                                             
                                                 <th>Created At</th>
                                                
                                            </tr>
                                            </thead>
                                            <tbody>
                                        @if(count($ordersItems) > 0)
                                        @foreach ($ordersItems as  $sp)
                                            <tr>
                                        
                                                <td>
                                                    <img src="{{asset('/images/products/'.$sp->image)}}" width="50px" height="50px"> 
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->order_No}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->product_name}}</a>
                                                </td>
                                            
                                                 <td>
                                                    <a href="#">{{number_format($sp->price,2)}}</a>
                                                </td>
                                                  <td>
                                                    <a href="#">{{$sp->qty}}</a>
                                                </td>
                                                 
                                            
                                                 
                                               
                                               
                                                {{$sp->description}}
                                                </td>      
                                                  <td>
                                                    <a href="#">{{$sp->created_at->format('d/m/y h:ma')}}</a>
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