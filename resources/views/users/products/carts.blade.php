@extends('layouts.app')
@section('content')


  <main id="content" role="main" class="cart-page">
            <!-- breadcrumb -->
            <div class="bg-gray-13 bg-md-transparent">
                <div class="container">
                    <!-- breadcrumb -->
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="../home/index.html">Home</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Cart</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            <!-- End breadcrumb -->

            <div class="container">
                <div class="mb-4">
                    <h1 class="text-center">Cart</h1>
                </div>
              
                <?php if(count($cart) > 0 ){?>
                <div class="mb-10 cart-table">
                    <form class="mb-4" action="#" method="post">
                        @csrf
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                   <th class="product-thumbnail">Item</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity w-lg-15">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                     <th class="product-remove">&nbsp;</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                
                             @foreach ($cart as $carts )
                                <tr class="">
                               
                                    <td class="d-none d-md-table-cell">
                                        <a href="#"><img width="50px" height="80"class="img-fluid max-width-100 p-1 border border-color-1" src="{{asset('images/products/' .$carts->model->image)}}" alt="Image Description"></a>
                                    </td>

                                    <td data-title="Product">
                                        <a href="#" class="text-gray-90">{{$carts->model->name}}</a><br>
                                        
                                    </td>

                                    <td data-title="Price">
                                        <span class="">₦{{number_format($carts->price)}}</span> / <span style="color:rgb(15, 15, 79); font-size:14px"> {{number_format($carts->model->exchange_rate)}} USD </span><br>
                                     
                                        <del style="color:red">{{number_format($carts->model->price)}}</del>
                                    </td>
                                
                                    <td data-title="Quantity">
                                        <span class="sr-only">Quantity</span>
                                        <div class="border py-1 width-12 w-xl-30 px-3 border-color-1">
                                            <div class="js-quantity row align-items-center"> 
                                                 <div class="col">
                                                    <input type="text" name="rowId" class="js-result form-control h-auto border-0 rounded p-0 shadow-none counter" type="text" value="{{$carts->quantity}} " readonly>
                                                 <input type="hidden" name="rowId" class="js-result form-control h-auto border-0 rounded p-0 shadow-none counter" type="text" value="{{$carts->id}}">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-title="Total">
                                        <span class="">{{number_format($carts->price*$carts->quantity )}}</span>
                                    </td>
                                   
                                      <td class="text-center">
                                       <a href="{{route('carts.delete',encrypt($carts->id))}}" name="rowId"  type="submit"  style="font-weight:bold" class="text-gray-36 font-size-12 btn btn-primary">REMOVE </button>
                                    </td>
                                </tr>
                                @endforeach
                              
								
                            </tbody>
                        </table>
                    </form>
                </div>
				
                <div class="mb-8 cart-total">
                    <div class="row">
                        <div class="col-xl-5 col-lg-6 offset-lg-6 offset-xl-7 col-md-8 offset-md-4">
                            <div class="border-bottom border-color-1 mb-3">
                                <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">Cart Total</h3>
                            </div>
                            <table class="table mb-3 mb-md-0">
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Subtotal</th>
                                        <td data-title="Subtotal"><span class="amount">{{Cart::getSubTotal()}}</span></td>
                                    </tr>
                                   
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td data-title="Total"><strong><span class="amount">{{Cart::getTotal()}}</span></strong></td>
                                    </tr>
                                    <tr class="order-total">
										<th></th>
                                        <td></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                             <a href="{{route('checkout.index')}}"><button type="button" class="btn btn-primary ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto ">Proceed to checkout</button></a>
                        </div>
                    </div>
                </div>
                <?php }else{ ?>
                <div style="background:#eee; text-align:center" class="p-3">
               <img src="{{asset('/images/images.png')}}"> 
           @guest
           <p>Don't have an account <strong style="color:#fed700"><a href="{{route('register')}}">Register</a></strong> OR <strong style="color:#fed700"><a href="{{route('login')}}">Login</a></strong> to continue shopping</p>
               @else
               <p>Click<a href=""> Here </a>to Start Shopping</p>
               @endguest
                </div>
                <?php }?>
            </div>
            
        </main>
        
@endsection
