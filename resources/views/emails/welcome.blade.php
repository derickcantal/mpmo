<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to {{ config('app.name') }}</title>
  <style>
    /* Basic responsive styles */
    body { margin:0; padding:0; font-family: sans-serif; background-color: #f5f5f5; }
    .container { width: 100%; max-width: 600px; margin: auto; background: #ffffff; padding: 20px; }
    .header { text-align: center; padding-bottom: 20px; }
    .header h1 { margin: 0; color: #333333; }
    .content { color: #555555; line-height: 1.5; }
    .button { display: inline-block; padding: 12px 24px; margin: 20px 0; background-color: #1a202c; color: #ffffff; text-decoration: none; border-radius: 4px; }
    .footer { font-size: 12px; color: #999999; text-align: center; margin-top: 30px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Welcome to {{ config('app.name') }}!</h1>
    </div>

    <div class="content">
      <p>Hi {{ $user->name }},</p>

      <p>Thank you for registering at <strong>{{ config('app.name') }}</strong>. We’re excited to have you on board!</p>

      <p>To get started, please verify your email address by clicking the button below:</p>

      <p style="text-align:center;">
        <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
      </p>

      <p>If you didn’t create an account, no further action is required.</p>

      <p>Once you’ve verified, you can log in and explore:</p>
      <ul>
        <li>🚀 Dashboard & stats</li>
        <li>🔒 Secure your wallet</li>
        <li>💬 Connect with support</li>
      </ul>

      <p>If you have any questions, just reply to this email—we’re always here to help.</p>

      <p>Cheers,<br>The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
      <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
      <p><a href="{{ url('/') }}" style="color:#999999; text-decoration:none;">Visit our site</a></p>
    </div>
  </div>
</body>
</html>
