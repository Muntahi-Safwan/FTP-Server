<?php
session_start();

if (isset($_POST['id'])) {
    $_SESSION['filter_search'] = $_POST['id'];
}
?>