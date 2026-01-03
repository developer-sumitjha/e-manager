# 🏗️ Vendor Storefront & Site Builder - Architecture Document

## 📋 Executive Summary

A comprehensive multi-tenant e-commerce storefront system with a powerful drag-and-drop site builder that allows vendors to fully customize their online stores without coding knowledge.

---

## ✅ Current Implementation Status

### **Completed (Phase 1):**

✅ **Database Schema** - `site_settings` table with 90+ customization fields  
✅ **SiteSettings Model** - Full Laravel model with relationships  
✅ **API Controller** - Storefront API with 6 endpoints  
✅ **Multi-tenant Support** - Subdomain-based tenant isolation  

### **Pending:**

⏳ Admin Site Builder Controller  
⏳ Admin Site Builder UI  
⏳ Vue.js Storefront Application  
⏳ Live Preview System  
⏳ Image Upload System  

---

## 🎯 System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    TENANT/VENDOR ADMIN PANEL                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │              SITE BUILDER INTERFACE                      │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │  • Theme Selection                                       │  │
│  │  • Color Customization (7 colors)                        │  │
│  │  • Logo & Favicon Upload                                 │  │
│  │  • Banner/Hero Section Editor                            │  │
│  │  • Navigation Menu Builder                               │  │
│  │  • Homepage Sections Manager                             │  │
│  │  • Footer Customization                                  │  │
│  │  • SEO Settings                                          │  │
│  │  • E-commerce Configuration                              │  │
│  │  • Custom CSS/JS Injection                               │  │
│  │  • Live Preview Button                                   │  │
│  └──────────────────────────────────────────────────────────┘  │
│                           ↓                                     │
│                    Saves to Database                            │
│                           ↓                                     │
└─────────────────────────────────────────────────────────────────┘
                             ↓
┌─────────────────────────────────────────────────────────────────┐
│                    LARAVEL BACKEND API                          │
├─────────────────────────────────────────────────────────────────┤
│  • StorefrontController (GET settings, products, categories)    │
│  • SiteBuilderController (POST/PUT settings updates)            │
│  • SiteSettings Model                                           │
│  • Tenant-based data isolation                                  │
└─────────────────────────────────────────────────────────────────┘
                             ↓
┌─────────────────────────────────────────────────────────────────┐
│                    VUE.JS STOREFRONT (SPA)                      │
├─────────────────────────────────────────────────────────────────┤
│  Components:                                                    │
│  ├─ Header (dynamic colors, logo, navigation)                   │
│  ├─ Hero/Banner (customizable image, text, CTA)                 │
│  ├─ ProductGrid (featured, new, filtered)                       │
│  ├─ ProductCard (multiple styles)                               │
│  ├─ ProductDetail (with related products)                       │
│  ├─ Cart (persistent)                                           │
│  ├─ Checkout (multi-step)                                       │
│  ├─ CategoryNav (from database)                                 │
│  └─ Footer (dynamic content, social links)                      │
│                                                                 │
│  Features:                                                      │
│  • Reactive theming (applies saved colors)                      │
│  • Product search & filtering                                   │
│  • Cart management (localStorage)                               │
│  • Responsive design                                            │
│  • SEO-friendly                                                 │
└─────────────────────────────────────────────────────────────────┘
                             ↓
┌─────────────────────────────────────────────────────────────────┐
│                         END USER                                │
│                    (Customer browsing store)                    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📊 Database Schema

### **site_settings Table (90+ Fields)**

