<?php
session_start();

// Уничтожаем все данные сессии
session_unset(); // Удаляет все переменные сессии
session_destroy(); // Уничтожает сессию

// Удаляем куки сессии (если они установлены)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Перенаправляем на главную страницу
header("Location: index.php");
exit();
?>
