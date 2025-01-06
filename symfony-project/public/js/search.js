document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('matchingForm');
    const resultsContainer = document.getElementById('results');

    // Fonction pour récupérer les données du formulaire
    const getFormData = () => {
        const formData = new FormData(form);
        const params = {};
        formData.forEach((value, key) => {
            if (params[key]) {
                params[key] = [...params[key], value]; // Gestion des champs multiples
            } else {
                params[key] = value;
            }
        });
        return params;
    };

    // Fonction pour effectuer la requête AJAX
    const fetchResults = async () => {
        const data = getFormData();
        try {
            const response = await fetch('/search', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                const results = await response.text();
                resultsContainer.innerHTML = results;
            } else {
                console.error('Erreur lors de la recherche');
            }
        } catch (error) {
            console.error('Erreur réseau :', error);
        }
    };

    // Ajouter des événements `input` pour la recherche dynamique
    form.querySelectorAll('input, select').forEach((input) => {
        input.addEventListener('input', fetchResults);
    });
});
