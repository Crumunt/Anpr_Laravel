<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - {{ config('app.name') }}</title>
    <style>
        /* Reset styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f7fa;
        }

        /* Container */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #006300 0%, #068406 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-header .subtitle {
            color: rgba(255, 255, 255, 0.9);
            margin-top: 8px;
            font-size: 16px;
        }

        .email-header .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        /* Body */
        .email-body {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .message {
            color: #4b5563;
            font-size: 16px;
            margin-bottom: 25px;
        }

        /* Icon Container */
        .icon-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background-color: #dcfce7;
            border-radius: 50%;
        }

        .icon-circle svg {
            width: 40px;
            height: 40px;
            color: #006300;
        }

        /* CTA Button */
        .cta-container {
            text-align: center;
            margin: 35px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #006300 0%, #068406 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(0, 99, 0, 0.4);
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            box-shadow: 0 6px 20px rgba(0, 99, 0, 0.5);
        }

        /* Warning Box */
        .warning-box {
            background-color: #fefce8;
            border: 1px solid #fef08a;
            border-radius: 8px;
            padding: 15px 20px;
            margin-top: 25px;
        }

        .warning-box .icon {
            display: inline-block;
            margin-right: 8px;
        }

        .warning-box p {
            color: #854d0e;
            font-size: 14px;
            margin: 0;
        }

        /* Info Box */
        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-box p {
            color: #166534;
            font-size: 14px;
            margin: 0;
        }

        /* Link fallback */
        .link-fallback {
            margin-top: 25px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
        }

        .link-fallback p {
            color: #6b7280;
            font-size: 13px;
            margin: 0 0 10px 0;
        }

        .link-fallback .url {
            word-break: break-all;
            color: #006300;
            font-size: 12px;
            font-family: monospace;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer p {
            color: #9ca3af;
            font-size: 13px;
            margin: 0 0 5px 0;
        }

        .email-footer .company {
            color: #006300;
            font-weight: 600;
        }

        /* Security notice */
        .security-notice {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .security-notice p {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Accent border */
        .accent-border {
            border-bottom: 4px solid #f3c423;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container accent-border">
            <!-- Header -->
            <div class="email-header">
                <h1>CLSU ANPR System</h1>
                <p class="subtitle">Password Reset Request</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <!-- Icon -->
                <div class="icon-container">
                    <div class="icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                </div>

                <p class="greeting">Hello,</p>

                <p class="message">
                    You are receiving this email because we received a password reset request for your account
                    associated with <strong>{{ $user->email }}</strong>.
                </p>

                <!-- Info Box -->
                <div class="info-box">
                    <p>
                        <strong>🔒 Security Tip:</strong> Make sure to choose a strong password that includes
                        uppercase letters, lowercase letters, numbers, and special characters.
                    </p>
                </div>

                <!-- CTA Button -->
                <div class="cta-container">
                    <a href="{{ $resetUrl }}" class="cta-button">
                        Reset Your Password
                    </a>
                </div>

                <!-- Expiration Warning -->
                <div class="warning-box">
                    <p>
                        <span class="icon">⏰</span>
                        <strong>Important:</strong> This password reset link will expire in {{ $expiresInMinutes }} minutes.
                        Please reset your password before then.
                    </p>
                </div>

                <!-- Link Fallback -->
                <div class="link-fallback">
                    <p>If the button above doesn't work, copy and paste this link into your browser:</p>
                    <p class="url">{{ $resetUrl }}</p>
                </div>

                <p class="message" style="margin-top: 25px;">
                    If you did not request a password reset, no further action is required. Your account is secure.
                </p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>This email was sent by <span class="company">CLSU ANPR System</span></p>
                <p>Central Luzon State University</p>
                <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>

                <div class="security-notice">
                    <p>
                        For security reasons, this link can only be used once. If you need to reset your password again,
                        please request a new link from the login page.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
