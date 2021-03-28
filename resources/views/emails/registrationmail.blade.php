<!DOCTYPE html>
<html>
<head>
    <!-- title -->
    <title>New Account Created</title>
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/3e5f9a0a23.js"></script>

    <!-- Google Fonts --> 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    
</head>
<style>
    body {
        background-color: #f8f8f8;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .main-body {
        margin: 20px 10px;
    }

    .email-header {
        background-color: #1e22a9;padding: 10px;
    }

    .email-header img {
        width: 200px;
    }

    .email-body {
        background-color: #ffffff;
        padding: 10px;
        max-width: 600px;
        margin: auto;
    }

    .email-btn a {
        background-color: #1e22a9;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
    }

    .email-btn a:hover {
        background-color: #f68b1e;
    }

    .email-footer {
        padding: 10px;
        max-width: 600px;
        margin: auto;
        text-align: center;
    }

    .ftr-socials {
    text-align: center;
    }

    .ftr-socials a .fa {
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        vertical-align: middle;
        padding: 10px;
        margin: 10px;
    }

    .fa-facebook {
        background: #4267b2;
    }

    .fa-instagram {
        background: #e1306c;
    }

    .ftr-socials a .fa:hover {
        background: #007bff;
    }
</style>
    <body>
        <div class="main-body">
            <div class="email-body">
                <div style="background-color: #1e22a9;padding: 10px;" class="email-header">
                    <img style="width: 200px;" src="https://mosmos.co.ke/assets/img/logo/web-logo.png" alt="Lipa Mos Mos">
                </div>

                <div class="email-text" style="padding:10px;">
                    <p>Hello, {{ $details['name'] }}</p>
                    
                    <p>Your <strong>Lipa Mos Mos</strong> account has been created successfully.</p>

                    <p>Your email address is: <strong>{{ $details['email'] }}</strong> and the password 
                        is <strong>{{ $details['password'] }}</strong>.</p>

                        <hr>

                    <p>Please Complete your booking. Use Paybill 4040299, account number <strong> {{$details['booking_reference']}}</strong> and amount <strong>Ksh. {{$details['initial_deposit']}}</strong></p>

                    <div  class="email-btn">
                        <a style="background-color: #1e22a9;color: #ffffff;padding: 5px 10px;border-radius: 5px;text-decoration: none;" href="https://mosmos.co.ke/login">Visit Dashboard</a>
                    </div>
                    
                    <p>Thank you,<br/>
                        Lipa Mos Mos Team</p>
                </div>
            </div>

            <div style="padding: 10px;max-width: 600px;margin: auto;text-align: center;" class="email-footer">
                <div style="text-align: center;" class="ftr-socials">
                    <a href="https://facebook.com/lipamosmoske" target="_blank"><img  style="height:35px;width:35px;object-fit:contain" src="https://mosmos.co.ke/assets/img/social/fb.png" alt=""></a>
                    <a href="https://instagram.com/lipamosmoske" target="_blank"><img  style="height:35px;width:35px;object-fit:contain" src="https://mosmos.co.ke/assets/img/social/insta.png" alt=""></a>
                </div>
                <div>
                    <span>
                        <a href="https://mosmos.co.ke">Visit Website</a>
                    </span> |
                    <span>
                        <a href="mailto:support@mosmos.co.ke">Email Support</a>
                    </span>
                </div>
                <p>&copy; Lipa Mos Mos | All Rights Reserved</p>
            </div>
        </div>

    </body>
</html>