<?php
// src/view/adminView.php — redirect to proper admin dashboard
require_once __DIR__ . '/../includes/admin_check.php';
header('Location: admin/dashboard.php');
exit;