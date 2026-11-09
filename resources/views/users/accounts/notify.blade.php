@extends('layouts.app')
@section('content')
<main id="main" class="account dashboard">
		<section class="header-page">
			<div class="container">
				<div class="row">
					<div class="col-sm-3 hidden-xs">
						<h1 class="mh-title">My Account</h1>
					</div>
					<div class="breadcrumb-w col-sm-9">
						<ul class="breadcrumb">
							<li>
								<a href="{{route('index')}}">Home</a>
							</li>
							<li>
								<span>Dashboard</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<section class="account-content parten-bg">
			<div class="container">
				<!--Account top banner -->
				
				<div class="row">
					<div class="col-md-12 cart-banner-top hidden-xs">
					
					</div>
				</div>
				<!--Account top banner : End-->
				<div class="row acc-order">
					<!--Account Sidebar: End-->
						@include('includes.sidebar')
						<!--Account Sidebar: End-->
					<!--Account main content : Begin -->
				<section class="account-main col-md-9 col-sm-8 col-xs-12">
						<h3 class="acc-title lg">Notifications</h3>
						<div class="row db-content">
							<div class="db-hello col-xs-12">
								<div class="pad-1015">
									
								</div>
							</div>
							<div class="db-info col-xs-12">
								<div class="row">
								@if(count($notify) > 0)
								@foreach ($notify as $notif )
									<div class="col-md-6 col-sm-6 db-contact">
										<h4 class="acc-title h-icon">
											{{$notif->title}}   <span style="float:right; font-size:12px"> {{$notif->created_at->DiffForHumans()}} <a href="{{route('notify.delete', encrypt($notif->id))}}" class="btn-danger btn-sm" title="Delete Notification" style="color:#fff"> x </a></span> 
											<a href="#" class="acc-edit" title="edit information">
												
											</a>
										</h4>
										<div class="acc-info-content pad-1015" >
											<span class="name word-wrap">{!!$notif->message!!}</span>
										</div>
									</div>
									@endforeach
									@else
									<div class="col-md-6 col-sm-6 db-contact">
									You don't have notifications yet
									</div>
									@endif
								</div>
							</div>
							
						
						</div>
					</section>


					<!-- Cart main content : End -->
				</div>
				
			</div>
		</section>
	</main>
	
	@endsection