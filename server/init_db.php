<?php
require_once 'config.php';

try {
    // Создание таблицы passes (пропусков)
    $sql = "CREATE TABLE IF NOT EXISTS passes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        gender ENUM('male', 'female') NOT NULL,
        table_number INT NOT NULL,
        seat_number INT NOT NULL,
        pass_number VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_name (name)
    )";

    $pdo->exec($sql);
    echo "Таблица passes успешно создана или уже существует.";
} catch(PDOException $e) {
    echo "Ошибка при создании таблицы: " . $e->getMessage();
}
?>