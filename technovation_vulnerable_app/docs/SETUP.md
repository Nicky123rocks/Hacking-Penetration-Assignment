# TechNovation Solutions - Complete Setup Guide

## Table of Contents
1. [System Requirements](#system-requirements)
2. [Installation Methods](#installation-methods)
3. [Configuration](#configuration)
4. [Troubleshooting](#troubleshooting)
5. [Verification](#verification)

## System Requirements

### Minimum Requirements
- **OS**: Windows 10/11, macOS 10.15+, or Linux (Ubuntu 20.04+)
- **RAM**: 4GB (8GB recommended)
- **Disk Space**: 2GB free space
- **Network**: Internet connection for installation

### Software Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 7.4, 8.0, or 8.1
- **MySQL**: 5.7+ or MariaDB 10.4+
- **Browser**: Chrome, Firefox, Edge (latest versions)

## Installation Methods

### Method 1: XAMPP (Recommended for Windows/Mac)

#### Step 1: Download and Install XAMPP
```
1. Download XAMPP from: https://www.apachefriends.org/
2. Run installer and install to default location
   - Windows: C:\xampp
   - Mac: /Applications/XAMPP
3. Start XAMPP Control Panel
```

#### Step 2: Deploy Application
```bash
# Windows
1. Copy technovation_vulnerable_app folder to C:\xampp\htdocs\
2. Rename if desired

# Mac/Linux
sudo cp -r technovation_vulnerable_app /Applications/XAMPP/htdocs/
```

#### Step 3: Start Services
```
1. Open XAMPP Control Panel
2. Start Apache
3. Start MySQL
4. Verify both are running (green status)
```

#### Step 4: Import Database
```
1. Open browser: http://localhost/phpmyadmin
2. Click "New" to create database
3. Database name: technovation
4. Collation: utf8mb4_general_ci
5. Click "Import" tab
6. Choose file: database/technovation.sql
7. Click "Go"
8. Verify tables are created
```

#### Step 5: Verify Installation
```
Open browser: http://localhost/technovation_vulnerable_app/
```

---

### Method 2: LAMP Stack (Linux)

#### Step 1: Install LAMP Components
```bash
# Update package list
sudo apt update

# Install Apache
sudo apt install apache2 -y

# Install MySQL
sudo apt install mysql-server -y

# Install PHP and extensions
sudo apt install php php-mysql php-cli php-common php-json php-mbstring -y

# Start services
sudo systemctl start apache2
sudo systemctl start mysql
sudo systemctl enable apache2
sudo systemctl enable mysql
```

#### Step 2: Deploy Application
```bash
# Copy application to web root
sudo cp -r technovation_vulnerable_app /var/www/html/

# Set permissions
sudo chown -R www-data:www-data /var/www/html/technovation_vulnerable_app
sudo chmod -R 755 /var/www/html/technovation_vulnerable_app
```

#### Step 3: Configure MySQL
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
```

```sql
CREATE DATABASE technovation;
CREATE USER 'technovation_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON technovation.* TO 'technovation_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 4: Import Database
```bash
sudo mysql -u root -p technovation < /var/www/html/technovation_vulnerable_app/database/technovation.sql
```

#### Step 5: Configure Database Connection
```bash
sudo nano /var/www/html/technovation_vulnerable_app/config/db.php
```

Update credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'technovation_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'technovation');
```

#### Step 6: Restart Apache
```bash
sudo systemctl restart apache2
```

---

### Method 3: Docker (Advanced)

#### Create Dockerfile
```dockerfile
FROM php:8.1-apache

# Install MySQL extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

# Copy application
COPY technovation_vulnerable_app /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80
```

#### Create docker-compose.yml
```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - ./technovation_vulnerable_app:/var/www/html
    depends_on:
      - db
    networks:
      - technovation_network

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: technovation
    ports:
      - "3306:3306"
    volumes:
      - ./database/technovation.sql:/docker-entrypoint-initdb.d/init.sql
      - mysql_data:/var/lib/mysql
    networks:
      - technovation_network

  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_USER: root
      PMA_PASSWORD: root
    depends_on:
      - db
    networks:
      - technovation_network

volumes:
  mysql_data:

networks:
  technovation_network:
    driver: bridge
```

#### Deploy with Docker
```bash
# Build and start containers
docker-compose up -d

# Verify containers are running
docker-compose ps

# Access application
# Web: http://localhost:8080/
# phpMyAdmin: http://localhost:8081/
```

---

## Configuration

### PHP Configuration (php.ini)

**For XAMPP:**
- Windows: `C:\xampp\php\php.ini`
- Mac: `/Applications/XAMPP/etc/php.ini`

**For LAMP:**
- `/etc/php/8.1/apache2/php.ini`

**Required Settings:**
```ini
display_errors = On
error_reporting = E_ALL
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M

; Enable required extensions
extension=mysqli
extension=pdo_mysql
extension=mbstring
extension=json
```

**Restart Apache after changes:**
```bash
# XAMPP: Use Control Panel
# Linux:
sudo systemctl restart apache2
```

### MySQL Configuration

**Create additional admin user (optional):**
```sql
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'SecurePassword123!';
GRANT ALL PRIVILEGES ON technovation.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;
```

### Apache Virtual Host (Optional)

**Create: `/etc/apache2/sites-available/technovation.conf`**
```apache
<VirtualHost *:80>
    ServerName technovation.local
    DocumentRoot /var/www/html/technovation_vulnerable_app
    
    <Directory /var/www/html/technovation_vulnerable_app>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/technovation_error.log
    CustomLog ${APACHE_LOG_DIR}/technovation_access.log combined
</VirtualHost>
```

**Enable site:**
```bash
sudo a2ensite technovation
sudo systemctl reload apache2
```

**Add to hosts file:**
```bash
# Linux/Mac: /etc/hosts
# Windows: C:\Windows\System32\drivers\etc\hosts

127.0.0.1 technovation.local
```

**Access:** `http://technovation.local/`

---

## Troubleshooting

### Issue 1: "Connection failed: Access denied for user 'root'@'localhost'"

**Solution:**
```bash
# Reset MySQL root password
sudo mysql

ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'newpassword';
FLUSH PRIVILEGES;
EXIT;

# Update config/db.php with new password
```

### Issue 2: "Call to undefined function mysqli_connect()"

**Solution:**
```bash
# Enable mysqli extension
# Edit php.ini and uncomment:
extension=mysqli

# Restart Apache
sudo systemctl restart apache2
```

### Issue 3: "404 Not Found"

**Solution:**
```bash
# Check Apache is running
sudo systemctl status apache2

# Verify file location
ls -la /var/www/html/technovation_vulnerable_app/

# Check Apache error log
sudo tail -f /var/log/apache2/error.log
```

### Issue 4: "Warning: session_start()"

**Solution:**
```bash
# Create session directory
sudo mkdir -p /var/lib/php/sessions
sudo chown -R www-data:www-data /var/lib/php/sessions
sudo chmod -R 700 /var/lib/php/sessions
```

### Issue 5: Database tables not created

**Solution:**
```bash
# Manual import
mysql -u root -p technovation < database/technovation.sql

# Or via phpMyAdmin
# 1. Select database
# 2. Click Import
# 3. Choose SQL file
# 4. Execute
```

### Issue 6: Blank page with no errors

**Solution:**
```bash
# Enable error display
# Edit php.ini:
display_errors = On
error_reporting = E_ALL

# Check PHP syntax
php -l index.php

# Check Apache error log
sudo tail -50 /var/log/apache2/error.log
```

---

## Verification

### Checklist

**1. Apache Running:**
```bash
sudo systemctl status apache2
# Or check: http://localhost/
```

**2. MySQL Running:**
```bash
sudo systemctl status mysql
# Or: mysql -u root -p
```

**3. PHP Working:**
```bash
php -v
# Create test file: /var/www/html/test.php
<?php phpinfo(); ?>
# Access: http://localhost/test.php
```

**4. Database Connected:**
```bash
mysql -u root -p -e "SHOW DATABASES;" | grep technovation
```

**5. Tables Created:**
```bash
mysql -u root -p -e "USE technovation; SHOW TABLES;"

# Should show:
# api_keys
# comments
# logs
# order_items
# orders
# products
# sessions
# users
```

**6. Application Accessible:**
```
Test URLs:
✓ http://localhost/technovation_vulnerable_app/
✓ http://localhost/technovation_vulnerable_app/auth/login.php
✓ http://localhost/technovation_vulnerable_app/admin/login.php
✓ http://localhost/technovation_vulnerable_app/api/products.php
```

**7. Default Credentials Work:**
```
Username: admin
Password: admin123
Login at: /admin/login.php
```

### Functionality Tests

**Test 1: Product Search**
```
1. Go to homepage
2. Enter "laptop" in search
3. Should show Laptop Pro 15
```

**Test 2: User Registration**
```
1. Go to /auth/register.php
2. Create account: testuser / test@test.com / test123
3. Should redirect to homepage
```

**Test 3: Add to Cart**
```
1. Login as user
2. Click any product
3. Click "Add to Cart"
4. Should redirect to checkout
```

**Test 4: Admin Panel**
```
1. Go to /admin/login.php
2. Login: admin / admin123
3. Should see dashboard with statistics
```

**Test 5: API Access**
```bash
curl http://localhost/technovation_vulnerable_app/api/products.php
# Should return JSON with products
```

---

## Security Notice

⚠️ **IMPORTANT**: This application contains intentional vulnerabilities

**DO NOT:**
- Deploy on public servers
- Use in production environments
- Leave accessible on public networks
- Use real payment information

**DO:**
- Use in isolated lab environment
- Use virtual machines
- Disconnect from internet when not needed
- Monitor for unauthorized access

---

## Next Steps

After successful installation:

1. **Review Documentation:**
   - README.md - Overview
   - VULNERABILITIES.md - Security flaws
   - TESTING_GUIDE.md - Penetration testing

2. **Start Testing:**
   - Begin with reconnaissance
   - Test each vulnerability
   - Document findings

3. **Develop Exploits:**
   - Create custom scripts
   - Automate testing
   - Practice techniques

4. **Remediation:**
   - Fix vulnerabilities
   - Implement security controls
   - Re-test for verification

---

## Support Resources

**Documentation:**
- Main README: `/README.md`
- Vulnerabilities: `/docs/VULNERABILITIES.md`
- Testing Guide: `/docs/TESTING_GUIDE.md`

**Community:**
- Course Forum: [Link]
- Lab Sessions: [Schedule]
- Instructor: [Contact]

**Additional Resources:**
- OWASP Top 10: https://owasp.org/Top10/
- PHP Security Guide: https://www.php.net/manual/en/security.php
- MySQL Security: https://dev.mysql.com/doc/refman/8.0/en/security.html

---

**Setup Guide Version**: 1.0  
**Last Updated**: January 2026  
**Tested On**: XAMPP 8.1, Ubuntu 22.04, Docker 24.0
