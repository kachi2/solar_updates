@extends('layouts.app')
@section('content')

<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Projects</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-wd">
                <div class="max-width-1100-wd">
                    
                    <article class="card mb-13 border-0">
                        <div class="js-slick-carousel u-slick overflow-hidden"  data-autoplay="true" data-speed="7000"
                            data-pagi-classes="js-pagination u-slick__pagination u-slick__pagination--long u-slick__pagination--hover mb-0">
                            @php $imag = json_decode($project->images) @endphp
                            @foreach ($imag as $proj )
                            <div class="js-slide">
                                <a href="" class="d-block"><img class="img-fluid" src="{{asset('/images/projects/'.$proj)}}" alt="{{asset('/images/projects/'.$proj)}}"></a>
                            </div>
                            @endforeach
                        </div>
                            <div class="card-body  pb-0 px-0">
                            <h4 class="mb-3"><a href="#">{{$project->title}}</a></h4>
                            <p>{!! $project->content !!}.</p>
                            </div>
                            
                    </article>
               
                </div>
            </div>

            <div class="col-xl-3 col-wd">
                <aside class="mb-7">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Latest Projects</h3>
                    </div>

                    @foreach ($recent as $post )
                    <article class="mb-4">
                        <div class="media">
                            <div class="width-75 height-75 mr-3">
                                <a href="{{route('projects.details', encrypt($post->id))}}" class="" style="color:green">    <img class="img-fluid object-fit-cover" src="{{asset('/images/projects/'.$post->image)}}" alt="Image Description"> </a>
                            </div>
                            <div class="media-body">
                                <h4 class="font-size-14 mb-1"><a href="{{route('projects.details', encrypt($post->id))}}" class="text-gray-39">{{$post->title}}</a></h4>
                            </div>
                           
                        </div>
                          <a href="{{route('projects.details', encrypt($post->id))}}" class="" style="color:green"> View Details</a>
                    </article>
                    @endforeach
                    
                </aside>
                
            </div>

        </div>
    </div>
</main>

@endsection