<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if (empty($email) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Veuillez remplir tous les champs."
    ]);
    exit;
}

try {
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $email]);

    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user["mot_de_passe"])) {
        echo json_encode([
            "success" => false,
            "message" => "Email ou mot de passe incorrect."
        ]);
        exit;
    }

    // ---- SESSION ----
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_nom"] = $user["nom"];
    $_SESSION["user_email"] = $user["email"];

    echo json_encode([
        "success" => true,
        "userId" => $user["id"],
        "userNom" => $user["nom"],
        "userEmail" => $user["email"]
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
