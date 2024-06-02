<?php
$hash = '$2y$10$jiG7mxkAwvYOHBfchr9lpOw597UIANfo9f3enXG2cWStgU.pW6pbe';
$password = 'admin123';

if (password_verify($password, $hash)) {
    echo "Mot de passe correct";
} else {
    echo "Mot de passe incorrect";
}
?>
