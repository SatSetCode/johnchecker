<?php
$messages = file('john.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($messages as $message) {
    echo '<p>' . htmlspecialchars($message) . '</p>';
}
?>