<html>
<body>
<h2 style="text-align: center; margin-top: 50px">Congratulation!!!</h2>
<div style="margin: 20px; background-color: whitesmoke; padding: 20px;">
    <p style="font-weight: bold; margin-top: 30px; font-size: 20px;">Dear {{$data['name']}},</p>
    <p style="margin-top: 20px; font-size: 18px;">Your password was reset successfully. Click the link below to login with your new password.</p>

    <a href={{$data['webpage']}} target="_blank" style="margin-top: 30px;">
        <p style="font-weight: bold; font-size: 18px; color: blue;">Click here to continue</p>
    </a>

    <p style="margin-top: 20px; font-size: 18px;">If that doesn't work, copy and paste the following link below in your browser</p>

    <p style="font-weight: bold; font-size: 18px; color: blue; margin-top: 20px;">{{$data['webpage']}}</p>

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
