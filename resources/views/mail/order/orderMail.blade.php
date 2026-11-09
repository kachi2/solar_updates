<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#e4e4e4" style="font-family:'Helvetica Neue','Helvetica','Arial','sans-serif';font-size:13px">
            <tbody><tr>
                <td bgcolor="#FFFFFF" width="100%">

                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="m_8958432110786407146tablewrapper">
                        <tbody><tr>
                            <td width="600" style="border:1px solid lightgrey">

                                
                                <table class="m_8958432110786407146tableheader" width="100%" cellspacing="0" cellpadding="0" style="vertical-align:middle">
                                    <tbody>
                                        <tr bgcolor="#ffffff">
                                          <td style="padding: 20px 20px; "><a href="#"><img src="{{assets('/frontend/images/logo.png')}}" alt="image"
                                                                                            style="height: 30px; margin-left: auto; margin-right: auto; display:block;"></a>
                                        </td>
                                    </tr>
                                </tbody></table>
                                <hr>
                               
                                <table class="m_8958432110786407146bodycontent" width="100%" cellspacing="0" style="padding-top:15px">
                                    <tbody><tr>
                                        <td class="m_8958432110786407146bodycontent_cell" style="background-color:#ffffff;color:#565656;padding:15px 15px 15px 15px" width="100%">


                                            Dear {{$data['name']}},
                                            
                                            <p>Thank you for shopping on sofarsolar! Your order <b>{{$data['order_No']}}</b> has been successfully confirmed.
                                            </p>

                                                   Your printing job will commence immediately, Once its out for delivery or available for pick-up you will receive a notification from us.<p>

                                                    Thank you for choosing sofarsolar.        
                                                                         

                                                <div style="padding:0 0">
                                                <div style="padding:0 0">
                                                    <div style="display:table;border-collapse:separate;width:100%;border-spacing:2px">
                                                        <div style="display:table-row">
                                                            <div style="width:50%;display:table-cell;background-color:#fff;border:1px solid #ddd;border-collapse:collapse;vertical-align:top">
                                                                <p style="background-color:#f8f8f8;font-weight:bold;margin-top:0;margin-bottom:0px;padding:3px;vertical-align:middle">Estimated delivery date(s)</p>
                                                                <p style="padding-left:3px;margin-top:1px">Between 3 - 5 days after payment</p>
                                                            </div>
                                                            <div style="width:50%;display:table-cell;background-color:#fff;border:1px solid #ddd;border-collapse:collapse;vertical-align:top">
                                                                <p style="background-color:#f8f8f8;font-weight:bold;margin-top:0;margin-bottom:0px;padding:3px;vertical-align:middle">Delivery method</p>
                                                                <p style="padding-left:3px;margin-top:1px">
                                                                         {{$data['delivery_method']}}
                                                                    </p>
                                                                                        </div>

                                                            </div>
                                                            <div style="display:table-row">
                                                                <div style="width:50%;display:table-cell;background-color:#fff;border:1px solid #ddd;border-collapse:collapse;vertical-align:top">
                                                                    <p style="background-color:#f8f8f8;font-weight:bold;margin-top:0;margin-bottom:0px;padding:3px;vertical-align:middle">Recipient details</p>
                                                                    <p style="padding-left:3px;margin-top:1px">{{$data['receiver_name']}} {{$data['phone']}}
                                                                    </p>
                                                                </div>
                                                                <div style="width:50%;display:table-cell;background-color:#fff;border:1px solid #ddd;border-collapse:collapse;vertical-align:top">
                                                                    <p style="background-color:#f8f8f8;font-weight:bold;margin-top:0;margin-bottom:0px;padding:3px;vertical-align:middle">Delivery address</p>
                                                                    <p style="padding-left:3px;margin-top:1px">{{$data['address']}}
                                                                    </p>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                                <table class="m_8958432110786407146orderinfotable" style="border:1px solid #ccc;margin:0;padding:0;width:100%;table-layout:fixed">
                                                    <caption class="m_8958432110786407146orderinfocaption" style="font-weight:bold;text-align:left;padding-top:10px">You ordered for:</caption>
                                                    <thead class="m_8958432110786407146orderinfohead" style="text-align:center">
                                                        <tr class="m_8958432110786407146orderinfohead" style="background:#f8f8f8;border:1px solid #ddd;text-transform:uppercase">
                                                            <th scope="col" style="width:15%"></th>
                                                            <th scope="col" style="width:50%">Item</th>
                                                            <th scope="col" style="width:15%">Quantity</th>
                                                            <th scope="col" style="width:15%">Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['order_items'] as $orders)
                                                            <tr class="m_8958432110786407146orderinfotr" style="border:1px solid #ddd;text-align:center">
                                                                <td class="m_8958432110786407146orderinfotd">
                                                                    <center style="overflow:hidden;max-width:100%">
                                                                        <input type="image" class="m_8958432110786407146itemimage" src="{{asset('/images/products/'.$orders->image)}}" width="100px" height="100px">
                                                                    </center>
                                                                </td>
                                                                <td class="m_8958432110786407146orderinfotd"><span class="m_8958432110786407146itemlabel" style="display:none;overflow:hidden;font-size:0px">Item</span>
                                                                    {{$orders->product_name}}</td>
                                                                <td class="m_8958432110786407146orderinfotd"><span class="m_8958432110786407146itemlabel" style="display:none;overflow:hidden;font-size:0px">Quantity</span>{{$orders->qty}}</td>
                                                                <td class="m_8958432110786407146orderinfotd" style="text-align:right"><span class="m_8958432110786407146itemlabel" style="display:none;overflow:hidden;font-size:0px">Price</span>₦{{number_format($orders->price)}}</td>
                                                            </tr>
                                                            @endforeach
                                                       
                                                    </tbody>
                                                </table>


                                                <table style="width:100%;border:1px solid #ccc;margin-top:5px;table-layout:auto">
                                                    <tbody><tr>
                                                        <td style="font-weight:bold!important;text-transform:uppercase!important">Shipping Cost</td>
                                                        <td style="text-align:right">₦0</td>
                                                    </tr>
                                                  
                                                    <tr>
                                                        <td style="font-weight:bold!important;text-transform:uppercase!important">TOTAL</td>
                                                        <td style="text-align:right">₦ {{$data['amount']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight:bold!important;text-transform:uppercase!important">Payment Method</td>
                                                        <td style="text-align:right">
                                                            card Payment
                                                        </td>
                                                    </tr>
                                                </tbody></table>

                                   


<table width="100%" cellspacing="0" style="padding:0 0 1% 0.5%;display:block">

    <tbody><tr><td><img src="">
</td></tr></tbody></table>

    <p>Thanks for choosing sofarsolar!</p>

    <p>Warm Regards,</p>
                 
                                    </td></tr>


                                            </tbody></table>
                                        </td>
                                    </tr>
                                  
                                </tbody>
                </table>
                                                              
                                                              
                                </td>
                                                          
                                                          
                        </tr>
                    </tbody>
        </table>