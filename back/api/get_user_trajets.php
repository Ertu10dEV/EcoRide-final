<?php
session_start();
header("Content-Type: application/json");
require "../config/db.php";

$userId = $_GET["user"] ?? null;

if (!$userId) {
    echo json_encode([
        "success" => false,
        "trajets" => [],
        "reservations" => []
    ]);
    exit;
}

/* --- RÉCUPÉRER TRAJETS PUBLIÉS PAR L’UTILISATEUR --- */
$stmt = $pdo->prepare("SELECT * FROM trajets WHERE id_utilisateur = ?");
$stmt->execute([$userId]);
$trajets = $stmt->fetchAll();

/* --- RÉCUPÉRER RÉSERVATIONS DE L’UTILISATEUR --- */
$stmt2 = $pdo->prepare("SELECT r.*, t.ville_depart, t.ville_arrivee, t.date_trajet, t.heure_trajet, t.prix
                        FROM reservations r
                        JOIN trajets t ON r.id_trajet = t.id
                        WHERE r.id_utilisateur = ?");
$stmt2->execute([$userId]);
$reservations = $stmt2->fetchAll();

/* --- RÉPONSE JSON COMPLÈTE --- */
echo json_encode([
    "success" => true,
    "trajets" => $trajets,
    "reservations" => $reservations
]);
