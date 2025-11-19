// =====================================
// RESERVATION.JS
// Gère le bouton réserver
// =====================================

document.addEventListener("click", (e) => {
    if (e.target.id !== "btnReserve") return;

    const idTrajet = e.target.dataset.id;
    const userId = localStorage.getItem("userId");

    if (!userId) {
        alert("Vous devez être connecté pour réserver.");
        window.location.href = "login.html";
        return;
    }

    fetch("../back/controllers/reserver.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_trajet: idTrajet, id_utilisateur: userId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Réservation confirmée !");
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(() => alert("Erreur serveur."));
});
