@extends('layouts.admin')
@section('content')

        <!-- begin::page-header -->
 <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <form action="{{ route('order.status.update', [encrypt($order->order_No)]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Update Order Status</h6>
                            <div class="row">
                               
                                     <div class="col-md-6">
                                       <div class="form-group">
                                        <select name="delivery" class="form-control" @if($order->is_delivered == 3 || $order->is_delivered == 2) readOnly @endif>
                                        <option value="0" @if($order->is_delivered == 0) selected @endif>Pending</option>
                                        <option value="1"@if($order->is_delivered == 1) selected @endif>Initiated</option>
                                        <option value="2"@if($order->is_delivered == 2) selected @endif>Completed</option>
                                        <option value="3" @if($order->is_delivered == 3) selected @endif>Cancel Order</option>
                                        </select>
                                          <small id="emailHelp" class="form-text text-muted">Delivery Status 
                                            </small>
                                            @error('name')
                                            <span class="invalid-feedback"> <small> * </small> </span>
                                            @enderror
                                        </div>
                                         </div>
                                       <div class="col-md-6">
                                  <div class="custom-file">
                                             <select name="dispatch" class="form-control" @if($order->dispatch_status == 2) readOnly @endif>
                                        <option value="0" @if($order->dispatch_status == 0) selected @endif>Pending</option>
                                        <option value="1" @if($order->dispatch_status == 1) selected @endif>Dispatched</option>
                                        <option value="2" @if($order->dispatch_status == 2) selected @endif>Delivered</option>
                                        </select>
                                            <small id="emailHelp" class="form-text text-muted">Dispatech Status
                                            </small>
                                              @error('image')
                                            <span class="invalid-feedback"> <small> *</small> </span>
                                            @enderror
                                         </div>          
                            </div> 
                               <div class="col-md-6">
                                  <div class="custom-file">
                                             <select name="payment" class="form-control" @if($order->is_paid == 1) readOnly @endif>
                                        <option value="0" @if($order->is_paid == 0) selected @endif>Pending</option>
                                        <option value="1" @if($order->is_paid == 1) selected @endif >Paid</option>
                                        </select>
                                            <small id="emailHelp" class="form-text text-muted">Payment Status
                                            </small>
                                              @error('image')
                                            <span class="invalid-feedback"> <small> *</small> </span>
                                            @enderror
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
                             <button type="submit" class="text-center btn btn-primary w-100 p-3 ">Update Status</button>
                           </div>
                           </div>
                           </div>
                        </div>
                        </div>
                    </form>

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