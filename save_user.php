<?php
session_start();

// Включаем отображение ошибок (для разработки)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Разрешён только POST метод']));
}

// Обязательные поля
$required_fields = ['login', 'password', 'fullname', 'citizenship', 'birthdate', 'passport'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        die(json_encode(['error' => "Не заполнено обязательное поле: $field"]));
    }
}

if (!isset($_POST['agree'])) {
    http_response_code(400);
    die(json_encode(['error' => "Вы должны согласиться с условиями обработки данных"]));
}

// Подключение к базе данных
$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => "Ошибка подключения к базе данных"]));
}

// Подготовка данных
$data = [
    'login' => trim($_POST['login']),
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'fullname' => trim($_POST['fullname']),
    'citizenship' => trim($_POST['citizenship']),
    'birthdate' => $_POST['birthdate'],
    'passport' => trim($_POST['passport']),
    'passport_expiry' => !empty($_POST['passport_expiry']) ? $_POST['passport_expiry'] : null,
    'in_russia' => $_POST['in_russia'] ?? null,
    'speak_russian' => $_POST['speak_russian'] ?? null,
    'agree' => 1,
    'passport_pdf' => null,
    'signed_docs' => null
];

// Обработка файлов (если они есть)
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Функция для обработки загрузки файлов
function handleUpload($fileKey, $uploadDir) {
    if (empty($_FILES[$fileKey]['name'])) {
        return null;
    }

    $filename = uniqid() . '_' . basename($_FILES[$fileKey]['name']);
    $target = $uploadDir . $filename;

    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $target)) {
        return $filename;
    }

    return null;
}

$data['passport_pdf'] = handleUpload('passport_pdf', $upload_dir);
$data['signed_docs'] = handleUpload('signed_docs', $upload_dir);

try {
    // Проверка существующего пользователя
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$data['login']]);

    if ($stmt->fetch()) {
        http_response_code(400);
        die(json_encode(['error' => "Пользователь с таким логином уже существует"]));
    }

    // Добавление нового пользователя
    $stmt = $pdo->prepare("INSERT INTO users
        (login, password, fullname, citizenship, birthdate, passport, passport_expiry,
         in_russia, speak_russian, agree, passport_pdf, signed_docs, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->execute([
        $data['login'],
        $data['password'],
        $data['fullname'],
        $data['citizenship'],
        $data['birthdate'],
        $data['passport'],
        $data['passport_expiry'],
        $data['in_russia'],
        $data['speak_russian'],
        $data['agree'],
        $data['passport_pdf'],
        $data['signed_docs']
    ]);

    // Установка сессии
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['login'] = $data['login'];

    // Успешный ответ
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => "Ошибка при сохранении данных"]));
}
