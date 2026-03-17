<!-- Save as otp-email.html -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your OTP Code</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Helvetica,Arial,sans-serif;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%"
        style="background-color:#f4f6f8;padding:24px 0;">
        <tr>
            <td align="center">
                <!-- Container -->
                <table role="presentation" cellpadding="0" cellspacing="0" width="600"
                    style="max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <!-- Header / Logo -->
                    <tr>
                        <td
                            style="padding:20px 24px;background:linear-gradient(90deg,#0f62fe,#5aa9ff);text-align:left;color:#ffffff;">
                            <h1 style="margin:0;font-size:20px;line-height:1.1;font-weight:700;">ExamMinutes</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:28px 24px 18px 24px;color:#243b53;">
                            <p style="margin:0 0 12px 0;font-size:16px;">Hi <strong>{{ $username }}</strong>,</p>
                            <p style="margin:0 0 18px 0;font-size:15px;color:#4b6072;">
                                Use the verification code below to complete your action. This code is valid for
                                <strong>2 minutes</strong>.
                            </p>

                            <!-- OTP box -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%"
                                style="margin:18px 0 20px 0;">
                                <tr>
                                    <td align="center">
                                        <div
                                            style="display:inline-block;padding:16px 24px;border-radius:8px;background:#f7fafc;border:1px dashed #d8e7ff;font-weight:700;font-size:28px;letter-spacing:4px;color:#0f62fe;">
                                            {{ $code }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Fallback plain OTP -->
                            <p style="margin:0 0 6px 0;font-size:13px;color:#6b7a8a;">
                                If the code box above isn’t visible, copy and paste this code where requested:
                            </p>
                            <p style="margin:6px 0 0 0;font-size:16px;color:#102a43;"><strong>OTP: {{ $code }}</strong></p>

                            <hr style="border:none;border-top:1px solid #eef2f6;margin:20px 0;" />

                            <p style="margin:0;font-size:13px;color:#7b8694;">
                                If you didn’t request this, you can safely ignore this email. For help, contact
                                <a href="mailto:contact@examminutes.com"
                                    style="color:#0f62fe;text-decoration:none;">contact@examminutes.com</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding:14px 24px 22px 24px;background:#fbfcfd;color:#91a3b7;font-size:12px;text-align:center;">
                            <div style="max-width:520px;margin:0 auto;">
                                <p style="margin:0 0 6px 0;">ExamMinutes · 123 Example St, City, Country</p>
                                <p style="margin:0;">© <span id="year">2026</span> ExamMinutes. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- End container -->
            </td>
        </tr>
    </table>
</body>

</html>