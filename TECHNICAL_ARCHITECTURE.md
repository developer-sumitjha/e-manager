# 🏛️ MULTI-TENANT SAAS - TECHNICAL ARCHITECTURE

## 🎯 SYSTEM OVERVIEW

E-Manager is transformed into a multi-tenant SaaS platform where each vendor gets:
- Isolated database for complete data security
- Subdomain-based access (vendor.emanager.com)
- Subscription-based pricing with trials
- Full business management features

---

## 🗄️ DATABASE ARCHITECTURE

### Central Platform Database: `emanager`

#### Core Tables

**1. `tenants`**
Stores all vendor business information
```sql
- id, tenant_id (TEN0001)
- business_name, business_email, subdomain
- owner credentials
- current_plan_id
- status (pending, trial, active, suspended)
- trial_ends_at, subscription dates
- database connection details
- settings, features (JSON)
- max limits (orders, products, users)
```

**2. `subscription_plans`**
Available pricing tiers
```sql
- id, plan_id (PLAN-FREE, PLAN-STARTER, etc.)
- name, slug, description
- price_monthly, price_yearly
- max limits per resource
- feature flags (has_inventory, has_accounting, etc.)
- trial_days, sort_order
```

**3. `subscriptions`**
Active subscriptions per tenant
```sql
- id, subscription_id
- tenant_id, plan_id
- billing_cycle (monthly/yearly)
- starts_at, ends_at, trial_ends_at
- amount, currency
- status (trial, active, expired, etc.)
- auto_renew, next_billing_date
```

**4. `super_admins`**
Platform administrators
```sql
- id, name, email, password
- role (super_admin, support, billing)
- is_active, last_login info
```

**5. `tenant_payments`**
All payment transactions
```sql
- id, payment_id
- tenant_id, subscription_id, invoice_id
- amount, currency, payment_method
- status (pending, completed, failed)
- transaction_id, gateway_response
```

**6. `tenant_invoices`**
Billing invoices
```sql
- id, invoice_number
- tenant_id, subscription_id
- subtotal, tax, total
- status (draft, sent, paid, overdue)
- items (JSON)
```

**7. `tenant_activities`**
Audit log for all tenant actions
```sql
- id, tenant_id
- activity_type, description
- metadata (JSON)
- ip_address, user_agent
```

---

### Tenant Databases: `tenant_TEN0001`, `tenant_TEN0002`, etc.

Each tenant gets a complete copy of all operational tables:
- users
- products
- categories
- orders
- order_items
- inventory
- shipments
- manual_deliveries
- delivery_boys
- accounting tables
- settings
- etc.

**Isolation:** Complete database-level separation ensures:
- No data leakage between tenants
- Independent backups/restores
- Custom migrations per tenant
- Better security

---

## 🔄 REQUEST FLOW

### 1. Vendor Access Flow

```
User Request: vendor1.emanager.com/admin/orders
    ↓
Apache/Nginx routes to Laravel
    ↓
IdentifyTenant Middleware
    - Extracts subdomain: "vendor1"
    - Finds Tenant in central DB
    - Switches to tenant_TEN0001 database
    ↓
Request continues with tenant context
    ↓
Controller queries tenant database
    ↓
Response returned with tenant data
```

### 2. Signup Flow

```
User: emanager.com/signup
    ↓
Vue.js Signup Component
    ↓
POST /api/tenants/signup
    ↓
TenantController@signup
    - Validates data
    - Creates tenant record in central DB
    - Generates tenant_id
    - Creates tenant database
    - Runs migrations on tenant DB
    - Creates trial subscription
    - Sends welcome email
    ↓
Returns: subdomain URL for login
```

### 3. Super Admin Flow

```
Super Admin: super.emanager.com/login
    ↓
SuperAdmin Guard Authentication
    ↓
Super Admin Dashboard
    - View all tenants
    - Manage subscriptions
    - View payments
    - Analytics
    ↓
Can access ANY tenant data
    - Switch database context
    - View tenant dashboard
    - Manage tenant users
```

---

## 🔐 AUTHENTICATION ARCHITECTURE

### Multiple Guards

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',      // Tenant users
    ],
    'super_admin' => [
        'driver' => 'session',
        'provider' => 'super_admins', // Platform admins
    ],
    'delivery_boy' => [
        'driver' => 'session',
        'provider' => 'delivery_boys', // Delivery riders
    ],
]
```

### Authentication Scopes
- **Super Admin:** Full platform access
- **Tenant Admin:** Own tenant only
- **Delivery Boy:** Own deliveries only

---

## 🛡️ MIDDLEWARE STACK

### Request Processing Order

1. **Global Middleware**
   - EncryptCookies
   - VerifyCsrfToken
   - HandleInertiaRequests

2. **Tenant Middleware** (for vendor.emanager.com)
   - IdentifyTenant
   - CheckTenantStatus
   - EnforceSubscriptionLimits

3. **Auth Middleware**
   - auth:web (tenants)
   - auth:super_admin (platform)
   - auth:delivery_boy (riders)

---

## 💾 DATABASE MANAGEMENT

### TenantManager Service

**Responsibilities:**
- Create tenant databases
- Configure dynamic connections
- Run tenant migrations
- Seed initial data
- Delete tenant databases
- Backup/restore tenant data

**Key Methods:**
```php
$tenantManager->createTenantDatabase($tenant)
$tenantManager->setTenant($tenant)
$tenantManager->deleteTenantDatabase($tenant)
```

### Dynamic Database Switching

```php
// In middleware or controller
$tenant->configureDatabaseConnection();

