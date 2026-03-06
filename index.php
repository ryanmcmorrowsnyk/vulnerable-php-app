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

// VULNERABILITY: XXE Injection (CWE-611)
if ($request_uri === '/api/parse-xml' && $request_method === 'POST') {
    $xml = file_get_contents('php://input');
    // Vulnerable: libxml_disable_entity_loader not called
    $doc = new DOMDocument();
    $doc->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
    echo json_encode(['parsed' => true]);
    exit;
}

// VULNERABILITY: YAML Deserialization (CWE-502)
if ($request_uri === '/api/parse-yaml' && $request_method === 'POST') {
    $yaml = file_get_contents('php://input');
    // Vulnerable: Unsafe YAML parsing (if yaml extension loaded)
    // This would execute arbitrary PHP code embedded in YAML
    echo json_encode(['yaml' => $yaml, 'vulnerable' => true]);
    exit;
}

// VULNERABILITY: Mass Assignment (CWE-915)
if ($request_uri === '/api/register' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    // Vulnerable: Allows setting 'role' field directly
    $new_user = [
        'id' => count($users) + 1,
        'username' => $data['username'] ?? '',
        'password' => password_hash($data['password'] ?? '', PASSWORD_DEFAULT),
        'email' => $data['email'] ?? '',
        'role' => $data['role'] ?? 'user' // Attacker can set role=admin
    ];
    $users[] = $new_user;
    echo json_encode(['success' => true, 'user' => $new_user]);
    exit;
}

// VULNERABILITY: IDOR (Insecure Direct Object Reference) (CWE-639)
if (preg_match('#^/api/users/(\d+)$#', $request_uri, $matches)) {
    $user_id = (int)$matches[1];
    // Vulnerable: No authorization check
    $user = null;
    foreach ($users as $u) {
        if ($u['id'] === $user_id) {
            $user = $u;
            break;
        }
    }
    echo json_encode(['user' => $user]);
    exit;
}

// VULNERABILITY: Missing Authentication (CWE-306)
if (preg_match('#^/api/admin/users/(\d+)$#', $request_uri, $matches) && $request_method === 'DELETE') {
    $user_id = (int)$matches[1];
    // Vulnerable: No authentication or authorization required
    $users = array_filter($users, function($u) use ($user_id) {
        return $u['id'] !== $user_id;
    });
    echo json_encode(['success' => true, 'deleted' => $user_id]);
    exit;
}

// VULNERABILITY: Unrestricted File Upload (CWE-434)
if ($request_uri === '/api/upload' && $request_method === 'POST') {
    // Vulnerable: No file type validation
    if (isset($_FILES['file'])) {
        $upload_dir = './uploads/';
        $upload_file = $upload_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
        echo json_encode(['success' => true, 'path' => $upload_file]);
    } else {
        echo json_encode(['error' => 'No file uploaded']);
    }
    exit;
}

// VULNERABILITY: Open Redirect (CWE-601)
if ($request_uri === '/api/redirect') {
    $url = $_GET['url'] ?? 'https://example.com';
    // Vulnerable: No validation of redirect URL
    header("Location: $url");
    exit;
}

// VULNERABILITY: LDAP Injection (CWE-90)
if ($request_uri === '/api/ldap-search') {
    $username = $_GET['username'] ?? '';
    // Vulnerable: Direct interpolation into LDAP query
    $ldap_query = "(&(objectClass=user)(username=$username))";
    echo json_encode(['query' => $ldap_query, 'vulnerable' => true]);
    exit;
}

// VULNERABILITY: XPATH Injection (CWE-643)
if ($request_uri === '/api/xpath-search') {
    $name = $_GET['name'] ?? '';
    // Vulnerable: User input in XPath query
    $xpath = "//user[name='$name']";
    echo json_encode(['xpath' => $xpath, 'vulnerable' => true]);
    exit;
}

// VULNERABILITY: Server-Side Template Injection (CWE-1336)
if ($request_uri === '/api/template' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $template = $data['template'] ?? '';
    // Vulnerable: Eval-based template rendering
    ob_start();
    eval('?>' . $template);
    $output = ob_get_clean();
    echo json_encode(['output' => $output]);
    exit;
}

// VULNERABILITY: Race Condition (CWE-362)
if ($request_uri === '/api/transfer' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $from = $data['from'] ?? '';
    $to = $data['to'] ?? '';
    $amount = $data['amount'] ?? 0;
    // Vulnerable: No locking, allows race conditions
    // Check balance (time window for race condition)
    usleep(100000); // 100ms delay
    // Perform transfer
    echo json_encode(['success' => true, 'transferred' => $amount]);
    exit;
}

// VULNERABILITY: Insufficient Logging (CWE-778)
if ($request_uri === '/api/sensitive-operation' && $request_method === 'POST') {
    // Vulnerable: No logging of sensitive operations
    $data = json_decode(file_get_contents('php://input'), true);
    // Perform sensitive operation without audit trail
    echo json_encode(['success' => true]);
    exit;
}

// VULNERABILITY: Insecure Randomness (CWE-330)
if ($request_uri === '/api/generate-token') {
    // Vulnerable: Using predictable random
    $token = md5(rand());
    echo json_encode(['token' => $token, 'algorithm' => 'rand()+md5']);
    exit;
}

// VULNERABILITY: Integer Overflow (CWE-190)
if ($request_uri === '/api/calculate') {
    $a = (int)($_GET['a'] ?? 0);
    $b = (int)($_GET['b'] ?? 0);
    // Vulnerable: No overflow checking
    $result = $a * $b;
    echo json_encode(['result' => $result]);
    exit;
}

// VULNERABILITY: Use of Hard-coded Password (CWE-259)
if ($request_uri === '/api/admin-login' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'] ?? '';
    // Vulnerable: Hardcoded admin password
    if ($password === ADMIN_PASSWORD) {
        echo json_encode(['success' => true, 'role' => 'admin']);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// VULNERABILITY: Information Exposure Through Error Messages (CWE-209)
if ($request_uri === '/api/database-connect') {
    // Vulnerable: Detailed error messages exposed
    try {
        $conn = new PDO("mysql:host=localhost;dbname=testdb", "root", DB_PASSWORD);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Vulnerable: Exposes database structure and credentials
        echo json_encode(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }
    exit;
}

// VULNERABILITY: Missing Rate Limiting (CWE-770)
if ($request_uri === '/api/brute-force-target' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'] ?? '';
    // Vulnerable: No rate limiting, allows brute force
    if ($password === 'correct_password') {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// VULNERABILITY: Cleartext Transmission of Sensitive Information (CWE-319)
if ($request_uri === '/api/send-credentials' && $request_method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    // Vulnerable: Credentials sent in cleartext (if not using HTTPS)
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    echo json_encode(['received' => true, 'username' => $username]);
    exit;
}

// VULNERABILITY: Use of GET Request Method With Sensitive Query Strings (CWE-598)
if (strpos($request_uri, '/api/reset-password') === 0) {
    $token = $_GET['token'] ?? '';
    $new_password = $_GET['password'] ?? '';
    // Vulnerable: Sensitive data in GET parameters (appears in logs)
    echo json_encode(['success' => true, 'token' => $token]);
    exit;
}

echo json_encode(['error' => 'Not found']);
?>
