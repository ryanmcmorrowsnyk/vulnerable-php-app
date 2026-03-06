# Vulnerability Catalog - Vulnerable PHP Application

**⚠️ WARNING: This application is intentionally vulnerable. Do NOT use in production!**

## Overview

This application contains **200+ vulnerabilities** across dependencies and source code for testing security remediation tools and processes.

---

## Code Vulnerabilities (SAST)

### 1. **SQL Injection** (CWE-89)
- **Location**: `index.php:58-66`
- **Severity**: Critical
- **Description**: User input directly concatenated into SQL query
```php
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```
- **Attack Vector**: `username=' OR '1'='1' --`
- **Remediation**: Use prepared statements with parameterized queries

---

### 2. **Command Injection** (CWE-78)
- **Location**: `index.php:70-76`
- **Severity**: Critical
- **Description**: User input passed directly to `shell_exec()`
```php
$output = shell_exec($cmd);
```
- **Attack Vector**: `cmd=ls; rm -rf /`
- **Remediation**: Avoid shell execution, use allowlists, or `escapeshellarg()`

---

### 3. **Path Traversal** (CWE-22)
- **Location**: `index.php:79-85`
- **Severity**: High
- **Description**: No sanitization of file paths
```php
$content = file_get_contents('./uploads/' . $filename);
```
- **Attack Vector**: `filename=../../../../etc/passwd`
- **Remediation**: Validate and sanitize file paths, use basename()

---

### 4. **Cross-Site Scripting (XSS)** (CWE-79)
- **Location**: `index.php:88-93`
- **Severity**: High
- **Description**: User input reflected without sanitization
```php
echo "<h1>Search Results for: $query</h1>";
```
- **Attack Vector**: `query=<script>alert(document.cookie)</script>`
- **Remediation**: Use `htmlspecialchars()` or output encoding

---

### 5. **Server-Side Request Forgery (SSRF)** (CWE-918)
- **Location**: `index.php:96-102`
- **Severity**: High
- **Description**: No URL validation before fetching
```php
$content = file_get_contents($url);
```
- **Attack Vector**: `url=http://169.254.169.254/latest/meta-data/`
- **Remediation**: Validate URLs, use allowlist, block private IPs

---

### 6. **Remote Code Execution via eval()** (CWE-94)
- **Location**: `index.php:105-111`
- **Severity**: Critical
- **Description**: Direct execution of user-provided code
```php
eval($code);
```
- **Attack Vector**: `code=system('whoami');`
- **Remediation**: Never use eval() with user input

---

### 7. **Insecure Deserialization** (CWE-502)
- **Location**: `index.php:114-120`
- **Severity**: Critical
- **Description**: Unserializing untrusted data
```php
$obj = unserialize($data);
```
- **Attack Vector**: Craft malicious serialized object
- **Remediation**: Use JSON instead, validate input

---

### 8. **Weak Cryptography - MD5** (CWE-327)
- **Location**: `index.php:123-130`
- **Severity**: Medium
- **Description**: Using broken MD5 for password hashing
```php
$hash = md5($password);
```
- **Remediation**: Use `password_hash()` with bcrypt/argon2

---

### 9. **XML External Entity (XXE) Injection** (CWE-611)
- **Location**: `index.php:149-156`
- **Severity**: High
- **Description**: XML parsing with external entities enabled
```php
$doc->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
```
- **Attack Vector**: Embed malicious DTD to read files
- **Remediation**: Disable external entities, use `libxml_disable_entity_loader(true)`

---

### 10. **YAML Deserialization** (CWE-502)
- **Location**: `index.php:159-166`
- **Severity**: Critical
- **Description**: Unsafe YAML parsing can execute code
- **Remediation**: Use safe YAML parsers, validate input

---

### 11. **Mass Assignment** (CWE-915)
- **Location**: `index.php:169-182`
- **Severity**: High
- **Description**: Allows users to set arbitrary fields including 'role'
```php
'role' => $data['role'] ?? 'user' // Attacker can set role=admin
```
- **Attack Vector**: `{"username":"hacker","password":"pass","role":"admin"}`
- **Remediation**: Use allowlist of assignable fields

---

