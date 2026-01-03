# 📱 SMS Marketing System - Complete Guide

## 🎯 Overview

A comprehensive SMS system integrated into the Super Admin dashboard for sending marketing messages, notifications, and notices. Built with Firebase integration and support for multiple SMS providers.

---

## ✨ Features

### 1. **SMS Dashboard**
- Real-time statistics (sent, delivered, failed)
- Interactive charts (delivery trends, status distribution)
- Recent message logs
- Campaign overview
- Credit balance monitoring

### 2. **Template Management**
- Create reusable SMS templates
- Variable support ({name}, {order_id}, etc.)
- Template types: Marketing, Notification, Transactional, OTP
- Easy template selection when sending

### 3. **Campaign Management**
- Bulk SMS campaigns
- Recipient targeting (All, Active, Trial, Pending, Custom)
- Schedule campaigns (Send now or later)
- Campaign analytics and tracking
- Success rate monitoring

### 4. **Single SMS**
- Send individual SMS messages
- Template integration
- Real-time character count
- Live preview
- Phone number validation

### 5. **SMS Logs**
- Complete message history
- Delivery status tracking
- Cost per message
- Search and filter capabilities
- Export functionality

### 6. **Credits Management**
- Real-time balance tracking
- Add credits easily
- Quick purchase buttons (100, 500, 1000, 5000)
- Usage statistics
- Cost per SMS configuration

---

## 🗄️ Database Structure

### Tables Created:

1. **sms_templates** - Reusable message templates
2. **sms_campaigns** - Bulk SMS campaigns
3. **sms_messages** - Individual SMS logs
4. **sms_statistics** - Daily aggregated stats
5. **sms_credits** - Credit balance management

---

## 🔧 Configuration

### 1. Firebase Setup

Add to `.env`:
```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_API_KEY=your-api-key
FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com
FIREBASE_DATABASE_URL=https://your-project.firebaseio.com
FIREBASE_STORAGE_BUCKET=your-project.appspot.com
FIREBASE_MESSAGING_SENDER_ID=your-sender-id
FIREBASE_APP_ID=your-app-id
FIREBASE_SERVER_KEY=your-server-key
SMS_PROVIDER=firebase
```

### 2. Alternative: Twilio Setup

```env
SMS_PROVIDER=twilio
TWILIO_SID=your-twilio-sid
TWILIO_TOKEN=your-twilio-token
TWILIO_FROM=+1234567890
```

---

## 📍 Routes

All routes are prefixed with `/super/sms/`:

| Method | URL | Purpose |
|--------|-----|---------|
| GET | `/super/sms` | SMS Dashboard |
| GET | `/super/sms/templates` | Manage templates |
| POST | `/super/sms/templates` | Create template |
| DELETE | `/super/sms/templates/{id}` | Delete template |
| GET | `/super/sms/campaigns` | View campaigns |
| GET | `/super/sms/campaigns/create` | Create campaign form |
| POST | `/super/sms/campaigns` | Store campaign |
| GET | `/super/sms/send-single` | Send single SMS form |
| POST | `/super/sms/send-single` | Send single SMS |
| GET | `/super/sms/logs` | View SMS logs |
| GET | `/super/sms/credits` | Manage credits |
| POST | `/super/sms/credits/add` | Add credits |

---

## 🚀 Usage Guide

### Creating SMS Templates

1. Navigate to **SMS System → Templates**
2. Click "New Template"
3. Fill in:
   - Template Name
   - Type (Marketing/Notification/Transactional/OTP)
   - Content (Use {variables} for dynamic data)
4. Click "Create Template"

**Example Template:**
```
Hi {name}! Your order #{order_id} has been confirmed. Total: Rs.{amount}. Thank you for shopping with us!
```

### Sending Single SMS

1. Go to **SMS System → Send SMS**
2. Enter phone number (with country code)
3. Optionally select a template
4. Write/edit message (max 160 chars)
5. Preview and send

### Creating SMS Campaign

