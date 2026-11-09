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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">My Addresses</li>
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
                             
                               <div class="row">
                               @if(count($addresses)>0)
                                @foreach($addresses as $addres)
                                <div class="mb-6 col-xl-6">
                            <table class="table table-bordered" cellspacing="0">
                            <thead>
                            <tr>
                           @if($addres->is_default == 1)
                           <th>Billing Address <span class="badge badge-danger">Default</span> <a href=""><span class="fa fa-pen float-right"></span></a> </th>
                          @else
                            <th>Billing Address<a href="{{route('users.update-address', encrypt($addres->id))}}"> <span class="badge badge-info">Set Default</span><span class="fa fa-pen float-right"></span></a> </th>
                          @endif
                            </tr>
                            
                            </thead>
                            <tbody>
                            <tr>
                            
                            <td>
                          
                            <p>
                                Name: {{$addres->receiver_name}}<br>
                                Address: {{$addres->address}}<br>
                                City: {{$addres->city . " , " . strtoupper($addres->state)}}<br>
                                Phone: {{$addres->receiver_phone}}<br>
                            
                            </td>   
                            </tr>
                            </tbody>
                            </table>
                            </div>
                              @endforeach
                            
                            </div>
                            <div class="float-right">
                            {{$addresses->links()}}
                            </div>
                            @else
                            <div class="mb-3">
                                   <p>You currently do not have any saved address</p>
                                <a href=""><button type="submit" class="btn btn-primary-dark-w px-5">Add New Address</button>
                                   
                                    </div>
                                    @endif
                    <a href="{{route('users.add-address')}}"><button type="submit" class="btn btn-primary-dark-w px-5">Add New Address</button>
                            </div>
                            
                            <!-- End Tab Content -->
                        </div>
                </div>
            </div>
</main>


@endsection