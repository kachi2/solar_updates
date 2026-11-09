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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Update Account</li>
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
                                <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">Update Details</h3>
                                @include('includes.message')
                            </div>
                            
                             <form action="{{ route('update.user-details') }}" method="POST">
                             @csrf
                             {{csrf_field()}}
                         <div class="row">
                      <div class="mb-6 col-xl-6">
                       
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">Name
                                    </label>
                                    <input type="address"  placeholder="full Address" value="{{auth()->user()->name}}" name="name" class="form-control">
                                </div>
                                <div class="js-form-message form-group mb-5">
                                    <label class="form-label" for="RegisterSrEmailExample3">Email
                                    </label>
                                    <input type="email"  placeholder="email" value="{{auth()->user()->email}}" name="email" class="form-control" >
                                </div>
                                </div>
                             <div class="mb-6 col-xl-6">
                                    <div class="form-group">
                                <div class="js-form-message js-focus-state">
                                    <label class="sr-only" for="signupPassword">Old Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="signupPasswordLabel">
                                                <span class="fas fa-lock"></span>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" name="oldpassword" id="signupPassword" placeholder="Old Password" aria-label="Password" aria-describedby="signupPasswordLabel"
                                        data-msg="Your password is invalid. Please try again."
                                        data-error-class="u-has-error"
                                        data-success-class="u-has-success">
                                    </div>
                                </div>
                            </div>
                                   <div class="form-group">
                                <div class="js-form-message js-focus-state">
                                    <label class="sr-only" for="signupPassword">New Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="signupPasswordLabel">
                                                <span class="fas fa-lock"></span>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" name="password" id="signupPassword" placeholder="New Password" aria-label="Password" aria-describedby="signupPasswordLabel"
                                        data-msg="Your password is invalid. Please try again."
                                        data-error-class="u-has-error"
                                        data-success-class="u-has-success">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="js-form-message js-focus-state">
                                <label class="sr-only" for="signupConfirmPassword">Confirm Password</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="signupConfirmPasswordLabel">
                                            <span class="fas fa-key"></span>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control" name="password_confirmation" id="signupConfirmPassword" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="signupConfirmPasswordLabel"
                                    data-msg="Password does not match the confirm password."
                                    data-error-class="u-has-error"
                                    data-success-class="u-has-success">
                                    </div>
                                </div>
                            </div>
                            
                                 </div> 
                                </div>
                                
                                <center>
                            <input type="submit" name="update"  value="Update Details" class="btn btn-primary px-5">
                                   </center>
                             </form>
                                
                        </div>
                        
                </div>
            </div>
</main>


@endsection