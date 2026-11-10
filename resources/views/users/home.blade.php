@extends('layouts.app')
@section('content')


<main id="content" role="main">
	<!-- Slider Section -->
	<div class="mb-5">
		<div class="bg-img-hero" style="background-image: url('{{ $sliders->first()?->imageSized(1600) }}'); ">
			<div class="container min-height-500 overflow-hidden">
				<div class="js-slick-carousel u-slick" data-autoplay="false" data-speed="50" 
					data-pagi-classes="text-center position-absolute right-0 bottom-0 left-0 u-slick__pagination u-slick__pagination--long justify-content-start mb-3 mb-md-4 offset-xl-5 pl-2 pb-1">
					<div class="js-slide bg-img-hero-center">
						<div class="row min-height-500 py-1 py-md-0">
							<div class="col-xl-2 col-xl-2 col-1 mt-md-2">
								<h1 class="font-size-64 text-lh-57 font-weight-light"
									data-scs-animation-in="fadeInUp">
								</h1>
							</div>
							<div class="col-xl-8 col-8  d-flex align-items-center"
								data-scs-animation-delay="500"
								data-slides-show=""
								data-slides-scroll="7"
								data-scs-animation-in="fadeIn"
								data-responsive='[{
									"breakpoint": 992,
						"settings": {
							"slidesToShow": 5
						}
					}, {
						"breakpoint": 768,
						"settings": {
							"slidesToShow": 1
						}
					}, {
						"breakpoint": 554,
						"settings": {
							"slidesToShow": 1
						}
					}]'>
								<video   controls autoplay muted height="400px" width="100%">  
									<source  src="{{asset('/vide.mp4')}}" type="video/mp4">
									 </video>
							</div>
						</div>
					</div>
					
					@foreach ($sliders as $slider)
					<div class="js-slide bg-img-hero-center">
						<div class="row min-height-500 py-1 py-md-0">
							<div class="col-xl-2 col-xl-2 col-1 mt-md-2">
								<h1 class="font-size-64 text-lh-57 font-weight-light"
									data-scs-animation-in="fadeInUp">
								</h1>
							</div>
							<div class="col-xl-8 col-8  d-flex align-items-center"
								data-scs-animation-delay="500"
								data-slides-show=""
								data-slides-scroll="7"
								data-scs-animation-in="fadeIn"
								data-responsive='[{
									"breakpoint": 992,
						"settings": {
							"slidesToShow": 5
						}
					}, {
						"breakpoint": 768,
						"settings": {
							"slidesToShow": 1
						}
					}, {
						"breakpoint": 554,
						"settings": {
							"slidesToShow": 1
						}
					}]'>
								<img class="img-fluid" src="{{ $slider->imageSized(1600) }}" loading="lazy" alt="Image Description">
							</div>
						</div>
					</div>
					@endforeach
					
				</div>
			</div>
		</div>
	</div>
	<!-- End Slider Section -->
	<div class="container">
		<!-- Banner -->
		<div class="mb-5">
			<div class="row">
				@foreach ($categories as  $xs)
				<div class="col-md-6 mb-4 mb-xl-0 col-xl-3">
					<a href="{{route('user.category', $xs)}}" class="sofar-cat-card">
						<span class="sofar-cat-card__media">
							<img class="sofar-cat-card__img" src="{{ $xs->imageSized(240) }}" alt="{{ $xs->name }}" loading="lazy">
						</span>
						<span class="sofar-cat-card__body">
							<span class="sofar-cat-card__eyebrow">Sofar Solar</span>
							<span class="sofar-cat-card__title">{{ $xs->name }}</span>
							<span class="sofar-cat-card__cta">
								Shop now
								<span class="sofar-cat-card__arrow"><i class="ec ec-arrow-right-categproes"></i></span>
							</span>
						</span>
					</a>
				</div>
				@endforeach
				
			</div>
		</div>
		<!-- End Banner -->
		<!-- Deals-and-tabs -->
		<div class="mb-5">
			<div class="row">
				<!-- Tab Prodcut -->
				<div class="col">
					<!-- Features Section -->
					<div class="">
						<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab">
								<div class="row sofar-product-grid">
									@foreach ($products as $pro)
									<div class="col-6 col-md-4 col-xl-3">
										@include('includes.product-card', ['product' => $pro, 'quickAdd' => false])
									</div>
									@endforeach
								</div>
							</div>
							
						</div>
						<!-- End Tab Content -->
					</div>
					<!-- End Features Section -->
				</div>
				<!-- End Tab Prodcut -->
			</div>
		</div>
		<!-- End Deals-and-tabs -->
	@if(count($recents) > 0)
	<div class="mb-6">
		<div class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
			<h3 class="section-title mb-0 pb-2 font-size-22">Recently Viewed</h3>
		</div>
		<div class="row sofar-product-grid">
			@foreach($recents as $recent)
			<div class="col-6 col-md-4 col-xl-3">
				@include('includes.product-card', ['product' => $recent, 'quickAdd' => false])
			</div>
			@endforeach
		</div>
	</div>
		@endif
		
	</div>
</main>

@endsection
