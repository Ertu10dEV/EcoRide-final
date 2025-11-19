<?php
session_start();
header("Content-Type: application/json");

// Si aucune session
if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "logged" => false,
        "message" => "Aucune session active"
    ]);
    exit;
}

// Si session active
echo json_encode([
    "logged" => true,
    "user_id" => $_SESSION["user_id"],
    "user_nom" => $_SESSION["user_nom"],
    "user_email" => $_SESSION["user_email"]
]);
