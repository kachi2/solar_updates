
     @extends('layouts.app')
     @section('content')
     @section('title', $title)
      <main id="content" role="main">
            <!-- breadcrumb -->
            <div class="bg-gray-13 bg-md-transparent">
                <div class="container">
                    <!-- breadcrumb -->
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="#">Home</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="">{{$product->category->name}}</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">{{$title}}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
                        <div class="mb-8 border border-width-2 border-color-3 borders-radius-6">
                            <!-- List -->
                            <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar">
                                <li>
                                    <a class="dropdown-toggle dropdown-toggle-collapse dropdown-title" href="javascript:;"
									 role="button" 
									 data-toggle="collapse" 
									 aria-expanded="false" 
									  aria-controls="sidebarNav1Collapse"
									   data-target="#sidebarNav1Collapse">
                                        Show All Categories
                                    </a>

                                    <div id="sidebarNav1Collapse" class="collapse" data-parent="#sidebarNav">
                                        <ul id="sidebarNav1" class="list-unstyled dropdown-list">
                                            <!-- Menu List -->
                                            @foreach($category as $catt)
                                            <li><a class="dropdown-item" href="">{{$catt->name}}</a></li>
                                            <!-- End Menu List -->
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                              
                            </ul>
                            <!-- End List -->
                        </div>
                        <div class="mb-8">
                            <div class="border-bottom border-color-1 mb-5">
                                <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Latest Products</h3>
                            </div>
                            <ul class="list-unstyled">
                                @foreach($products as $prods)
                                <li class="mb-4">
                                    <div class="row">
                                        <div class="col-auto">
                                       
                                            <a href="{{route('product-details', $prods)}}" class="d-block width-75">
                                                <img class="img-fluid" src="{{asset('images/products/'.$prods->image)}}" alt="Image Description">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <h3 class="text-lh-1dot2 font-size-14 mb-0"><a href="{{route('product-details', $prods)}}">{{$prods->name}}</a></h3>
                                           
                                            <div class="font-weight-bold">
                                                <del class="font-size-15 text-gray-9 d-block">₦{{number_format($prods->price)}} </del>
                                                
                                                <ins class="font-size-15 text-red text-decoration-none d-block">₦{{number_format($prods->sale_price)}} / <span style="color:rgb(15, 15, 79); font-size:14px"> {{number_format($prods->exchange_rate)}}  USD </span></ins>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-xl-9 col-wd-9gdot5">
                        <!-- Single Product Body -->
                        <div class="mb-xl-14 mb-6">
                            <div class="row">
                              <div class="col-md-5 mb-4 mb-md-0">
                            <div id="sliderSyncingNav" class="js-slick-carousel u-slick mb-2"
                                data-infinite="true"
                                data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
                                data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
                                data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
                                data-nav-for="#sliderSyncingThumb">
								
                                 @foreach ((json_decode((string) $product->gallery) ?: []) as $key =>$imag)
                             @if($imag !=null)
                                <div class="js-slide">
                                    <img style="width: 300px; max-height:400px;" class="img-fluid" src="{{asset('images/products/'.$imag)}}" alt="Image Description">
                                </div>
                                 @endif
                                   @endforeach
                            </div>

                            <div id="sliderSyncingThumb" class="js-slick-carousel u-slick u-slick--slider-syncing u-slick--gutters-1 u-slick--transform-off"
                                data-infinite="true"
                                data-slides-show="5"
                                data-is-thumbs="true"
                                data-nav-for="#sliderSyncingNav">
                             @foreach ((json_decode((string) $product->gallery) ?: []) as $key =>$imag )
                             @if($imag !=null)
                                <div class="js-slide" style="cursor: pointer;">
                                    <img class="img-fluid" src="{{asset('images/products/'.$imag)}}"alt="Image Description">
                                </div>
                                @endif
                                   @endforeach
                            </div>
                        </div>
                                <div class="col-md-7 mb-md-6 mb-lg-0">
                                    <div class="mb-2">
                                        <div class="border-bottom mb-3 pb-md-1 pb-3">
                                            <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">{{$product->category->name}}</a>
                                            <h2 class="font-size-25 text-lh-1dot2">{{$title}}</h2>
                                            <div class="mb-2">
                                         <p>{{$product->heading}}</p>
                                             </div>
                                            <div class="d-md-flex align-items-center">
                                              Availability: <span class="text-green font-weight-bold"> &nbsp; In-Stock</span></div>
                                            </div>
                                        </div>
                                        <div class="flex-horizontal-center flex-wrap mb-4">
                                            {{-- <a href="#" class="text-gray-6 font-size-13 mr-2"><i class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            <a href="#" class="text-gray-6 font-size-13 ml-2"><i class="ec ec-compare mr-1 font-size-15"></i> Compare</a> --}}
                                        </div>
                                        <div class="mb-4">
                                            <div class="d-flex align-items-baseline">
                                              <ins class="font-size-36 text-decoration-none">₦{{number_format($product->sale_price)}} <span style="color:rgb(15, 15, 79); font-size:14px"> {{number_format($product->exchange_rate)}}  USD </span></ins>
                                        <del class="font-size-20 ml-2 text-gray-6">₦{{number_format($product->price)}}</del>
                                             </div>
                                        </div>
                                      
                                        <div class="d-md-flex align-items-end mb-3">
                                            <div class="max-width-150 mb-4 mb-md-0">
                                                <h6 class="font-size-14">Quantity</h6>
                                                <!-- Quantity -->
                                                 
                                                    <div class="border rounded-pill py-2 px-3 border-color-1">
                                                        <div class="js-quantity row align-items-center">
                                                            <div class="col">
                                                                <input class="counter js-result form-control h-auto border-0 rounded p-0 shadow-none" name="qty" id="qty2" type="number" value="1">
                                                            </div>
                                                          <div class="col-auto pr-1">
                                                    <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                        <small class="fas fa-minus btn-icon__inner decrement-btn"></small>
                                                    </a>
                                                    <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                        <small class="fas fa-plus btn-icon__inner increment-btn"></small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                                <!-- End Quantity -->
                                            </div>
                                            <div class="ml-md-3">
                                        <button  class="btn px-5 btn-primary transition-3d-hover" id="add2cart"><i class="ec ec-add-to-cart mr-2 font-size-20"></i> Add to Cart</button>
                                       
                                    </div>
                                    
                                </div>
                                <span class="badge-success p-2 border-radius-5" hidden id="alerts"> Item added to cart successfully</span>
                                    
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- End Single Product Body -->
                        <!-- Single Product Tab -->
                        <div style="height:50px"></div>
                        <div class="mb-8">
                            <div class="position-relative mx-md-6">
                                <ul class="nav nav-classic nav-tab nav-tab-lg justify-content-xl-center flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble border-0 pb-1 pb-xl-0 mb-n1 mb-xl-0" id="pills-tab-8" role="tablist">
                                    <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                        <a class="nav-link active" id="Jpills-one-example1-tab" data-toggle="pill" href="#Jpills-one-example1" role="tab" aria-controls="Jpills-one-example1" aria-selected="true">Description</a>
                                    </li>

                                    <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                        <a class="nav-link" id="Jpills-two-example1-tab" data-toggle="pill" href="#Jpills-two-example1" role="tab" aria-controls="Jpills-two-example1" aria-selected="true">Specification</a>
                                    </li>
                                   
                                    <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                        <a class="nav-link" id="Jpills-four-example1-tab" data-toggle="pill" href="#Jpills-four-example1" role="tab" aria-controls="Jpills-four-example1" aria-selected="false">Reviews</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Tab Content -->
                            <div class="borders-radius-17 border p-4 mt-4 mt-md-0 px-lg-10 px-xl-4 px-wd-10 py-lg-9 py-xl-5 py-wd-9">
                                <div class="tab-content" id="Jpills-tabContent">
                                    <div class="tab-pane fade active show" id="Jpills-one-example1" role="tabpanel" aria-labelledby="Jpills-one-example1-tab">
                                      
                                       <p>{!!$product->description!!}</p>
                                 
                                    </div>
                                    <div class="tab-pane fade" id="Jpills-two-example1" role="tabpanel" aria-labelledby="Jpills-two-example1-tab">
                                      
                                      @if(isset($product->specification))
                                    <iframe src="{{asset('images/pdf/'.$product->specification)}}" width="800px" height="500px"> </iframe>
                                    @endif
                                     </div>
                                   
                                    
                                    <div class="tab-pane fade" id="Jpills-four-example1" role="tabpanel" aria-labelledby="Jpills-four-example1-tab">
                                        <div class="row mb-8">
                                             <div class="col-md-6" style="border-right:1px solid #ee0">
                                                 <h5>Customers Review</h5>
                                                 <hr>
                                                 @if(count($rating)>0)
                                            @foreach($rating as $reviews)
                                        <div class="pb-4">
                                            <!-- Review Rating -->
                                            
                                            <div class="d-flex justify-content-between align-items-center text-secondary font-size-1 mb-2">
                                                <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                                  <?php $xx = 0?> 
                                                   @while($xx < $reviews->rated)
                                                    <small class="fas fa-star"></small>
                                                    <?php $xx++ ?>
                                                    @endwhile
                                                    @if($reviews->rated == 1)
                                                    <small class="far fa-star text-muted"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                    @elseif($reviews->rated == 2)
                                                    <small class="far fa-star text-muted"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                    @elseif($reviews->rated == 3)
                                                    <small class="far fa-star text-muted"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                     @elseif($reviews->rated == 4)
                                                    <small class="far fa-star text-muted"></small>
                                                    @else($reviews->rated == 5)
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                            <!-- End Review Rating -->

                                            <span class="text-gray-90">{{$reviews->description}}</span>

                                            <!-- Reviewer -->
                                            <div class="mb-2">
                                                
                                                <strong>{{$reviews->email}}</strong><br>
                                                 <strong>{{$reviews->name}}</strong>
                                                <span class="font-size-13 text-gray-23">- {{$reviews->created_at->format('d/m/yy')}}</span>
                                            </div>
                                            <!-- End Reviewer -->
                                        </div>
                                        @endforeach
                                        @else
                                        <p style="color:gray">There is no Review yet for this product, be the first to say a review</p>
                                        @endif
                                                <!-- End Ratings -->
                                            </div>
                                            <div class="col-md-6">
                                                <h3 class="font-size-18 mb-5">Add a review</h3>
                                                <!-- Form -->
                                                 <form action="{{ route('review.store', [$product->id]) }}" method="POST">
                                                 @csrf
                                                    <div class="row align-items-center mb-4">
                                                        <div class="col-md-4 col-lg-3">
                                                            <label for="rating" class="form-label mb-0">Rate</label>
                                                        </div>
                                                        <div class="col-md-8 col-lg-9">
                                                             <select name="rated" id="rating" class="custom-select focus-shadow-0">
                                                            <option value="5">★★★★★ (5/5)</option>
                                                            <option value="4">★★★★☆ (4/5)</option>
                                                            <option value="3">★★★☆☆ (3/5)</option>
                                                            <option value="2">★★☆☆☆ (2/5)</option>
                                                            <option value="1">★☆☆☆☆ (1/5)</option>
                                                          </select>
                                                        </div>
                                                    </div>
                                                    <div class="js-form-message form-group mb-3 row">
                                                        <div class="col-md-4 col-lg-3">
                                                            <label for="descriptionTextarea" class="form-label">Your Review</label>
                                                        </div>
                                                        <div class="col-md-8 col-lg-9">
                                                            <textarea class="form-control" name="description" rows="3" id="descriptionTextarea"
                                                            data-msg="Please enter your message."
                                                            data-error-class="u-has-error"
                                                            data-success-class="u-has-success"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="js-form-message form-group mb-3 row">
                                                        <div class="col-md-4 col-lg-3">
                                                            <label for="inputName"  class="form-label">Name <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-md-8 col-lg-9">
                                                            <input type="text" class="form-control" name="name" id="inputName" aria-label="" required
                                                            data-msg="Please enter your name."
                                                            data-error-class="u-has-error"
                                                            data-success-class="u-has-success">
                                                        </div>
                                                    </div>
                                                    <div class="js-form-message form-group mb-3 row">
                                                        <div class="col-md-4 col-lg-3">
                                                            <label for="emailAddress"  class="form-label">Email <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-md-8 col-lg-9">
                                                            <input type="email" class="form-control" name="email" id="emailAddress" aria-label="" required
                                                            data-msg="Please enter a valid email address."
                                                            data-error-class="u-has-error"
                                                            data-success-class="u-has-success">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="offset-md-4 offset-lg-3 col-auto">
                                                            <button type="submit" class="btn btn-primary-dark btn-wide transition-3d-hover">Add Review</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- End Form -->
                                            </div>
                                        </div>
                                    
                                        <!-- End Review -->
                                        <!-- Review -->
                               
                                        <!-- End Review -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Tab Content -->
                        </div>
                    </div>
                </div>
                 <div class="mb-6">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
                        <h3 class="section-title mb-0 pb-2 font-size-22">Recently Viewed</h3>
                    </div>
                    <div class="row sofar-product-grid">
                        @foreach($recent as $recents)
                        <div class="col-6 col-md-4 col-xl-3">
                            @include('includes.product-card', ['product' => $recents])
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-6">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
                        <h3 class="section-title mb-0 pb-2 font-size-22">You May also Like</h3>
                    </div>
                    <div class="row sofar-product-grid">
                        @foreach($related as $related_pro)
                        <div class="col-6 col-md-4 col-xl-3">
                            @include('includes.product-card', ['product' => $related_pro])
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
      @endsection

