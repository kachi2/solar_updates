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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="">Home</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            <!-- End breadcrumb -->

            <div class="container">
                <div class="mb-5">
                    <h4 class="text-center ">Checkout</h4>
					@include('includes.message') 
                </div>
                <!-- Accordion -->
                <!-- End Accordion -->

                    <div class="row">
                   
                        <div class="col-lg-4 order-lg-2 mb-7 mb-lg-0">
                            <div class="pl-lg-3 ">
                                <div class="bg-gray-1 rounded-lg">
                                    <!-- Order Summary -->
                                    
                                    <div class="p-4 mb-4 checkout-table">
                                        <!-- Title -->
                                        <div class="border-bottom border-color-1 mb-5">
                                            <h6 class="section-title mb-0 "><strong>Your order ({{count($cart)}} items)</strong></h6>
                                        </div>
                                        <!-- End Title -->
                                  <form action="{{ route('checkout.store') }}" method="POST" class="js-validate" novalidate="novalidate">
                                  @csrf
                                        <!-- Product Content -->
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="product-name"></th>
                                                    <th class="product-name">Product</th>
                                                    <th class="product-total">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cart as $carts)
                                                <tr class="cart_item">
                                                    <td><img width="40px" height="40px"src="{{asset('/images/products/'.$carts->model->image )}}"></td>
                                                    <td style="font-size:12px">{{$carts->model->name}}<strong class="product-quantity"><br>QTY:{{$carts->quantity}}</strong></td>
                                                    <td style="font-size:12px">₦{{number_format($carts->price * $carts->quantity, 2)}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                             <tr>
                                                    <th> Shipping</th>
													<th></th>
                                                    
                                                    <td><strong>₦800.00</strong></td>
                                                </tr>
                                                @php
                                                $cart = \Cart::getSubTotal(false);
                                                $total = \Cart::getSubTotal(false) + 800;
                                                @endphp
                                                <tr>
                                                    <th>Total</th>
													<td></td>
                                                    <td><strong>₦{{number_format($total, 2)}}</strong></td>
                                                </tr>
                                                
                                            </tfoot>
                                        </table>
                                      <div class="float-right p-1"><a href="{{route('carts.index')}}"><strong>Modify Cart</strong></a></div>
                                        <button type="submit" class="btn btn-primary btn-block  btn-sm ">Proceed to Payment</button>
                                    </div>
                                    <!-- End Order Summary -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 order-lg-1">
                          
               <div class="row">
                      <div class="mb-6 col-xl-12">
                       <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                            <th class="defaults mb-0">Shipping Address<a style="color:#000" href=""><span class="fa fa-pen float-right">CHANGE</span></a> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            @if(count($shipping) > 0)
                            <td> 
                            
                            @foreach($shipping as $address)
                            Receiver:{{$address->name}} <br>
                            Address: {{$address->address}} <br>
                            City: {{$address->city . $address->state}} <br>
                            Email: {{$address->email}} <br>
                            Phone: {{$address->phone}} <br>

                            @endforeach
                            </td>
                            </tr>
                         
                           <tr><td>
                          
                            <div class="btn-popup pull-right">
                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-original-title="test" data-target="#exampleModal">Ship to a different Address</button>
                    
                                </div>
                                </td>
                                </tr>
                                @else
                                <td><strong> You do not have any Shipping Address yet</td>
                                </tr>

                                <tr>
                                <td>
                            <div class="btn-popup pull-right">
                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-original-title="test" data-target="#exampleModal">Add a Shipping Address</button>
                    
                                </div>
                                </td>
                                @endif
                                </tr>
                                  </tbody>
                                </table>
                 
             </div>
                        </div>
                        
                        <div class="row">
                      <div class="mb-6 col-xl-12">
                       <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                            <th class=" mb-0">Payment Method</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <td> 
                   <div class="border-top border-width-3 border-color-1 pt-3 mb-3">
                                            <!-- Basics Accordion -->
                                            <div id="basicsAccordion1">
                                                <div class="border-bottom border-color-1 border-dotted-bottom">
                                                    <div class="p-3" id="basicsHeadingThree">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="thirdstylishRadio1" value="cash_delivery" name="payment_method">
                                                            <label class="custom-control-label form-label" for="thirdstylishRadio1"
                                                                data-toggle="collapse"
                                                                data-target="#basicsCollapseThree"
                                                                aria-expanded="false"
                                                                aria-controls="basicsCollapseThree">
                                                                Cash on delivery
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div id="basicsCollapseThree" class="collapse border-top border-color-1 border-dotted-top bg-dark-lighter"
                                                        aria-labelledby="basicsHeadingThree"
                                                        data-parent="#basicsAccordion1">
                                                        <div class="p-4">
                                                            Please note that you will pay for shipping fee before item is shipped to you on this method
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Card -->

                                                <!-- Card -->
                                                <div class="border-bottom border-color-1 border-dotted-bottom">
                                                    <div class="p-3" id="basicsHeadingFour">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="FourstylishRadio1" value="card" name="payment_method">
                                                            <label class="custom-control-label form-label" for="FourstylishRadio1"
                                                                data-toggle="collapse"
                                                                data-target="#basicsCollapseFour"
                                                                aria-expanded="false"
                                                                aria-controls="basicsCollapseFour">
                                                                Pay with Debit Card
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div id="basicsCollapseFour" class="collapse border-top border-color-1 border-dotted-top bg-dark-lighter"
                                                        aria-labelledby="basicsHeadingFour"
                                                        data-parent="#basicsAccordion1">
                                                        <div class="p-4">
                                                            You can pay with your VISA, MasterCard, Verve Cards on our secured platform
                                                        </div>
                                                    </div>
                                                </div>
                                              
                                                <!-- End Card -->
                                            </div>
                                            <!-- End Basics Accordion -->
                                        </div>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                             </form>
                                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                       <form action="{{ route('checkout.address') }}" method="POST" class="js-validate" novalidate="novalidate">
                                       @csrf
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title f-w-600" id="exampleModalLabel">Add new Address</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="js-form-message form-group mb-5">
                                                          
                                                            <div class="form-group">
                                                                <label for="validationCustom01" class="mb-1">Name of Receiver</label>
                                                                <input class="form-control" name="name" id="validationCustom01" type="text">
                                                        
                                                                <label for="validationCustom01" class="mb-1">Address</label>
                                                                <input class="form-control" name="address" id="validationCustom01" type="text">
                                                            
                                                                <label for="validationCustom01" class="mb-1">City</label>
                                                                <input class="form-control" name="city" id="validationCustom01" type="text">
                                                            
                                                                <label for="validationCustom01" class="mb-1">State</label>
                                                                <input class="form-control" name="state" id="validationCustom01" type="text">
                        
                                                                <label for="validationCustom01" class="mb-1">Email Address</label>
                                                                <input class="form-control" name="email" id="validationCustom01" type="email">
                                                          
                                                                <label for="validationCustom01" class="mb-1">Phone</label>
                                                                <input class="form-control" name="phone" id="validationCustom01" type="text">
                                                            </div>
                                                       Make Default: <input class="checkbox" value="1" name="default" id="validationCustom01" type="checkbox"> 
                                                            
                                                            
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" name="modal" type="submit">Save</button>
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>

                                        </div>
                                         </form>
                                    </div>
                  
             </div>
                        </div>
                    </div>
                     
            </div>
        </main>

@endsection

