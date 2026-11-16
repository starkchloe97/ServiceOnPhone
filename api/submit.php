<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function respond(int $status, bool $success, string $message, array $extra = []): void
{
    http_response_code($status);
    echo json_encode(
        array_merge(['success' => $success, 'message' => $message], $extra),
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    respond(405, false, 'Method not allowed. Send this form using POST.');
}

$contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($contentLength > 16384) {
    respond(413, false, 'The submitted form is too large.');
}

$rawBody = file_get_contents('php://input');
$input = json_decode($rawBody ?: '', true);

if (!is_array($input)) {
    respond(400, false, 'Invalid request body. Please refresh the page and try again.');
}

// A filled honeypot field is treated as successful but is not stored.
if (!empty($input['website'])) {
    respond(200, true, 'Thank you. Your request has been received.');
}

$readText = static function (string $key, int $maxLength) use ($input): string {
    $value = $input[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }

    $value = trim(strip_tags(str_replace("\0", '', $value)));
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength, 'UTF-8');
    }

    return substr($value, 0, $maxLength);
};

$name = $readText('name', 100);
$phone = $readText('phone', 24);
$email = $readText('email', 190);
$message = $readText('message', 2000);
$source = $readText('source', 24);

if ($name === '' || $phone === '') {
    respond(422, false, 'Please provide your name and phone number.');
}

if (preg_match('/[\r\n]/', $name)) {
    respond(422, false, 'Please enter a valid name.');
}

if (!preg_match('/^[0-9+\s().-]{7,24}$/D', $phone)) {
    respond(422, false, 'Please enter a valid phone number.');
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(422, false, 'Please enter a valid email address.');
}

$allowedSources = ['contact', 'callback', 'service'];
if (!in_array($source, $allowedSources, true)) {
    $source = 'contact';
}

$configPath = __DIR__ . '/config/database.php';
if (!is_file($configPath)) {
    error_log('ServiceOnPhone: database configuration is missing.');
    respond(500, false, 'The contact form is not configured yet. Please contact us by phone.');
}

try {
    $database = require $configPath;

    if (!is_array($database)) {
        throw new RuntimeException('Database configuration must return an array.');
    }

    $host = (string) ($database['host'] ?? '127.0.0.1');
    $port = (int) ($database['port'] ?? 3306);
    $databaseName = (string) ($database['database'] ?? '');
    $username = (string) ($database['username'] ?? '');
    $password = (string) ($database['password'] ?? '');
    $charset = (string) ($database['charset'] ?? 'utf8mb4');

    if ($databaseName === '' || $username === '') {
        throw new RuntimeException('Database name and username are required.');
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $host,
        $port,
        $databaseName,
        $charset
    );

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $statement = $pdo->prepare(
        'INSERT INTO service_leads (name, phone, email, message, source, ip_address, user_agent)
         VALUES (:name, :phone, :email, :message, :source, :ip_address, :user_agent)'
    );

    $statement->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email !== '' ? $email : null,
        ':message' => $message !== '' ? $message : null,
        ':source' => $source,
        ':ip_address' => substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45) ?: null,
        ':user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500) ?: null,
    ]);

    respond(201, true, 'Thank you. Your request has been received. Our team will contact you soon.');
} catch (Throwable $exception) {
    error_log('ServiceOnPhone contact form error: ' . $exception->getMessage());
    respond(500, false, 'We could not submit your request right now. Please try again later or call us.');
}
