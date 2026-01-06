<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response to Your Inquiry</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #28a745;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .tagline {
            color: #7f8c8d;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .response-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
            line-height: 1.8;
        }
        .inquiry-summary {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            color: #7f8c8d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">✨ Eternal Diamonds</div>
            <div class="tagline">Exquisite Diamond Jewelry</div>
        </div>

        <div class="content">
            <div class="greeting">
                Dear <span class="highlight">{{ $inquiry->name }}</span>,
            </div>

            <p>Thank you for contacting Eternal Diamonds. We have reviewed your inquiry and wanted to provide you with a response.</p>

            <div class="inquiry-summary">
                <strong>Your Original Inquiry:</strong><br>
                <em>{{ Str::limit($inquiry->message, 150) }}</em>
            </div>

            <h3 style="color: #2c3e50; margin-bottom: 15px;">Our Response:</h3>
            <div class="response-content">
                {!! nl2br(e($inquiry->response)) !!}
            </div>

            <p>If you have any additional questions or need further assistance, please don't hesitate to <a href="{{ route('contact') }}" style="color: #28a745;">contact us again</a> or reply to this email.</p>

            <div style="text-align: center;">
                <a href="{{ route('shop') }}" class="button">Continue Shopping</a>
            </div>

            <div class="signature">
                <p>
                    <strong>Best regards,</strong><br>
                    The Eternal Diamonds Team<br>
                    <small>Customer Service Department</small>
                </p>
            </div>
        </div>

        <div class="footer">
            <p>
                <strong>Eternal Diamonds</strong><br>
                123 Diamond Avenue, New York, NY 10001<br>
                Phone: +1 (555) 123-4567 | Email: info@eternaldiamonds.com
            </p>
            <p style="font-size: 12px; margin-top: 20px;">
                This email was sent in response to your inquiry submitted on {{ $inquiry->created_at->format('M j, Y') }}.
                If you didn't submit this inquiry, please ignore this email.
            </p>
        </div>
    </div>
</body>
</html>
