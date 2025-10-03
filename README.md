<div align="center">

# 🌱 **Greenzio** - Premium Grocery E-Commerce Platform

### *Your One-Stop Solution for Modern Grocery Business Management*

[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.13-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-4.5+-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-Proprietary-red?style=for-the-badge)](#license)
[![GitHub Stars](https://img.shields.io/github/stars/Aniket-Dev-IT/Greenzio?style=for-the-badge&color=gold)](https://github.com/Aniket-Dev-IT/Greenzio)
[![GitHub Forks](https://img.shields.io/github/forks/Aniket-Dev-IT/Greenzio?style=for-the-badge&color=blue)](https://github.com/Aniket-Dev-IT/Greenzio/network/members)

</div>

---

## 🎯 **About Greenzio**

**Greenzio** is a sophisticated, full-featured e-commerce platform specifically designed for the grocery industry. Built with modern web technologies and following industry best practices, it provides a seamless shopping experience for customers while offering comprehensive management tools for administrators.

The platform handles everything from product catalog management to order processing, inventory tracking, and customer relationship management, making it a complete solution for grocery businesses of all sizes.

### 🌟 **Why Choose Greenzio?**

- 🛒 **Complete E-Commerce Solution** - Everything you need to run a grocery business online
- 🎨 **Modern & Responsive Design** - Works perfectly on desktop, tablet, and mobile devices  
- 🔐 **Enterprise-Level Security** - Advanced security features to protect your business and customers
- ⚡ **High Performance** - Optimized for speed and reliability
- 📊 **Advanced Analytics** - Detailed insights into your business performance
- 🛠️ **Easy to Manage** - Intuitive admin panel for effortless management




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

## 📸 **Project Screenshots**

<div align="center">

### 🏠 **Customer Experience**
<img src="Project Snap/Home + Products Page.png" alt="Greenzio Homepage and Product Catalog" width="800"/>
<p><em>Modern homepage and product catalog with seamless shopping experience</em></p>

### 🔧 **Admin Management**

<table>
  <tr>
    <td width="50%" align="center">
      <img src="Project Snap/Admin Product Management.jpeg" alt="Product Management" width="400"/>
      <br/><strong>📦 Product Management</strong>
    </td>
    <td width="50%" align="center">
      <img src="Project Snap/Admin Stock Management Dashboard.jpeg" alt="Stock Management" width="400"/>
      <br/><strong>📊 Stock Management</strong>
    </td>
  </tr>
</table>

</div>

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

## 👨‍💻 **Meet the Developer**

<div align="center">
  <img src="https://github.com/Aniket-Dev-IT.png" alt="Aniket Kumar" width="120" style="border-radius: 50%;" />
  
  ### **Aniket Kumar**
  *Full-Stack Developer & Software Engineer*
  
  [![Profile Views](https://komarev.com/ghpvc/?username=Aniket-Dev-IT&color=brightgreen&style=for-the-badge)](https://github.com/Aniket-Dev-IT)
  [![GitHub Followers](https://img.shields.io/github/followers/Aniket-Dev-IT?style=for-the-badge&color=blue)](https://github.com/Aniket-Dev-IT?tab=followers)
</div>

---

### 🎯 **Professional Experience**
- 🏆 **Specialization**: Full-Stack Web Development, E-Commerce Solutions
- 💼 **Experience**: 5+ years in PHP, MySQL, JavaScript, and Modern Web Technologies
- 🎓 **Expertise**: CodeIgniter, Laravel, React, Node.js, Database Design
- 🌟 **Focus**: Creating scalable, secure, and user-friendly web applications

### 📈 **Project Statistics**
- 📝 **Lines of Code**: 15,000+
- ⏱️ **Development Time**: 6+ months of dedicated development
- 🔄 **Last Updated**: October 2025
- 📦 **Version**: 2.0.0
- 🚀 **Status**: Production Ready

### 🛠️ **Development Standards**
- ✅ **Code Quality**: PSR-2 coding standards with clean architecture
- 🔄 **Version Control**: Git with semantic versioning
- 🧪 **Testing**: Comprehensive unit and integration testing
- 📚 **Documentation**: Detailed inline documentation
- 🔒 **Security**: Regular security audits and vulnerability assessments

---

## 📞 **Get In Touch**

<div align="center">

### 🤝 **Let's Connect & Collaborate!**

I'm always open to discussing new opportunities, collaborating on interesting projects, or just having a chat about technology!

<table>
  <tr>
    <td align="center">
      <a href="mailto:aniket.kumar.devpro@gmail.com">
        <img src="https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white" alt="Email"/>
      </a>
      <br/>
      <strong>📧 aniket.kumar.devpro@gmail.com</strong>
    </td>
    <td align="center">
      <a href="https://wa.me/918318601925">
        <img src="https://img.shields.io/badge/WhatsApp-25D366?style=for-the-badge&logo=whatsapp&logoColor=white" alt="WhatsApp"/>
      </a>
      <br/>
      <strong>📱 +91 8318601925</strong>
    </td>
    <td align="center">
      <a href="https://github.com/Aniket-Dev-IT">
        <img src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white" alt="GitHub"/>
      </a>
      <br/>
      <strong>🐙 @Aniket-Dev-IT</strong>
    </td>
  </tr>
</table>

---

### 🌐 **Professional Networks**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/aniket-kumar-devpro)
[![Twitter](https://img.shields.io/badge/Twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/AniketDevIT)
[![Stack Overflow](https://img.shields.io/badge/Stack_Overflow-FE7A16?style=for-the-badge&logo=stack-overflow&logoColor=white)](https://stackoverflow.com/users/aniket-dev)
[![Dev.to](https://img.shields.io/badge/dev.to-0A0A0A?style=for-the-badge&logo=devdotto&logoColor=white)](https://dev.to/aniketdevit)

</div>

---

## 🤝 **Contributing**

<div align="center">

### **Interested in Contributing?**

*I welcome contributions, issues, and feature requests!*

[![Contributors](https://img.shields.io/github/contributors/Aniket-Dev-IT/Greenzio?style=for-the-badge)](https://github.com/Aniket-Dev-IT/Greenzio/graphs/contributors)
[![Issues](https://img.shields.io/github/issues/Aniket-Dev-IT/Greenzio?style=for-the-badge)](https://github.com/Aniket-Dev-IT/Greenzio/issues)
[![Pull Requests](https://img.shields.io/github/issues-pr/Aniket-Dev-IT/Greenzio?style=for-the-badge)](https://github.com/Aniket-Dev-IT/Greenzio/pulls)

</div>

### 📝 **How to Contribute**
1. 🍴 **Fork** the repository
2. 🌿 **Create** your feature branch (`git checkout -b feature/amazing-feature`)
3. 💾 **Commit** your changes (`git commit -m 'Add some amazing feature'`)
4. 📤 **Push** to the branch (`git push origin feature/amazing-feature`)
5. 🔀 **Open** a Pull Request

### 🐛 **Found a Bug?**
- Check if the issue already exists in our [Issues](https://github.com/Aniket-Dev-IT/Greenzio/issues)
- If not, feel free to [create a new issue](https://github.com/Aniket-Dev-IT/Greenzio/issues/new)
- Include detailed steps to reproduce the bug

### 💡 **Have an Idea?**
- Open a [feature request](https://github.com/Aniket-Dev-IT/Greenzio/issues/new) with the label "enhancement"
- Describe your idea in detail
- Explain why this feature would be beneficial

---

## 💖 **Support the Project**

<div align="center">

If you find this project helpful, please consider:

⭐ **Starring** the repository  
🍴 **Forking** for your own experiments  
🐛 **Reporting** any issues you find  
💌 **Sharing** with fellow developers  

### **Show Your Support**

<table align="center">
  <tr>
    <td align="center">
      <a href="https://github.com/Aniket-Dev-IT/Greenzio">
        ⭐<br/><strong>Star this repo</strong>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/Aniket-Dev-IT/Greenzio/fork">
        🍴<br/><strong>Fork it</strong>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/Aniket-Dev-IT/Greenzio/issues">
        🐛<br/><strong>Report issues</strong>
      </a>
    </td>
    <td align="center">
      <a href="mailto:aniket.kumar.devpro@gmail.com">
        📧<br/><strong>Get in touch</strong>
      </a>
    </td>
  </tr>
</table>

*Your support and feedback mean the world to me!* 

</div>

---

##  **Acknowledgments**

- 🎨 **Bootstrap Team** - For the amazing UI framework
- 🔥 **CodeIgniter** - For the robust PHP framework
- 🗃️ **MySQL** - For reliable database management
- 🌟 **Font Awesome** - For beautiful icons
- 📊 **Chart.js** - For interactive charts
- 🚀 **All Contributors** - For making this project better
- ☕ **Coffee** - For fueling late-night coding sessions

---

<div align="center">

## 🌱 **Greenzio - Where Technology Meets Fresh Groceries**

*Built with ❤️ by [Aniket Kumar](https://github.com/Aniket-Dev-IT)*

**"Delivering excellence through code, one commit at a time."**

---

### 📊 **GitHub Statistics**

![Top Languages](https://github-readme-stats.vercel.app/api/top-langs/?username=Aniket-Dev-IT&layout=compact&theme=radical)

![GitHub Stats](https://github-readme-stats.vercel.app/api?username=Aniket-Dev-IT&show_icons=true&theme=radical)

---

**© 2025 Aniket Kumar. All rights reserved.**

[![Made with Love](https://img.shields.io/badge/Made%20with-❤️-red?style=for-the-badge)](https://github.com/Aniket-Dev-IT)
[![Powered by Coffee](https://img.shields.io/badge/Powered%20by-☕-brown?style=for-the-badge)](https://github.com/Aniket-Dev-IT)

</div>
