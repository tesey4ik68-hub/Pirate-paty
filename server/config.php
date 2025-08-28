<?php
// Данные для подключения к базе данных reg.ru
// Замените эти значения на реальные данные вашей базы данных
$host = 'server282.hosting.reg.ru';
$dbname = 'ваша_база_данных'; // Замените на имя вашей базы данных
$username = 'ваш_пользователь'; // Замените на имя пользователя базы данных
$password = 'ваш_пароль'; // Замените на пароль к базе данных

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Вместо остановки выполнения, выводим сообщение об ошибке в формате JSON
    // чтобы клиентский код мог его обработать
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ошибка подключения к базе данных: ' . $e->getMessage()]);
    exit();
}
?>