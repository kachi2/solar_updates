<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#e4e4e4" style="font-family:'Helvetica Neue','Helvetica','Arial','sans-serif';font-size:13px">
            <tbody><tr>
                <td bgcolor="#FFFFFF" width="100%">

                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="m_8958432110786407146tablewrapper">
                        <tbody><tr>
                            <td width="600" style="border:1px solid lightgrey">

                                
                                <table class="m_8958432110786407146tableheader" width="100%" cellspacing="0" cellpadding="0" style="vertical-align:middle">
                                    <tbody>
                                        <tr bgcolor="#ffffff">
                                          <td style="padding: 20px 20px; "><a href="#"><img src="http://staging.sofarsolar.ng/frontend/images/logo.png" alt="image"
                                                                                            style="height: 30px; margin-left: auto; margin-right: auto; display:block;"></a>
                                        </td>
                                    </tr>
                                </tbody></table>
                                <hr>
                               
                                <table class="m_8958432110786407146bodycontent" width="100%" cellspacing="0" style="padding-top:15px">
                                    <tbody><tr>
                                        <td class="m_8958432110786407146bodycontent_cell" style="background-color:#ffffff;color:#565656;padding:15px 15px 15px 15px" width="100%">


                                            Dear {{$data['name']}},
                                            
                                            <p>Thank you for shopping on sofarsolar! Your order <b>{{$data['order_No']}}</b> has been successfully Dispatched.
                                            </p>

                                                  @if($data['delivery_method'] == 'home_delivery')
                                                  The package will be delivered by our delivery agent at the following address:{{$data['address']}}.
                                                   @else
                                                   Please visit our Address at 1 Adeola Adeoye Street, Off Olowu Street or Toyin Street, Ikeja, Lagos Nigeria to pickup your packages.
                                                   @endif
                                                You will receive an SMS on {{$data['phone']}}..        
                                               <table class="m_8958432110786407146orderinfotable" style="border:1px solid #ccc;margin:0;padding:0;width:100%;table-layout:fixed">
                                                    <caption class="m_8958432110786407146orderinfocaption" style="font-weight:bold;text-align:left;padding-top:10px">Items on this delivery:</caption>
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