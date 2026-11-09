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
                          @include('includes.message')
                            <div class="borders-radius-17 border p-4 mt-4 mt-md-0 px-lg-10 px-xl-4 px-wd-10 py-lg-9 py-xl-5 py-wd-9">
                      <div class="row">
                      <div class="mb-6 col-xl-6">
                       <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                            <th>Accounnt Details <a href=""><span class="fa fa-pen float-right"></span></a> </th>
                            </tr>
                            
                            </thead>
                            <tbody>
                            <tr>
                            <td> 
                            Name: {{strtoupper(auth()->user()->name)}}<br>
                            Email: {{auth()->user()->email}}<br>
                            Date Registered: {{auth()->user()->created_at->format('d/m/yy')}} <br><br>
                           <a href=""> <span style="color:#ffc107; font-size:13px; font-weight:bolder"> CHANGE PASSWORD</span></a>
                            
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                             <div class="mb-5 col-xl-6">
                             <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                                @if(count($addresses) >0)
                            @foreach($addresses as $addres)
                            <th>Billing Address <span class="btn btn-success btn-xs p-1">Default</span> <a href=""><span class="fa fa-pen float-right"></span></a> </th>
                            @endforeach
                            @endif
                            </tr>
                            
                            </thead>
                            <tbody>
                            <tr>
                            @if(count($addresses) >0)
                             @foreach($addresses as $address)
                            <td>
                            <p>Name: {{$address->receiver_name}}<br>
                            Address: {{$address->address}}<br>
                            City: {{$address->city . " , " . strtoupper($address->state)}}<br>
                            Phone: {{$address->receiver_phone}}<br>
                            </td>
                            @endforeach
                            @else
                            <th> Shipping Address</th>
                             </tr>
                            </thead>
                            <tbody>
                             <tr>
                           <td>   <h5> You dont have any Default shipping Address yet</h5>
                              
                             
                              <div class="mb-3">
                                   
                                <a href="{{route('users.add-address')}}"><button type="submit" class="btn btn-primary-dark-w px-5">Add New</button>
                                   
                                    </div>
                                    </td>
                                </tr>
                                @endif
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