@section('scripts')
<script>
    $(function () {
        var $addButton = $('#add2cart');
        var $overlay = $('#cartAddedOverlay');
        var $quantity = $('#qty2');

        $('.increment-btn').on('click', function (event) {
            event.preventDefault();
            $quantity.val((Number($quantity.val()) || 0) + 1);
        });

        $('.decrement-btn').on('click', function (event) {
            event.preventDefault();
            $quantity.val(Math.max(1, (Number($quantity.val()) || 1) - 1));
        });

        function formatCurrency(amount) {
            return '₦' + Number(amount).toLocaleString('en-NG', {
                maximumFractionDigits: 2
            });
        }

        function closeBasket() {
            $overlay.prop('hidden', true).attr('aria-hidden', 'true');
        }

        $addButton.on('click', function (event) {
            event.preventDefault();

            var $button = $(this);
            var quantity = Number($('#qty2').val());

            if (!Number.isInteger(quantity) || quantity < 1) {
                $('#qty2').val(1).trigger('focus');
                return;
            }

            $button.prop('disabled', true);

            $.ajax({
                url: @json(route('carts.add', ['id' => $product->id])),
                type: 'GET',
                dataType: 'json',
                data: { qty: quantity }
            }).done(function (response) {
                $('.cartReload').text(response.cartQuantity);
                $('.cartReloads').text(formatCurrency(response.subtotal));

                $('#cartAddedName').text(response.item.name);
                $('#cartAddedItemMeta').text(
                    formatCurrency(response.item.price) + ' · Qty: ' + response.item.quantity
                );
                $('#cartAddedItemTotal').text(formatCurrency(response.item.total));
                $('#cartAddedSubtotal, #cartAddedTotal').text(formatCurrency(response.subtotal));

                var imageBase = @json(asset('images/products'));
                $('#cartAddedImage')
                    .attr('src', imageBase + '/' + encodeURIComponent(response.item.image))
                    .attr('alt', response.item.name);

                $overlay.prop('hidden', false).attr('aria-hidden', 'false');
                $('#cartAddedClose').trigger('focus');
            }).fail(function () {
                window.alert('We could not add this product to your cart. Please try again.');
            }).always(function () {
                $button.prop('disabled', false);
            });
        });

        $('#cartAddedClose').on('click', closeBasket);
        $overlay.on('click', function (event) {
            if (event.target === this) {
                closeBasket();
            }
        });

        $(document).on('keydown', function (event) {
            if (event.key === 'Escape' && !$overlay.prop('hidden')) {
                closeBasket();
            }
        });
    });
</script>
@endsection