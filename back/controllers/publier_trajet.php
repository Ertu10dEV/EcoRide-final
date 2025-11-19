<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../config/db.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Vous devez être connecté pour publier un trajet."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$ville_depart = trim($data["ville_depart"] ?? "");
$ville_arrivee = trim($data["ville_arrivee"] ?? "");
$date_trajet = $data["date_trajet"] ?? "";
$heure_trajet = $data["heure_trajet"] ?? "";
$prix = $data["prix"] ?? "";
$places = $data["places"] ?? "";

if (!$ville_depart || !$ville_arrivee || !$date_trajet || !$heure_trajet || !$prix || !$places) {
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs doivent être remplis."
    ]);
    exit;
}

try {
    $sql = "INSERT INTO trajets
        (id_utilisateur, ville_depart, ville_arrivee, date_trajet, heure_trajet, prix, places)
        VALUES (:id_user, :vd, :va, :date_t, :heure_t, :prix, :places)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":id_user" => $_SESSION["user_id"],
        ":vd" => $ville_depart,
        ":va" => $ville_arrivee,
        ":date_t" => $date_trajet,
        ":heure_t" => $heure_trajet,
        ":prix" => $prix,
        ":places" => $places
    ]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur : " . $e->getMessage()
    ]);
}
