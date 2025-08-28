<?php
// Данные для подключения к базе данных reg.ru
// Замените эти значения на реальные данные вашей базы данных на reg.ru
//
// Пример данных для подключения (замените на свои):
// $host = 'server282.hosting.reg.ru';
// $dbname = ' имя_вашей_базы_данных';
// $username = ' имя_пользователя_базы_данных';
// $password = ' пароль_пользователя_базы_данных';

$host = 'server282.hosting.reg.ru';
$dbname = 'u3243360_default'; // Замените на имя вашей базы данных
$username = 'u3243360_tesey4ik'; // Замените на имя пользователя базы данных
$password = 'Papa1211!Papa1211!'; // Замените на пароль к базе данных

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