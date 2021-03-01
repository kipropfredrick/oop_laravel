<!DOCTYPE html>
<html>
<head>
    <title>Combine</title>
</head>
<body>
   
        <h1 style="background-color:#000;color:#FFF;padding:20px;width:100%">Combine</h1>
            <h5>Dear<strong> &nbsp;{{ $details['customer_name'] }}</strong></h5>
            <p>You had attempted to make a booking of :<strong>{{ $details['product_name'] }}</strong>, On mosmos.co.ke, The Product Price is  : <strong>KES  {{ $details['total_cost'] }}</strong>
            If you are still intrested, Go to Mpesa , Select Paybill Enter : <strong>4029165</strong> and Account Number : <strong>{{$details['booking_reference']}} </strong> ,Enter any amount you wish to pay. Terms & Conditions Apply.</p>
            <p><strong>Thank you</strong></p>
</body>
</html>