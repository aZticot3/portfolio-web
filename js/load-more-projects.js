document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le bouton "Voir plus"
    const loadMoreButton = document.getElementById('load-more');
    
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', loadMoreProjects);
    }
    
    function loadMoreProjects() {
        // Récupérer les paramètres actuels
        const currentPage = parseInt(loadMoreButton.getAttribute('data-page'));
        const categorieId = loadMoreButton.getAttribute('data-categorie');
        const container = document.getElementById('projets-container');
        
        // Montrer un indicateur de chargement
        loadMoreButton.textContent = 'Chargement...';
        loadMoreButton.disabled = true;
        
        // Utiliser le chemin EXACT qui fonctionne dans le navigateur
        let apiUrl = 'http://localhost/php/get_projects.php?page=' + currentPage + '&limit=3';
        
        if (categorieId && categorieId !== '') {
            apiUrl += '&categorie=' + categorieId;
        }
        
        console.log("Requête AJAX vers:", apiUrl); // Log pour déboguer
        
        // Utiliser fetch pour faire la requête AJAX
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur réseau: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Données reçues:", data);
                
                // Traiter les données reçues
                if (data.projets && data.projets.length > 0) {
                    // Supprimer d'abord le bouton de chargement pour le réinsérer après
                    const loadMoreContainer = loadMoreButton.parentNode;
                    container.removeChild(loadMoreContainer);
                    
                    // Ajouter les nouveaux projets
                    data.projets.forEach(projet => {
                        const article = document.createElement('article');
                        article.className = 'projet-item';
                    
                        // Vérification plus robuste pour src2 et src3
                        const hasSrc2 = projet.src2 && projet.src2 !== "NULL" && projet.src2 !== "";
                        const hasSrc3 = projet.src3 && projet.src3 !== "NULL" && projet.src3 !== "";
                        const hasMultipleImages = hasSrc2 || hasSrc3;
                    
                        // On ajoute les attributs data-src* nécessaires au carousel
                        article.setAttribute('data-project-id', projet.id);
                        article.setAttribute('data-src1', projet.src1);
                        if (hasSrc2) article.setAttribute('data-src2', projet.src2);
                        if (hasSrc3) article.setAttribute('data-src3', projet.src3);
                    
                        article.innerHTML = `
                        <section class="carousel">
                            ${hasMultipleImages ? `<img src="../img/arrow-left.svg" alt="aperçu précédent" class="left-arrow" style="width: 9%; height: auto; cursor: pointer;">` : ''}
                            
                            <img src="${projet.src1}.png" alt="aperçu 1" class="carousel-image" style="width: 80%; height: auto; object-fit: contain; display: block;">
                            
                            ${hasMultipleImages ? `<img src="../img/arrow-right.svg" alt="aperçu suivant" class="right-arrow" style="width: 9%; height: auto; cursor: pointer;">` : ''}
                        </section>
                        <aside>
                            <h3>${projet.name}</h3>
                            <a>${projet.desc}</a>
                        </aside>
                        `;
                    
                        container.appendChild(article);
                    
                        if (hasMultipleImages) {
                            window.setupCarousel(article);
                        }
                    });
                    
                    // Réinsérer le bouton à la fin
                    container.appendChild(loadMoreContainer);
                    
                    // Mettre à jour le numéro de page pour la prochaine requête
                    loadMoreButton.setAttribute('data-page', currentPage + 1);
                    
                    // Vérifier s'il y a plus de projets à charger
                    if (!data.pagination.has_more) {
                        // S'il n'y a plus de projets, masquer le bouton
                        loadMoreButton.style.display = 'none';
                    }
                }
                else {
                    // S'il n'y a pas de projets, afficher un message
                    const noMoreMessage = document.createElement('p');
                    noMoreMessage.textContent = 'Tous les projets ont été chargés.';
                    noMoreMessage.className = 'no-more-projects';
                    loadMoreButton.parentNode.replaceChild(noMoreMessage, loadMoreButton);
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des projets:', error);
                // Afficher un message d'erreur
                alert('Erreur lors du chargement des projets: ' + error.message);
            })
            .finally(() => {
                // Réactiver le bouton
                loadMoreButton.textContent = 'Voir plus de projets';
                loadMoreButton.disabled = false;
            });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM chargé, initialisation des carousels existants");
    
    // Appliquer la fonction à tous les articles existants lors du chargement
    const existingArticles = document.querySelectorAll('#projets-container article:not(.carousel-initialized)');
    console.log(`${existingArticles.length} articles existants trouvés`);
    
    existingArticles.forEach(function(article) {
        window.setupCarousel(article);
    });
});