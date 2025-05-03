<?php 
$pageTitle = "Contact";
$pageSpecificCSS = "css/contact.css";
require_once '../includes/header.php';
require_once '../classes/Collaboration.php';

$collaborationManager = new Collaboration();
$errors = [];
$success = false;
$formData = [
    'desc' => '',
    'tech' => '',
    'user_name' => '',
    'tel' => '',
    'email' => ''
];

// Traitement du formulaire si soumission en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données
    $formData = [
        'desc' => isset($_POST['desc']) ? htmlspecialchars(trim($_POST['desc'])) : '',
        'tech' => isset($_POST['tech']) ? htmlspecialchars(trim($_POST['tech'])) : '',
        'user_name' => isset($_POST['user_name']) ? htmlspecialchars(trim($_POST['user_name'])) : '',
        'tel' => isset($_POST['tel']) ? htmlspecialchars(trim($_POST['tel'])) : '',
        'email' => isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : ''
    ];
    
    // Validation côté serveur
    if (empty($formData['desc'])) {
        $errors['desc'] = "La description du projet est obligatoire";
    } elseif (strlen($formData['desc']) < 10) {
        $errors['desc'] = "La description doit contenir au moins 10 caractères";
    }
    
    if (empty($formData['tech'])) {
        $errors['tech'] = "Veuillez préciser les technologies";
    }
    
    if (empty($formData['user_name'])) {
        $errors['user_name'] = "Votre nom est obligatoire";
    }
    
    if (empty($formData['tel'])) {
        $errors['tel'] = "Votre numéro de téléphone est obligatoire";
    } elseif (!preg_match("/^[0-9]{10}$/", str_replace(' ', '', $formData['tel']))) {
        $errors['tel'] = "Le format du numéro de téléphone n'est pas valide";
    }
    
    if (empty($formData['email'])) {
        $errors['email'] = "Votre email est obligatoire";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide";
    }
    
    // Si aucune erreur, enregistrer la demande
    if (empty($errors)) {
        try {
            // Construction de la description complète avec les technologies
            $fullDesc = "Description du projet: " . $formData['desc'] . "\n\nTechnologies: " . $formData['tech'];
            
            // Enregistrement dans la base de données
            $result = $collaborationManager->addCollaboration(
                $fullDesc,
                $formData['user_name'],
                $formData['tel'],
                $formData['email']
            );
            
            if ($result) {
                $success = true;
                // Réinitialiser les données du formulaire
                $formData = [
                    'desc' => '',
                    'tech' => '',
                    'user_name' => '',
                    'tel' => '',
                    'email' => ''
                ];
            } else {
                $errors['general'] = "Une erreur est survenue lors de l'enregistrement de votre demande";
            }
        } catch (Exception $e) {
            $errors['general'] = "Erreur technique: " . $e->getMessage();
        }
    }
}
?>

    <h1>Me contacter</h1>
</header>
<!-- Section principale -->
<main>
    <section>
        <h2>Voici mes coordonnées :</h2>
        <address>
            <p><strong>Email : </strong>clement.bontemps@etu.unistra.fr</p>
            <p><strong>Téléphone : </strong>07.86.77.68.79</p>
        </address>
    </section>

    <section class="collaboration-form-section">
        <h2>Proposition de collaboration :</h2>
        
        <?php if ($success): ?>
            <article class="success-message">
                <p>Votre demande de collaboration a été envoyée avec succès ! Je vous recontacterai dans les plus brefs délais.</p>
            </article>
        <?php endif; ?>
        
        <?php if (isset($errors['general'])): ?>
            <article class="error-message">
                <p><?php echo $errors['general']; ?></p>
            </article>
        <?php endif; ?>
        
        <form id="collaboration-form" action="" method="post" novalidate>
            <fieldset>
                <legend>Informations du projet</legend>
                <section class="form-field">
                    <label for="desc">Description du projet :</label>
                    <textarea id="desc" name="desc" required><?php echo $formData['desc']; ?></textarea>
                    <?php if (isset($errors['desc'])): ?>
                        <output class="error" for="desc"><?php echo $errors['desc']; ?></output>
                    <?php endif; ?>
                    <output class="error-js" id="desc-error" for="desc"></output>
                </section>

                <section class="form-field">
                    <label for="tech">Technologies utilisées :</label>
                    <textarea id="tech" name="tech" required><?php echo $formData['tech']; ?></textarea>
                    <?php if (isset($errors['tech'])): ?>
                        <output class="error" for="tech"><?php echo $errors['tech']; ?></output>
                    <?php endif; ?>
                    <output class="error-js" id="tech-error" for="tech"></output>
                </section>
            </fieldset>

            <fieldset>
                <legend>Vos coordonnées</legend>
                <section class="form-field">
                    <label for="user_name">Votre nom:</label>
                    <input type="text" id="user_name" name="user_name" value="<?php echo $formData['user_name']; ?>" required>
                    <?php if (isset($errors['user_name'])): ?>
                        <output class="error" for="user_name"><?php echo $errors['user_name']; ?></output>
                    <?php endif; ?>
                    <output class="error-js" id="user_name-error" for="user_name"></output>
                </section>

                <section class="form-field">
                    <label for="tel">Votre téléphone:</label>
                    <input type="text" id="tel" name="tel" value="<?php echo $formData['tel']; ?>" required>
                    <?php if (isset($errors['tel'])): ?>
                        <output class="error" for="tel"><?php echo $errors['tel']; ?></output>
                    <?php endif; ?>
                    <output class="error-js" id="tel-error" for="tel"></output>
                </section>

                <section class="form-field">
                    <label for="email">Votre email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $formData['email']; ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <output class="error" for="email"><?php echo $errors['email']; ?></output>
                    <?php endif; ?>
                    <output class="error-js" id="email-error" for="email"></output>
                </section>
            </fieldset>

            <button type="submit" id="submit-btn">Envoyer</button>
        </form>
    </section>
</main>

<!-- Script JavaScript pour la validation côté client -->
<script src="../js/form-validation.js"></script>

<?php require_once '../includes/footer.php'; ?>