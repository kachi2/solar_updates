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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="">Checkout</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Payment</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            <!-- End breadcrumb -->

            <div class="container">
                <div class="mb-5">
                    <h4 class="text-center ">Payment</h4>
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
                                                    <th></th>
													<th></th>
                                                    
                                                </tr>
                                                @php
                                                $cart = \Cart::getSubTotal(false);
                                                $total = \Cart::getSubTotal(false);
                                                @endphp
                                                <tr>
                                                    <th>Total</th>
													<td></td>
                                                    <td><strong>₦{{number_format($total, 2)}}</strong></td>
                                                </tr>
                                                
                                            </tfoot>
                                        </table>
                                        
                                        <script src="https://checkout.flutterwave.com/v3.js"></script>
                                        <form>
                                        <button type="button" onClick="makePayment()" id="btnsubmit2" class="btn btn-primary btn-block  btn-sm ">Pay Now</button>
                                    </form>
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
                            <th class="defaults mb-0">Shipping Address </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                        
                            <td> 
                            
                            
                            Receiver:{{$address->name}} <br>
                            Address: {{$address->address}} <br>
                            City: {{$address->city . $address->state}} <br>
                            Email: {{$address->email}} <br>
                            Phone: {{$address->phone}} <br>

                           
                            </td>
                            </tr>
                         
                           <tr><td>
                                </td>
                                </tr>
                              
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
                                        
                                                <div class="border-bottom border-color-1 border-dotted-bottom">
                                                    <div class="p-3" id="basicsHeadingFour">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="FourstylishRadio1" value="card" name="payment_method" checked>
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
                            
                  
             </div>
                        </div>
                    </div>
                     
            </div>
        </main>

@endsection

@section('scripts')
<script>

let total_paid = {!! json_encode(\Cart::getSubTotal(false))!!};
let fare = 1;
let total = parseInt(total_paid) + parseInt(fare);
// $('.toggleM').on('change', function(){
// 	//alert(document.getElementById('fees').value);
// 	if(document.getElementById('home_delivery').checked == true){
// 	 $('#shipment').html('<span class="font-weight-bold">'+'₦'+thousands_separators(fare)+'</span>'); 
// 	 $('#home_de').attr('hidden', false);
// 	 $('#pickup_de').attr('hidden', true);
// 	 $('#fee').attr('value', fare);
// 	  $('#total_paid').html('₦'+thousands_separators(total)); 
// 	}else{
//  	$('#shipment').html('<span class="font-weight-bold">'+'₦'+0+'</span>'); 
// 		$('#pickup_de').attr('hidden', false);
// 		 $('#fees').attr('value', 0);
// 		$('#total_paid').html('₦'+thousands_separators(total_paid)); 
// 		$('#home_de').attr('hidden', true);
// 	}
// });




var _token = {!!  json_encode(config('services.flutterwave.public_key'))!!}
let email = {!! json_encode(auth()->user()->email) !!};
let phone = {!! json_encode(auth()->user()->phone) !!};
let name = {!! json_encode(auth()->user()->name) !!};
let amounts = {!! json_encode(\Cart::getSubTotal(false))!!}

 function makePayment() {
    FlutterwaveCheckout({
      public_key: _token,
      tx_ref: "SFSL"+Math.floor((Math.random() * 1000000) + 1),
      amount: amounts,
      currency: "NGN",
      country: "NG",
      payment_options: "card, ussd",
     // redirect_url: // specified redirect URL
       // https://callbacks.piedpiper.com/flutterwave.aspx?ismobile=34",
      meta: {
        consumer_id: 1,
        consumer_mac: "92a3-912ba-1192a",
        purpose: "Payment for Order",
        
      },
      customer: {
        email: email,
        phone_number: phone,
        name: name,
      },
       callback: function (response) {
          var transactionId = encodeURIComponent(String(response.transaction_id));
          var confirmationUrl = @json(route('confirm.payment', ['id' => '__TRANSACTION_ID__']));
          window.location = confirmationUrl.replace('__TRANSACTION_ID__', transactionId);
      },
      onclose: function() {
        // close modal
      },
      customizations: {
        title: "Sofarsolar",
        description: "Payment for Order",
       logo: "",
      },
    });
  };
function thousands_separators(num)
  {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
  }
  
</script>

@endsection