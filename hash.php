<?php
$password = "password"; // The password to hash manually
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

echo "Hashed Password: " . $hashedPassword;
?>
