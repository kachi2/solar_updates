<div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
    <div class="mb-6 border border-width-2 border-color-3 borders-radius-6">
        <!-- List -->
        <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar view-all">
          <li><div class="dropdown-title  mb-0" style="background: #103178; color:#fff"><strong>Account Information</strong></div></li>
          <li><a class="dropdown-item " style="padding:15px; text-weight:bold" href="{{route('users.account')}}"> <i class="fa fa-user"> </i> &nbsp;  My Account </a></li>
            <li><a class="dropdown-item " style="padding:15px" href="{{route('users.orders')}}"> <i class=" fa fa-bars"> </i> &nbsp; My Orders</a></li>
            <li><a class="dropdown-item " style="padding:15px" href="{{route('users.address')}}"> <i class="fa fa-book"> </i> &nbsp;  My Address Book</a></li>
             <li><a class="dropdown-item " style="padding:15px"aria-hidden="true"href="{{route('users.recentViews')}}"><i class="fa fa-clock"> </i> &nbsp; Recently Viewed</a></li>
            <li><a class="dropdown-item " style="padding:15px"aria-hidden="true"href="{{route('user.transactions')}}"> <i class="fa fa-wallet"> </i>&nbsp; Card Payments</a></li>
            <li><a class="dropdown-item " style="padding:15px" aria-hidden="true" href="{{route('user.account.details')}}"><i class="fa fa-retweet"> </i>&nbsp; Update Account</a></li>
          
        </ul>
        <!-- End List -->
    </div>
</div>