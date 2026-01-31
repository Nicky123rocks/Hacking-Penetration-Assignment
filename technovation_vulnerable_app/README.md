# TechNovation Solutions - Vulnerable E-Commerce Platform

## ⚠️ WARNING: EDUCATIONAL PURPOSE ONLY

This application contains **intentional security vulnerabilities** for educational and penetration testing purposes. **DO NOT deploy in production environments.**

## Project Overview

TechNovation Solutions is a deliberately vulnerable e-commerce web application created for the CCS6324 Ethical Hacking and Penetration Testing course. This application simulates a real-world web application with multiple security flaws aligned with OWASP Top 10 vulnerabilities.

## Table of Contents

1. [Features](#features)
2. [Intentional Vulnerabilities](#intentional-vulnerabilities)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Usage](#usage)
6. [Testing Scenarios](#testing-scenarios)
7. [Remediation Guide](#remediation-guide)
8. [Project Structure](#project-structure)

## Features

### User Features
- User registration and authentication
- Product browsing and search
- Shopping cart functionality
- Checkout and order placement
- Product reviews and comments
- User profile management

### Admin Features
- Admin dashboard with statistics
- Product management (CRUD operations)
- Order management
- User management
- Database backup functionality
- System logs viewing

### API Features
- RESTful API endpoints
- JSON responses
- CRUD operations for products
- Public API access (no authentication required)

## Intentional Vulnerabilities

This application implements the following vulnerabilities for educational purposes:

### 1. SQL Injection (SQLi)
- **Location**: `index.php` (search functionality)
- **Location**: `auth/login.php` (login form)
- **Location**: `admin/login.php` (admin login)
- **Location**: `product.php` (product ID parameter)
- **Example Payload**: `' OR '1'='1`

### 2. Cross-Site Scripting (XSS)
- **Reflected XSS**: `index.php` (search parameter)
- **Stored XSS**: `product.php` (comment system)
- **DOM-based XSS**: `assets/js/main.js` (message parameter)
- **Example Payload**: `<script>alert('XSS')</script>`

### 3. Broken Authentication
- **Weak password policy** (minimum 4 characters)
- **Plain text password storage**
- **No account lockout mechanism**
- **Session fixation vulnerability**
- **Predictable session IDs**

### 4. Sensitive Data Exposure
- **Database credentials** in plain text
- **Error messages** revealing system information
- **API keys** exposed in JavaScript
- **Debug information** accessible via URL parameters

### 5. Broken Access Control
- **Insecure Direct Object Reference (IDOR)** in product/order access
- **Missing function-level access control**
- **Horizontal privilege escalation** possible
- **Vertical privilege escalation** via SQL injection

### 6. Security Misconfiguration
- **Directory listing** enabled
- **Default credentials** (admin/admin123)
- **Detailed error messages** exposed
- **Unnecessary features** enabled (debug mode)

### 7. Cross-Site Request Forgery (CSRF)
- **No CSRF tokens** in forms
- **State-changing operations** via GET requests
- **Deletions without confirmation**

### 8. Using Components with Known Vulnerabilities
- **Outdated dependencies** (simulated)
- **Vulnerable libraries** (for testing purposes)

### 9. Insufficient Logging & Monitoring
- **Inadequate logging** of security events
- **No alerting mechanisms**
- **Missing audit trails**

### 10. Command Injection
- **Location**: `admin/dashboard.php` (backup functionality)
- **Example Payload**: `backup; cat /etc/passwd`

### Additional Vulnerabilities

11. **Price Manipulation**
    - Client-side price values trusted on server
    - No server-side validation of prices

12. **Information Disclosure**
    - Debug parameters expose sensitive data
    - Verbose error messages
    - Comments in source code

13. **Weak Password Recovery**
    - No email verification for registration
    - Predictable password reset tokens

14. **API Security Issues**
    - No authentication required
    - No rate limiting
    - CORS misconfiguration

## Installation

### Prerequisites
- **Web Server**: Apache 2.4+ or Nginx
- **PHP**: 7.4 or higher
- **MySQL**: 5.7+ or MariaDB 10.4+
- **Git**: For version control

### Step-by-Step Installation

#### 1. Clone the Repository
```bash
git clone <repository-url>
cd technovation_vulnerable_app
```

#### 2. Set Up Web Server

**For Apache (XAMPP/WAMP/LAMP):**
```bash
# Copy application to web root
sudo cp -r technovation_vulnerable_app /var/www/html/
# Or for XAMPP
cp -r technovation_vulnerable_app C:/xampp/htdocs/
```

**For Nginx:**
```bash
sudo cp -r technovation_vulnerable_app /usr/share/nginx/html/
```

#### 3. Configure Database

**Import the database schema:**
```bash
mysql -u root -p < database/technovation.sql
```

Or using phpMyAdmin:
1. Open phpMyAdmin
2. Create database named `technovation`
3. Import `database/technovation.sql`

#### 4. Configure Database Connection

Edit `config/db.php` if needed:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password
define('DB_NAME', 'technovation');
```

#### 5. Set Permissions
```bash
chmod -R 755 technovation_vulnerable_app
chmod -R 777 technovation_vulnerable_app/uploads  # If exists
```

#### 6. Access the Application

Open your web browser and navigate to:
- **Main Site**: `http://localhost/technovation_vulnerable_app/`
- **Admin Panel**: `http://localhost/technovation_vulnerable_app/admin/login.php`
- **API**: `http://localhost/technovation_vulnerable_app/api/products.php`

## Configuration

### Default Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin123`

**Test User Accounts:**
- Username: `john_doe` / Password: `password`
- Username: `jane_smith` / Password: `12345`
- Username: `demo` / Password: `demo`

### Application Settings

Edit configuration in `config/db.php`:
```php
// Enable/Disable error display (vulnerability)
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## Usage

### For Students (Penetration Testing)

1. **Reconnaissance Phase**
   - Use tools like WHOIS, DNS enumeration
   - Perform directory bruteforcing
   - Technology stack identification

2. **Scanning Phase**
   - Run Nmap for port scanning
   - Use Nikto for web vulnerability scanning
   - Employ Burp Suite for web application analysis

3. **Exploitation Phase**
   - Test SQL injection vulnerabilities
   - Attempt XSS attacks
   - Try authentication bypass
   - Test CSRF vulnerabilities

4. **Post-Exploitation**
   - Attempt privilege escalation
   - Data exfiltration
   - Persistence mechanisms

### Testing URLs

**SQL Injection Test:**
```
http://localhost/technovation_vulnerable_app/index.php?search=' OR '1'='1
```

**XSS Test:**
```
http://localhost/technovation_vulnerable_app/index.php?search=<script>alert('XSS')</script>
```

**Command Injection Test:**
```
http://localhost/technovation_vulnerable_app/admin/dashboard.php?backup=1&filename=test;ls
```

## Testing Scenarios

### Scenario 1: SQL Injection Attack
**Objective**: Bypass login authentication

**Steps**:
1. Navigate to login page
2. Enter: Username: `admin' OR '1'='1`
3. Enter: Password: `anything`
4. Click Login

**Expected Result**: Successfully logged in as admin

### Scenario 2: Stored XSS
**Objective**: Store malicious script in comments

**Steps**:
1. Login as any user
2. Navigate to any product
3. Add comment: `<script>alert(document.cookie)</script>`
4. Reload page

**Expected Result**: Alert box displays with cookies

### Scenario 3: Price Manipulation
**Objective**: Purchase product at modified price

**Steps**:
1. Use browser developer tools
2. Inspect "Add to Cart" form
3. Modify hidden price field value
4. Submit form and checkout

**Expected Result**: Order placed at manipulated price

## Remediation Guide

For each vulnerability, refer to `docs/REMEDIATION.md` which includes:
- Vulnerability description
- Impact assessment
- Step-by-step remediation
- Secure code examples
- Testing procedures

## Project Structure

```
technovation_vulnerable_app/
├── admin/                  # Admin panel files
│   ├── dashboard.php       # Main admin dashboard
│   ├── login.php          # Admin authentication
│   └── delete_product.php # Product deletion
├── api/                    # API endpoints
│   └── products.php       # Products REST API
├── assets/                 # Static resources
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   └── main.js        # Client-side scripts
│   └── images/            # Product images
├── auth/                   # Authentication
│   ├── login.php          # User login
│   ├── register.php       # User registration
│   └── logout.php         # Logout functionality
├── cart/                   # Shopping cart
│   └── add_to_cart.php    # Add to cart handler
├── config/                 # Configuration files
│   └── db.php             # Database connection
├── database/               # Database files
│   └── technovation.sql   # Database schema
├── docs/                   # Documentation
│   ├── VULNERABILITIES.md # Vulnerability details
│   ├── REMEDIATION.md     # Security fixes
│   └── TESTING_GUIDE.md   # Testing procedures
├── index.php              # Main homepage
├── product.php            # Product details page
├── checkout.php           # Checkout page
└── README.md              # This file
```

## Documentation Files

- **README.md**: Main documentation (this file)
- **VULNERABILITIES.md**: Detailed vulnerability descriptions
- **REMEDIATION.md**: Security remediation guide
- **TESTING_GUIDE.md**: Penetration testing procedures
- **SETUP.md**: Detailed setup instructions

## Security Notes

### For Instructors
- This application should only be deployed in isolated lab environments
- Use virtual machines or containers for testing
- Do not expose to public networks
- Monitor student activities during testing

### For Students
- Only test on authorized systems
- Follow responsible disclosure practices
- Document all findings thoroughly
- Do not use these techniques on production systems

## License

This project is created for educational purposes under the CCS6324 course at Multimedia University. All rights reserved.

## Disclaimer

This application intentionally contains security vulnerabilities for educational purposes. The developers are not responsible for any misuse of this application. Users must ensure they have proper authorization before conducting any security testing.

## Support

For questions or issues:
- Course Instructor: [Contact Information]
- Lab Assistant: [Contact Information]
- Course Forum: [Link]

## Acknowledgments

- OWASP for vulnerability classifications
- Multimedia University CCS6324 Course Team
- Security research community

---

**Version**: 1.0  
**Last Updated**: January 2026  
**Course**: CCS6324 - Ethical Hacking and Penetration Testing  
**Institution**: Multimedia University
