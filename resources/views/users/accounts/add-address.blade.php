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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">My Account</li>
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
                            <div class="border-bottom border-color-1 mb-6">
                                @include('includes.message')
                                <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">My Shipping Address</h3>
                            </div>
                             <form action="{{ route('address.store', [auth()->user()->id]) }}" method="POST">
                             @csrf
                             {{csrf_field()}}
                         <div class="row">
                      <div class="mb-6 col-xl-6">
                        <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">Receiver
                                    </label>
                                    <input type="address"  placeholder="Name of Receiver" name="name" class="form-control" value="" required>
                                </div>
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">Address
                                    </label>
                                    <input type="address"  placeholder="full Address" name="address" class="form-control" value="" required>
                                </div>
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">Email
                                    </label>
                                    <input type="email"  placeholder="email" name="email" class="form-control" value="" required>
                                </div>
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label"  for="RegisterSrEmailExample3">Phone
                                    </label>
                                    <input type="text" placeholder="phone" name="phone"class="form-control" value="" required>
                                </div>
                                </div>
                                
                                  
                      <div class="mb-6 col-xl-6">
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">City
                                    </label>
                                    <input type="text" placeholder="city" name="city" class="form-control" value="" required>
                                </div>
                                
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">State
                                    </label>
                                    <input type="text" placeholder="state" name="state" class="form-control" value="" required>
                                </div>
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">
                                    </label>
                                    Set as Default: <input type="checkbox"  value="1" name="default">
                                </div>
                            
                                 </div> 
                                </div>
                                <center>
                                <button type="submit" class="btn btn-primary-dark-w px-5">Add Address</button>
                                   </center>
                             </form>
                                
                        </div>
                        
                </div>
            </div>
</main>


@endsection