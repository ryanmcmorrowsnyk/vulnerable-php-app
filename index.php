<?php
/**
 * Intentionally Vulnerable PHP/Laravel Application
 * DO NOT USE IN PRODUCTION - FOR SECURITY TESTING ONLY
 */

// VULNERABILITY: Hardcoded secrets (CWE-798)
define('JWT_SECRET', 'super_secret_jwt_key_12345');
define('ADMIN_PASSWORD', 'admin123');
define('DB_PASSWORD', 'password123');
define('API_KEY', 'AKIA_FAKE_PHP_KEY_FOR_TESTING_ONLY');

// VULNERABILITY: Error reporting enabled in production (CWE-209)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// In-memory user store (simulating database)
$users = [
    ['id' => 1, 'username' => 'admin', 'password' => 'hashed_password', 'email' => 'admin@example.com', 'role' => 'admin'],
    ['id' => 2, 'username' => 'user', 'password' => 'hashed_password', 'email' => 'user@example.com', 'role' => 'user']
];

// Simple router
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_uri === '/' || $request_uri === '/index.php') {
    echo <<<HTML
<html>
<head><title>Vulnerable PHP App</title></head>
<body>
    <h1>Intentionally Vulnerable PHP/Laravel Application</h1>
    <p>This application contains numerous security vulnerabilities for testing purposes.</p>
    <h2>Available Endpoints:</h2>
    <ul>
        <li>POST /api/login - SQL Injection</li>
        <li>GET /api/exec?cmd=ls - Command Injection</li>
        <li>GET /api/files?filename=test.txt - Path Traversal</li>
        <li>POST /api/upload - Unrestricted File Upload</li>
        <li>GET /api/search?query=test - XSS</li>
        <li>GET /api/proxy?url=http://example.com - SSRF</li>
        <li>POST /api/evaluate - RCE via eval</li>
        <li>POST /api/deserialize - Insecure Deserialization</li>
        <li>DELETE /api/admin/users/{id} - Missing Authentication</li>
        <li>GET /api/users/{id} - IDOR</li>
        <li>POST /api/parse-xml - XXE Injection</li>
        <li>POST /api/parse-yaml - YAML Deserialization</li>
        <li>POST /api/register - Mass Assignment</li>
        <li>GET /api/debug - Sensitive Data Exposure</li>
    </ul>
</body>
</html>
HTML;
    exit;
}

// VULNERABILITY: SQL Injection (CWE-89)
if ($request_uri === '/api/login' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    // Vulnerable: Direct string concatenation
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    echo json_encode(['query' => $query, 'vulnerable' => true]);
    exit;
}

// VULNERABILITY: Command Injection (CWE-78)
if (strpos($request_uri, '/api/exec') === 0) {
    $cmd = $_GET['cmd'] ?? '';
    // Vulnerable: User input directly in shell command
    $output = shell_exec($cmd);
    echo json_encode(['success' => true, 'output' => $output]);
    exit;
}

// VULNERABILITY: Path Traversal (CWE-22)
if (strpos($request_uri, '/api/files') === 0) {
    $filename = $_GET['filename'] ?? '';
    // Vulnerable: No sanitization of file path
    $content = file_get_contents('./uploads/' . $filename);
    echo json_encode(['content' => $content]);
    exit;
}

// VULNERABILITY: XSS (CWE-79)
if (strpos($request_uri, '/api/search') === 0) {
    $query = $_GET['query'] ?? '';
    // Vulnerable: Reflects user input without sanitization
    echo "<h1>Search Results for: $query</h1>";
    exit;
}

// VULNERABILITY: SSRF (CWE-918)
if (strpos($request_uri, '/api/proxy') === 0) {
    $url = $_GET['url'] ?? '';
    // Vulnerable: No URL validation
    $content = file_get_contents($url);
    echo json_encode(['data' => $content]);
    exit;
}

// VULNERABILITY: RCE via eval (CWE-94)
if ($request_uri === '/api/evaluate' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $code = $data['code'] ?? '';
    // Vulnerable: Direct eval of user input
    eval($code);
    exit;
}

// VULNERABILITY: Insecure Deserialization (CWE-502)
if ($request_uri === '/api/deserialize' && $request_method === 'POST') {
    $data = file_get_contents('php://input');
    // Vulnerable: Unserializing untrusted data
    $obj = unserialize($data);
    echo json_encode(['result' => $obj]);
    exit;
}

// VULNERABILITY: Weak Cryptography (CWE-327)
if ($request_uri === '/api/hash' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'] ?? '';
    // Vulnerable: Using MD5
    $hash = md5($password);
    echo json_encode(['hash' => $hash, 'algorithm' => 'MD5']);
    exit;
}

// VULNERABILITY: Sensitive Data Exposure (CWE-200)
if ($request_uri === '/api/debug') {
    echo json_encode([
        'environment' => $_ENV,
        'server' => $_SERVER,
        'jwt_secret' => JWT_SECRET,
        'admin_password' => ADMIN_PASSWORD,
        'db_password' => DB_PASSWORD,
        'users' => $users
    ]);
    exit;
}

echo json_encode(['error' => 'Not found']);
?>
