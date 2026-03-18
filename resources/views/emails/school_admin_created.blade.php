<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ config('app.name', 'Exam Platform') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:30px 0;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#4f46e5;padding:18px 24px;text-align:center;">
                        <h2 style="margin:0;color:#ffffff;font-size:20px;font-weight:600;">
                            Welcome to {{ config('app.name', 'Exam Platform') }}
                        </h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:24px;color:#333333;">

                        <p style="margin:0 0 12px 0;font-size:16px;font-weight:bold;">
                            Hello, {{ $admin->name }}
                        </p>

                        <p style="margin:0 0 18px 0;font-size:14px;line-height:1.6;">
                            An administrator account has been created for you for <strong>{{ $school->name }}</strong>. Please find your login credentials below.
                        </p>

                        <!-- Details box -->
                        <table cellpadding="0" cellspacing="0" style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:15px;margin:20px 0;">
                            <tr>
                                <td style="padding:8px 0;font-size:14px;font-weight:bold;width:140px;">Login URL:</td>
                                <td style="padding:8px 0;font-size:14px;"><a href="{{ route('admin.login') }}" style="color:#4f46e5;text-decoration:none;">{{ route('admin.login') }}</a></td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;font-size:14px;font-weight:bold;">Username/Email:</td>
                                <td style="padding:8px 0;font-size:14px;">{{ $admin->email }}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;font-size:14px;font-weight:bold;">Password:</td>
                                <td style="padding:8px 0;font-size:14px;font-family:monospace;color:#c026d3;">{{ $password }}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;font-size:14px;font-weight:bold;">School Code:</td>
                                <td style="padding:8px 0;font-size:14px;">{{ $school->code }}</td>
                            </tr>
                        </table>

                        <p style="margin:0 0 10px 0;font-size:13px;color:#555;font-weight:bold;">
                            For security reasons, please log in and change your password immediately.
                        </p>

                        <p style="margin:20px 0 0 0;font-size:14px;line-height:1.6;">
                            Best Regards,<br>
                            The {{ config('app.name', 'Exam Platform') }} Team
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f9fafb;padding:14px 24px;text-align:center;border-top:1px solid #e5e7eb;">
                        <p style="margin:0;font-size:12px;color:#888;">
                            © {{ date('Y') }} {{ config('app.name', 'Exam Platform') }}. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
</body>
</html>
