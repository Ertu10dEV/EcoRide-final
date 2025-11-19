<?php
session_start();
header("Content-Type: application/json");

$response = [
    "logged" => false
];

if (isset($_SESSION["user_id"])) {
    $response["logged"] = true;
    $response["userId"] = $_SESSION["user_id"];
    $response["userNom"] = $_SESSION["user_nom"];
    $response["userEmail"] = $_SESSION["user_email"];
}

echo json_encode($response);