1. Navigate to **SMS System → Campaigns**
2. Click "New Campaign"
3. Configure:
   - Campaign Name
   - Recipients (All/Active/Trial/Pending/Custom)
   - Message content
   - Schedule (Now/Later)
4. Click "Create Campaign"

### Managing Credits

1. Go to **SMS System → Credits**
2. View current balance
3. Enter amount to add
4. Or use quick purchase buttons
5. Click "Add Credits"

**Initial Balance:** 1000 free credits
**Cost per SMS:** Rs. 0.05 (configurable)

---

## 💡 Advanced Features

### Variable Support

Use these variables in templates:
- `{name}` - Recipient name
- `{business_name}` - Business/tenant name
- `{order_id}` - Order number
- `{amount}` - Order amount
- `{status}` - Order status
- `{date}` - Current date
- Custom variables as needed

### Recipient Targeting

**All Vendors**: Send to everyone
**Active Vendors**: Only active tenants
**Trial Vendors**: Only trial period tenants
**Pending Vendors**: Only pending approval
**Custom**: Comma-separated phone numbers

### Scheduling

- **Send Now**: Immediate delivery
- **Schedule for Later**: Pick date and time

### Analytics

Track campaign performance:
- Total recipients
- Messages sent
- Delivery rate
- Failed messages
- Total cost
- Success rate percentage

---

## 🔐 Security

- Phone number validation
- Credit balance checks
- Rate limiting (configurable)
- Audit logging
- Secure API integration

---

## 📊 Reports & Analytics

### Dashboard Metrics:
- Total SMS sent
- Delivery success rate
- Failed messages count
- Active campaigns
- Credit balance
- Total cost

### Monthly Trends:
- 6-month SMS volume chart
- Status distribution pie chart
- Cost analysis

---

## 🛠️ Customization

### Modify SMS Provider

Edit `app/Services/FirebaseSmsService.php`:

```php
protected function sendViaSmsGateway(string $phoneNumber, string $message): array
{
    // Add your SMS provider integration here
    // Examples: Twilio, Nexmo, AWS SNS, etc.
}
```

### Change Cost Per SMS

Update in database:
```sql
UPDATE sms_credits SET cost_per_sms = 0.10 WHERE id = 1;
```

Or via admin interface in Credits page.

### Add Custom Templates

Default templates can be seeded:
```php
SmsTemplate::create([
    'name' => 'Welcome Message',
    'slug' => 'welcome-message',
    'content' => 'Welcome to our platform, {name}!',
    'type' => 'notification',
    'is_active' => true,
]);
```

---

## 🐛 Troubleshooting

### SMS Not Sending

1. Check credit balance
2. Verify phone number format (+country code)
3. Check Firebase/Twilio credentials
4. Review error logs: `storage/logs/laravel.log`

### Campaign Stuck

1. Check scheduled time
2. Verify recipient count
3. Check credit balance
4. Review campaign status

### Low Delivery Rate

1. Validate phone numbers
2. Check provider status
3. Review message content (avoid spam words)
4. Verify sender ID

---

## 📞 Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review SMS logs in dashboard
- Contact support team

---

## 🎉 Success Metrics

Monitor these KPIs:
- ✅ Delivery Rate: > 95%
- ✅ Open Rate: Track via links
- ✅ Response Rate: Monitor replies
- ✅ Cost per SMS: Optimize pricing
- ✅ Campaign ROI: Track conversions

---

## 🔄 Future Enhancements

Potential additions:
- Two-way SMS (replies)
- SMS automation workflows
- A/B testing campaigns
- Advanced segmentation
- SMS chatbots
- Integration with CRM
- Scheduled recurring campaigns
- Multi-language support

---

## ✅ Checklist for Launch

- [ ] Configure Firebase/Twilio credentials
- [ ] Test SMS delivery
- [ ] Create initial templates
- [ ] Add credits
- [ ] Train team on usage
- [ ] Set up monitoring
- [ ] Configure backup provider

---

**System Version:** 1.0.0  
**Last Updated:** October 2025  
**Platform:** E-Manager Super Admin

---

*Happy SMS Marketing! 🚀*
