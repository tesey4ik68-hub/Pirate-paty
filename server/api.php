<?php
// Установка заголовков для CORS и JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Всегда возвращаем JSON
function sendJson($data) {
    echo json_encode($data);
    exit();
}

// Подключение к базе данных
require_once 'config.php';

// Получение метода запроса
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo);
        break;
    case 'PUT':
        handlePut($pdo);
        break;
    case 'DELETE':
        handleDelete($pdo);
        break;
    default:
        sendJson(['error' => 'Неподдерживаемый метод']);
        break;
}

function handleGet($pdo) {
    try {
        if (isset($_GET['name'])) {
            // Получение конкретного пропуска по имени
            $stmt = $pdo->prepare("SELECT * FROM passes WHERE name = ?");
            $stmt->execute([$_GET['name']]);
            $pass = $stmt->fetch(PDO::FETCH_ASSOC);
            sendJson($pass ?: ['error' => 'Пропуск не найден']);
        } else {
            // Получение всех пропусков
            $stmt = $pdo->query("SELECT * FROM passes ORDER BY table_number, seat_number");
            $passes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendJson($passes);
        }
    } catch(PDOException $e) {
        sendJson(['error' => 'Ошибка при получении пропусков: ' . $e->getMessage()]);
    }
}

function handlePost($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Проверка обязательных полей
        if (!$input || !isset($input['name']) || !isset($input['gender']) || 
            !isset($input['table_number']) || !isset($input['seat_number']) || 
            !isset($input['pass_number'])) {
            sendJson(['error' => 'Некорректные данные для создания пропуска']);
        }
        
        // Проверка, существует ли уже пропуск с таким именем
        $stmt = $pdo->prepare("SELECT id FROM passes WHERE name = ?");
        $stmt->execute([$input['name']]);
        $existingPass = $stmt->fetch();
        
        if ($existingPass) {
            sendJson(['error' => 'Пропуск с таким именем уже существует']);
        }
        
        // Вставка нового пропуска
        $stmt = $pdo->prepare("INSERT INTO passes (name, gender, table_number, seat_number, pass_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $input['name'],
            $input['gender'],
            $input['table_number'],
            $input['seat_number'],
            $input['pass_number']
        ]);
        
        sendJson(['success' => 'Пропуск успешно создан', 'id' => $pdo->lastInsertId()]);
    } catch(PDOException $e) {
        sendJson(['error' => 'Ошибка при создании пропуска: ' . $e->getMessage()]);
    }
}

function handlePut($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Проверка обязательных полей
        if (!$input || !isset($input['name']) || !isset($input['gender']) || 
            !isset($input['table_number']) || !isset($input['seat_number']) || 
            !isset($input['pass_number'])) {
            sendJson(['error' => 'Некорректные данные для обновления пропуска']);
        }
        
        // Обновление пропуска
        $stmt = $pdo->prepare("UPDATE passes SET gender = ?, table_number = ?, seat_number = ?, pass_number = ? WHERE name = ?");
        $result = $stmt->execute([
            $input['gender'],
            $input['table_number'],
            $input['seat_number'],
            $input['pass_number'],
            $input['name']
        ]);
        
        if ($stmt->rowCount() > 0) {
            sendJson(['success' => 'Пропуск успешно обновлен']);
        } else {
            sendJson(['error' => 'Пропуск не найден или не был изменен']);
        }
    } catch(PDOException $e) {
        sendJson(['error' => 'Ошибка при обновлении пропуска: ' . $e->getMessage()]);
    }
}

function handleDelete($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['name'])) {
            sendJson(['error' => 'Не указано имя для удаления']);
        }
        
        // Удаление пропуска
        $stmt = $pdo->prepare("DELETE FROM passes WHERE name = ?");
        $stmt->execute([$input['name']]);
        
        if ($stmt->rowCount() > 0) {
            sendJson(['success' => 'Пропуск успешно удален']);
        } else {
            sendJson(['error' => 'Пропуск не найден']);
        }
    } catch(PDOException $e) {
        sendJson(['error' => 'Ошибка при удалении пропуска: ' . $e->getMessage()]);
    }
}
?>