<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting us</title>
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
        .inquiry-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #2c3e50;
        }
        .detail-value {
            flex: 1;
            color: #34495e;
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
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
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

            <p>Thank you for reaching out to Eternal Diamonds! We've successfully received your inquiry and our team is already reviewing it.</p>

            <div class="inquiry-details">
                <h3 style="margin-top: 0; color: #2c3e50;">Your Inquiry Details:</h3>

                <div class="detail-row">
                    <div class="detail-label">Inquiry ID:</div>
                    <div class="detail-value">#{{ $inquiry->id }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Subject:</div>
                    <div class="detail-value">{{ ucfirst($inquiry->subject) }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Submitted:</div>
                    <div class="detail-value">{{ $inquiry->created_at->format('M j, Y \a\t g:i A') }}</div>
                </div>

                @if($inquiry->phone)
                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value">{{ $inquiry->phone }}</div>
                </div>
                @endif
            </div>

            <p><strong>What happens next?</strong></p>
            <ul>
                <li>Our customer service team will review your inquiry within 24-48 hours</li>
                <li>You'll receive a personalized response via email</li>
                <li>If your inquiry requires immediate attention, we'll contact you by phone</li>
            </ul>

            <p>In the meantime, feel free to browse our <a href="{{ route('shop') }}" style="color: #3498db;">latest collections</a> or learn more <a href="{{ route('about') }}" style="color: #3498db;">about us</a>.</p>

            <div style="text-align: center;">
                <a href="{{ route('shop') }}" class="button">Browse Our Collection</a>
            </div>
        </div>

        <div class="footer">
            <p>
                <strong>Eternal Diamonds</strong><br>
                123 Diamond Avenue, New York, NY 10001<br>
                Phone: +1 (555) 123-4567 | Email: info@eternaldiamonds.com
            </p>
            <p style="font-size: 12px; margin-top: 20px;">
                This email was sent to {{ $inquiry->email }} because you submitted an inquiry on our website.
                If you didn't submit this inquiry, please ignore this email.
            </p>
        </div>
    </div>
</body>
</html>
