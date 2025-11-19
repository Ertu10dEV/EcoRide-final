// =====================================
// DETAIL.JS
// Charge un trajet + affiche le bouton réserver
// =====================================

document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("detailContainer");
    if (!container) return;

    const id = localStorage.getItem("trajet_detail_id");
    if (!id) {
        container.innerHTML = "<p>Trajet introuvable.</p>";
        return;
    }

    fetch(`../back/api/get_trajet.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                container.innerHTML = "<p>Erreur lors du chargement.</p>";
                return;
            }

            const t = data.trajet;

            container.innerHTML = `
                <div class="detail-card">

                    <div class="driver-section">
                        <img src="img/${t.chauffeur_photo}" class="driver-photo-large">
                        <div>
                            <h2>${t.chauffeur}</h2>
                            <p>⭐ Note : 4.5 / 5</p>
                        </div>
                    </div>

                    <div class="trajet-info">
                        <p><strong>Trajet :</strong> ${t.ville_depart} → ${t.ville_arrivee}</p>
                        <p><strong>Date :</strong> ${t.date_trajet} à ${t.heure_trajet}</p>
                        <p><strong>Places restantes :</strong> ${t.places}</p>
                        <p><strong>Prix :</strong> ${t.prix} €</p>
                    </div>

                    <button id="btnReserve" data-id="${t.id}">
                        Réserver ce trajet
                    </button>
                </div>
            `;
        })
        .catch(() => {
            container.innerHTML = "<p>Erreur lors du chargement.</p>";
        });
});
