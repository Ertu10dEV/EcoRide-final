<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../config/db.php";

// Vérifier connexion
if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Vous devez être connecté pour réserver."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id_trajet = $data["id_trajet"] ?? null;

if (!$id_trajet) {
    echo json_encode(["success" => false, "message" => "ID trajet manquant."]);
    exit;
}

try {
    // Vérifier si des places sont dispos
    $placeCheck = $pdo->prepare("SELECT places FROM trajets WHERE id = :id");
    $placeCheck->execute([":id" => $id_trajet]);
    $trajet = $placeCheck->fetch();

    if (!$trajet) {
        echo json_encode(["success" => false, "message" => "Trajet introuvable."]);
        exit;
    }

    if ($trajet["places"] <= 0) {
        echo json_encode(["success" => false, "message" => "Plus de places disponibles."]);
        exit;
    }

    // Vérifier si déjà réservé
    $checkResa = $pdo->prepare("
        SELECT id FROM reservations
        WHERE id_utilisateur = :uid AND id_trajet = :tid
    ");
    $checkResa->execute([
        ":uid" => $_SESSION["user_id"],
        ":tid" => $id_trajet
    ]);

    if ($checkResa->fetch()) {
        echo json_encode([
            "success" => false,
            "message" => "Vous avez déjà réservé ce trajet."
        ]);
        exit;
    }

    // Insérer la réservation
    $insert = $pdo->prepare("
        INSERT INTO reservations (id_utilisateur, id_trajet)
        VALUES (:uid, :tid)
    ");

    $insert->execute([
        ":uid" => $_SESSION["user_id"],
        ":tid" => $id_trajet
    ]);

    // Décrémenter les places
    $update = $pdo->prepare("
        UPDATE trajets SET places = places - 1 WHERE id = :id
    ");

    $update->execute([":id" => $id_trajet]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur : " . $e->getMessage()
    ]);
}
