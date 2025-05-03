<?php
require_once 'Database.php';

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Récupérer toutes les catégories
     */
    public function getAllCategories() {
        $requete = "SELECT id, name, desc FROM categories";
        $stmt = $this->db->query($requete);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer une catégorie par son ID
     */
    public function getCategoryById($id) {
        $requete = "SELECT id, name, desc FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Ajouter une nouvelle catégorie
     */
    public function addCategory($name, $desc) {
        $requete = "INSERT INTO categories (name, desc) VALUES (:name, :desc)";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    /**
     * Mettre à jour une catégorie existante
     */
    public function updateCategory($id, $name, $desc) {
        $requete = "UPDATE categories SET name = :name, desc = :desc WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    /**
     * Supprimer une catégorie
     */
    public function deleteCategory($id) {
        $requete = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}