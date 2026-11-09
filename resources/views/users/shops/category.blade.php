@extends('layouts.app')
@section('content')
<main id="main category">
		<section class="header-page">
			<div class="container">
				<div class="row">
					<div class="col-sm-3 hidden-xs">
						<h1 class="mh-title">@if(isset($products['0']->category->name)) {{$products['0']->category->name}} @else Category  @endif</h1>
					</div>
					<div class="breadcrumb-w col-sm-9"> 
						<ul class="breadcrumb">
							<li>
								<a href="{{route('index')}}">Home</a>
							</li>
							<li>
								<span>@if(isset($products['0']->category->name)) {{$products['0']->category->name}} @else All Designs @endif</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		
		<section class="category-w parten-bg">
			<div class="container">
				<div class="row">
					<aside id="sidebar_cate" class="col-sm-3 hidden-xs">
						<h3 class="sidebar-title">All Designs</h3>
						<ul id="cate_list" class="cate_list">
                        @foreach( $menu_categories as $cat )
                       	<li class="level1 nav-1-1 first item">
										<a href="{{route('user.category', encrypt($cat->id))}}" title="{{$cat->name}}">
										  {{$cat->name}}
											<span class="count-item">{{count($cat->products)}}</span>
										</a>
							</li>
                             @endforeach
                              
                        </li>
                      
						</ul>
					
					</aside>
					<!--Category product grid : Begin -->
					<section class="category grid col-sm-9 col-xs-12">
						<div class="row">
							<div class="col-xs-12 category-image visible-md visible-lg">
								<a href="#" title="category image">
									<img src="{{asset('/images/category/'.$products[0]->category->image)}}" alt="Business card">
								</a>
							</div>
							<div class="col-xs-12 category-image visible-sm">
								<a href="#" title="category image">
									<img src="{{asset('/images/category/'.$products[0]->category->image)}}" alt="Business card">
								</a>
							</div>
							<div class="col-xs-12 category-image visible-xs">
								<a href="#" title="category image">
									<img src="{{asset('/images/category/'.$products[0]->category->image)}}" alt="Business card">
								</a>
							</div>
						</div>
						
						<div class="row products-grid category-product">
							<ul>
                            @if(count($products) > 0)
                            @foreach ($products as $product )
                             @php
                            $name = preg_replace("[\(|\)|/|\"|\"]", '-', $product->name);  
                            @endphp
								<li class="pro-item col-md-4 col-sm-6 col-xs-12">
									<div class="product-image-action">
										<img src="{{asset('/images/products/'.$product->image)}}" alt="{{$product->name}}">
									</div>
									<div class="product-info">
										<a href="{{url('/products/details',$name.'_'.encrypt($product->id))}}" titl e="product" class="product-name">{{$product->name}}</a>
										<div class="price-box">
										 <span class="normal-price">{{number_format($product->sale_price)}}/ <span style="color:rgb(15, 15, 79); font-size:14px"> {{number_format($prods->exchange_rate)}}  USD </span></span>
										</div>	 <br>
                                        	<a href="{{url('/products/details',$name.'_'.encrypt($product->id))}}" class="btn-readmore order-now">Order now</a>
						
									</div>
								</li>
                                   @endforeach
								  
							</ul>			
						</div>
 `					<center><div> {{$products->links()}}</div></center>
						@else
                            <div class="pro-item col-md-4 col-sm-6 col-xs-12">
									<div class="product-info">
										No Products for this Category	
									</div>
								</div>
                            @endif
						<div class="bottom-toolbar row">
							<div class="col-md-12 pager">
							</div>
						</div>
					</section><!-- Category product grid : End -->
				</div>
			
			</div>
		</section>
	</main>
    
@endsection