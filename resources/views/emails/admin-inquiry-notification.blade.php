<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Customer Inquiry</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #e74c3c;
        }
        .header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .alert-badge {
            background: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 15px;
        }
        .inquiry-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .inquiry-meta {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .meta-row {
            display: flex;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .meta-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .meta-label {
            font-weight: bold;
            width: 140px;
            color: #2c3e50;
            flex-shrink: 0;
        }
        .meta-value {
            flex: 1;
            color: #34495e;
        }
        .message-content {
            background: #fff;
            border: 1px solid #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            line-height: 1.8;
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
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .button.danger {
            background-color: #e74c3c;
        }
        .button.danger:hover {
            background-color: #c0392b;
        }
        .priority-high {
            color: #e74c3c;
            font-weight: bold;
        }
        .priority-medium {
            color: #f39c12;
            font-weight: bold;
        }
        .priority-low {
            color: #27ae60;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background: #f39c12;
            color: white;
        }
        .status-new {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="alert-badge">🔔 New Customer Inquiry</div>
            <div class="inquiry-title">
                Inquiry #{{ $inquiry->id }} - {{ ucfirst($inquiry->subject) }}
            </div>
        </div>

        <div class="content">
            <div class="inquiry-meta">
                <div class="meta-row">
                    <div class="meta-label">👤 Customer:</div>
                    <div class="meta-value">
                        <strong>{{ $inquiry->name }}</strong><br>
                        📧 {{ $inquiry->email }}<br>
                        @if($inquiry->phone)📞 {{ $inquiry->phone }}<br>@endif
                    </div>
                </div>

                <div class="meta-row">
                    <div class="meta-label">📅 Submitted:</div>
                    <div class="meta-value">
                        {{ $inquiry->created_at->format('M j, Y \a\t g:i A') }}<br>
                        <small style="color: #7f8c8d;">{{ $inquiry->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <div class="meta-row">
                    <div class="meta-label">📍 Location:</div>
                    <div class="meta-value">
                        IP: {{ $inquiry->ip_address }}<br>
                        <small style="color: #7f8c8d;">{{ $inquiry->user_agent }}</small>
                    </div>
                </div>

                <div class="meta-row">
                    <div class="meta-label">🏷️ Status:</div>
                    <div class="meta-value">
                        <span class="status-badge status-{{ $inquiry->status }}">{{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}</span>
                        @if($inquiry->is_read)
                            <small style="color: #27ae60;">✓ Read</small>
                        @else
                            <small class="priority-high">⚠ Unread</small>
                        @endif
                    </div>
                </div>
            </div>

            <h3 style="color: #2c3e50; margin-bottom: 15px;">📝 Message Content:</h3>
            <div class="message-content">
                {!! nl2br(e($inquiry->message)) !!}
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="button">View Full Details</a>
                <a href="{{ route('admin.inquiries.index') }}" class="button">View All Inquiries</a>
                <a href="mailto:{{ $inquiry->email }}?subject=Re: Inquiry #{{ $inquiry->id }}" class="button">Reply via Email</a>
            </div>

            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin-top: 20px;">
                <strong>💡 Quick Actions:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li>Mark as read/unread in the admin panel</li>
                    <li>Update status (pending → in progress → resolved)</li>
                    <li>Add internal notes for team reference</li>
                    <li>Send response email to customer</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>
                <strong>Admin Notification System</strong><br>
                This is an automated notification from your Eternal Diamonds website.<br>
                <small>Inquiry submitted at {{ $inquiry->created_at->format('M j, Y g:i A T') }}</small>
            </p>
        </div>
    </div>
</body>
</html>
