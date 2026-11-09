@extends('layouts.app')

@section('content')

<main id="content" role="main" class="checkout-page">
            <!-- breadcrumb -->
     <div class="bg-gray-13 bg-md-transparent">
                <div class="container">
                    <!-- breadcrumb -->
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="#">Home</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">My Orders</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            
            <div class="container">
                <div class="row mb-8">
                @include('includes.accountNav')

                      <div class="mb-8 col-xl-9">
                            <div class="borders-radius-17 border p-4 mt-4 mt-md-0 px-lg-10 px-xl-4 px-wd-10 py-lg-9 py-xl-5 py-wd-9">
                              <strong>ORDERS</strong>
                               <div class="row">
                                <table class="table">
                                            <thead>
                                                <tr>
                                                </tr>
                                                <tr>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($orders)>0)
                                            @foreach($orders as $order)
                                                <tr class="cart_item">
                                                    <td>
                                                  <a href="#"><img width="100px" height="150"class="img-fluid max-width-100 p-1" src="{{asset('images/products/' .$order->image)}}" alt="Image Description"></a>
                                                    </td>
                                                    <td>
                                                     {{$order->product_name}}<br>
                                                    Placed On: {{$order->created_at}}<br>
                                                     QTY: {{$order->qty}}<br>
                                                     Amount: ₦{{$order->amount > 0? number_format($order->amount,2): 0}}<br>
                                                      <span>Payment Status:  @if($order->is_paid == 1) <span class="badge badge-success ">&nbsp; Paid &nbsp; &nbsp; </span>@else <span class="badge badge-warning   ">&nbsp; Pending &nbsp;</span> @endif</span><br>
                                                   <span>Delivery Status:  @if($order->is_delivered == 1) <span class="badge badge-success"> &nbsp;Delivered &nbsp;</span>@else <span  class="badge badge-warning  ">&nbsp; Pending  &nbsp;</span> @endif</span><br>
                                                   
                                                   </td>

                                                    <td>
                                                  <a href="{{route('users.order-details',encrypt($order->order_No))}}"  class="btn btn-outline-success btn-xs p-1 float-right">&nbsp; View Details &nbsp;</a>
                                                  
                                                  </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                
                                                <p>You have not done any transaction yet</p>
                                                @endif
                                                
                                            </tbody>
                                            </table>
                             
                            </div>
                            
                    
                            </div>
                            <div class="float-right">{{$orders->links()}}</div>
                            <!-- End Tab Content -->
                        </div>
                </div>
            </div>
</main>


@endsection