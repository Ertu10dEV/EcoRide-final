console.log("SEARCH.JS CHARGÉ :", window.location.pathname);

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("searchForm");
    const resultsContainer = document.getElementById("results");

    // ===============================
    // 1) FORMULAIRE DE RECHERCHE
    // ===============================
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const depart = document.getElementById("ville_depart").value.trim();
            const arrivee = document.getElementById("ville_arrivee").value.trim();
            const date = document.getElementById("date_trajet").value;

            if (!depart || !arrivee || !date) {
                alert("Veuillez remplir tous les champs.");
                return;
            }

            localStorage.setItem("search_depart", depart);
            localStorage.setItem("search_arrivee", arrivee);
            localStorage.setItem("search_date", date);

            window.location.href = "resultats.html";
        });

        return; // IMPORTANT → on ne charge pas la logique résultats sur index.html
    }

    // ===============================
    // 2) PAGE DES RÉSULTATS
    // ===============================
    if (resultsContainer) {

        const depart = localStorage.getItem("search_depart");
        const arrivee = localStorage.getItem("search_arrivee");
        const date = localStorage.getItem("search_date");

        fetch(`../back/api/get_all_trajets.php?depart=${depart}&arrivee=${arrivee}&date=${date}`)
            .then(res => res.json())
            .then(data => {

                console.log("DATA REÇUE :", data);

                if (!data.success || data.trajets.length === 0) {
                    resultsContainer.innerHTML = "<p>Aucun trajet trouvé.</p>";
                    return;
                }

                resultsContainer.innerHTML = "";

                data.trajets.forEach(trajet => {
                    const card = document.createElement("div");
                    card.className = "trajet-card";

                    card.innerHTML = `
                        <h3>${trajet.ville_depart} → ${trajet.ville_arrivee}</h3>
                        <p>Date : ${trajet.date_trajet} à ${trajet.heure_trajet}</p>
                        <p>Prix : ${trajet.prix} €</p>
                        <p>Places restantes : ${trajet.places}</p>

                        <button class="btn-detail" data-id="${trajet.id}">
                            Voir le détail
                        </button>
                    `;

                    resultsContainer.appendChild(card);
                });

                // ======================
                // LOG MONGO
                // ======================
                console.log("=== LANCEMENT DU LOG MONGO ===");

                fetch("/EcoRide/back/api/log_search.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        depart,
                        arrivee,
                        date,
                        userId: localStorage.getItem("userId") || null
                    })
                })
                .then(res => res.json())
                .then(log => console.log("LOG MONGO OK :", log))
                .catch(err => console.error("LOG MONGO ERROR :", err));
            })
            .catch(err => {
                console.error(err);
                resultsContainer.innerHTML = "<p>Erreur lors du chargement.</p>";
            });
    }

    // ===============================
    // 3) DETAIL
    // ===============================
    document.addEventListener("click", (e) => {
        if (e.target.classList.contains("btn-detail")) {
            localStorage.setItem("trajet_detail_id", e.target.dataset.id);
            window.location.href = "detail.html";
        }
    });

});
