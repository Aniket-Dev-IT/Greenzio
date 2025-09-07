# 🌱 Greenzio - Premium Grocery E-Commerce Platform

<div align="center">
  <img src="Project Snap/homepage.png" alt="Greenzio Homepage" width="800"/>
</div>

## 📋 Table of Contents
- [Project Overview](#-project-overview)
- [Key Features](#-key-features)
- [Technology Stack](#-technology-stack)
- [System Requirements](#-system-requirements)
- [Installation Guide](#-installation-guide)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [Usage Instructions](#-usage-instructions)
- [Admin Panel](#-admin-panel)
- [API Documentation](#-api-documentation)
- [Screenshots](#-screenshots)
- [Security Features](#-security-features)
- [Performance Optimization](#-performance-optimization)
- [License](#-license)
- [Developer Information](#-developer-information)

## 🎯 Project Overview

Greenzio is a sophisticated, full-featured e-commerce platform specifically designed for the grocery industry. Built with modern web technologies and following industry best practices, it provides a seamless shopping experience for customers while offering comprehensive management tools for administrators.

The platform handles everything from product catalog management to order processing, inventory tracking, and customer relationship management, making it a complete solution for grocery businesses of all sizes.

## ✨ Key Features

### 🛒 Customer Experience
- **Smart Product Catalog**: Organized categories including Fresh Produce, Dairy & Bakery, Grains & Rice, Spices & Condiments
- **Advanced Search & Filtering**: Intelligent search with filters by category, brand, price range, and availability
- **Dynamic Shopping Cart**: Real-time cart updates with stock validation and bulk pricing
- **Secure User Authentication**: Registration, login, and profile management with session security
- **Streamlined Checkout Process**: Multi-step checkout with address management and payment integration
- **Order Tracking System**: Complete order lifecycle tracking from placement to delivery
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices
- **Product Reviews & Ratings**: Customer feedback system for products

### 🔧 Administrative Tools
- **Comprehensive Dashboard**: Real-time analytics, sales metrics, and business insights
- **Product Management Suite**: Advanced inventory control with bulk operations
- **Order Processing System**: Complete order management with status tracking
- **Customer Management**: User account administration and customer support tools
- **Inventory Control**: Stock level monitoring with low-stock alerts
- **Sales Analytics**: Detailed reporting with charts and export capabilities
- **Content Management**: Category and product content administration
- **System Settings**: Configurable platform settings and maintenance tools

### 🔐 Security & Performance
- **Data Protection**: XSS and SQL injection prevention
- **Session Management**: Secure session handling with timeout protection
- **Input Validation**: Comprehensive server-side and client-side validation
- **Error Handling**: Robust error management with logging
- **Performance Optimization**: Database query optimization and caching strategies

## 🛠 Technology Stack

| Component | Technology | Version |
|-----------|------------|--------|
| **Backend Framework** | CodeIgniter | 3.1.13 |
| **Frontend** | HTML5, CSS3, JavaScript | Latest |
| **UI Framework** | Bootstrap | 4.5+ |
| **Database** | MySQL | 5.7+ |
| **Server** | Apache/Nginx | Compatible |
| **PHP** | PHP | 7.4+ |
| **Icons** | Font Awesome | 5.x |
| **Charts** | Chart.js | Latest |

## 💻 System Requirements

### Minimum Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: 512MB RAM
- **Storage**: 2GB available space
- **Extensions**: mysqli, gd, curl, mbstring, zip

### Recommended Specifications
- **PHP**: 8.0+
- **MySQL**: 8.0+
- **Memory**: 1GB+ RAM
- **Storage**: 5GB+ SSD
- **SSL Certificate**: For production deployment

## 🚀 Installation Guide

### Step 1: Environment Preparation
```bash
# Clone the repository
git clone https://github.com/DevAniketIT/Greenzio.git
cd Greenzio

# Set proper permissions
chmod 755 application/logs
chmod 755 application/cache
chmod 755 assets/images/products
```

### Step 2: Database Configuration
```sql
-- Create database
CREATE DATABASE greenzio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import schema
source database/greenzio_schema.sql;
source database/greenzio_data.sql;
```

### Step 3: Application Configuration
```php
// application/config/database.php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'greenzio',
    // ... other settings
);

// application/config/config.php
$config['base_url'] = 'https://yourdomain.com/';
$config['encryption_key'] = 'your_32_character_encryption_key';
```

### Step 4: Web Server Setup

#### Apache Configuration
```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html/Greenzio
    ServerName yourdomain.com
    
    <Directory /var/www/html/Greenzio>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/greenzio_error.log
    CustomLog ${APACHE_LOG_DIR}/greenzio_access.log combined
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/html/Greenzio;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## 📁 Project Structure

```
Greenzio/
├── 📂 application/
│   ├── 📂 controllers/      # MVC Controllers
│   │   ├── Admin.php        # Admin panel controller
│   │   ├── Shop.php         # Main shopping controller
│   │   ├── User.php         # User management
│   │   └── Shopping.php     # Cart & checkout
│   ├── 📂 models/          # Data models
│   │   ├── Products.php     # Product management
│   │   ├── Cart.php         # Shopping cart
│   │   └── Adminmodel.php   # Admin operations
│   ├── 📂 views/           # Template files
│   │   ├── 📂 admin/        # Admin interface
│   │   ├── 📂 main/         # Customer interface
│   │   └── 📂 pages/        # Content pages
│   └── 📂 config/          # Configuration files
├── 📂 assets/              # Frontend resources
│   ├── 📂 css/             # Stylesheets
│   ├── 📂 js/              # JavaScript files
│   └── 📂 images/          # Image assets
├── 📂 database/            # Database files
│   ├── greenzio_schema.sql # Database structure
│   └── greenzio_data.sql   # Sample data
├── 📂 Project Snap/        # Screenshots
└── 📄 Documentation files
```

## 🗃 Database Schema

### Core Tables
| Table | Purpose | Key Features |
|-------|---------|-------------|
| `users` | Customer accounts | Authentication, profiles, preferences |
| `admin` | Administrator accounts | Role-based access control |
| `product` | Product catalog | Inventory, pricing, categories |
| `cart` | Shopping cart | Session/user-based cart management |
| `orders` | Order records | Complete order lifecycle |
| `order_details` | Order items | Product quantities and pricing |
| `billing_address` | Customer addresses | Billing and shipping information |

### Database Relationships
```
users (1) ←→ (n) orders
orders (1) ←→ (n) order_details
product (1) ←→ (n) order_details
product (1) ←→ (n) cart
users (1) ←→ (n) cart
```

## 📖 Usage Instructions

### Customer Workflow
1. **Browse Products**: Navigate categories or use search functionality
2. **Product Details**: View detailed product information and reviews
3. **Add to Cart**: Select quantities and add items to shopping cart
4. **Account Creation**: Register for faster checkout and order tracking
5. **Checkout Process**: Review cart, enter shipping details, select payment
6. **Order Confirmation**: Receive confirmation and tracking information
7. **Order Tracking**: Monitor order status through user dashboard

### Administrator Workflow
1. **Dashboard Access**: Login at `/admin` with administrator credentials
2. **Product Management**: Add/edit products, manage inventory levels
3. **Order Processing**: Review and process incoming customer orders
4. **Customer Support**: Manage customer accounts and resolve issues
5. **Analytics Review**: Monitor sales performance and generate reports
6. **System Maintenance**: Update settings and perform system maintenance

## 🛡 Admin Panel

### Default Administrator Access
- **URL**: `/admin`
- **Username**: `admin@greenzio.com`
- **Password**: `admin123`

> ⚠️ **Security Notice**: Change default credentials immediately after installation

### Admin Features
- **Product Management**: Bulk operations, inventory tracking
- **Order Management**: Status updates, customer communication
- **User Administration**: Account management, access control
- **Analytics Dashboard**: Sales metrics, performance indicators
- **System Configuration**: Settings, maintenance mode

## 📊 Screenshots

<div align="center">
  <img src="Project Snap/admin-dashboard.png" alt="Admin Dashboard" width="400"/>
  <img src="Project Snap/product-catalog.png" alt="Product Catalog" width="400"/>
</div>

*Additional screenshots available in the `Project Snap/` directory*

## 🔒 Security Features

- **Authentication**: Secure login with password hashing
- **Authorization**: Role-based access control
- **Input Validation**: XSS and SQL injection prevention
- **Session Security**: Timeout and hijacking protection
- **CSRF Protection**: Form token validation
- **Error Handling**: Secure error messages
- **Data Encryption**: Sensitive data protection

## ⚡ Performance Optimization

- **Database Indexing**: Optimized query performance
- **Caching Strategy**: Page and query result caching
- **Asset Optimization**: Minified CSS and JavaScript
- **Image Optimization**: Compressed product images
- **Lazy Loading**: Improved page load times
- **CDN Ready**: Static asset delivery optimization

## 📜 License

**PROPRIETARY SOFTWARE LICENSE**

This software is proprietary and confidential. Unauthorized copying, distribution, or modification of this software, via any medium, is strictly prohibited without explicit written permission from the owner.

**Usage Rights**: This software is licensed, not sold. Any use of this software requires prior written authorization from:

**Owner**: Aniket Kumar  
**Email**: aniket.kumar.devpro@gmail.com  
**Repository**: https://github.com/DevAniketIT/Greenzio

### Terms and Conditions:
1. **No Redistribution**: This software may not be redistributed in any form
2. **No Modification**: Source code modifications require explicit permission
3. **Commercial Use**: Commercial deployment requires a separate license
4. **Attribution**: Original authorship must be acknowledged in all deployments
5. **Support**: Technical support is provided exclusively by the original developer

**Violation of these terms may result in legal action.**

## 👨‍💻 Developer Information

**Lead Developer**: Aniket Kumar  
**Email**: aniket.kumar.devpro@gmail.com  
**GitHub**: [@DevAniketIT](https://github.com/DevAniketIT)  
**LinkedIn**: [Connect with Developer](https://linkedin.com/in/aniket-kumar-devpro)

### Development Standards
- **Code Quality**: PSR-2 coding standards
- **Version Control**: Git with semantic versioning
- **Testing**: Unit and integration testing protocols
- **Documentation**: Comprehensive inline documentation
- **Security**: Regular security audits and updates

### Project Statistics
- **Lines of Code**: 15,000+
- **Development Time**: 6+ months
- **Last Updated**: September 2025
- **Version**: 2.0.0

---

<div align="center">
  <h3>🌱 Greenzio - Fresh Groceries Delivered with Technology Excellence</h3>
  <p><em>Built with passion for quality code and exceptional user experience</em></p>
  
  [![GitHub](https://img.shields.io/badge/GitHub-Repository-blue?style=for-the-badge&logo=github)](https://github.com/DevAniketIT/Greenzio)
  [![PHP](https://img.shields.io/badge/PHP-7.4+-purple?style=for-the-badge&logo=php)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange?style=for-the-badge&logo=mysql)](https://mysql.com)
  [![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.13-red?style=for-the-badge&logo=codeigniter)](https://codeigniter.com)
</div>
