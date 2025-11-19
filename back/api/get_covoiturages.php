<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=utf-8");

// ============================
// 1. Connexion SQL
// ============================
require_once __DIR__ . '/../config/db.php';

// Vérifie que la connexion existe
if (!isset($pdo)) {
    echo json_encode(["success" => false, "message" => "Connexion PDO non trouvée"]);
    exit;
}

try {

    // ============================
    // 2. Requête SQL
    // ============================
    $sql = "
        SELECT 
            t.id,
            t.ville_depart,
            t.ville_arrivee,
            t.date_trajet,
            t.heure_trajet,
            t.prix,
            t.places,
            u.nom AS chauffeur,
            u.photo AS chauffeur_photo
        FROM trajets t
        INNER JOIN utilisateurs u ON t.id_utilisateur = u.id
        WHERE t.places > 0
        ORDER BY t.date_trajet ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ============================
    // 3. Réponse JSON propre
    // ============================
    echo json_encode([
        "success" => true,
        "trajets" => $trajets
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
