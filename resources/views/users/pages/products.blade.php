@extends('layouts.app')
@section('content')
        <!-- ========== END HEADER ========== -->
          <main id="content" role="main">
            <!-- breadcrumb -->
            <div class="bg-gray-13 bg-md-transparent">
                <div class="container">
                    <!-- breadcrumb -->
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="#">Home</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Products</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            <!-- End breadcrumb -->

            <div class="container">
                <div class="row mb-8">
                    <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
                        <div class="mb-6 border border-width-2 border-color-3 borders-radius-6">
                            <!-- List -->
                            <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar view-all">
                                <li><div class="dropdown-title">Browse Categories</div></li>
                               @foreach($menu_categories as $catt)
                                <li><a class="dropdown-item" href="{{route('user.category',encrypt($catt->id))}}">{{$catt->name}}</a></li>
                                @endforeach
                            </ul>
                            <!-- End List -->
                        </div>
                        <div class="mb-8">
                            <div class="border-bottom border-color-1 mb-5">
                                <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Latest Products</h3>
                            </div>
                            <ul class="list-unstyled">
                               @foreach($prod as $prods)
                                <li class="mb-4">
                                    <div class="row">
                                        <div class="col-auto">
                                            <a href="{{route('product-details', encrypt($prods->id))}}" class="d-block width-75">
                                                <img class="img-fluid" src="{{asset('images/products/'.$prods->image)}}" alt="Image Description">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <h3 class="text-lh-1dot2 font-size-14 mb-0"><a href="{{route('product-details', encrypt($prods->id))}}">{{$prods->name}}</a></h3>
                                            <div class="font-weight-bold">
                                                <del class="font-size-15 text-red d-block">₦{{number_format($prods->price)}}</del>
                                                <ins class="font-size-15  text-gray-9 text-decoration-none d-block">₦{{number_format($prods->sale_price)}}</ins>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-9 col-wd-9gdot5">
                        <!-- Recommended Products -->
                       
                        <!-- End Recommended Products -->
                        <!-- Shop-control-bar Title -->
                        <div class="flex-center-between mb-3">
                            <h3 class="font-size-25 mb-0">Products</h3>
                            <p class="font-size-14 text-gray-90 mb-0">Showing Results</p>
                        </div>
                        <!-- End shop-control-bar Title -->
                        <!-- Shop-control-bar -->
                        <div class="bg-gray-1 flex-center-between borders-radius-9 py-1">
                            <div class="d-xl-none">
                                <!-- Account Sidebar Toggle Button -->
                                <a id="sidebarNavToggler1" class="btn btn-sm py-1 font-weight-normal" href="javascript:;" role="button"
                                    aria-controls="sidebarContent1"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    data-unfold-event="click"
                                    data-unfold-hide-on-scroll="false"
                                    data-unfold-target="#sidebarContent1"
                                    data-unfold-type="css-animation"
                                    data-unfold-animation-in="fadeInLeft"
                                    data-unfold-animation-out="fadeOutLeft"
                                    data-unfold-duration="500">
                                    <i class="fas fa-sliders-h"></i> <span class="ml-1">Filters</span>
                                </a>
                                <!-- End Account Sidebar Toggle Button -->
                            </div>
                            <div class="px-3 d-none d-xl-block">
                                <ul class="nav nav-tab-shop" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-one-example1-tab" data-toggle="pill" href="#pills-one-example1" role="tab" aria-controls="pills-one-example1" aria-selected="false">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                <i class="fa fa-th"></i>
                                            </div>
                                        </a>
                                    </li>
                                  
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-three-example1-tab" data-toggle="pill" href="#pills-three-example1" role="tab" aria-controls="pills-three-example1" aria-selected="true">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                <i class="fa fa-list"></i>
                                            </div>
                                        </a>
                                    </li>
                               
                                </ul>
                            </div>
                            <div class="d-flex">
                                <form method="get">
                                    <!-- Select -->
                                    <select class="js-select selectpicker dropdown-select max-width-200 max-width-160-sm right-dropdown-0 px-2 px-xl-0"
                                        data-style="btn-sm bg-white font-weight-normal py-2 border text-gray-20 bg-lg-down-transparent border-lg-down-0">
                                        <option value="one" selected>Default sorting</option>
        
                                    </select>
                                    <!-- End Select -->
                                </form>
            
                            </div>
                        </div>
                        <!-- End Shop-control-bar -->
                        <!-- Shop Body -->
                        <!-- Tab Content -->
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab" data-target-group="groups">
                                <ul class="row list-unstyled products-group no-gutters">
                                    @forelse ($products as $product )
                                    <li class="col-6 col-md-3 product-item">
                                        <div class="product-item__outer h-100 w-100">
                                            <div class="product-item__inner px-xl-4 p-3">
                                                <div class="product-item__body pb-xl-2">
                                                   
                                                    <div class="mb-2"><a href="{{route('product-details', encrypt($product->id))}}" class="font-size-12 text-gray-5"> {{$product->category->name}}</a></div>
                                                    <hr>
                                                    <h5 class="mb-1 product-item__title"><a href="{{route('product-details', encrypt($product->id))}}" class="text-blue font-weight-bold">{{$product->name}}</a></h5>

            
                                                    <div class="mb-2">
                                                        <a href="{{route('product-details', encrypt($product->id))}}" class="d-block text-center"><img class="img-fluid" src="{{asset('/images/products/'.$product->image)}}" alt="Image Description"></a>
                                                    </div>
                                                    <div class="flex-center-between mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">₦{{number_format($product->sale_price)}}</div>
                                                         <del style="font-size:13px">₦{{number_format($product->price)}}</del>
                                                        </div>
                                                        <div class="d-none d-xl-block prodcut-add-cart">
                                                            <a href="{{route('product-details', encrypt($product->id))}}" class="btn-add-cart btn-primary transition-3d-hover"><i class="ec ec-add-to-cart"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                         <a href="{{route('product-details', encrypt($product->id))}}" class="text-gray-6 font-size-13"><i class="ec ec-favorites mr-1 font-size-15"></i>Add to Cart</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @empty
                                    <li >
                                        <div class="product-item__outer h-100 w-100">
                                            <div class="product-item__inner p-3">
                                                <div class="">
                                                   
                                                    <div class="mb-2"><a href="" class="font-size-12 text-gray-5"> No product found</a></div>
                                                    <hr>
                                                    <h5 class="mb-1 product-item__title"><a href="" class="text-blue font-weight-bold"></a></h5>

                                                </div>
                                                
                                            </div>
                                        </div>
                                    </li>
                                        
                                    @endforelse
                                </ul>
                            </div>
                         
                        </div>
                        <!-- End Tab Content -->
                        <!-- End Shop Body -->
                        <!-- Shop Pagination -->
                        <nav class="d-md-flex justify-content-between align-items-center border-top pt-3" aria-label="Page navigation example">
                            <div class="text-center text-md-left mb-3 mb-md-0"></div>
                            <ul class="pagination mb-0 pagination-shop justify-content-center justify-content-md-start">
                            {{$products->links()}}
                            </ul>
                        </nav>
                        <!-- End Shop Pagination -->
                    </div>
                </div>
                <!-- Brand Carousel -->
                <!-- End Brand Carousel -->
            </div>
        </main>
@endsection