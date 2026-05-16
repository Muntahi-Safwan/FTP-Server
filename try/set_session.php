<?php
session_start();
$_SESSION['filter_search']=0;

if (isset($_POST['id'])) {
    $_SESSION['filter_search'] = (int)$_POST['id'];
}
?>