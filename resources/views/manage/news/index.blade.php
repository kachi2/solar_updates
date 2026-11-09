@extends('layouts.admin')
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                     <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">News</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                       
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="table-responsive">
                                        <table id="myTable" class="table table-striped table-bordered">
                                           <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Image</th>
                                                 <th>Created At</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                  
                                        @if(count($news) > 0)
                                        @foreach ($news as  $sp)
                                            <tr>
                                                <td>
                                                    <a href="#">{{$sp->title}}</a>
                                                </td>
                                                <td>
                                                    <img src="{{asset('images/news/'.$sp->image)}}" width="100px" height="100px"> 
                                                </td>
                                                     
                                                  <td>
                                                    <a href="#">{{$sp->created_at->format('d/M/y')}}</a>
                                                </td>
                                               
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                 <a href="{{route('admin.edit.news', encrypt($sp->id))}}" class="dropdown-item">Edit News</a>
                                                            @if($sp->status != 1) 
                                                            <form method="post" action="{{route('news.status', encrypt($sp->id))}}" id="form1"> 
                                                            @csrf    
                                                             <input type="hidden" name="status" value="1">
                                                              <button type="submit"  class="dropdown-item" style="color:red">Disable</button>
                                                             </form>
                                                       @else
                                                        <form method="post" action="{{route('news.status', encrypt($sp->id))}}" id="form2"> 
                                                              @csrf  
                                                              <input type="hidden" name="status" value="0">
                                                              <button type="submit"  class="dropdown-item" style="color:green">Enable</button>
                                                                 </form>
                                                       @endif
                                                       
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                              @endforeach
                                              @else 
                                              <tr>
                                              <td> No data available </td>
                                              </tr>
                                              @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                 </div>
                  </div>

@endsection

@section('script')
<script>

$('.clockpicker-example').clockpicker({
    autoclose: true
});

$('input[name="audition_date"]').daterangepicker({
  singleDatePicker: true,
  showDropdowns: true
});

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