<html>
<body>
<h2 style="text-align: center; margin-top: 50px">Password Reset Request!</h2>
<div style="margin: 20px; background-color: whitesmoke; padding: 20px;">
    <p style="font-weight: bold; margin-top: 30px; font-size: 20px;">Dear {{$data['name']}},</p>
    <p style="margin-top: 20px; font-size: 18px;">Resetting your password is easy. Just click the link below and follow the instructions.
        We'll have you up and running in no time.</p>

    {{--<p style="margin-top: 25px; font-size: 18px; font-weight: bold;">{{$data['OTP']}}</p>--}}

    <a href={{$data['webpage']}}/reset_password_request/{{$data['encodedEmail']}} target="_blank" style="margin-top: 30px;">
        <p style="font-weight: bold; font-size: 18px; color: blue;">Click here to continue</p>
    </a>

    <p style="margin-top: 20px; font-size: 18px;">If that doesn't work, copy and paste the following link below in your browser</p>

    <p style="font-weight: bold; font-size: 18px; color: blue; margin-top: 20px;">{{$data['webpage']}}/reset_password_request/{{$data['encodedEmail']}}</p>

    <p style="margin-top: 50px; font-size: 18px;">Cheers,</p>
    <p style="font-size: 18px; font-weight: bold; margin-top: -15px;">The Versa Team</p>
</div>

<div style="background-color: cornflowerblue; padding: 20px; margin: 20px;">
    <p style="font-weight: bold; margin-top: 30px; font-size: 20px; text-align: center;">Need any Help?</p>
    <a href={{$data['webpage']}} target="_blank" style="font-weight: bold; margin-top: 30px; font-size: 20px; text-align: center;">
        <p style="font-weight: bold; margin-top: 30px; font-size: 20px; text-align: center; color: blue;">Talk to us</p>
    </a>
</div>
</body>
</html>
