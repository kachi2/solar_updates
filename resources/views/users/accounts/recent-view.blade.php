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
                            <div class="row sofar-product-grid">
                            @forelse($recent as $pro)
                                <div class="col-6 col-lg-4">
                                    @include('includes.product-card', ['product' => $pro])
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="sofar-product-empty">You don't have any recently viewed items</p>
                                </div>
                            @endforelse
                            </div>
                        </div>
                     </div>
                            
                      
                            
                    
                          
                            <!-- End Tab Content -->
                        
                </div>
            </div>
</main>


@endsection