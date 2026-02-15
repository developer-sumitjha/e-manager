<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="color: #6c5ce7; margin-top: 0;">New Contact Form Submission</h2>
        <p style="margin: 0; color: #666;">You have received a new message from your website contact form.</p>
    </div>
    
    <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: bold; width: 150px;">Name:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">{{ $name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: bold;">Email:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><a href="mailto:{{ $email }}">{{ $email }}</a></td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: bold;">Phone:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">{{ $phone }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: bold;">Subject:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">{{ $subject }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; vertical-align: top;">Message:</td>
                <td style="padding: 10px 0; white-space: pre-wrap;">{{ $message }}</td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; font-size: 12px; color: #666;">
        <p style="margin: 0;">This email was sent from the contact form on <strong>{{ $tenant->business_name }}</strong> website.</p>
        <p style="margin: 5px 0 0 0;">You can reply directly to this email to respond to {{ $name }}.</p>
    </div>
</body>
</html>