// Now all queries use tenant database
DB::connection('tenant')->table('orders')->get();

// Or with models
Order::on('tenant')->get();
```

---

## 📊 SUBSCRIPTION MANAGEMENT

### Trial Period
- 14 days for most plans
- 30 days for Enterprise
- Full feature access
- Automatic expiration
- Email reminders at 7 days, 3 days, 1 day

### Billing Cycles
- **Monthly:** Charged every month
- **Yearly:** Charged annually (16-20% discount)

### Subscription States
```
trial → active → expired
         ↓
      cancelled
```

### Auto-Renewal Process
```
1. Subscription end date approaching
2. Generate invoice 7 days before
3. Send payment reminder emails
4. On due date: Charge payment method
5. If successful: Renew subscription
6. If failed: Mark as past_due, retry 3 times
7. If still failed: Suspend tenant
```

---

## 💳 PAYMENT PROCESSING

### Supported Gateways

#### eSewa
- Merchant ID configuration
- Payment verification
- Webhook handling
- Refund support

#### Khalti
- Public/Secret key auth
- Payment initiation
- Status verification
- Transaction tracking

### Payment Flow
```
1. Invoice generated
2. User initiates payment
3. Redirect to gateway
4. Gateway processes payment
5. Callback to webhook
6. Verify payment
7. Update subscription
8. Send receipt email
```

---

## 🔒 SECURITY CONSIDERATIONS

### Tenant Isolation
✅ Database-level separation
✅ No shared tables between tenants
✅ Independent user authentication
✅ Separate session stores

### Data Protection
✅ Encrypted passwords
✅ Secure database credentials
✅ CSRF protection
✅ SQL injection prevention
✅ XSS protection

### Access Control
✅ Role-based permissions
✅ Guard-based authentication
✅ Middleware authorization
✅ API rate limiting

---

## 📈 SCALABILITY

### Performance Optimization
- Database indexing on all foreign keys
- Query optimization with eager loading
- Caching for subscription plans
- CDN for static assets
- Redis for sessions/cache

### Horizontal Scaling
- Database sharding by tenant
- Load balancing for web servers
- Separate database servers
- Queue workers for background jobs

---

## 🧪 TESTING STRATEGY

### Unit Tests
- Model relationships
- Business logic methods
- Helper functions

### Feature Tests
- Tenant signup flow
- Subscription creation
- Payment processing
- Database switching

### Integration Tests
- Multi-tenant isolation
- Payment gateway integration
- Email notifications

---

## 🚀 DEPLOYMENT CHECKLIST

### Prerequisites
- [ ] Domain configured (emanager.com)
- [ ] Wildcard SSL certificate (*.emanager.com)
- [ ] MySQL server with CREATE DATABASE privileges
- [ ] Redis server for caching
- [ ] Email service (SMTP/Mailgun)
- [ ] Payment gateway accounts (eSewa, Khalti)

### Deployment Steps
1. Configure environment variables
2. Run migrations on production
3. Seed subscription plans
4. Create super admin
5. Configure subdomain DNS
6. Setup SSL certificates
7. Configure payment webhooks
8. Test tenant creation
9. Monitor logs

---

## 📊 MONITORING & ANALYTICS

### Platform Metrics
- Total tenants (active, trial, suspended)
- Monthly recurring revenue (MRR)
- Churn rate
- Subscription conversions
- Average revenue per user (ARPU)

### Tenant Metrics (Per Vendor)
- Orders per month
- Revenue generated
- Storage used
- Active users
- Feature usage

### System Health
- Database connections
- Response times
- Error rates
- Queue backlogs

---

## 🔧 MAINTENANCE

### Daily Tasks
- Check failed payments
- Monitor error logs
- Review new signups

### Weekly Tasks
- Send subscription renewal reminders
- Generate usage reports
- Backup central database

### Monthly Tasks
- Review tenant usage limits
- Analyze platform metrics
- Update subscription plans if needed

---

## 📞 SUPPORT SYSTEM

### Super Admin Capabilities
- View any tenant's dashboard
- Manage subscriptions manually
- Process refunds
- Suspend/activate tenants
- Access tenant databases for support

### Tenant Support Tickets
- Built-in ticketing system
- Priority support for Pro/Enterprise
- Knowledge base integration

---

**This technical architecture provides complete guidance for building and maintaining the multi-tenant SaaS platform!**







