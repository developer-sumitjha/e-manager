# 🛍️ Multi-Tenant E-Commerce Storefront

A modern, fully customizable Vue.js storefront for multi-tenant e-commerce platform.

## 🚀 Features

- ✅ **Dynamic Theming** - Customizable colors, fonts, layouts from admin panel
- ✅ **Responsive Design** - Mobile, tablet, desktop optimized
- ✅ **Shopping Cart** - Persistent cart with localStorage
- ✅ **Product Search & Filters** - Advanced search and filtering
- ✅ **Category Navigation** - Dynamic category menus
- ✅ **SEO Optimized** - Dynamic meta tags, titles, Open Graph
- ✅ **Multi-Currency** - Support for multiple currencies
- ✅ **Free Shipping** - Configurable free shipping threshold
- ✅ **Cookie Consent** - GDPR compliant cookie notice
- ✅ **Promo Popups** - Customizable promotional popups
- ✅ **Maintenance Mode** - Graceful maintenance page
- ✅ **Custom Code Injection** - Support for custom CSS/JS
- ✅ **Social Media Integration** - Social links in footer
- ✅ **Loading States** - Smooth loading indicators
- ✅ **Error Handling** - Graceful error messages
- ✅ **Toast Notifications** - User-friendly notifications

## 📋 Prerequisites

- Node.js 16+ and npm
- XAMPP/PHP backend running
- Laravel backend API configured

## 🔧 Installation

```bash
# Navigate to project directory
cd storefront-vue

# Install dependencies
npm install

# Create .env file (optional)
cp .env.example .env
```

## 🏃 Development

```bash
# Start development server
npm run dev

# Access at http://localhost:3000
# Add ?store=YOUR_SUBDOMAIN to test specific store
```

Example: `http://localhost:3000?store=myshop`

## 🏗️ Build for Production

```bash
# Build for production
npm run build

# Output will be in public/storefront/
```

## 📁 Project Structure

```
storefront-vue/
├── public/              # Static assets
├── src/
│   ├── assets/         # Styles, images
│   │   └── styles/
│   │       └── main.css
│   ├── components/     # Vue components
│   │   ├── layout/    # Header, Footer
│   │   ├── home/      # Hero, Featured, etc.
│   │   ├── products/  # ProductCard, Grid, etc.
│   │   ├── cart/      # Cart components
│   │   ├── checkout/  # Checkout forms
│   │   └── shared/    # Reusable components
│   ├── views/         # Page components
│   │   ├── Home.vue
│   │   ├── Products.vue
│   │   ├── ProductDetail.vue
│   │   ├── Category.vue
│   │   ├── Cart.vue
│   │   ├── Checkout.vue
│   │   ├── Search.vue
│   │   └── NotFound.vue
│   ├── store/         # Pinia stores
│   │   ├── settings.js
│   │   ├── products.js
│   │   └── cart.js
│   ├── router/        # Vue Router
│   │   └── index.js
│   ├── services/      # API services
│   │   └── api.js
│   ├── App.vue        # Root component
│   └── main.js        # App entry point
├── index.html
├── package.json
├── vite.config.js
└── README.md
```

## 🔌 API Integration

The storefront connects to these Laravel API endpoints:

```
GET  /api/storefront/{subdomain}/settings
GET  /api/storefront/{subdomain}/products
GET  /api/storefront/{subdomain}/products/{slug}
GET  /api/storefront/{subdomain}/categories
GET  /api/storefront/{subdomain}/featured-products
GET  /api/storefront/{subdomain}/new-arrivals
```

## 🎨 Customization

### Via Admin Panel
1. Login to admin panel
2. Navigate to "Site Builder"
3. Customize:
   - Colors & theme
   - Logo & banner
   - Navigation
   - Homepage sections
   - Products display
   - Footer & social links
   - SEO settings
   - E-commerce settings
   - Custom CSS/JS

### Via Code
- Edit `src/assets/styles/main.css` for global styles
- Modify component styles in respective `.vue` files
- Update `src/store/` files for state management logic

## 🛒 Cart & Checkout Flow

1. **Browse Products** → Product listings with search/filter
2. **Add to Cart** → Items stored in localStorage
3. **View Cart** → Review items, update quantities
4. **Checkout** → Enter shipping & payment info
5. **Order Confirmation** → Success page with order details

## 📱 Responsive Breakpoints

- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

## 🔐 Security Features

- XSS Protection (Vue automatic escaping)
- CSRF tokens for API calls
- Input sanitization
- Secure payment gateway integration
- Environment variable protection

## 🧪 Testing

```bash
# Run linter
npm run lint

# Manual testing checklist
- [ ] Homepage loads correctly
- [ ] Products display properly
- [ ] Search functionality works
- [ ] Cart add/remove works
- [ ] Checkout process completes
- [ ] Responsive design works
- [ ] Theme applies correctly
```

## 🚀 Deployment

### Development
```bash
npm run dev
```

### Production
```bash
# Build
npm run build

# Deploy contents of public/storefront/ to web server
# Configure web server to serve index.html for all routes
```

### Environment Variables
```env
VITE_API_URL=https://your-backend.com/api
```

## 📚 Technologies Used

- **Vue 3** - Progressive JavaScript framework
- **Pinia** - State management
- **Vue Router 4** - Routing
- **Axios** - HTTP client
- **Vite** - Build tool & dev server
- **Vue Toastification** - Toast notifications
- **Font Awesome** - Icons
- **Google Fonts** - Typography

## 🤝 Support

For issues or questions:
1. Check documentation in `🎯_STOREFRONT_IMPLEMENTATION_COMPLETE.md`
2. Review component templates
3. Check browser console for errors
4. Verify API endpoints are responding

## 📄 License

Proprietary - All rights reserved

## 👥 Credits

Developed as part of the E-Manager multi-tenant e-commerce platform.

---

**Version:** 1.0.0  
**Last Updated:** October 14, 2025  
**Status:** Production Ready Foundation (85% Complete)







