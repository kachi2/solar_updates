@extends('layouts.admin')
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                     <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Product</h6>
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
                                            <tr><th class="text-left">S/N</th>
                                                <th>Category</th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Dollar Price</th>
                                                <th>Image</th>
                                                <th>Views</th>
                                                 <th>Created At</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                  
                                        @if(count($products) > 0)
                                        @foreach ($products as  $sp)
                                            <tr>
                                            <td>{{$sp->id}}</td>
                                                <td>
                                                    <a href="#">@if(isset($sp->category->name)){{$sp->category->name}} @endif</a>
                                                </td> 
                                                <td>
                                                    <a href="#">{{$sp->name}}</a>
                                                </td>
                                                 <td>
                                                    <a href="#">{{number_format($sp->sale_price,2)}}</a>
                                                </td> 
                                                <td>
                                                    <a href="#">{{number_format($sp->exchange_rate,2)}}</a>
                                                </td> 
                                                <td>
                                                    <a href="#"><img src="{{asset('/images/products/'.$sp->image)}}" width="50px" height="50px"></a> 
                                                </td>  
                                                
                                                 <td>
                                                    <a href="#">{{$sp->views}}</a>
                                                </td>       
                                                  <td>
                                                    <a href="#">{{$sp->created_at->format('d/M/y')}}</a>
                                                </td>
                                               
                                                         @php
                                                        $id = $sp->id;
                                                        $parameter = encrypt($id);
                                                        @endphp
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                           
                                                                 <a href="{{route('product.edit', encrypt($sp->id))}}" class="dropdown-item">Edit Product</a>
                                                            @if($sp->status != 1) 
                                                            <form method="post" action="{{route('product.status', encrypt($sp->id))}}" id="form1"> 
                                                            @csrf    
                                                             <input type="hidden" name="status" value="1">
                                                              <button type="submit"  class="dropdown-item" style="color:red">Disable</button>
                                                             </form>
                                                       @else
                                                        <form method="post" action="{{route('product.status', encrypt($sp->id))}}" id="form2"> 
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