### 12. **Insecure Direct Object Reference (IDOR)** (CWE-639)
- **Location**: `index.php:185-196`
- **Severity**: High
- **Description**: No authorization check before accessing user data
- **Attack Vector**: Enumerate `/api/users/1`, `/api/users/2`, etc.
- **Remediation**: Verify user has permission to access requested resource

---

### 13. **Missing Authentication** (CWE-306)
- **Location**: `index.php:199-208`
- **Severity**: Critical
- **Description**: Admin delete endpoint has no authentication
- **Attack Vector**: DELETE `/api/admin/users/1` without credentials
- **Remediation**: Require authentication and authorization

---

### 14. **Unrestricted File Upload** (CWE-434)
- **Location**: `index.php:211-221`
- **Severity**: Critical
- **Description**: No file type validation on uploads
```php
move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
```
- **Attack Vector**: Upload PHP shell, execute arbitrary code
- **Remediation**: Validate file types, randomize names, store outside webroot

---

### 15. **Open Redirect** (CWE-601)
- **Location**: `index.php:224-229`
- **Severity**: Medium
- **Description**: Unvalidated redirect
```php
header("Location: $url");
```
- **Attack Vector**: `url=http://malicious.com`
- **Remediation**: Validate redirect URLs against allowlist

---

### 16. **LDAP Injection** (CWE-90)
- **Location**: `index.php:232-238`
- **Severity**: High
- **Description**: User input in LDAP query
```php
$ldap_query = "(&(objectClass=user)(username=$username))";
```
- **Attack Vector**: `username=*)(objectClass=*`
- **Remediation**: Escape LDAP special characters

---

### 17. **XPath Injection** (CWE-643)
- **Location**: `index.php:241-247`
- **Severity**: High
- **Description**: User input in XPath query
```php
$xpath = "//user[name='$name']";
```
- **Attack Vector**: `name=' or '1'='1`
- **Remediation**: Use parameterized XPath queries

---

### 18. **Server-Side Template Injection (SSTI)** (CWE-1336)
- **Location**: `index.php:250-258`
- **Severity**: Critical
- **Description**: Eval-based template rendering
```php
eval('?>' . $template);
```
- **Attack Vector**: Inject PHP code in template
- **Remediation**: Use safe templating engines (Twig, Blade)

---

### 19. **Race Condition** (CWE-362)
- **Location**: `index.php:261-272`
- **Severity**: Medium
- **Description**: No locking on financial transactions
- **Attack Vector**: Send multiple concurrent transfer requests
- **Remediation**: Use database transactions and locking

---

### 20. **Insufficient Logging** (CWE-778)
- **Location**: `index.php:275-281`
- **Severity**: Low
- **Description**: No audit trail for sensitive operations
- **Remediation**: Log all security-relevant events

---

### 21. **Insecure Randomness** (CWE-330)
- **Location**: `index.php:284-289`
- **Severity**: Medium
- **Description**: Predictable random token generation
```php
$token = md5(rand());
```
- **Remediation**: Use `random_bytes()` or `openssl_random_pseudo_bytes()`

---

### 22. **Integer Overflow** (CWE-190)
- **Location**: `index.php:292-298`
- **Severity**: Low
- **Description**: No overflow checking on arithmetic
- **Remediation**: Validate input ranges, use BC Math for large numbers

---

### 23. **Hardcoded Password** (CWE-259)
- **Location**: `index.php:8-11, 301-311`
- **Severity**: Critical
- **Description**: Admin password hardcoded in source
```php
define('ADMIN_PASSWORD', 'admin123');
```
- **Remediation**: Use environment variables, proper authentication

---

### 24. **Information Exposure Through Error Messages** (CWE-209)
- **Location**: `index.php:13-15, 314-324`
- **Severity**: Medium
- **Description**: Detailed error messages expose internal structure
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
- **Remediation**: Log errors, show generic messages to users

---

### 25. **Missing Rate Limiting** (CWE-770)
- **Location**: `index.php:327-337`
- **Severity**: Medium
- **Description**: No protection against brute force attacks
- **Remediation**: Implement rate limiting, account lockout

---

### 26. **Cleartext Transmission of Sensitive Information** (CWE-319)
- **Location**: `index.php:340-349`
- **Severity**: High
- **Description**: Credentials transmitted without HTTPS enforcement
- **Remediation**: Enforce HTTPS, use HSTS headers

