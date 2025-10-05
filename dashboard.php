<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Если пользователь не авторизован, перенаправляем на главную страницу
    exit;
}

// Конфигурация подключения к базе данных
$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получаем данные пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            <h1>Личный кабинет</h1>
            <div class="user-info">
                <p><strong>Логин:</strong> <?php echo htmlspecialchars($_SESSION['login']); ?></p>
                <?php if (isset($user['fullname'])): ?>
                <p><strong>Полное имя:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
                <?php endif; ?>
            </div>

            <div class="actions">
                <a href="index.php" class="btn btn-primary">На главную</a>
                <a href="logout.php" class="btn btn-secondary">Выйти</a>
            </div>
        </div>
    </div>
</body>
</html>
