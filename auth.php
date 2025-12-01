<?php

require 'sessao.php';

if (empty($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>
