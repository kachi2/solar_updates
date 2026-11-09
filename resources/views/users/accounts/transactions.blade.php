@extends('layouts.app')

@section('content')

<main id="content" role="main" class="checkout-page">
            <!-- breadcrumb -->
     <div class="bg-gray-13 bg-md-transparent">
                <div class="container">
                    <!-- breadcrumb -->
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="#">Home</a></li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Wallet Transaction</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- End breadcrumb -->
                </div>
            </div>
            
            <div class="container">
                <div class="row mb-8">
                @include('includes.accountNav')
                               <div class="mb-8 col-xl-9" >
                               <span class="p-1 font-size-18">Transaction History</span>
                            <div class="borders-radius-17 border p-4 mt-4 mt-md-0 px-lg-10 px-xl-4 px-wd-10 py-lg-9 py-xl-5 py-wd-9">
                               <div class="row">
                               <div class="input-group p-2"> <span class="input-group-addon">Filter Transaction</span>
                                    <div class="col-xl-6" >
                                        <input id="filter" type="text" class="form-control" placeholder="Search by Date, Amount, transaction ref">
                                    </div>
                                    </div>
                                <table class="table tabled-bordered  table-striped table-bordered">
                                            <thead class="thead-dark font-size-12">
                                                <tr>
                                              
                                                <th> Trxn Ref</th>
                                                <th>External Ref </th>
                                                <th> Order Number </th>
                                                <th> Type </th>
                                                <th> Amount </th>
                                                <th>Prev Balance</th>
                                                <th>Avail Balance</th>
                                                <th>Transaction Time</th>
                                                </tr>
                                            </thead>
                                            <tbody class="font-size-12 searchable">
                                                @if(count($transactions)>0)
                                    @foreach($transactions as $transaction)                   
                                                <tr>
                                                   
                                                    <td>
                                                      {{$transaction->payment_ref}}
                                                  </td>
                                                  <td>
                                                     {{$transaction->external_ref}}
                                                  </td>
                                                  <td>
                                                     {{$transaction->order_No}}
                                                  </td>
                                                  <td>
                                                     {{$transaction->type}}
                                                  </td>
                                                  <td>
                                                     {{number_format($transaction->amount)}}
                                                  </td>
                                                  <td>
                                                      {{number_format($transaction->prev_bal,2)}}
                                                  </td>
                                                  <td>
                                                      {{number_format($transaction->avail_bal,2)}}
                                                  </td>
                                                  <td>
                                                     {{$transaction->created_at}}
                                                  </td>
                                                </tr>
                                             @endforeach
                                             @else
                                             <tr> <td> <p>You have not done any transaction yet</p></td></tr>
                                            
                                             @endif
                                            </tbody>
                                            </table>
                             
                            </div>
                            </div>
                            <span class="float-right">
                            {{$transactions->links()}}
                            </span>
                            <!-- End Tab Content -->
                        </div>
                </div>
                
            </div>
            
</main>


@endsection

@section('scripts')
<script>

$(document).ready(function () {

    (function ($) {

        $('#filter').keyup(function () {

            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        })

    }(jQuery));

});
</script>
@endsection