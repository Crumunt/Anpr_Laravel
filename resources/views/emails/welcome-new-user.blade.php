<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
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

        /* Info Box */
        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-box .label {
            color: #166534;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-box .value {
            color: #15803d;
            font-size: 18px;
            font-weight: 600;
        }

        /* CTA Button */
        .cta-container {
            text-align: center;
            margin: 35px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.4);
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.5);
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
            color: #16a34a;
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
            color: #6b7280;
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
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>{{ config('app.name') }}</h1>
                <p class="subtitle">Welcome to your new account</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p class="greeting">Hello {{ $userName }},</p>

                <p class="message">
                    Your account has been created successfully! You've been assigned the role of
                    <strong>{{ $userRole }}</strong>. Before you can access your account, you'll need
                    to set up a secure password.
                </p>

                <!-- Account Info -->
                <div class="info-box">
                    <p class="label">Your Email Address</p>
                    <p class="value">{{ $user->email }}</p>
                </div>

                <!-- CTA Button -->
                <div class="cta-container">
                    <a href="{{ $setupUrl }}" class="cta-button">
                        Set Up Your Password
                    </a>
                </div>

                <!-- Expiration Warning -->
                <div class="warning-box">
                    <p>
                        <span class="icon">⏰</span>
                        <strong>Important:</strong> This link will expire in {{ $expiresInHours }} hours.
                        Please set up your password before then.
                    </p>
                </div>

                <!-- Link Fallback -->
                <div class="link-fallback">
                    <p>If the button above doesn't work, copy and paste this link into your browser:</p>
                    <p class="url">{{ $setupUrl }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>This email was sent by <span class="company">{{ config('app.name') }}</span></p>
                <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>

                <div class="security-notice">
                    <p>
                        If you didn't request this account, please ignore this email or contact support
                        if you have concerns about your account security.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
