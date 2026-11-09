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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Order Details</li>
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
                      <a href="" class="btn btn-gray arrow btn-xs p-1"><< Back</a>
                         <span style="font-size:24px">Order Details</span> 
                            <div class="borders-radius-17 border p-4 mt-4 mt-md-0 px-lg-10 px-xl-4 px-wd-10 py-lg-9 py-xl-5 py-wd-9">
                             
                            
                             Order No: {{$orders->order_No}}<br>
                             Total Amount:  ₦{{ number_format($orders->amount,2)}}<br>
                              Placed On:  {{$orders->created_at}} <br>  
                           

                             <hr>
                              <strong>Items in this Order</strong>
                               <div class="row">
                                <table class="table">
                                            <thead>
                                                <tr>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($items as $item)
                                                <tr class="cart_item">
                                                    <td>
                                                  <a href="#"><img width="100px" height="150"class="img-fluid max-width-100 p-1" src="{{asset('images/products/'.$item->image)}}" alt="Image Description"></a>
                                                    </td>
                                                    <td>
                                                     {{$item->product_name}}<br>
                                                    Placed On: {{$item->created_at}}<br>
                                                    Amount: ₦{{number_format($item->price,2)}}<br>
                                                     <strong class="product-quantity">QTY: {{$item->qty}}</strong><br>
                                                  </td>

                                                
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            </table>
                             
                            </div>
                            <hr>
                            <div class="row">
                          
                             <div class="mb-6 col-xl-6">
                       <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                            <th><strong>SHIPPING INFORMATION</strong> </th>
                            </tr>
                            
                            </thead>
                            <tbody>
                            <tr>
                            <td> 
                            <p> <strong>Shipping Details</strong><br>
							Phone:  {{$orders->shipping?->receiver_name}}<br>
							Phone:  {{$orders->shipping?->receiver_phone}}<br>
                           Email: {{$orders->shipping?->receiver_email}}<br>
                            Address: {{$orders->shipping?->address}}<br>
                           City:  {{$orders->shipping?->city }} {{ $orders->shipping?->state}}<br>
						   City:  {{$orders->shipping?->delivery_method}}<br>
                           
                            </p>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                                 <div class="mb-6 col-xl-6">
                       <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                            <th><strong>Payment Information</strong> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <td> 
                             <div style="height:10px">
                            </div>
                           <p>
                           @if(isset($orders->external_ref))
                           <span class="border-bottom border-color-1 mb-5">Payment Type: {{$orders->payment_method}}</span><br>
                           <span class="border-bottom border-color-1 mb-5">Payment Reference: {{$orders->external_ref}}</span><br>
                           @endif
                           <span class="border-bottom border-color-1 mb-5" style="line-height:2"> Transaction Ref: {{$orders->payment_ref}}</span><br>
                           <span class="border-bottom border-color-1 mb-5" style="line-height:2">Amount: ₦{{number_format($orders->amount)}}</span><br>
						   <span class="border-bottom border-color-1 mb-5" style="line-height:2">Payment Status: @if($orders->is_paid == 1) <span class="badge badge-success ">&nbsp; Success &nbsp;</span>@else <span  class="badge badge-warning ">Pending</span>@endif</span><br>
                           <span class="border-bottom border-color-1 mb-5" style="line-height:2">Delivery Status:  @if($orders->is_delivered == 1) <span class="badge badge-success ">&nbsp; Delivered &nbsp;</span>@else <span class="badge badge-warning ">Pending </span> @endif</span><br>
						   </p>
                            <div style="height:30px">
                            </div>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                            </div>
                         
                            </div>
                            <!-- End Tab Content -->
                        </div>
                </div>
            </div>
</main>


@endsection