<?php
session_start();
header("Content-Type: application/json");
require "../config/db.php";

$userId = $_GET["user"] ?? null;

if (!$userId) {
    echo json_encode(["success" => false, "reservations" => []]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.*
    FROM reservations r
    JOIN trajets t ON r.id_trajet = t.id
    WHERE r.id_utilisateur = ?
");
$stmt->execute([$userId]);

echo json_encode([
    "success" => true,
    "reservations" => $stmt->fetchAll()
]);
