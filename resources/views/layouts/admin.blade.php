<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> SolarSofar | Admin</title>
    <!-- Favicon -->
 <link rel="stylesheet" href="{{asset('/backend/vendors/dataTable/dataTables.min.css')}}" type="text/css">
  <link rel="icon" href="{{asset('/images/fav2.png')}}">
    <!-- Plugin styles -->
    <link rel="stylesheet" href="{{asset('/backend/vendors/bundle.css')}}" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <!-- Datepicker -->
    <link rel="stylesheet" href="{{asset('/backend/vendors/clockpicker/bootstrap-clockpicker.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/backend/vendors/datepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/backend/vendors/slick/slick.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/backend/vendors/slick/slick-theme.css')}}" type="text/css">
    <!-- Vmap -->
    <link rel="stylesheet" href="{{asset('/backend/vendors/vmap/jqvmap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/backend/css/app.min.css')}}" type="text/css">
</head>
<body>
    <!-- App styles  -->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<div class="header">
  <div>
        <ul class="navbar-nav">
            <!-- begin::navigation-toggler -->
            
            <li class="nav-item navigation-toggler mobile-toggler">
                <a href="#" class="nav-link" title="Show navigation">
                    <i data-feather="menu"> </i>
                </a>
            </li>
            <!-- end::navigation-toggler -->

        </ul>
        <form method="post" action="{{route('updateDollarRate')}}">
      @csrf
        <div  style="display: flex" class="p-2" >
        <input type="number" name="rate" class="form-control" value="{{ $dollar_exchange_rate?->rate }}"> <input type="submit" value="Update Exchange Rate" class="btn btn-primary" id="dollar_rate" >  </div>
    </form>
    </div>
   
    <div>
        <ul class="navbar-nav">
               <li class="nav-item dropdown">
                <a href="#" class="nav-link nav-link-notify" title="Notifications" data-toggle="dropdown">
                    <i data-feather="bell"></i> <span style="color:red"> {{count($unread_notify)}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-4 text-center d-flex justify-content-between"
                         data-backround-image="assets/media/image/image1.jpg">
                        <h6 class="mb-0">Notifications</h6>
                        <small class="font-size-11 opacity-7">{{count($unread_notify)}} unread notifications</small>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                        @if(count($unread_notify) > 0)
                         @foreach ($unread_notify as $noti )
                            <li>
                                <a href="{{route('update.admin-notify', encrypt($noti->id))}}" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-success-bright text-success rounded-circle">
                                                    <i class="ti-bell"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            {{$noti->message}}
                                            <i title="Mark as read" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-circle-o font-size-11"></i>
                                        </p>
                                        <span class="text-muted small">{{$noti->created_at->DiffForHumans()}}</span>
                                    </div>
                                 
                                </a>
                            </li>
                          @endforeach
                          @endif
                            <li class="text-divider small pb-2 pl-3 pt-3">
                                <span>Old notifications</span>
                            </li>
                            @if(count($read_notify) > 0)
                             @foreach ($read_notify as $notif )
                            <li>
                                <a href="#" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-warning-bright text-warning rounded-circle">
                                                    <i class="ti-bell"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                           {{$notif->message}}
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <span class="text-muted small">{{$notif->created_at->DiffForHumans()}}</span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                            @endif
                           @if(count($read_notify) > 0)
                              <a href="#" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-warning-bright text-warning rounded-circle">
                                                    <i class="ti-trash"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <form action="{{ route('admin.notify.clear') }}" method="POST">
                                    @csrf
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                          <button style="border:none">
                                           Clear Notifications
                                           </button>
                                            <i title="Clear all" data-toggle="tooltip"
                                               class="fa fa-trash font-size-11"></i>
                                        </p>
                                        <span class="text-muted small"></span>
                                    </div>
                                    </form>
                                </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                 
                </div>
            </li>
            <!-- end::header notification dropdown -->
            <!-- begin::user menu -->
            <li class="nav-item dropdown">
                <a href="{{route('admin.profile')}}" class="nav-link" title="User menu" >
                    <i data-feather="settings"></i>
                </a>
            </li>
            <!-- end::user menu -->
        </ul>
        <!-- begin::mobile header toggler -->
        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item header-toggler">
                <a href="#" class="nav-link">
                    <i data-feather="arrow-down"></i>
                </a>
            </li>
        </ul>
        <!-- end::mobile header toggler -->
    </div>
</div>
<div id="main">
    <!-- begin::navigation -->
    <div class="navigation">
        <div class="navigation-menu-tab">
          <div>
                <div class="navigation-menu-tab-header" data-toggle="tooltip" title="Admin" data-placement="right">
                    <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                        <figure class="avatar avatar-sm">
                                 <img src="{{asset('/images/fav.png')}}" height="10px" width="10px">
                        </figure>
                    </a>
                </div>
            </div>
               <div class="flex-grow-1">
               <ul>
                    <li>
                        <a class="active" href="" data-toggle="tooltip" data-placement="right" title="Dashboard"
                           data-nav-target="#dashboards">
                            <i data-feather="bar-chart-2"></i>
                        </a>
                    </li>
                    </ul>
            </div>
              <div>
                <ul>
                    <li>
                        <a href="{{route('admin.profile')}}" data-toggle="tooltip" data-placement="right" title="Settings">
                            <i data-feather="settings"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit()" data-placement="right" title="Logout">
                            <i data-feather="log-out"></i>
                        </a>
                         <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form> 
                    </li>
                </ul>
            </div>
        </div>

        <!-- begin::navigation menu -->
        <div class="navigation-menu-body">
            <!-- begin::navigation-logo -->
            <div>
                <div id="navigation-logo">
                    <a href="{{route('admin.index')}}">
                        <img class="logo" src="{{asset('/images/logo.png')}}" height="auto" width="120px">
                        <img class="logo-light" src="{{asset('/images/logo.png')}}" height="auto" width="120px" alt="light logo">
                    </a>
                </div>
            </div>
            <!-- end::navigation-logo -->
            <div class="navigation-menu-group">
                <div class="open" id="dashboards">
                    <ul>
                     <li class="navigation-divider">Dashboard</li>
                     <li>
                        <a  href="" data-toggle="tooltip" data-placement="right" title="View and Manage Products"
                       data-nav-target="#dashboards">
                        <i data-feather="home"></i>&nbsp;Menu</a>
                        <ul>
                            <li><a href="{{route('admin.addMenu')}}">Add Menu</a></li>
                            <li><a href="{{route('admin.MenuIndex')}}">Manage Menu</a></li>
                        </ul>
                    </li>
                    <li>
                        <a  href="" data-toggle="tooltip" data-placement="right" title="View and Manage Products"
                       data-nav-target="#dashboards">
                        <i data-feather="package"></i>&nbsp;Slider</a>
                        <ul>
                            <li><a href="{{route('slider.create')}}">Add Slider</a></li>
                            <li><a href="{{route('slider.index')}}">Manage Slider</a></li>
                        </ul>
                    </li>
                           <li>
                           <a  href="" data-toggle="tooltip" data-placement="right" title="View and Manage Category"
                           data-nav-target="#dashboards">
                            <i data-feather="server"></i> &nbsp; Category
                        </a>
                            <ul>
                                <li><a  href="{{route('category.create')}}">Add Category</a></li>
                                <li><a href="{{route('category.index')}}">Manage Category</a></li>
                            </ul>
                        </li>
                        <li>
                            <a  href="" data-toggle="tooltip" data-placement="right" title="View and Manage Products"
                           data-nav-target="#dashboards">
                            <i data-feather="package"></i>&nbsp;Products</a>
                            <ul>
                                <li><a href="{{route('product.create')}}">Add Product</a></li>
                                <li><a href="{{route('product.index')}}">Manage Products</a></li>
                            </ul>
                        </li>
                        
                            <li>
                           <a  href="" data-toggle="tooltip" data-placement="right" title="View and Manage Sales"
                           data-nav-target="#dashboards">
                            <i data-feather="sliders"></i>&nbsp;Sales</a>
                            <ul>
                                <li><a href="{{route('admin.orders')}}">View Orders</a></li>
                                <li><a href="{{route('admin.transctions')}}">Manage Transactions</a></li>
                            </ul>
                        </li>
                      <li>
                        <a  href="" data-toggle="tooltip" data-placement="right" title="View and Manage Users"
                           data-nav-target="#dashboards">
                            <i data-feather="user"></i> &nbsp; Users</a>
                            <ul>
                                  <li><a href="{{route('admin.users')}}">Manage Users</a></li>
                            </ul>
                        </li>
                       <li>
                        <a  href="" data-toggle="tooltip" data-placement="right" title="Send Notifications to Users"
                           data-nav-target="#dashboards">
                            <i data-feather="bell"></i>&nbsp;Notifications</a>
                            <ul>
                                  <li><a href="{{route('admin.notify')}}">Send Notifications</a></li>
                            </ul>
                        </li>
                     <li>
                        <a  href="" data-toggle="tooltip" data-placement="right" title="View User's activities"
                           data-nav-target="#dashboards">
                            <i data-feather="server"></i>&nbsp; News</a>
                            <ul>
                                  <li><a href="{{route('admin.news.create')}}">Post News</a></li>
                                  <li><a href="{{route('admin.news.index')}}">Manage News</a></li>
                            </ul>
                        </li>
                        <li>
                            <a  href="" data-toggle="tooltip" data-placement="right" title="View User's activities"
                               data-nav-target="#dashboards">
                                <i data-feather="package"></i>&nbsp; Projects</a>
                                <ul>
                                      <li><a href="{{route('admin.project.create')}}">Add Projects</a></li>
                                      <li><a href="{{route('admin.project.index')}}">Manage Projects</a></li>
                                </ul>
                            </li>
                     <li>
                        <a  href="" data-toggle="tooltip" data-placement="right" title="View User's activities"
                           data-nav-target="#dashboards">
                            <i data-feather="trending-up"></i>&nbsp; Analytical</a>
                            <ul>
                                  <li><a href="{{route('admin.analytical')}}">Manage Analytical</a></li>
                            </ul>
                        </li>
                          
                    </ul>
                </div>
            </div>
        </div>
        <!-- end::navigation menu -->

    </div>
    
 <div class="main-content">
        <div class="page-header">
            <div class="container-fluid d-sm-flex justify-content-between">
                <h4>{{$bheading}}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.index')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{$breadcrumb}}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- end::page-header -->
    @yield('content')

     <footer>
            <div class="container-fluid">
                
                  
                </div>
            </div>
        </footer>
        <!-- end::footer -->

    </div>
    <!-- end::main-content -->

</div>
<!-- end::main -->

<!-- Plugin scripts -->
<script src="{{asset('/backend/vendors/bundle.js')}}"></script>
<!-- Chartjs -->
<script src="{{asset('/backend/vendors/charts/chartjs/chart.min.js')}}"></script>
<script src="{{asset('/backend/vendors/dataTable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/backend/vendors/dataTable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/backend/vendors/dataTable/dataTables.bootstrap4.min.js')}}"></script>
<!-- Apex chart -->
<script src="{{asset('/backend/vendors/charts/apex/apexcharts.min.js')}}"></script>
<script src="{{asset('/backend/vendors/irregular-data-series.js')}}"></script>
<script src="{{asset('/backend/vendors/charts/apex/apexcharts.min.js')}}"></script>

<!-- Circle progress -->
<script src="{{asset('/backend/vendors/circle-progress/circle-progress.min.js')}}"></script>
<!-- Peity -->
<script src="{{asset('/backend/vendors/charts/peity/jquery.peity.min.js')}}"></script>
<script src="{{asset('/backend/js/examples/charts/peity.js')}}"></script>

<script src="{{asset('/backend/vendors/prism/prism.js')}}"></script>
<script src="{{asset('/backend/js/examples/clockpicker.js')}}"></script>
<!-- Datepicker -->
<script src="{{asset('/backend/vendors/datepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/backend/vendors/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
<!-- Slick -->
<script src="{{asset('/backend/vendors/slick/slick.min.js')}}"></script>
<!-- Vamp -->
<script src="{{asset('/backend/vendors/vmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('/backend/vendors/vmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{asset('/backend/js/examples/vmap.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<!-- Dashboard scripts -->
<script src="{{asset('/backend/js/examples/dashboard.js')}}"></script>
<div class="colors"> <!-- To use theme colors with Javascript -->
    <div class="bg-primary"></div>
    <div class="bg-primary-bright"></div>
    <div class="bg-secondary"></div>
    <div class="bg-secondary-bright"></div>
    <div class="bg-info"></div>
    <div class="bg-info-bright"></div>
    <div class="bg-success"></div>
    <div class="bg-success-bright"></div>
    <div class="bg-danger"></div>
    <div class="bg-danger-bright"></div>
    <div class="bg-warning"></div>
    <div class="bg-warning-bright"></div>
</div>
<!-- App scripts -->
<script src="{{asset('/backend/js/app.min.js')}}"></script>
<script>
$(document).ready(function (){
    $('#myTable').DataTable();
});

$(document).ready(function() {
  $('#summernote').summernote();
  
});

 $('#summernote').summernote({
        tabsize: 2,
        height: 200
      });
</script>
@yield('script')


</body>
</html>