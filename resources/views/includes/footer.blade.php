   <footer>
            <div class="pt-8 pb-4 bg-gray-13">
                <div class="container mt-1">
                    <div class="row">
                        <div class="col-lg-4 ">
                            <div class="mb-4">
                                <a href="#" class="d-inline-block">
                                   <img src="{{asset('/images/logo.png')}}" width="100px">
                                </a>
                            </div>
                            <div class="mb-4">
                                <div class="row no-gutters">
                                    <div class="col-auto">
                                        <i class="ec ec-support text-primary font-size-20"></i>
                                    </div>
                                    <div class="col pl-3">
                                        <div class="font-size-13 font-weight-light">Got questions? Call us 24/7!</div>
                                     (+234) 0813-532-4241,  
                                   
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="mb-1 font-weight-bold">Contact info</h6>
                                <address class="">
                                     Block C12 shop 02 & 09, Arena Shopping Complex, Bolade-oshodi, Lagos
                                     
                                </address>
                            </div>
                            <div class="my-4 my-md-3">
                                <ul class="list-inline mb-0 opacity-7">
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-facebook-f btn-icon__inner"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-google btn-icon__inner"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-twitter btn-icon__inner"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-0">
                                        <a class="btn font-size-20 btn-icon btn-soft-dark btn-bg-transparent rounded-circle" href="#">
                                            <span class="fab fa-github btn-icon__inner"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-4 col-md mb-3 mb-md-0">
                                    <h6 class="mb-3 font-weight-bold">Categories</h6>
                                    <!-- List Group -->
                                    <ul class="list-group list-group-flush list-group-borderless mb-0 list-group-transparent">
                                        @foreach ($menu_categories as $mnu )
                                        <li><a class="list-group-item list-group-item-action" href="{{route('user.category', encrypt($mnu->id))}}">{{$mnu->name}}</a></li>
                                        @endforeach
                                    </ul>
                                    <!-- End List Group -->
                                </div>

                                <div class="col-4 col-md mb-3 mb-md-0">
                                    <h6 class="mb-3 font-weight-bold">Links</h6>
                                    <!-- List Group -->
                                    <ul class="list-group list-group-flush list-group-borderless mb-0 list-group-transparent">
                                        @foreach ($menu as $ss )
                                        <li><a class="list-group-item list-group-item-action" href="{{route('pages',$ss->slug)}}">{{$ss->name}}</a></li>
                                        @endforeach
         
                                    </ul>
                                    <!-- End List Group -->
                                </div>

                                <div class="col-4 col-md mb-3 mb-md-0">
                                    <h6 class="mb-3 font-weight-bold">Contact Us</h6>
                                    <!-- List Group -->
                                    <div class="font-size-13 font-weight-bold">Got questions? Call us 24/7!</div>
                                    <div class="font-size-13 font-weight-light">Phone Number</div>
                                        (+234) 0813-532-4241, 
                                    <!-- End List Group -->
                                    <div class="font-size-13 font-weight-light">Email Address</div>
                                       support@sofarsolar.ng
                                    <!-- End List Group -->
                                    <h6 class="mb-1 font-weight-light">Physical Address</h6>
                                    <address class="">
                                          Block C12 shop 02 & 09, Arena Shopping Complex, Bolade-oshodi, Lagos
                                    </address>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-14 py-2">
                <div class="container">
                    <div class="flex-center-between d-block d-md-flex">
                        <div class="mb-3 mb-md-0">© <a href="#" class="font-weight-bold text-gray-90">Sofarsolar.ng</a> - All rights Reserved</div>
                        
                    </div>
                </div>
            </div>
            <!-- End Footer-copy-right -->
        </footer>