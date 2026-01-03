# E-Manager - Quick Start Guide

## 🚀 Getting Started

### 1. Access the Admin Panel
- URL: `http://localhost/e-manager/admin/login`
- Email: `admin@example.com`
- Password: `password`

### 2. Access Delivery Boy Panel
- URL: `http://localhost/e-manager/delivery-boy/login`
- Phone: `+923001234567` (or DB001-DB005)
- Password: `password123`

---

## 📋 Complete Order Workflow

### Step 1: Order Creation
1. Go to **Pending Orders** → Click **Create Order**
2. Or orders come from website automatically

### Step 2: Order Confirmation
1. Go to **Pending Orders**
2. Select order(s)
3. Click **Confirm** (moves to Orders menu)
4. Or click **Reject** (moves to Rejected Orders)

### Step 3: Delivery Allocation
1. Go to **Manual Delivery** → **Order Allocation**
2. Select confirmed order(s)
3. Choose delivery boy
4. Click **Allocate**

### Step 4: Delivery Boy Handles Order
1. Delivery boy logs in at `/delivery-boy/login`
2. Views assigned deliveries on dashboard
3. Updates status: **Assigned → Picked Up → In Transit → Delivered**
4. Marks COD as collected (if applicable)
5. Uploads delivery proof

### Step 5: COD Settlement (if COD order)
1. Admin goes to **Manual Delivery** → **COD Settlements**
2. Views pending COD by delivery boy
3. Clicks **Settle** next to delivery boy
4. Selects orders to settle
5. Chooses payment method
6. Creates settlement

---

## 🔑 Key Features

### Manual Delivery System
- **Allocation**: `/admin/manual-delivery/allocation`
- **Boy-Wise Orders**: `/admin/manual-delivery/delivery-boy-wise`
- **COD Settlements**: `/admin/manual-delivery/cod-settlements`
- **Performance**: `/admin/manual-delivery/performance`
- **Analytics**: `/admin/manual-delivery/delivery-boy/{id}/analytics`

### Gaaubesi Logistics
- **Dashboard**: `/admin/gaaubesi`
- **Create Shipment**: `/admin/gaaubesi/create`
- Features: Track shipments, download labels, add comments

### Accounting
- **Dashboard**: `/admin/accounting`
- **Accounts**: Create chart of accounts
- **Sales**: Generate invoices
- **Purchases**: Record purchases
- **Expenses**: Track expenses
- **Payments**: Record payments
- **Reports**: View financial reports

### Inventory
- **Dashboard**: `/admin/inventory`
- Features: Stock tracking, bulk updates, low stock alerts

---

## 👥 User Roles & Access

### Admin
- Full system access
- Order management
- Delivery allocation
- COD settlements
- Accounting
- Reports

### Delivery Boy
- Personal dashboard
- View assigned deliveries
- Update delivery status
- Upload proof
- Mark COD collected
- View profile & stats

---

## 📊 Reports & Analytics

### Manual Delivery Performance
- Daily delivery metrics
- Delivery boy comparison
- Success rate tracking
- Revenue by period

### Delivery Boy Analytics
- Individual performance
- COD collected vs settled
- Success rate
- Delivery history

### Accounting Reports
- Profit & Loss
- Balance Sheet
- Cash Flow
- Transaction ledger

---

## 🔄 Status Flow

### Order Status:
`pending` → `confirmed` → `processing` → `shipped` → `completed`
                ↓
            `rejected`

### Delivery Status:
`assigned` → `picked_up` → `in_transit` → `delivered`
                                         ↓
                                    `cancelled`

### COD Status:
`collected` → `pending_settlement` → `settled`

### Invoice Status:
`pending` → `paid` (or `cancelled`)

---

## 🎯 Quick Actions

### Create Manual Order:
1. Pending Orders → Create Order
2. Fill customer details
3. Add products
4. Submit

### Allocate to Manual Delivery:
1. Manual Delivery → Allocation
2. Select order(s)
3. Choose delivery boy
4. Allocate

### Settle COD:
1. Manual Delivery → COD Settlements
2. Click Settle for delivery boy
3. Select deliveries
4. Choose payment method
5. Submit

### Create Invoice:
1. Accounting → Sales
2. Create Invoice
3. Select customer
4. Add items
5. Generate

### Record Payment:
1. Accounting → Payments
2. Click Record Payment
3. Select invoice
4. Enter amount & method
5. Submit

---

## 🐛 Troubleshooting

### Issue: Can't login as delivery boy
**Solution**: Use phone number (e.g., +923001234567) and password: password123

### Issue: Order not showing in allocation
**Solution**: Make sure order status is "confirmed" and not already allocated

### Issue: COD not showing in settlements
**Solution**: Delivery must be "delivered" and COD must be marked as "collected"

### Issue: Routes not found
**Solution**: Run: `php artisan optimize`

### Issue: Views not updating
**Solution**: Run: `php artisan view:clear`

---

## 📱 Mobile Access

All interfaces are responsive and work on:
- Desktop
- Tablet
- Mobile phones

Delivery boys can use their mobile phones to:
- Login
- View deliveries
- Update status
- Upload proof
- Check stats

---

## 🔐 Security Features

- Separate authentication for admin and delivery boys
- Role-based access control
- Session management
- Password encryption
- Activity logging (delivery boys)
- IP address tracking

---

## 📈 Performance Optimization

The system has been optimized with:
- Route caching
- Config caching
- View compilation
- Database indexing
- Eager loading relationships

---

## 💡 Tips & Best Practices

1. **Always confirm orders** before allocating for delivery
2. **Settle COD regularly** to maintain accurate records
3. **Check performance metrics** to identify top delivery boys
4. **Review rejected orders** to understand issues
5. **Generate reports monthly** for financial tracking
6. **Keep delivery boy profiles updated** with current zones
7. **Use bulk operations** to save time
8. **Track all expenses** for accurate accounting

---

## 📞 Support

For issues or questions:
1. Check the SYSTEM_STATUS.md file
2. Review error logs in `storage/logs/`
3. Clear caches: `php artisan optimize:clear`

---

**Last Updated:** October 13, 2025  
**Version:** 1.0.0  
**Status:** Production Ready ✅







