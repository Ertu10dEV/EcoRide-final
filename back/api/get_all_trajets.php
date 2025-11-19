<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->query("
        SELECT 
            id,
            id_utilisateur,
            ville_depart,
            ville_arrivee,
            date_trajet,
            heure_trajet,
            prix,
            places
        FROM trajets
        ORDER BY date_trajet ASC, heure_trajet ASC
    ");
    
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "trajets" => $trajets
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur : " . $e->getMessage()
    ]);
}
