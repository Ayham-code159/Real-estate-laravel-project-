<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify your Servixa account</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f6f3ff;">
    <div style="max-width: 620px; margin: 30px auto; background: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #eadfff;">
        <div style="background: linear-gradient(135deg, #6F3CC3, #8B5CF6); padding: 26px; color: white;">
            <h1 style="margin: 0; font-size: 26px;">Servixa</h1>
            <p style="margin: 8px 0 0; opacity: 0.9;">Verify your account</p>
        </div>

        <div style="padding: 28px; color: #172033;">
            <h2 style="margin-top: 0;">Hello {{ $firstName }},</h2>

            <p style="line-height: 1.7;">
                Thanks for registering with Servixa. Please verify your email address to complete your account creation.
            </p>

            <p style="line-height: 1.7;">
                This verification link will expire in <strong>5 minutes</strong>.
            </p>

            <div style="margin: 30px 0;">
                <a href="{{ $verificationUrl }}"
                   style="display: inline-block; background: linear-gradient(135deg, #6F3CC3, #8B5CF6); color: white; text-decoration: none; padding: 14px 22px; border-radius: 14px; font-weight: 800;">
                    Verify Email
                </a>
            </div>

            <p style="line-height: 1.7; color: #6b7280;">
                If you did not create this account, you can ignore this email.
            </p>

            <p style="font-size: 13px; color: #8b8fa3; word-break: break-all;">
                {{ $verificationUrl }}
            </p>
        </div>
    </div>
</body>
</html>
