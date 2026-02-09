<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - <?= APP_COMPANY_NAME ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0088cc 0%, #0066aa 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-top: 15px;
            letter-spacing: 0.5px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333333;
        }
        .message {
            font-size: 14px;
            color: #666666;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .otp-label {
            font-size: 14px;
            color: #666666;
            margin-bottom: 10px;
            text-align: center;
        }
        .otp-box {
            background-color: #f8f9fa;
            border: 2px dashed #0088cc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #333333;
            font-family: 'Courier New', monospace;
        }
        .expiry {
            font-size: 13px;
            color: #999999;
            text-align: center;
            margin-top: 15px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            font-size: 13px;
            color: #856404;
            border-radius: 4px;
        }
        .help-section {
            background-color: #f8f9fa;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            text-align: center;
        }
        .help-title {
            font-size: 16px;
            font-weight: 600;
            color: #0088cc;
            margin-bottom: 10px;
        }
        .help-text {
            font-size: 13px;
            color: #666666;
            margin-bottom: 15px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #0088cc;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0066aa;
        }
        .button-secondary {
            background-color: #ffffff;
            color: #0088cc !important;
            border: 2px solid #0088cc;
        }
        .button-secondary:hover {
            background-color: #f8f9fa;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer-text {
            font-size: 12px;
            color: #999999;
            line-height: 1.6;
        }
        .company-info {
            margin-top: 15px;
            font-size: 12px;
            color: #999999;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 20px;
            }
            .content {
                padding: 30px 20px;
            }
            .otp-code {
                font-size: 28px;
                letter-spacing: 6px;
            }
            .button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo"><?= APP_COMPANY_NAME ?></div>
            <h1>Kode OTP Akun Aplikasi</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hai<?= !empty($nama) ? ', ' . esc($nama) : '' ?>!</div>
            
            <div class="message">
                Ini adalah email otomatis yang dikirimkan kepada Anda untuk memberikan kode One-Time Password (OTP) yang diperlukan untuk <?= esc($loginRegisterStr) ?> akun Anda.
            </div>

            <div class="otp-label">Kode OTP Anda adalah:</div>
            
            <div class="otp-box">
                <div class="otp-code"><?= esc($otpCode) ?></div>
            </div>

            <div class="expiry">
                Berlaku hingga <?= esc($expiryDateTime) ?>
            </div>

            <div class="warning">
                ⚠️ Mohon untuk tidak memberikan informasi ini kepada siapapun. OTP ini adalah rahasia dan hanya untuk penggunaan Anda sendiri.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="company-info">
                <strong><?= APP_COMPANY_NAME ?></strong><br>
                <?= !empty(APP_COMPANY_ADDRESS) ? esc(APP_COMPANY_ADDRESS) : 'Surabaya - Indonesia' ?>
            </div>

            <div class="footer-text" style="margin-top: 20px;">
                Email ini dikirim secara otomatis. Mohon untuk tidak membalas email ini.<br>
                © <?= date('Y') ?> <?= APP_COMPANY_NAME ?>. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>