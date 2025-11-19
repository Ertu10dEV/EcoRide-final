/******************************************************
 *  TRAJETS.JS — VERSION PROPRE
 *  - Publication d'un trajet (publier.html)
 *  - Affichage de la liste des trajets (covoiturage.html)
 *  - Bouton "Voir le détail"
 ******************************************************/

// =======================
// 1. Publication d'un trajet
// =======================
function initPublishForm() {
    const form = document.getElementById("trajetForm");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const payload = {
            ville_depart : document.getElementById("ville_depart").value.trim(),
            ville_arrivee : document.getElementById("ville_arrivee").value.trim(),
            date_trajet : document.getElementById("date_trajet").value,
            heure_trajet : document.getElementById("heure_trajet").value,
            prix : document.getElementById("prix").value,
            places : document.getElementById("places").value
        };

        fetch("../back/controllers/publier_trajet.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Trajet publié !");
                window.location.href = "index.html";
            } else {
                alert(data.message);
            }
        })
        .catch(() => alert("Erreur serveur."));
    });
}


// =======================
// 2. Affichage liste trajets (covoiturage.html)
// =======================
function loadAllRides() {
    const container = document.getElementById("allRides");
    if (!container) return;

    fetch("../back/api/get_covoiturages.php")
        .then(res => res.json())
        .then(data => {
            if (!data.success || data.trajets.length === 0) {
                container.innerHTML = "<p>Aucun trajet disponible.</p>";
                return;
            }

            container.innerHTML = "";

            data.trajets.forEach(t => {
                const card = document.createElement("div");
                card.className = "ride-card";

                card.innerHTML = `
                    <div class="driver-info">
                        <img src="img/${t.chauffeur_photo}" class="driver-photo">
                        <div>
                            <strong>${t.chauffeur}</strong><br>
                            ⭐ 4.5 / 5
                        </div>
                    </div>

                    <div class="ride-details">
                        <p><strong>Trajet :</strong> ${t.ville_depart} → ${t.ville_arrivee}</p>
                        <p><strong>Date :</strong> ${t.date_trajet} à ${t.heure_trajet}</p>
                        <p><strong>Places restantes :</strong> ${t.places}</p>
                        <p><strong>Prix :</strong> ${t.prix} €</p>

                        <button class="btn-detail" data-id="${t.id}">
                            Voir le détail
                        </button>
                    </div>
                `;

                container.appendChild(card);
            });
        })
        .catch(() => {
            container.innerHTML = "<p>Erreur lors du chargement.</p>";
        });
}


// =======================
// 3. Redirection vers la page détail
// =======================
function initDetailButtons() {
    document.addEventListener("click", (e) => {
        if (!e.target.classList.contains("btn-detail")) return;

        const id = e.target.dataset.id;
        localStorage.setItem("trajet_detail_id", id);
        window.location.href = "detail.html";
    });
}


// =======================
// INITIALISATION GLOBALE
// =======================
document.addEventListener("DOMContentLoaded", () => {
    initPublishForm();   // uniquement sur publier.html
    loadAllRides();      // uniquement sur covoiturage.html
    initDetailButtons(); // global (boutons dynamiques)
});
