<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Response to Your Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h1 {
            color: #1a1a1a;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .message-container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #d4af37;
        }
        .original-message {
            background-color: #fffbe6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-style: italic;
            color: #4a4a4a;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #d4af37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Response to Your Inquiry</h1>
    </div>
    
    <p>Dear {{ $inquiry->name }},</p>
    
    <p>Thank you for contacting Eternal Diamonds. We appreciate your interest in our products and services.</p>
    
    <div class="message-container">
        <p><strong>Our Response:</strong></p>
        <p>{!! nl2br(e($inquiry->response)) !!}</p>
    </div>
    
    <div class="original-message">
        <p><strong>Your original message sent on {{ $inquiry->created_at->format('F j, Y') }}:</strong></p>
        <p>{!! nl2br(e($inquiry->message)) !!}</p>
    </div>
    
    <p>If you have any further questions or need additional assistance, please don't hesitate to contact us.</p>
    
    <p>Best regards,<br>
    The Eternal Diamonds Team</p>
    
    <div class="footer">
        <p>© {{ date('Y') }} Eternal Diamonds. All rights reserved.</p>
        <p>123 Diamond Avenue, New York, NY 10001<br>
        +1 (555) 123-4567 | info@eternaldiamonds.com</p>
        
        <div class="social-links">
            <a href="#">Facebook</a> | 
            <a href="#">Instagram</a> | 
            <a href="#">Twitter</a>
        </div>
    </div>
</body>
</html>