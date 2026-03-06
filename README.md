# Vulnerable PHP Application

**⚠️ WARNING: This application is intentionally vulnerable and should NEVER be deployed to production!**

This is an intentionally vulnerable Laravel/PHP application designed for testing security remediation tools.

## Vulnerabilities

### Dependency Vulnerabilities (SCA)
- **100+ vulnerable Composer packages** from 2019
- Laravel 5.8.35 with extensive CVE-laden dependencies
- Symfony components with known vulnerabilities
- Expected **200+ total vulnerabilities**

### Code Vulnerabilities (SAST)
- SQL Injection, Command Injection, Path Traversal
- XSS, SSRF, RCE via eval
- Insecure Deserialization, XXE, YAML injection
- Hardcoded secrets, Weak crypto (MD5)
- 20+ total code vulnerabilities

## Setup

```bash
composer install
php -S localhost:8000
```

## License
MIT License - Educational purposes only.
