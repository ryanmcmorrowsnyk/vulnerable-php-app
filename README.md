# Vulnerable PHP/Laravel Application

**⚠️ WARNING: This application is intentionally vulnerable and should NEVER be deployed to production!**

This is an intentionally vulnerable Laravel/PHP application designed for testing security remediation tools, SAST/SCA scanners, and security training.

## 🎯 Purpose

This application demonstrates:
- **200+ open source dependency vulnerabilities** requiring complex remediation
- **28 code-level security vulnerabilities** covering OWASP Top 10
- Realistic vulnerable code patterns found in real-world applications
- Various remediation scenarios (simple upgrades, breaking changes, deprecated packages)

## 📊 Vulnerability Summary

### Dependency Vulnerabilities (SCA)
- **103 vulnerable Composer packages** from 2019
- Laravel 5.8.35 with extensive CVE-laden dependencies
- Symfony 4.3.4 components with known vulnerabilities
- Expected **200+ total vulnerabilities** across direct and transitive dependencies
- Mix of critical, high, medium, and low severity issues

**Key Vulnerable Packages:**
- `laravel/framework: 5.8.35` (multiple CVEs)
- `symfony/*: 4.3.4` (security advisories)
- `guzzlehttp/guzzle: 6.3.3` (header injection, cookie leakage)
- `monolog/monolog: 1.24.0` (RCE vulnerability)
- `phpmailer/phpmailer: 6.0.7` (RCE, object injection)
- `firebase/php-jwt: 5.0.0` (algorithm confusion)
- And 97+ more...

### Code Vulnerabilities (SAST)

**28 intentional code vulnerabilities** including:

1. **SQL Injection** (CWE-89) - index.php:64
2. **Command Injection** (CWE-78) - index.php:73
3. **Path Traversal** (CWE-22) - index.php:82
4. **Cross-Site Scripting** (CWE-79) - index.php:91
5. **Server-Side Request Forgery** (CWE-918) - index.php:99
6. **Remote Code Execution via eval()** (CWE-94) - index.php:109
7. **Insecure Deserialization** (CWE-502) - index.php:117
8. **Weak Cryptography - MD5** (CWE-327) - index.php:127
9. **XXE Injection** (CWE-611) - index.php:154
10. **YAML Deserialization** (CWE-502) - index.php:164
11. **Mass Assignment** (CWE-915) - index.php:178
12. **IDOR** (CWE-639) - index.php:193
13. **Missing Authentication** (CWE-306) - index.php:206
14. **Unrestricted File Upload** (CWE-434) - index.php:217
15. **Open Redirect** (CWE-601) - index.php:227
16. **LDAP Injection** (CWE-90) - index.php:236
17. **XPath Injection** (CWE-643) - index.php:245
18. **Server-Side Template Injection** (CWE-1336) - index.php:256
19. **Race Condition** (CWE-362) - index.php:269
20. **Insufficient Logging** (CWE-778) - index.php:279
21. **Insecure Randomness** (CWE-330) - index.php:287
22. **Integer Overflow** (CWE-190) - index.php:296
23. **Hardcoded Password** (CWE-259) - index.php:8-11
24. **Information Exposure** (CWE-209) - index.php:13-15
25. **Missing Rate Limiting** (CWE-770) - index.php:334
26. **Cleartext Transmission** (CWE-319) - index.php:347
27. **Sensitive Data in GET** (CWE-598) - index.php:356
28. **Exposed Secrets** - .env file

## 🚀 Setup

### Prerequisites
- PHP 7.2+ (application uses PHP 7.2 features)
- Composer

### Installation

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/vulnerable-php-app.git
cd vulnerable-php-app

# Install dependencies (expect warnings about vulnerabilities)
composer install

# Start the development server
php -S localhost:8000
```

### Access the Application
Open your browser to `http://localhost:8000`

## 🔍 Testing Vulnerabilities

### Scan with Snyk
```bash
# Install Snyk CLI
npm install -g snyk

# Authenticate
snyk auth

# Test for vulnerabilities
snyk test

# Expected output: 200+ vulnerabilities
```

### Scan with Composer Audit
```bash
composer audit
```

### Available Vulnerable Endpoints

The application exposes multiple intentionally vulnerable endpoints:

- `POST /api/login` - SQL Injection
- `GET /api/exec?cmd=ls` - Command Injection
- `GET /api/files?filename=test.txt` - Path Traversal
- `POST /api/upload` - Unrestricted File Upload
- `GET /api/search?query=test` - XSS
- `GET /api/proxy?url=http://example.com` - SSRF
- `POST /api/evaluate` - RCE via eval
- `POST /api/deserialize` - Insecure Deserialization
- `DELETE /api/admin/users/{id}` - Missing Authentication
- `GET /api/users/{id}` - IDOR
- `POST /api/parse-xml` - XXE Injection
- `POST /api/parse-yaml` - YAML Deserialization
- `POST /api/register` - Mass Assignment
- `GET /api/debug` - Sensitive Data Exposure
- And 13 more...

## 📚 Documentation

- **[VULNERABILITIES.md](VULNERABILITIES.md)** - Detailed vulnerability catalog with CVEs, CWEs, and remediation guidance
- **.env** - Exposed secrets and credentials (intentionally vulnerable)

## 🛡️ Remediation Scenarios

This application demonstrates various remediation complexities:

### Simple Direct Upgrades (30-40 vulnerabilities)
```bash
# Example: Update Guzzle
composer require guzzlehttp/guzzle:^7.5
```

### Framework Major Version Upgrade (100+ vulnerabilities)
```bash
# Requires significant code changes
composer require laravel/framework:^10.0
# Must update application code for breaking changes
```

### Transitive Dependency Resolution (50+ vulnerabilities)
- Symfony vulnerabilities fixed by Laravel upgrade
- Diamond dependency scenarios
- Requires understanding dependency tree

### Deprecated Package Migration (10-20 vulnerabilities)
- Some packages no longer maintained
- Requires finding alternative solutions

## ⚠️ Security Notice

**DO NOT:**
- Deploy this application to production
- Expose this application to the internet
- Use any code patterns from this app in real applications
- Commit the .env file to version control (it's included here for educational purposes only)

**DO:**
- Use for security testing and tool validation
- Use for security training and education
- Run in isolated environments only
- Understand each vulnerability before testing

## 📖 Educational Use

This application is designed for:
- Testing SAST/SCA security scanners (Snyk, SonarQube, Checkmarx, etc.)
- Security training and workshops
- Understanding vulnerability remediation complexity
- Practicing secure coding techniques
- Testing CI/CD security pipelines

## 🤝 Contributing

This is an intentionally vulnerable application. "Fixes" that remove vulnerabilities are not accepted, as the vulnerabilities are the features!

However, contributions welcome for:
- Additional vulnerability examples
- Documentation improvements
- Additional remediation scenario examples

## 📄 License

MIT License - Educational and testing purposes only.

## ⚡ Quick Start for Security Testing

```bash
# 1. Install dependencies
composer install

# 2. Scan with Snyk
snyk test --all-projects

# 3. Review vulnerabilities
cat VULNERABILITIES.md

# 4. Test code vulnerabilities
curl http://localhost:8000/api/debug

# 5. Practice remediation
# Try fixing one vulnerability at a time and rescanning
```

---

**Remember**: This application is intentionally insecure. Use responsibly and only in controlled environments!
