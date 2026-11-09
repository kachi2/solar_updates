@extends('layouts.admin')
@section('content')

 <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Update Profile</h6>
                            <div class="row">
                                <div class="col-md-6">
                                 
                                        <div class="form-group">
                                            <input type="text" name="name"  value="{{$admin->name}}" class="form-control @error('title') is-invalid @enderror" id="exampleInputEmail1"
                                                   aria-describedby="emailHelp" readOnly>
                                            @error('title')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                     
                                </div>
                                <div class="col-md-6">
                                 
                                        <div class="form-group">
                                            <input type="text" name="name"  value="{{$admin->email}}" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" readOnly
                                                   aria-describedby="emailHelp">
                                            
                                            @error('name')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                     
                                </div>
                                  <div class="col-md-6">
                                 
                                        <div class="form-group">
                                            <input type="text" name="phone"  value="{{$admin->phone}}" class="form-control @error('title') is-invalid @enderror" id="exampleInputEmail1"
                                                   aria-describedby="emailHelp" readOnly>
                                            @error('phone')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                     
                                </div>           
                            </div> 
                        </div>
                        <hr>
                        @if(Session::has('pass'))

    <span class="alert {{Session::get('alert')}}" role="alert"> {{Session::get('pass')}}</span>
@endif
                            <div class="card-body">
                            <h6 class="card-title">Update Password</h6>
                            <div class="row">
                                <div class="col-md-6">
                                 
                                        <div class="form-group">
                                            <input type="password" placeholder="Old Password" name="oldPassword"  class="form-control @error('oldPassword') is-invalid @enderror" id="exampleInputEmail1"
                                                   aria-describedby="emailHelp">
                                            @error('oldPassword')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                     
                                </div>
                                <div class="col-md-6">
                                 
                                        <div class="form-group">
                                            <input type="password" placeholder="New Password" name="password"  class="form-control @error('password') is-invalid @enderror" id="exampleInputEmail1"
                                                   aria-describedby="emailHelp">
                                            
                                            @error('password')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                     
                                </div>
                                
                                           
                            </div> 
                        </div>

                         
                    </div>


                         <div class="card">
                        <div class="card-body">
                        <div class="row">
                          <div class="col-md-4">
                          </div>
                          <div class="col-md-4">
                        <div class="p-5">
                             <button type="submit" class="btn btn-primary w-100 p-3">Update</button>
                           
                           </div>
                           </div>
                           </div>
                        </div>
                        </div>
                    </form>

    </div>
                        </div>
                    </>
                   

@endsection
@section('script')
<script>

let message = {!! json_encode(Session::get('message')) !!};
let msg = {!! json_encode(Session::get('alert')) !!};
//alert(msg);
toastr.options = {
        timeOut: 3000,
        progressBar: true,
        showMethod: "slideDown",
        hideMethod: "slideUp",
        showDuration: 200,
        hideDuration: 200
    };
if(message != null && msg == 'success'){
toastr.success(message);
}else if(message != null && msg == 'error'){
    toastr.error(message);
}
</script>
@endsection