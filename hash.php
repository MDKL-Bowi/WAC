<?php
$password = "superuser"; // The password you want to hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

echo "Hashed Password: " . $hashedPassword;
?>