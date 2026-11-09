@extends('layouts.app')
@section('content')
<main id="content" role="main">
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">News</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-wd">
                <div class="min-width-1100-wd">
                    @foreach ($news as $blog )
                    <article class="card mb-13 border-0">
                        <div class="row">
                            <div class="col-lg-4 mb-5 mb-lg-0">
                                <a href="#" class="d-block"><img class="img-fluid min-height-250 object-fit-cover" src="{{asset('/images/news/'.$blog->image)}}" alt="{{asset('/images/news/'.$blog->image)}}"></a>
                            </div>
                            <div class="col-lg-8">
                                <div class="card-body p-0">
                                    <h4 class="mb-3"><a href="{{route('news.details',encrypt($blog->id))}}">{{$blog->title}}</a></h4>
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="list-group list-group-horizontal flex-wrap list-group-borderless align-items-center mx-n0dot5">
                                            <a href="{{route('news.details',encrypt($blog->id))}}" class="mx-0dot5 text-gray-5">posted on: {{$blog->created_at->format('d/m/y')}}</a>
                                        </div>
                                    </div>
                                    <p>{!! substr($blog->content,0,500)!!}</p>
                                    <div class="flex-horizontal-center">
                                        <a href="{{route('news.details',encrypt($blog->id))}}" class="btn btn-soft-secondary-w mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5">Read More</a>
                                                
                                     </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            <div class="col-xl-3 col-wd">
                <aside class="mb-7">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Recent Posts</h3>
                    </div>
                    @foreach ($recent as $post )
                    <article class="mb-4">
                        <div class="media">
                            <div class="width-75 height-75 mr-3">
                                <img class="img-fluid object-fit-cover" src="{{asset('/images/news/'.$post->image)}}" alt="Image Description">
                            </div>
                            <div class="media-body">
                                <h4 class="font-size-14 mb-1"><a href="../blog/single-blog-post.html" class="text-gray-39">{{$post->title}}</a></h4>
                                <span class="text-gray-5">{{$post->created_at->format('d/m/y')}}</span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                    
                </aside>
                
            </div>
        </div>
    </div>
</main>
@endsection