@extends('layouts.app')

@section('content')
<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Register</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <div class="my-4 my-xl-8">
            <div class="row">
                <div class="col-md-5 ml-xl-auto mr-md-auto mr-xl-0 mb-8 mb-md-0">
                    <!-- Title -->
                    <div class="border-bottom border-color-1 mb-6">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">Register</h3>
                    </div>
                    <form class="js-validate" novalidate="novalidate" method="post" action={{route('register')}}>
                        @csrf
                        <!-- Form Group -->
                        <div class="form-group">
                            <div class="js-form-message form-group">
                                <label class="form-label" for="signinSrPasswordExample2">Full Name <span class="text-danger">*</span></label>
                                <input type="text" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" name="name" id="signinEmail" placeholder="Full Name"  required
                                data-msg="Please enter a valid email address."
                                data-error-class="u-has-error"
                                data-success-class="u-has-success">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{$message}}</strong>
                                </span>
                                @enderror
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="js-form-message form-group">
                                <label class="form-label" for="signinSrPasswordExample2">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" value="{{old('phone')}}" class="form-control @error('phone') is-invalid @enderror" name="phone"  placeholder="Phone Number" aria-label="Phone Number"  required
                                data-msg="Please enter a valid email address."
                                data-error-class="u-has-error"
                                data-success-class="u-has-success">
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{$message}}</strong>
                                </span>
                                @enderror
                                
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="js-form-message form-group">
                                    <label class="form-label" for="signinSrPasswordExample2">Email <span class="text-danger">*</span></label>
                                    <input type="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" name="email" id="signinEmail" placeholder="Email" aria-label="Email" aria-describedby="signinEmailLabel" required
                                    data-msg="Please enter a valid email address."
                                    data-error-class="u-has-error"
                                    data-success-class="u-has-success">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>
                            </div>
                 
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div class="js-form-message form-group">
                            <label class="form-label" for="signinSrPasswordExample2">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password" id="signinSrPasswordExample2" placeholder="Password" aria-label="Password" required
                            data-msg="Your password is invalid. Please try again."
                            data-error-class="u-has-error"
                            data-success-class="u-has-success">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <!-- End Form Group -->

                        <!-- Checkbox -->
                        <div class="js-form-message form-group">
                            <label class="form-label" for="signinSrPasswordExample2">Re-enter Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control  @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="signinSrPasswordExample2" placeholder="Re-enter Password" aria-label="Password" required
                            data-msg="Your password is invalid. Please try again."
                            data-error-class="u-has-error"
                            data-success-class="u-has-success">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <!-- End Checkbox -->

                        <!-- Button -->
                        <div class="mb-1">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary px-5">Register</button>
                            </div>
                        </div>
                        <!-- End Button -->
                    </form>
                </div>
                <div class="col-md-1 d-none d-md-block">
                    <div class="flex-content-center h-100">
                        <div class="width-1 bg-1 h-100"></div>
                        
                    </div>
                </div>
                <div class="col-md-5 ml-md-auto ml-xl-0 mr-xl-auto">
                    <!-- Title -->
                    <div class="border-bottom border-color-1 mb-6">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">Already have Account?</h3>
                    </div>
                    <p class="text-gray-90 mb-4">Login to your account.</p>
                        
                       
                        <div class="mb-6">
                            <div class="mb-3">
                                <a href="{{route('login')}}" class="btn btn-primary btn-sm ">Click here to login</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
