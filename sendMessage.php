<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];


    file_put_contents('john.txt', $name . ': ' . $message . PHP_EOL, FILE_APPEND);
}
?>