<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$nom = trim($data["nom"] ?? "");
$email = trim($data["email"] ?? "");
$telephone = trim($data["telephone"] ?? "");
$mot_de_passe = $data["mot_de_passe"] ?? "";

if (empty($nom) || empty($email) || empty($mot_de_passe)) {
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs obligatoires doivent être remplis."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Email invalide."
    ]);
    exit;
}

// Vérifier si l'email existe déjà
try {
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
    $check->execute([":email" => $email]);

    if ($check->fetch()) {
        echo json_encode([
            "success" => false,
            "message" => "Cet email est déjà utilisé."
        ]);
        exit;
    }

    // Hash du mot de passe
    $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    $insert = $pdo->prepare("
        INSERT INTO utilisateurs (nom, email, mot_de_passe, telephone)
        VALUES (:nom, :email, :mot_de_passe, :telephone)
    ");

    $insert->execute([
        ":nom" => $nom,
        ":email" => $email,
        ":mot_de_passe" => $hash,
        ":telephone" => $telephone ?: null
    ]);

    echo json_encode([
        "success" => true
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
