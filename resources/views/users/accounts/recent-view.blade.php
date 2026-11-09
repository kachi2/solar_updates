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
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Recent Views</li>
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
                      <div class="mb-6">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
                                <h3 class="section-title mb-0 pb-2 font-size-22">Recently Viewed Items</h3>
                            </div>
                            <ul class="row list-unstyled products-group no-gutters">
                            @if(count($recent) > 0)
                             @foreach($recent as $pro)
                             <li class="col-6 col-wd-3 col-md-3 product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-xl-4 p-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a href="{{route('product-details', encrypt($pro->id))}}" class="font-size-12 text-gray-5">{{$pro->category->name}}</a></div>
                                            <h5 class="mb-1 product-item__title"><a href="{{route('product-details', encrypt($pro->id))}}" class="text-blue font-weight-bold">{{$pro->name}}</a></h5>
                                            <div class="mb-2">
                                                <a href="{{route('product-details', encrypt($pro->id))}}" class="d-block text-center"><img class="img-fluid" src="{{asset('/images/products/'.$pro->image)}}" alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">₦{{number_format($pro->price)}}</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="{{route('product-details', encrypt($pro->id))}}" class="btn-add-cart btn-primary transition-3d-hover"><i class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                                @endforeach
                                @else
                                <p> You don't have any recent viewed Items </p>
                                @endif
                                
                            </ul>
                        </div>
                     </div>
                            
                      
                            
                    
                          
                            <!-- End Tab Content -->
                        
                </div>
            </div>
</main>


@endsection