---

### 27. **Sensitive Data in GET Parameters** (CWE-598)
- **Location**: `index.php:352-358`
- **Severity**: Medium
- **Description**: Password reset token and new password in URL
- **Remediation**: Use POST method for sensitive data

---

### 28. **Exposed Secrets in Environment File** (.env)
- **Location**: `.env:1-68`
- **Severity**: Critical
- **Description**: All credentials stored in plaintext .env file
- Database passwords, AWS keys, API keys, JWT secrets
- **Remediation**: Use secrets management, never commit .env

---

## Dependency Vulnerabilities (SCA)

### Expected Vulnerability Breakdown (200+ Total)

#### Critical/High Severity Dependencies

1. **Laravel Framework 5.8.35** (2019)
   - Multiple CVEs in core framework
   - SQL injection vulnerabilities
   - XSS vulnerabilities
   - Session fixation issues

2. **Symfony Components 4.3.4** (2019)
   - CVE-2019-18888 (CSRF)
   - CVE-2019-18889 (HTTP Response Splitting)
   - Multiple security advisories

3. **Guzzle 6.3.3** (2018)
   - CVE-2022-29248 (Header injection)
   - CVE-2022-31042 (Cookie leakage)
   - CVE-2022-31043 (Authorization header disclosure)

4. **Monolog 1.24.0** (2019)
   - CVE-2021-41142 (Arbitrary code execution)
   - Insecure handling of log data

5. **Doctrine DBAL 2.9.2** (2019)
   - SQL injection vulnerabilities
   - Multiple security issues

6. **PHPMailer 6.0.7** (2019)
   - CVE-2020-13625 (RCE)
   - CVE-2020-36326 (Object injection)

7. **SwiftMailer 6.2.1** (2019)
   - CVE-2021-21413 (SMTP injection)
   - Multiple vulnerabilities

8. **Firebase PHP-JWT 5.0.0** (2019)
   - Algorithm confusion vulnerabilities
   - Weak key validation

9. **PHPSecLib 2.0.23** (2019)
   - Cryptographic issues
   - Multiple CVEs

10. **AWS SDK PHP 3.110.0** (2019)
    - Multiple transitive vulnerabilities
    - Outdated dependencies

#### Transitive Dependency Vulnerabilities

- **Symfony sub-components**: 50+ vulnerable packages
- **Doctrine ecosystem**: 20+ vulnerabilities
- **Illuminate components**: 30+ vulnerabilities
- **League packages**: 15+ vulnerabilities
- **PSR implementations**: 10+ vulnerabilities
- **Development dependencies**: 30+ vulnerabilities

### Remediation Scenarios

#### Scenario 1: Simple Direct Upgrades (30-40 vulnerabilities)
- `guzzlehttp/guzzle: 6.3.3 → 7.5.0`
- `monolog/monolog: 1.24.0 → 3.3.0`
- `nesbot/carbon: 1.38.4 → 2.66.0`

#### Scenario 2: Framework Major Version (100+ vulnerabilities)
- `laravel/framework: 5.8.35 → 10.x` (Breaking changes)
- Requires code refactoring
- Many Illuminate components must upgrade together

#### Scenario 3: Transitive Upgrades (50+ vulnerabilities)
- Symfony vulnerabilities require Laravel upgrade
- Doctrine components depend on each other
- Diamond dependency scenarios

#### Scenario 4: Deprecated Packages (10-20 vulnerabilities)
- Some old packages no longer maintained
- May require alternative solutions

---

## Testing Vulnerabilities

### Scan with Snyk
```bash
cd /path/to/vulnerable-php-app
composer install
snyk test
```

### Expected Results
- **200+ open source vulnerabilities**
- **28 code vulnerabilities**
- Multiple critical, high, medium, and low severity issues
- Complex remediation scenarios requiring:
  - Simple version bumps
  - Framework upgrades with breaking changes
  - Transitive dependency resolution
  - Code refactoring

---

## Disclaimer

This application is for **educational and testing purposes only**. Never deploy to production or expose to the internet.

## License

MIT License - Use at your own risk for security testing and tool validation only.
