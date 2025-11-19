<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

// Récupération des paramètres GET
$depart = $_GET["depart"] ?? "";
$arrivee = $_GET["arrivee"] ?? "";
$date = $_GET["date"] ?? "";

// Vérification
if (empty($depart) || empty($arrivee) || empty($date)) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT * FROM trajets 
            WHERE ville_depart LIKE :depart 
            AND ville_arrivee LIKE :arrivee 
            AND date_trajet = :date";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":depart" => "%$depart%",
        ":arrivee" => "%$arrivee%",
        ":date" => $date
    ]);

    $resultats = $stmt->fetchAll();

    echo json_encode($resultats);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
