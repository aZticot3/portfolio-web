<!DOCTYPE html>
<html lang="fr">
    <head>


        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Portfolio d'un développeur freelance - Projets réalisés">
        <title><?php echo $pageTitle ?? 'Portfolio'; ?></title>
        <?php if ($pageTitle == "Accueil") : ?>
            <link rel="stylesheet" href="css/style.css">
            <?php if(isset($pageSpecificCSS)): ?>
                <link rel="stylesheet" href="<?php echo $pageSpecificCSS; ?>">
            <?php endif; ?>
        <?php else : ?>
            <link rel="stylesheet" href="../css/style.css">
            <?php if(isset($pageSpecificCSS)): ?>
                <link rel="stylesheet" href="../<?php echo $pageSpecificCSS; ?>">
            <?php endif; ?>
        <?php endif; ?>

    </head>
    <body>
        <header>
            <div class ='logo'>
                <?php if ($pageTitle == "Accueil") : ?>
                    <img src="img/logo.png" alt="Logo personnel">
                <?php else : ?>
                    <img src="../img/logo.png" alt="Logo personnel">
                <?php endif; ?>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/index.php">Accueil</a></li>
                    <li><a href="/php/projects.php">Mes projets</a></li>
                    <li><a href="/php/contact.php">Contact</a></li>
                    <li><a>Shop</a></li> <!-- En travaux -->
                </ul>
            </nav>