```sql
CREATE TABLE site_settings (
    id BIGINT PRIMARY KEY,
    tenant_id BIGINT UNIQUE,
    
    -- Basic Info (8 fields)
    site_name VARCHAR(255),
    site_tagline VARCHAR(255),
    site_description TEXT,
    logo VARCHAR(255),
    favicon VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(255),
    address TEXT,
    
    -- Theme & Colors (9 fields)
    theme VARCHAR(50),
    layout VARCHAR(50),
    primary_color VARCHAR(7),
    secondary_color VARCHAR(7),
    accent_color VARCHAR(7),
    text_color VARCHAR(7),
    background_color VARCHAR(7),
    header_bg_color VARCHAR(7),
    footer_bg_color VARCHAR(7),
    
    -- Typography (3 fields)
    font_family VARCHAR(255),
    font_size INT,
    heading_font VARCHAR(255),
    
    -- Banner (6 fields)
    banner_image VARCHAR(255),
    banner_title VARCHAR(255),
    banner_subtitle VARCHAR(255),
    banner_button_text VARCHAR(255),
    banner_button_link VARCHAR(255),
    show_banner BOOLEAN,
    
    -- Navigation (4 fields)
    navigation_links JSON,
    show_categories_menu BOOLEAN,
    show_search_bar BOOLEAN,
    show_cart_icon BOOLEAN,
    
    -- Homepage Sections (6 fields)
    show_featured_products BOOLEAN,
    show_new_arrivals BOOLEAN,
    show_categories BOOLEAN,
    show_testimonials BOOLEAN,
    show_about_section BOOLEAN,
    homepage_sections_order JSON,
    
    -- Product Display (5 fields)
    product_card_style VARCHAR(50),
    products_per_page INT,
    show_product_ratings BOOLEAN,
    show_quick_view BOOLEAN,
    show_add_to_cart_button BOOLEAN,
    
    -- Footer (12 fields)
    footer_about TEXT,
    footer_links JSON,
    show_social_links BOOLEAN,
    facebook_url VARCHAR(255),
    instagram_url VARCHAR(255),
    twitter_url VARCHAR(255),
    youtube_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    
    -- SEO (6 fields)
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    og_image VARCHAR(255),
    google_analytics_code TEXT,
    facebook_pixel_code TEXT,
    
    -- E-commerce (10 fields)
    currency VARCHAR(10),
    currency_symbol VARCHAR(10),
    currency_position VARCHAR(10),
    enable_guest_checkout BOOLEAN,
    enable_reviews BOOLEAN,
    enable_wishlist BOOLEAN,
    min_order_amount DECIMAL(10,2),
    shipping_cost DECIMAL(10,2),
    free_shipping_over BOOLEAN,
    free_shipping_amount DECIMAL(10,2),
    
    -- Popups (5 fields)
    show_cookie_notice BOOLEAN,
    cookie_notice_text TEXT,
    show_promo_popup BOOLEAN,
    promo_popup_content TEXT,
    promo_popup_image VARCHAR(255),
    
    -- Custom Code (4 fields)
    custom_css TEXT,
    custom_js TEXT,
    header_code TEXT,
    footer_code TEXT,
    
    -- Maintenance (3 fields)
    is_active BOOLEAN,
    maintenance_mode BOOLEAN,
    maintenance_message TEXT,
    
    -- Flexible (1 field)
    additional_settings JSON,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 🔌 API Endpoints

### **Public Storefront API**

```
GET /api/storefront/{subdomain}/settings
    Returns: Site customization settings

GET /api/storefront/{subdomain}/products
    Query params: search, category, min_price, max_price, featured, sort, page
    Returns: Paginated products

GET /api/storefront/{subdomain}/products/{slug}
    Returns: Single product with related products

GET /api/storefront/{subdomain}/categories
    Returns: All categories with product counts

GET /api/storefront/{subdomain}/featured-products
    Returns: Featured products (limit 8)

GET /api/storefront/{subdomain}/new-arrivals
    Returns: Latest products (limit 8)
```

### **Admin Site Builder API** (To be implemented)

```
GET /admin/site-builder
    Returns: Current site settings

POST /admin/site-builder/basic-info
    Body: {site_name, site_tagline, logo, etc.}
    Returns: Updated settings

POST /admin/site-builder/theme
    Body: {theme, layout, colors}
    Returns: Updated settings

