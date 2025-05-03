<?php
class Database {
    private static $instance = null;
    private $pdo;
    
    /**
     * Constructeur privé (pattern Singleton)
     */
    private function __construct() {
        $chemin_db = __DIR__.'/../portfolio.db';
        
        if (!file_exists($chemin_db)) {
            throw new Exception("La base de données n'existe pas: $chemin_db");
        }
        
        // Création de l'instance PDO pour SQLite
        $this->pdo = new PDO('sqlite:' . $chemin_db);
        
        // Configuration d'options importantes
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    
    /**
     * Méthode pour obtenir l'instance de la classe Database (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Obtenir l'objet PDO
     */
    public function getPDO() {
        return $this->pdo;
    }
    
    /**
     * Préparer et exécuter une requête
     */
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    /**
     * Exécuter une requête simple
     */
    public function query($sql) {
        return $this->pdo->query($sql);
    }
}