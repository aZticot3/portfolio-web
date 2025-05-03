<?php 
// index.php
$pageTitle = "Accueil";
$pageSpecificCSS = "css/index.css";
require_once 'includes/header.php'; 
require_once 'classes/Category.php';

// Instancier les objets Category
$categoryManager = new Category();

// Récupération des catégories
$categories = $categoryManager->getAllCategories()
?>



    <section>
        <h1>Portfolio</h1>
        <h2>Clément Bontemps</h2>
        <p>Développeur</p>
    </section>
</header>

<main>
    <?php foreach ($categories as $categorie): ?>
        <article data-id="<?php echo $categorie['id']; ?>">
            <h3><?php echo $categorie['name']; ?></h3>
            <p><?php echo $categorie['desc']; ?></p>
        </article>
    <?php endforeach; ?>
</main>

<script src="js/redirection.js"></script>

<?php require_once 'includes/footer.php'; ?>

