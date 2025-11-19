<?php


header("Content-Type: application/json");

// Autoload Composer
require __DIR__ . "/../../vendor/autoload.php";

// Charger le .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

$uri = $_ENV["MONGO_URI"] ?? null;

if (!$uri) {
    echo json_encode(["success" => false, "error" => "MONGO_URI manquant"]);
    exit;
}

try {
    // Connexion MongoDB
    $client = new MongoDB\Client($uri);
    $collection = $client->EcoRide->search_logs;

    // Lecture JSON envoyé par fetch()
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["success" => false, "error" => "JSON invalide"]);
        exit;
    }

    // Insertion
    $insert = $collection->insertOne([
        "depart" => $data["depart"] ?? null,
        "arrivee" => $data["arrivee"] ?? null,
        "date" => $data["date"] ?? null,
        "userId" => $data["userId"] ?? null,
        "timestamp" => new MongoDB\BSON\UTCDateTime()
    ]);

    echo json_encode([
        "success" => true,
        "id" => (string) $insert->getInsertedId()
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
