document.querySelectorAll('main article').forEach(article => {
    article.addEventListener('click', () => {
        const id = article.dataset.id;
        const baseURL = '/php/projects.php?categorie=';
        window.location.href = baseURL + id;
    });
});