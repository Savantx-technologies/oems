<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login OTP</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:30px 0;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#4f46e5;padding:18px 24px;text-align:center;">
                        <h2 style="margin:0;color:#ffffff;font-size:20px;font-weight:600;">
                            Admin Login Verification
                        </h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:24px;color:#333333;">

                        <p style="margin:0 0 12px 0;font-size:14px;">
                            Hello,
                        </p>

                        <p style="margin:0 0 18px 0;font-size:14px;line-height:1.6;">
                            Use the following One-Time Password (OTP) to log in to your Admin account.
                        </p>

                        <!-- OTP box -->
                        <div style="text-align:center;margin:24px 0;">
                            <div style="
                                display:inline-block;
                                background:#eef2ff;
                                color:#020202;
                                font-size:28px;
                                font-weight:700;
                                letter-spacing:4px;
                                padding:14px 26px;
                                border-radius:6px;
                            ">
                                {{ $otp }}
                            </div>
                        </div>

                        <p style="margin:0 0 10px 0;font-size:13px;color:#555;">
                            This OTP is valid for <strong>5 minutes</strong>.
                        </p>

                        <p style="margin:0;font-size:13px;color:#555;">
                            If you did not request this login, please ignore this email.
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f9fafb;padding:14px 24px;text-align:center;">
                        <p style="margin:0;font-size:12px;color:#888;">
                            © {{ date('Y') }} Exam-Portal – Secure Admin Access
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