POST /admin/site-builder/banner
    Body: {banner_image, banner_title, etc.}
    Returns: Updated settings

POST /admin/site-builder/navigation
    Body: {navigation_links, toggles}
    Returns: Updated settings

POST /admin/site-builder/homepage
    Body: {section_visibility, order}
    Returns: Updated settings

POST /admin/site-builder/footer
    Body: {footer_about, social_links}
    Returns: Updated settings

POST /admin/site-builder/seo
    Body: {meta_title, meta_description, etc.}
    Returns: Updated settings

POST /admin/site-builder/ecommerce
    Body: {currency, shipping, etc.}
    Returns: Updated settings

POST /admin/site-builder/custom-code
    Body: {custom_css, custom_js}
    Returns: Updated settings

GET /admin/site-builder/preview
    Returns: Preview URL
```

---

## 🎨 Admin Site Builder UI Structure

### **Navigation Tabs**

```
┌─────────────────────────────────────────────────────────────────┐
│  Site Builder                                                   │
├─────────────────────────────────────────────────────────────────┤
│  [General] [Theme] [Banner] [Navigation] [Homepage] [Products]  │
│  [Footer] [SEO] [E-commerce] [Advanced] [Preview]              │
└─────────────────────────────────────────────────────────────────┘
```

### **Tab Contents**

#### 1. **General Tab**
- Site Name (text input)
- Tagline (text input)
- Description (textarea)
- Logo Upload (file upload with preview)
- Favicon Upload (file upload with preview)
- Contact Email (email input)
- Contact Phone (tel input)
- Address (textarea)

#### 2. **Theme Tab**
- Theme Selection (radio buttons: Modern, Classic, Minimal)
- Layout (radio buttons: Default, Sidebar, Fullwidth)
- Primary Color (color picker)
- Secondary Color (color picker)
- Accent Color (color picker)
- Text Color (color picker)
- Background Color (color picker)
- Header Background (color picker)
- Footer Background (color picker)
- Font Family (dropdown)
- Font Size (range slider)
- Heading Font (dropdown)

#### 3. **Banner Tab**
- Banner Image Upload
- Banner Title (text input)
- Banner Subtitle (textarea)
- Button Text (text input)
- Button Link (URL input)
- Show Banner (toggle switch)

#### 4. **Navigation Tab**
- Custom Menu Links (repeater field)
  - Link Text
  - Link URL
  - Open in New Tab
- Show Categories Menu (toggle)
- Show Search Bar (toggle)
- Show Cart Icon (toggle)

#### 5. **Homepage Tab**
- Section Visibility Toggles:
  - Featured Products
  - New Arrivals
  - Categories
  - Testimonials
  - About Section
- Section Order (drag & drop sortable list)

#### 6. **Products Tab**
- Card Style (select: Card, Grid, List)
- Products Per Page (number input)
- Show Ratings (toggle)
- Show Quick View (toggle)
- Show Add to Cart Button (toggle)

#### 7. **Footer Tab**
- About Text (wysiwyg editor)
- Footer Links (repeater field)
- Show Social Links (toggle)
- Social Media URLs:
  - Facebook
  - Instagram
  - Twitter
  - YouTube
  - LinkedIn

#### 8. **SEO Tab**
- Meta Title (text input)
- Meta Description (textarea)
- Meta Keywords (text input)
- OG Image Upload
- Google Analytics Code (textarea)
- Facebook Pixel Code (textarea)

#### 9. **E-commerce Tab**
- Currency (dropdown)
- Currency Symbol (text input)
- Currency Position (radio: Before/After)
- Enable Guest Checkout (toggle)
- Enable Reviews (toggle)
- Enable Wishlist (toggle)
- Minimum Order Amount (number input)
- Shipping Cost (number input)
- Free Shipping Threshold (number input)

#### 10. **Advanced Tab**
- Custom CSS (code editor)
- Custom JavaScript (code editor)
- Header Code (textarea)
- Footer Code (textarea)
- Cookie Notice (toggle + text)
- Promo Popup (toggle + content + image)
- Maintenance Mode (toggle + message)

#### 11. **Preview Tab**
- Live Preview iframe
- Device Size Toggles (Desktop/Tablet/Mobile)
- Refresh Preview Button
- Open in New Tab Button

---

## 💻 Vue.js Storefront Structure

### **Project Structure**

```
storefront/
├── public/
│   ├── index.html
│   └── favicon.ico
├── src/
│   ├── main.js
│   ├── App.vue
│   ├── router/
│   │   └── index.js
│   ├── store/
│   │   ├── index.js
│   │   ├── modules/
│   │   │   ├── settings.js
│   │   │   ├── products.js
│   │   │   ├── cart.js
│   │   │   └── categories.js
│   ├── components/
│   │   ├── layout/
│   │   │   ├── Header.vue
│   │   │   ├── Navigation.vue
│   │   │   ├── Footer.vue
│   │   │   └── Sidebar.vue
│   │   ├── home/
│   │   │   ├── HeroBanner.vue
│   │   │   ├── FeaturedProducts.vue
│   │   │   ├── NewArrivals.vue
│   │   │   ├── CategoriesGrid.vue
│   │   │   └── AboutSection.vue
│   │   ├── products/
│   │   │   ├── ProductGrid.vue
│   │   │   ├── ProductCard.vue
│   │   │   ├── ProductDetail.vue
│   │   │   ├── ProductFilters.vue
│   │   │   └── ProductQuickView.vue
│   │   ├── cart/
│   │   │   ├── Cart.vue
│   │   │   ├── CartItem.vue
│   │   │   └── CartSummary.vue
│   │   ├── checkout/
│   │   │   ├── Checkout.vue
│   │   │   ├── ShippingForm.vue
│   │   │   ├── PaymentForm.vue
│   │   │   └── OrderSummary.vue
│   │   └── shared/
│   │       ├── Loading.vue
│   │       ├── ErrorMessage.vue
│   │       ├── Pagination.vue
│   │       └── SearchBar.vue
│   ├── views/
│   │   ├── Home.vue
│   │   ├── Products.vue
│   │   ├── ProductDetail.vue
│   │   ├── Category.vue
│   │   ├── Cart.vue
│   │   ├── Checkout.vue
│   │   └── NotFound.vue
│   ├── services/
│   │   ├── api.js
│   │   ├── settings.js
│   │   ├── products.js
│   │   └── cart.js
│   ├── utils/
│   │   ├── currency.js
│   │   ├── helpers.js
│   │   └── validators.js
│   └── assets/
│       ├── styles/
│       │   ├── main.css
│       │   ├── themes.css
│       │   └── animations.css
│       └── images/
├── package.json
└── vue.config.js
```

### **Key Components**

#### **Header.vue**
```vue
<template>
  <header :style="headerStyle">
    <div class="container">
      <img :src="settings.logo" :alt="settings.site_name" />
      <Navigation :links="settings.navigation_links" />
      <SearchBar v-if="settings.show_search_bar" />
      <Cart v-if="settings.show_cart_icon" />
    </div>
  </header>
