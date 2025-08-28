-- Создание таблицы для хранения пропусков
CREATE TABLE IF NOT EXISTS passes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    table_number INT NOT NULL,
    seat_number INT NOT NULL,
    pass_number VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_name (name)
);

-- Добавление индексов для улучшения производительности
CREATE INDEX idx_table_number ON passes(table_number);
CREATE INDEX idx_seat_number ON passes(seat_number);
CREATE INDEX idx_pass_number ON passes(pass_number);