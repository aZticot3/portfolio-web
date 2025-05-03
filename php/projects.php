<?php 
$pageTitle = "Projets";
$pageSpecificCSS = "css/projects.css";
require_once '../includes/header.php';
require_once '../classes/Project.php';
require_once '../classes/Category.php';

// Instancier les objets Project et Category
$projectManager = new Project();
$categoryManager = new Category();

// Récupération des catégories
$categories = $categoryManager->getAllCategories();

// Récupération de l'ID de catégorie sélectionnée (si présent dans l'URL)
$categorie_selectionnee = isset($_GET['categorie']) ? $_GET['categorie'] : null;

// Paramètres de pagination
$limit = 3; // Nombre de projets par page initiale
$page = 1;  // Page initiale

// Récupération des projets avec pagination
$projets = $projectManager->getProjects($page, $limit, $categorie_selectionnee);

// Récupération du nombre total de projets pour savoir s'il faut afficher le bouton "Voir plus"
$total_projets = $projectManager->countProjects($categorie_selectionnee);
$has_more = $total_projets > $limit;
?>

    <section>
        <h1>Mes projets</h1>
            <!-- Les boutons de catégories -->
            <nav>
                <form method="GET" action="">
                    <!-- Bouton pour afficher tous les projets -->
                    <button type="submit" name="categorie" value="" class="categorie-btn tous-btn <?php echo is_null($categorie_selectionnee) ? 'active' : ''; ?>">
                        Tous les projets
                    </button>
                    
                    <!-- Boutons pour chaque catégorie -->
                    <?php foreach ($categories as $categorie): ?>
                        <button type="submit" name="categorie" value="<?php echo $categorie['id']; ?>" 
                                class="categorie-btn <?php echo $categorie_selectionnee == $categorie['id'] ? 'active' : ''; ?>">
                            <?php echo $categorie['name']; ?>
                        </button>
                    <?php endforeach; ?>
                </form> 
            </nav>
    </section>
</header>
<main id="projets-container">
    
    <?php if (count($projets) > 0): ?>
        <?php foreach ($projets as $projet): ?>
            <article 
                data-project-id="<?php echo $projet['id']; ?>"
                data-src1="<?php echo $projet['src1']; ?>"
                <?php if ($projet['src2']): ?>data-src2="<?php echo $projet['src2']; ?>"<?php endif; ?>
                <?php if ($projet['src3']): ?>data-src3="<?php echo $projet['src3']; ?>"<?php endif; ?>
            >
                <section class="carousel">
                    <?php if ($projet['src2'] != NULL || $projet['src3'] != NULL): ?>
                        <img src="../img/arrow-left.svg" alt="aperçu précédent" class="left-arrow" style="width: 9%; height: auto; cursor: pointer;">
                    <?php endif; ?>
                    
                    <img src="<?php echo $projet['src1']; ?>.png" alt="aperçu 1" class="carousel-image" style="width: 80%; height: auto; object-fit: contain; display: block;">
                    
                    <?php if ($projet['src2'] != NULL || $projet['src3'] != NULL): ?>
                        <img src="../img/arrow-right.svg" alt="aperçu suivant" class="right-arrow" style="width: 9%; height: auto; cursor: pointer;">
                    <?php endif; ?>
                </section>
                <aside>
                    <h3><?php echo $projet['name']; ?></h3>
                    <a><?php echo $projet['desc']; ?></a>
                </aside>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun projet trouvé pour cette catégorie.</p>
    <?php endif; ?>
    <?php if ($has_more): ?>
        <div class="load-more-container">
            <button id="load-more" data-page="2" data-categorie="<?php echo htmlspecialchars($categorie_selectionnee ?? ''); ?>">
                Voir plus de projets
            </button>
        </div>
    <?php endif; ?>
</main>


<script src="../js/carousel.js"></script>
<!-- Ajout du script JavaScript pour le chargement AJAX -->
<script src="../js/load-more-projects.js"></script>


<?php require_once '../includes/footer.php'; ?>