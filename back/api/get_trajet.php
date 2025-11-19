<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

try {
    if (!isset($_GET['id'])) {
        echo json_encode(["success" => false, "message" => "ID manquant"]);
        exit;
    }

    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("
        SELECT 
            t.id,
            t.ville_depart,
            t.ville_arrivee,
            t.date_trajet,
            t.heure_trajet,
            t.prix,
            t.places,
            u.nom AS chauffeur,
            'default-driver.png' AS chauffeur_photo
        FROM trajets t
        JOIN utilisateurs u ON t.id_utilisateur = u.id
        WHERE t.id = ?
    ");

    $stmt->execute([$id]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trajet) {
        echo json_encode(["success" => false, "message" => "Trajet introuvable"]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "trajet" => $trajet
    ]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
