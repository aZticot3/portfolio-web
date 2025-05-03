// Définir la fonction setupCarousel globalement avant tout
window.setupCarousel = function(article) {
    console.log("setupCarousel appelé pour l'article:", article);
    
    // Récupérer les éléments
    const leftArrow = article.querySelector('.left-arrow');
    const rightArrow = article.querySelector('.right-arrow');
    const imageElement = article.querySelector('.carousel-image');
    
    // Vérification et débogage
    if (!imageElement) {
        console.error("Élément d'image non trouvé dans l'article", article);
        return;
    }
    
    // Récupérer les attributs data-* qui contiennent les chemins des images
    const sources = [];
    if (article.dataset.src1) sources.push(article.dataset.src1);
    if (article.dataset.src2) sources.push(article.dataset.src2);
    if (article.dataset.src3) sources.push(article.dataset.src3);
    
    console.log("Sources d'images trouvées:", sources);
    
    // Si on n'a pas au moins 2 images, pas besoin de carousel
    if (sources.length < 2) {
        console.log("Moins de 2 sources d'images, carousel non nécessaire");
        return;
    }
    
    // Index de l'image actuellement affichée
    let currentIndex = 0;
    
    // Fonction pour mettre à jour l'image
    function updateImage() {
        console.log("Mise à jour de l'image:", sources[currentIndex]);
        imageElement.src = `${sources[currentIndex]}.png`;
        imageElement.alt = `aperçu ${currentIndex + 1}`;
    }
    
    // Événement pour la flèche gauche
    if (leftArrow) {
        console.log("Flèche gauche trouvée, ajout de l'écouteur d'événements");
        leftArrow.addEventListener('click', function(e) {
            console.log("Clic sur flèche gauche");
            e.preventDefault(); // Empêcher le comportement par défaut
            currentIndex = (currentIndex - 1 + sources.length) % sources.length;
            updateImage();
        });
    } else {
        console.log("Pas de flèche gauche trouvée");
    }
    
    // Événement pour la flèche droite
    if (rightArrow) {
        console.log("Flèche droite trouvée, ajout de l'écouteur d'événements");
        rightArrow.addEventListener('click', function(e) {
            console.log("Clic sur flèche droite");
            e.preventDefault(); // Empêcher le comportement par défaut
            currentIndex = (currentIndex + 1) % sources.length;
            updateImage();
        });
    } else {
        console.log("Pas de flèche droite trouvée");
    }
    
    // Ajout d'une classe pour montrer que le carousel est actif
    const sectionElement = article.querySelector('section');
    if (sectionElement) {
        sectionElement.classList.add('carousel-active');
    }
    
    // Marquer cet article comme ayant un carousel initialisé
    article.classList.add('carousel-initialized');
    console.log("Carousel initialisé avec succès pour cet article");
};

// Attendre le chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM chargé, initialisation des carousels existants");
    
    // Appliquer la fonction à tous les articles existants lors du chargement
    const existingArticles = document.querySelectorAll('#projets-container article:not(.carousel-initialized)');
    console.log(`${existingArticles.length} articles existants trouvés`);
    
    existingArticles.forEach(function(article) {
        window.setupCarousel(article);
    });
});