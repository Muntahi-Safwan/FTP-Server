<?php

function emailExists($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function findUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function findUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUser($pdo, $name, $email, $password, $role) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO users (name, email, password_hash, role, created_at)
         VALUES (?, ?, ?, ?, NOW())"
    );
    return $stmt->execute([$name, $email, $hash, $role]);
}

function updateUserProfile($pdo, $id, $name, $email, $picture = null) {
    if ($picture) {
        $stmt = $pdo->prepare(
            "UPDATE users SET name=?, email=?, profile_picture=? WHERE id=?"
        );
        return $stmt->execute([$name, $email, $picture, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        return $stmt->execute([$name, $email, $id]);
    }
}

function updateUserPassword($pdo, $id, $newPassword) {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash=? WHERE id=?");
    return $stmt->execute([$hash, $id]);
}