<?php
session_start();

// Конфигурация подключения к базе данных
$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Проверка логина и пароля
    $stmt = $pdo->prepare("SELECT id, login, password, fullname FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Авторизация успешна, создаем сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['login'];
        // Перенаправление на главную страницу
        header('Location: index.php');
        exit();
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>

<!-- <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
</head>
<body>
    <h1>Авторизация</h1>
    <form method="POST">
        <div>
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" required>
        </div>
        <div>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Войти</button>
    </form>
</body>
</html> -->