</template>

<script>
export default {
  computed: {
    settings() {
      return this.$store.state.settings.data;
    },
    headerStyle() {
      return {
        backgroundColor: this.settings.header_bg_color,
        color: this.settings.text_color
      };
    }
  }
};
</script>
```

#### **ProductCard.vue**
```vue
<template>
  <div class="product-card" :class="cardStyle">
    <img :src="product.image" :alt="product.name" />
    <h3>{{ product.name }}</h3>
    <p class="price">{{ formatPrice(product.price) }}</p>
    <button 
      v-if="settings.show_add_to_cart_button"
      @click="addToCart"
      :style="buttonStyle"
    >
      Add to Cart
    </button>
  </div>
</template>

<script>
export default {
  props: ['product'],
  computed: {
    settings() {
      return this.$store.state.settings.data;
    },
    cardStyle() {
      return this.settings.product_card_style;
    },
    buttonStyle() {
      return {
        backgroundColor: this.settings.primary_color,
        color: '#ffffff'
      };
    }
  },
  methods: {
    formatPrice(price) {
      const symbol = this.settings.currency_symbol;
      const position = this.settings.currency_position;
      return position === 'before' ? `${symbol}${price}` : `${price}${symbol}`;
    },
    addToCart() {
      this.$store.dispatch('cart/add', this.product);
    }
  }
};
</script>
```

---

## 🚀 Implementation Steps

### **Phase 1: Backend Foundation** ✅ COMPLETED
1. ✅ Create `site_settings` table migration
2. ✅ Create `SiteSettings` model
3. ✅ Create `StorefrontController` API

### **Phase 2: Admin Site Builder** (Next)
4. Create `SiteBuilderController`
5. Create admin routes
6. Create Site Builder UI views (Blade)
7. Add file upload functionality
8. Add color picker components
9. Add form validation
10. Add live preview iframe

### **Phase 3: Vue.js Storefront** (After Phase 2)
11. Setup Vue.js project
12. Configure Vue Router
13. Setup Vuex store
14. Create API service layer
15. Create layout components (Header, Footer)
16. Create product components
17. Create cart functionality
18. Create checkout process
19. Add responsive design
20. Add animations & transitions

### **Phase 4: Integration & Testing**
21. Connect admin builder to API
22. Test all customization options
23. Test storefront rendering
24. Mobile responsiveness testing
25. Performance optimization
26. SEO optimization

---

## 🎨 Design Principles

1. **Mobile-First**: Responsive design from the start
2. **Performance**: Lazy loading, code splitting
3. **SEO-Friendly**: Server-side rendering consideration
4. **Accessibility**: WCAG 2.1 AA compliance
5. **User-Friendly**: Intuitive admin interface
6. **Flexible**: Easy to extend with new features

---

## 📦 Technology Stack

**Backend:**
- Laravel 10+
- MySQL 8.0+
- RESTful API
- Multi-tenancy support

**Frontend:**
- Vue.js 3
- Vue Router 4
- Vuex or Pinia
- Axios
- Tailwind CSS or Bootstrap 5
- Chart.js (for admin analytics)

**Tools:**
- Vite (build tool)
- Composer (PHP dependencies)
- NPM (JS dependencies)

---

## 🔐 Security Considerations

1. **Input Validation**: All form inputs validated
2. **XSS Protection**: Sanitize user HTML/CSS/JS
3. **CSRF Protection**: Laravel tokens
4. **File Upload Security**: Type/size validation
5. **SQL Injection**: Laravel Eloquent ORM
6. **Authentication**: Laravel Sanctum for API
7. **Rate Limiting**: API throttling

---

## 🎯 Success Metrics

- **Vendor Satisfaction**: Easy to use, powerful customization
- **Page Load Time**: < 3 seconds
- **Mobile Score**: > 90 on Lighthouse
- **Conversion Rate**: Track improvement
- **Time to Customize**: < 30 minutes for full setup

---

## 💡 Future Enhancements

1. Drag-and-drop page builder
2. Pre-built templates library
3. A/B testing functionality
4. Advanced analytics dashboard
5. Email marketing integration
6. Social media integration
7. Multi-language support
8. Mobile app (React Native)
9. AI-powered product recommendations
10. Chatbot integration

---

## 📞 Support & Documentation

- API Documentation: Swagger/OpenAPI
- Component Documentation: Storybook
- User Guide: In-app tooltips + PDF
- Video Tutorials: YouTube channel
- Developer Docs: GitHub Wiki

---

**Created**: October 14, 2025  
**Status**: Phase 1 Complete, Phase 2 Ready to Start  
**Estimated Completion**: 40-60 hours for full implementation  

---

*This is a living document that will be updated as development progresses.*






