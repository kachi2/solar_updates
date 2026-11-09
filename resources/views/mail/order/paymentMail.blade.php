<!doctype html>
<html lang="en">
<body class="bg-white">
    <!-- email template -->
    <table class="body-wrap"
       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #eaf0f7; margin: 0;"
       bgcolor="#eaf0f7">
        <tr>
             <td valign="top"  width="30%"></td>
            <td class="container" width="400"
                style="display: block !important; max-width: 600px !important; clear: both !important;"
                valign="top">
                <div class="content" style="padding: 50px 0">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0"
                           style="border: 1px dashed #4d79f6;">
                        <tr>
                            <td class="content-wrap aligncenter" style="padding: 20px; background-color: #fff;"
                                align="center" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding-bottom: 20px; "><a href="#"><img src="http://staging.sofarsolar.ng/frontend/images/logo.png" alt="image"
                                                                                            style="height: 30px; margin-left: auto; margin-right: auto; display:block;"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block" style="padding: 0 0 20px;" valign="top">
                                            <h5 class="aligncenter"
                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,'Lucida Grande',sans-serif;font-size: 14px; color:black; line-height: 1.2em; font-weight: 300; text-align: center;"
                                                align="center">Your payment was successful and has been received</span>.
                                            </h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block aligncenter" style="padding: 0 0 20px;"
                                            align="center" valign="top">
                                            <table class="invoice" style="width: 80%;">
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; text-align: center;"
                                                        valign="top">Payment Details
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px 0;" valign="top">
                                                        <table class="invoice-items" cellpadding="0"
                                                               cellspacing="0" style="width: 100%;">
                                                            <tr>
                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    valign="top">Amount Paid
                                                                </td>
                                                                <td class="alignright"
                                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    align="right" valign="top">₦{{number_format($data['amount'],2)}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    valign="top">Payment Method
                                                                </td>
                                                                <td class="alignright"
                                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    align="right" valign="top">Card Payment
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    valign="top">Order No
                                                                </td>
                                                                <td class="alignright"
                                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    align="right" valign="top">{{$data['order_No']}}
                                                                </td>
                                                            </tr>
                                                              <tr>
                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    valign="top">Transaction Ref
                                                                </td>
                                                                <td class="alignright"
                                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    align="right" valign="top">{{$data['payment_ref']}}
                                                                </td>
                                                            </tr>
                                                              <tr>
                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    valign="top">External Ref
                                                                </td>
                                                                <td class="alignright"
                                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 10px 0;"
                                                                    align="right" valign="top">{{$data['external_ref']}}
                                                                </td>
                                                            </tr>


                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                   
                                       <tr>
                                        <td class="content-block aligncenter"
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                            align="center" valign="top">
                                         <hr>If you have questions or issues with this payment, contact sofarsolar at orders@sofarsolar.ng</td>
                                    </tr>
                                  
                                    <tr>
                                        <td class="content-block aligncenter"
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                            align="center" valign="top"> 
                                            <hr>
                                            {{date('d M Y')}}
                                        </td>
                                    </tr>
                                </table>
                                <!--end table-->
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <!-- ./ email template -->
</html>