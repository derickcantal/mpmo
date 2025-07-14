Hi {{ $user->name }},

Welcome to {{ config('app.name') }}! Thanks for signing up.

Please verify your email address by visiting the link below:
{{ $verificationUrl }}

If you didn’t register, you can safely ignore this message.

Once verified, you can log in here:
{{ url('/login') }}

If you have any questions, reply to this email. We’re happy to help.

Cheers,
The {{ config('app.name') }} Team

© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
