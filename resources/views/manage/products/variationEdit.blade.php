@extends('layouts.admin')
@section('content')
 <div class="container-fluid">
      <form action="{{ route('variation.update', [encrypt($variation->id)]) }}" method="POST" enctype="multipart/form-data">
              @csrf
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Edit Product variation</h6>
                            <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="qty"  value="{{$variation->qty}}" class="form-control @error('qty') is-invalid @enderror" id="exampleInputEmail1"
                                                   aria-describedby="emailHelp">
                                            <small id="emailHelp" class="form-text text-muted">Edit Quantity
                                            </small>
                                            @error('qty')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                        </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <input type="text" name="price"  value="{{$variation->price}}" class="form-control @error('price') is-invalid @enderror" id="exampleInput"
                                                   aria-describedby="EventLocation">
                                            <small id="emailHelp" class="form-text text-muted"> Edit Price
                                            </small>
                                            @error('price')
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
                   

@endsection
@section('script')
<script>



$('.clockpicker-example').clockpicker({
    autoclose: true
});

$('input[name="date"]').daterangepicker({
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