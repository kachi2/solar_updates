@extends('layouts.app')
@if (isset($currentCategory))
    @section('title', $currentCategory->name)
    @section('description', 'Shop '.$currentCategory->name.' at Sofar Solar Nigeria. Genuine products, great prices, and delivery across Nigeria. Order online.')
    @section('image', (string) $currentCategory->image_url)
@elseif (isset($search))
    @section('title', 'Search results for “'.$search.'”')
@else
    @section('title', 'Shop all products')
    @section('description', 'Shop all Sofar solar inverters, lithium batteries, solar panels and accessories. Genuine products with delivery across Nigeria.')
@endif
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
                        <div class="mb-6">
                            @include('includes.category-card', [
                                'activeCategoryId' => isset($currentCategory) ? $currentCategory->id : null,
                                'allActive' => ! isset($currentCategory) && ! isset($search),
                            ])
                        </div>
                        <section class="sofar-side-card" aria-labelledby="latestProductsTitle">
                            <h2 class="sofar-side-card__title" id="latestProductsTitle">Latest products</h2>
                            <ul class="sofar-mini-products">
                                @foreach ($prod as $prods)
                                    @php
                                        $miniOld = (float) $prods->price;
                                        $miniNow = (float) $prods->sale_price;
                                    @endphp
                                    <li>
                                        <a href="{{ route('product-details', $prods) }}" class="sofar-mini-product">
                                            <span class="sofar-mini-product__thumb">
                                                <img src="{{ $prods->imageSized(480) }}" alt="" loading="lazy">
                                            </span>
                                            <span class="sofar-mini-product__info">
                                                <span class="sofar-mini-product__name">{{ $prods->name }}</span>
                                                <span class="sofar-mini-product__price">
                                                    ₦{{ number_format($miniNow) }}
                                                    @if ($miniOld > $miniNow)
                                                        <del>₦{{ number_format($miniOld) }}</del>
                                                    @endif
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
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
                                <div class="row sofar-product-grid">
                                    @forelse ($products as $product)
                                    <div class="col-6 col-lg-4">
                                        @include('includes.product-card', ['product' => $product])
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <p class="sofar-product-empty">No product found</p>
                                    </div>
                                    @endforelse
                                </div>
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