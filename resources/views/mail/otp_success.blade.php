<html>
    <body>
        <h2 style="text-align: center; margin-top: 50px">OTP for Login</h2>
        <div style="margin: 20px; background-color: whitesmoke; padding: 20px;">
            <p style="font-weight: bold; margin-top: 30px; font-size: 20px;">Dear {{$data['name']}},</p>
            <p style="margin-top: 20px; font-size: 18px;">We refer to the login request you initiated.
                Kindly find below the token required to complete the request:</p>

            <p style="margin-top: 25px; font-size: 18px; font-weight: bold;">{{$data['OTP']}}</p>

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
