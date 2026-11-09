<aside id="sidebarHeader1" class="u-sidebar u-sidebar--left" aria-labelledby="sidebarHeaderInvoker">
	<div class="u-sidebar__scroller">
		<div class="u-sidebar__container">
			<div class="u-header-sidebar__footer-offset">
				<!-- Toggle Button -->
				<div class="position-absolute top-0 right-0 z-index-2 pt-4 pr-4 bg-white">
					<button type="button" class="close ml-auto"
						aria-controls="sidebarHeader"
						aria-haspopup="true"
						aria-expanded="false"
						data-unfold-event="click"
						data-unfold-hide-on-scroll="false"
						data-unfold-target="#sidebarHeader1"
						data-unfold-type="css-animation"
						data-unfold-animation-in="fadeInLeft"
						data-unfold-animation-out="fadeOutLeft"
						data-unfold-duration="500">
						<span aria-hidden="true"><i class="ec ec-close-remove text-gray-90 font-size-20"></i></span>
					</button>
				</div>
				<!-- End Toggle Button -->

				<!-- Content -->
				<div class="js-scrollbar u-sidebar__body">
					<div id="headerSidebarContent" class="u-sidebar__content u-header-sidebar__content">
						<!-- Logo -->
						<a class="navbar-brand u-header__navbar-brand u-header__navbar-brand-center mb-3" href="{{asset('index')}}" aria-label="">
						   <img src="{{asset('/images/logo.png')}}">
						</a>
						<!-- End Logo -->

						<!-- List -->
						<em>	<span class="font-weight-bold"> Categories</span></em>
						<ul id="headerSidebarList" class="u-header-collapse__nav">
							<!-- Value of the Day -->
							@foreach($menu_categories as $cat )
							<!-- Cameras, Audio & Video -->
							<li class="u-has-submenu u-header-collapse__submenu">
								<a class="u-header-collapse__nav-link u-header-collapse__nav-pointer" href="{{route('user.category', encrypt($cat->id))}}" data-target="#headerSidebarCamerasCollapse{{$cat->id}}" 
								role="button" data-toggle="collapse" aria-expanded="false" aria-controls="headerSidebarCamerasCollapse">
									{{$cat->name}}
								</a>

								<div id="headerSidebarCamerasCollapse{{$cat->id}}" class="collapse" data-parent="#headerSidebarContent">
									<ul class="u-header-collapse__nav-list">
										@foreach ($cat->subCategories as $cats)
										<li><span class="u-header-sidebar__sub-menu-title"> <a href="{{route('user.category', encrypt($cat->id))}}" style="color:#000">{{$cats->name}} </a></span></li>
										@endforeach
										</ul>
								</div>
							</li>
							@endforeach
						</ul>
						<!-- End List -->
						<hr style="color:#000">
					<em>	<span class="font-weight-bold"> Menu</span></em>
						
						<ul id="headerSidebarList" class="u-header-collapse__nav">
							<!-- Value of the Day -->
							@foreach ($menu as $mn )
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('pages',$mn->slug)}}"> {{$mn->name}}</a>
							</li>
							@endforeach
							
						</ul>

						<hr style="color:#000">
						<em>	<span class="font-weight-bold">Personal Data</span></em>
						<ul id="headerSidebarList" class="u-header-collapse__nav">
							<!-- Value of the Day -->
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('users.account')}}">My Account</a>
							</li>
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('user.transactions')}}">My Transactions</a>
							</li>
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('users.orders')}}">My Orders</a>
							</li>
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('users.recentViews')}}">Recent Viewed Items</a>
							</li>
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('users.address')}}">Address Book</a>
							</li>
							<li class="">
								<a class="u-header-collapse__nav-link " href="{{route('user.account.details')}}">Update Details</a>
							</li>

						</ul>
					</div>
					
				</div>
				<!-- End Content -->
				
			</div>
			<!-- Footer -->

			
			<footer id="SVGwaveWithDots" class="svg-preloader u-header-sidebar__footer col-xl-none col-md-none">
				<ul class="list-inline mb-0">
					<li class="list-inline-item pr-3">
						<a class="u-header-sidebar__footer-link text-gray-90" href="{{route('users.account')}}">Settings</a>
					</li>
					<li class="list-inline-item pr-3">
						<a class="u-header-sidebar__footer-link text-gray-90" href="#">Privacy</a>
					</li>
					<li><a class="list-inline-item" href="{{ route('logout') }}"
						onclick="event.preventDefault();
									  document.getElementById('logout-form').submit();">
						 {{ __('Logout') }}
					 </a>
				 </li>
					 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						 @csrf
					 </form> 
				</ul>

				<!-- SVG Background Shape -->
				<div class="position-absolute right-0 pt-4 bottom-0 left-0 z-index-n1" style="bottom:opx">
					<img class="js-svg-injector" src="{{asset('/frontend/svg/components/wave-bottom-with-dots.svg')}}" alt="Image Description"
					data-parent="#SVGwaveWithDots">
				</div>
				<!-- End SVG Background Shape -->
			</footer>
			<!-- End Footer -->
		</div>
	</div>
</aside>