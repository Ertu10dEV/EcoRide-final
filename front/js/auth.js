// ==========================
// INSCRIPTION
// ==========================
document.addEventListener("DOMContentLoaded", () => {
    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const nom = document.getElementById("nom").value.trim();
            const email = document.getElementById("email").value.trim();
            const telephone = document.getElementById("telephone").value.trim();
            const mot_de_passe = document.getElementById("mot_de_passe").value;
            const mot_de_passe_conf = document.getElementById("mot_de_passe_conf").value;

            if (mot_de_passe !== mot_de_passe_conf) {
                alert("Les mots de passe ne correspondent pas.");
                return;
            }

            fetch("../back/controllers/inscription.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ nom, email, telephone, mot_de_passe })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Inscription réussie !");
                    window.location.href = "login.html";
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erreur serveur.");
            });
        });
    }
});

// ==========================
// CONNEXION
// ==========================
document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");

    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const email = document.getElementById("email_login").value.trim();
            const password = document.getElementById("password_login").value;

            fetch("../back/controllers/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || "Identifiants incorrects.");
                    return;
                }

                // Réplique dans localStorage
                localStorage.setItem("userId", data.userId);
                localStorage.setItem("userNom", data.userNom);
                localStorage.setItem("userEmail", data.userEmail);

                alert("Connexion réussie !");
                window.location.href = "index.html";
            })
            .catch(err => {
                console.error(err);
                alert("Erreur serveur.");
            });
        });
    }
});

// ==========================
// CHARGEMENT ESPACE UTILISATEUR
// ==========================
document.addEventListener("DOMContentLoaded", () => {

    const nameField = document.getElementById("userName");
    const emailField = document.getElementById("userEmail");
    const trajetsContainer = document.getElementById("userTrajets");
    const reservationsContainer = document.getElementById("userReservations");

    // Pas sur la page espace-utilisateur →
    if (!nameField || !emailField) return;

    console.log("📌 ESPACE UTILISATEUR : Chargement…");

    // Vérifier la session
    fetch("../back/api/check_session.php")
        .then(res => res.json())
        .then(data => {

            console.log("📌 SESSION DATA :", data);

            if (!data.logged) {
                window.location.href = "login.html";
                return;
            }

            // Remplir infos
            nameField.textContent = data.userNom;
            emailField.textContent = data.userEmail;

            // Charger TRAJETS PUBLIÉS
            fetch(`../back/api/get_user_trajets.php?user=${data.userId}`)
                .then(res => res.json())
                .then(d => {

                    if (!d.success || !Array.isArray(d.trajets) || d.trajets.length === 0) {
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

            // Charger RÉSERVATIONS
            fetch(`../back/api/get_user_trajets.php?user=${data.userId}`)
                .then(res => res.json())
                .then(d => {

                    if (!d.success || !d.reservations || !Array.isArray(d.reservations) || d.reservations.length === 0) {
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

        }); // <- FERMETURE DU then(data => { ... })

}); // <- FERMETURE DU DOMContentLoaded
