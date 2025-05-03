<?php
// En-têtes pour autoriser l'accès AJAX et spécifier le type de contenu JSON
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Pour déboguer
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les classes nécessaires
require_once '../classes/Project.php';

// Paramètres de pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3; // Nombre de projets par page
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;

// Log pour déboguer
$debug = [
    'params' => [
        'page' => $page,
        'limit' => $limit,
        'categorie' => $categorie,
        'offset' => ($page - 1) * $limit
    ]
];

try {
    // Instancier l'objet Project
    $projectManager = new Project();
    
    // Récupérer les projets avec pagination
    $projets = $projectManager->getProjects($page, $limit, $categorie);
    
    $debug['sql'] = [
        'projects_count' => count($projets)
    ];
    
    // Récupération du nombre total de projets pour la pagination
    $total_projets = $projectManager->countProjects($categorie);
    
    // Calcul du nombre total de pages
    $total_pages = ceil($total_projets / $limit);
    
    // Construction de la réponse JSON
    $response = [
        'projets' => $projets,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total_projets' => $total_projets,
            'total_pages' => $total_pages,
            'has_more' => $page < $total_pages
        ],
        'debug' => $debug // Informations de débogage
    ];
    
    echo json_encode($response);
} catch (Exception $e) {
    // En cas d'erreur, retourner un message d'erreur
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur: ' . $e->getMessage(),
        'debug' => $debug
    ]);
}