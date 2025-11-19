<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

// Vérifier connexion
if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Non connecté"
    ]);
    exit;
}

$userId = $_SESSION["user_id"];

try {
    // --- Infos utilisateur ---
    $stmtUser = $pdo->prepare("SELECT id, nom, email, telephone FROM utilisateurs WHERE id = :id");
    $stmtUser->execute([":id" => $userId]);
    $user = $stmtUser->fetch();

    // --- Trajets publiés ---
    $stmtTrajets = $pdo->prepare("
        SELECT id, ville_depart, ville_arrivee, date_trajet, heure_trajet, prix, places
        FROM trajets
        WHERE id_utilisateur = :id
        ORDER BY date_trajet DESC, heure_trajet DESC
    ");
    $stmtTrajets->execute([":id" => $userId]);
    $trajets = $stmtTrajets->fetchAll();

    // --- Réservations (avec infos trajet) ---
    $stmtResa = $pdo->prepare("
        SELECT 
            r.id AS id_reservation,
            r.date_resa,
            t.id AS id_trajet,
            t.ville_depart,
            t.ville_arrivee,
            t.date_trajet,
            t.heure_trajet,
            t.prix
        FROM reservations r
        INNER JOIN trajets t ON r.id_trajet = t.id
        WHERE r.id_utilisateur = :id
        ORDER BY r.date_resa DESC
    ");
    $stmtResa->execute([":id" => $userId]);
    $reservations = $stmtResa->fetchAll();

    echo json_encode([
        "success" => true,
        "user" => $user,
        "trajets" => $trajets,
        "reservations" => $reservations
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur : " . $e->getMessage()
    ]);
}
