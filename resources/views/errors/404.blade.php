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
						<li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="">Home</a></li>
						<li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Errors</li>
					</ol>
				</nav>
			</div>
			<!-- End breadcrumb -->
		</div>
	</div>
		


		
            <div class="container">
                    <div class="row">
						<div style="" class="mb-7 mb-lg-0 p-3">
                            <div >
                                <div class="bg-gray-1 rounded-lg p-2">
                                        <div class=" mb-5">
                                            <p class=" mb-0 p-3 "><i class="fa fa-check"></i> This page cannot be found on this server</p>
											<a href="{{route('index')}}" class="btn btn-primary btn-sm p-2"> Return Home</a>
									
                                        </div>
                                </div>
                            </div>
                        </div>
			</div>
		</main>

@endsection