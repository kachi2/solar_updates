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
                                            <tr><th class="text-left">User Email</th>
                                                <th>Order No</th>
                                                <th>Payment Ref</th>
                                                  <th>External Ref</th>
                                                   <th>Payment Method</th>
                                                <th>Amount</th>
                                                <th>Type</th>
                                                <th>Prev balance</th>
                                                <th>Avail Balance</th>
                                                 <th>Created At</th>
                                               
                                            </thead>
                                            <tbody>
                                        @if(count($transactions) > 0)
                                        @foreach ($transactions as  $sp)
                                            <tr>
                                            
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
                                                    <a href="#">{{$sp->external_ref}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->payment_method}}</a>
                                                </td>
                                                 <td>
                                                    <a href="#">{{number_format($sp->amount,2)}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{$sp->type}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{number_format($sp->prev_balance,2)}}</a>
                                                </td>
                                                <td>
                                                    <a href="#">{{number_format($sp->avail_balance,2)}}</a>
                                                </td>
                                                 
                                                <td>
                                                    <a href="#">{{$sp->created_at->format('d/M/y h:s')}}</a>
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