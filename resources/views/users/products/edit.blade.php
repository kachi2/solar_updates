@extends('layouts.app')

@section('content')


	<section class="breadcrumb-section">
			<h2 class="sr-only">Site Breadcrumb</h2>
			<div class="container">
				<div class="breadcrumb-contents">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.html">Home</a></li>
							<li class="breadcrumb-item active">Checkout</li>
						</ol>
					</nav>
				</div>
			</div>
		</section>

	 <section class="mb--30">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-md-6 mt--30">
                        <div class="feature-box h-100">
                            <div class="icon">
                                <i class="fas fa-plus-circle" style="color:#000"></i>
                            </div>
                            <div class="text">
                                <p>Add to Cart</p>
                            </div>
                        </div>
						
                    </div>
				
                    <div class="col-xl-3 col-md-6 mt--30">
                        <div class="feature-box h-100 ">
                            <div class="icon">
                                <i class="fas fa-shopping-cart" style="color:#000"></i>
                            </div>
                            <div class="text">
                                <h5>View Cart</h5>
                            </div>
							
                        </div>		
                    </div>
					
                    <div class="col-xl-3 col-md-6 mt--30">
                        <div class="feature-box h-100">
                            <div class="icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="text">
                                <h5>Add Delivery</h5>
                            </div>
							
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mt--30">
                        <div class="feature-box h-100">
                            <div class="icon">
                                <i class="fas fa-credit-card" style="color:#000"></i>
                            </div>
                            <div class="text">
                                <h5>Payment</h5>
                            </div>
							
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<main id="content" class="page-section inner-page-sec-padding-bottom space-db--20">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<!-- Checkout Form s-->
						<div class="checkout-form">
							<div class="row row-40">
								<div class="col-12">

								@if(Session::has('message'))
								
								<span class="alert alert-{{Session::get('alert')}}"> 
								{{Session::get('message')}}
								@if(Session::has('alert') == 'danger')
								<a class="p-2 btn-info" href="{{route('password.request')}}"> Reset Password </a>
								@endif
								</span>

								@endif	
									<!-- Slide Down Trigger  -->
									<div class="checkout-quick-box">
										<p><i class="far fa-sticky-note"></i>Have a coupon? <a href="javascript:"
												class="slide-trigger" data-target="#quick-cupon">
												Click here to enter your code</a></p>
									</div>
									<!-- Slide Down Blox ==> Cupon Box -->
									<div class="checkout-slidedown-box" id="quick-cupon">
										<form action="">
											<div class="checkout_coupon">
												<input type="text" class="mb-0" placeholder="Coupon Code">
												<a href="#" class="btn btn-outlined">Apply coupon</a>
											</div>
										</form>
									</div>
								</div>
								<div class="col-lg-8 mb--20">
									<!-- Billing Address -->
							<form action="{{ route('checkout.add') }}" method="POST">
							@csrf
									<div id="billing-form " class="mb-40">
										<p class="checkout-title ">Customer Details</p>
										<div class="row">
										<div class="col-md-6 col-12 mb--20">
												<label>Name*</label>
												<input type="text" name="name"@auth value=" {{auth()->user()->name}}" @endauth value="{{old('name')}}" class="@error('name') is-invalid @enderror" placeholder="Name" @auth readOnly @endauth>

												@error('name')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											<div class="col-md-6 col-12 mb--20">
												<label>Email Address*</label>
												<input type="email" name="email" value="@auth {{auth()->user()->email}} @endauth {{old('email')}} " class="@error('email') is-invalid @enderror" placeholder="Email Address" @auth readOnly @endauth>
											@error('email')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											<div class="col-md-12 col-12 mb--20">
												<label>Phone no*</label>
												<input type="text" name="phone" @auth value=" {{auth()->user()->phone}} @endauth" value="{{old('phone')}}" class="@error('phone') is-invalid @enderror" placeholder="Phone number" @auth readOnly @endauth>
											@error('phone')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>								
										</div>
									</div>


									<div id="billing-form " class="mb-40">
										<p class="checkout-title ">Shipping Information</p>
										<div class="row">
										<div class="col-md-6 col-12 mb--20">
												<label>Name*</label>
												<input type="text" name="receiver_name"  value="{{old('receiver_name')}}" class="@error('receiver_name') is-invalid @enderror" placeholder="First Name">
											@error('receiver_name')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											<div class="col-md-6 col-12 mb--20">
												<label>Email Address*</label>
												<input type="email" name="receiver_email" value="{{old('receiver_email')}}" class="@error('receiver_email') is-invalid @enderror" placeholder="Email Address">
											@error('receiver_email')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											<div class="col-md-6 col-12 mb--20">
												<label>Phone no*</label>
												<input type="text" name="receiver_phone"   value="{{old('receiver_phone')}}" class="@error('receiver_phone') is-invalid @enderror" placeholder="Phone number">
											@error('receiver_phone')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>	

												<div class="col-6 mb--20">
												<label>Address*</label>
												<input type="text" name="address"  value="{{old('address')}}" class="@error('address') is-invalid @enderror"placeholder="Address">
											@error('address')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											
											<div class="col-md-6 col-12 mb--20">
												<label>Town/City*</label>
												<input type="text" name="city"  value="{{old('city')}}"   class="@error('city') is-invalid @enderror" placeholder="Town/City">
											@error('city')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											<div class="col-md-6 col-12 mb--20">
												<label>State*</label>
												<input type="text"  name="state"  value="{{old('state')}}"   class="@error('state') is-invalid @enderror" placeholder="State">
											@error('state')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
											<div class="col-md-12 col-12 mb--20">
												<label>Zip Code*</label>
												<input type="text" name="zip_code" value="{{old('zip_code')}}"   class="@error('zip_code') is-invalid @enderror" placeholder="Zip Code">
											</div>
													
																		
										</div>
									</div>
									<!-- Shipping Address -->

									
									<div id="billing-form " class="mb-40">
										<p class="checkout-title ">Select Delivery Method</p>
										<div class="row">
										<div class="col-md-12 col-12 mb--20">
												<label>Delivery Method</label>
												<select name="delivery_method"  class=" form-control @error('name') is-invalid @enderror">
												
												<option value="home_delivery">Deliver to me</option>
												<option value="pickup"> Self Pickup</option>
												</select>

												@error('name')
												<span class="btn-danger" role="alert">
												<small> {{$message}}</small>
												</span>
												@enderror
											</div>
																		
										</div>
									</div>
								
								</div>
								<div class="col-lg-4">
									<div class="row">
										<!-- Cart Total -->
										<div class="col-12">
											<div class="checkout-cart-total">
												<h2 class="checkout-title">YOUR ORDER</h2>
												@if(count($carts)>0)
												<h4>Product <span>Price</span></h4>
												<ul>
												@foreach ($carts as $cart )
													<li><span class="left">
                                            <a href=""><img src="{{asset('')}}/frontend/image/products/product-1.jpg" width="50px" height="50px" alt=""></a>
    <a href="">{{$cart->model->name}} <br> Qty: {{$cart->quantity}}</a>
													</span> <span
															class="right">₦{{number_format($cart->price,2)}}</span></li>
																@endforeach
												
												</ul>
												<p>Sub Total <span>₦{{number_format(\Cart::getSubTotal(false))}}</span></p>
												<p>Shipping Fee <span>200.00</span></p>
												<p>TAX Fee <span> ₦{{number_format(\Cart::getSubTotal(false)*0.075,2)}}</span></p>
												<h4>Grand Total <span>₦{{number_format((\Cart::getSubTotal(false)*0.075) + \Cart::getSubTotal(false) + 2000 ,2)}} </span></h4>
												@endif
												
												<div class="term-block">
													<input type="checkbox" id="accept_terms2">
													<label for="accept_terms2">I’ve read and accept the terms &
														conditions</label>
												</div>
												<button class="btn btn-success w-100">Place Order</button>
											</div>
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
	</div>

@endsection