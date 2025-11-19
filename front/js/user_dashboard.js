document.addEventListener("DOMContentLoaded", () => {

    const nameField = document.getElementById("userName");
    const emailField = document.getElementById("userEmail");
    const trajetsContainer = document.getElementById("userTrajets");
    const reservationsContainer = document.getElementById("userReservations");

    // Si on n'est pas sur la page espace-utilisateur → stop
    if (!nameField || !emailField) return;

    console.log("📌 Chargement espace utilisateur…");

    // 1) Vérifier session
    fetch("../back/api/check_session.php")
        .then(res => res.json())
        .then(data => {

            console.log("SESSION =", data);

            if (!data.logged) {
                window.location.href = "login.html";
                return;
            }

            // Remplir infos utilisateur
            nameField.textContent = data.userNom;
            emailField.textContent = data.userEmail;

            // ============================
            //     TRAJETS PUBLIÉS
            // ============================
            fetch(`../back/api/get_user_trajets.php?user=${data.userId}`)
                .then(res => res.json())
                .then(d => {
                    console.log("TRAJETS =", d);

                    if (!d.success || !d.trajets || d.trajets.length === 0) {
                        trajetsContainer.innerHTML = "<p>Aucun trajet publié.</p>";
                        return;
                    }

                    trajetsContainer.innerHTML = "";

                    d.trajets.forEach(t => {
                        trajetsContainer.innerHTML += `
                            <div class="trajet-card">
                                <p>${t.ville_depart} → ${t.ville_arrivee}</p>
                                <p>${t.date_trajet} à ${t.heure_trajet}</p>
                                <p>${t.prix} €</p>
                            </div>
                        `;
                    });
                });

            // ============================
            //       RÉSERVATIONS
            // ============================
            fetch(`../back/api/get_user_reservations.php?user=${data.userId}`)
                .then(res => res.json())
                .then(d => {
                    console.log("RESERVATIONS =", d);

                    if (!d.success || !d.reservations || d.reservations.length === 0) {
                        reservationsContainer.innerHTML = "<p>Aucune réservation.</p>";
                        return;
                    }

                    reservationsContainer.innerHTML = "";

                    d.reservations.forEach(r => {
                        reservationsContainer.innerHTML += `
                            <div class="reservation-card">
                                <p>${r.ville_depart} → ${r.ville_arrivee}</p>
                                <p>${r.date_trajet} à ${r.heure_trajet}</p>
                                <p>${r.prix} €</p>
                            </div>
                        `;
                    });
                });

        })
        .catch(err => console.error("❌ ERREUR ESPACE UTILISATEUR :", err));

});
