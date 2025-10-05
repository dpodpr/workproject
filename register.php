<?php
// Включаем отображение ошибок (удалите на продакшене)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Подключаемся к базе данных
        $host = 'localhost';
        $dbname = 'test';
        $username = 'root';
        $password = '';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES utf8");

        // Получаем данные из формы
        $login = $_POST['login'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $fullname = $_POST['fullname'];
        $citizenship = $_POST['citizenship'];
        $birthdate = $_POST['birthdate'];
        $passport = $_POST['passport'];
        $passport_expiry = $_POST['passport_expiry'];
        $in_russia = $_POST['in_russia'];
        $speak_russian = $_POST['speak_russian'];
        $agree = isset($_POST['agree']) ? 1 : 0;

        // Обработка загрузки файлов
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $passport_pdf_path = '';
        $signed_docs_path = '';

        if (isset($_FILES['passport_pdf']) && $_FILES['passport_pdf']['error'] === UPLOAD_ERR_OK) {
            $passport_pdf_path = $upload_dir . uniqid() . '_' . basename($_FILES['passport_pdf']['name']);
            move_uploaded_file($_FILES['passport_pdf']['tmp_name'], $passport_pdf_path);
        }

        if (isset($_FILES['signed_docs']) && $_FILES['signed_docs']['error'] === UPLOAD_ERR_OK) {
            $signed_docs_path = $upload_dir . uniqid() . '_' . basename($_FILES['signed_docs']['name']);
            move_uploaded_file($_FILES['signed_docs']['tmp_name'], $signed_docs_path);
        }

        // Проверяем, не существует ли уже пользователь с таким логином
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
        $check_stmt->execute([$login]);
        if ($check_stmt->fetchColumn() > 0) {
            throw new Exception("Пользователь с таким логином уже существует");
        }

        // SQL-запрос для добавления пользователя
        $sql = "INSERT INTO users (login, password, fullname, citizenship, birthdate, passport,
                passport_expiry, in_russia, speak_russian, agree, passport_pdf, signed_docs)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $login,
            $password,
            $fullname,
            $citizenship,
            $birthdate,
            $passport,
            $passport_expiry,
            $in_russia,
            $speak_russian,
            $agree,
            $passport_pdf_path,
            $signed_docs_path
        ]);

        if ($result) {
            // Создаем сессию для нового пользователя
            session_start();
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['login'] = $login;

            // Отправляем JSON-ответ с успехом
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Регистрация успешно завершена']);
        } else {
            throw new Exception("Ошибка при добавлении пользователя");
        }

    } catch (Exception $e) {
        // Отправляем JSON-ответ с ошибкой
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Отправляем JSON-ответ с ошибкой метода
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
}
?>
