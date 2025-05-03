<?php
require_once 'Database.php';

class Project {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Récupérer des projets avec pagination et filtrage optionnel par catégorie
     */
    public function getProjects($page = 1, $limit = 3, $categorie = null) {
        $offset = ($page - 1) * $limit;
        
        if ($categorie) {
            $requete = "SELECT * FROM projets WHERE id_cat = :id_cat LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($requete);
            $stmt->bindValue(':id_cat', $categorie, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        } else {
            $requete = "SELECT * FROM projets LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($requete);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Compter le nombre total de projets (avec filtrage optionnel par catégorie)
     */
    public function countProjects($categorie = null) {
        if ($categorie) {
            $requete = "SELECT COUNT(*) as total FROM projets WHERE id_cat = :id_cat";
            $stmt = $this->db->prepare($requete);
            $stmt->bindValue(':id_cat', $categorie, PDO::PARAM_INT);
        } else {
            $requete = "SELECT COUNT(*) as total FROM projets";
            $stmt = $this->db->prepare($requete);
        }
        
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    /**
     * Récupérer un projet par son ID
     */
    public function getProjectById($id) {
        $requete = "SELECT * FROM projets WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Ajouter un nouveau projet
     */
    public function addProject($name, $desc, $id_cat, $src1, $src2 = null, $src3 = null) {
        $requete = "INSERT INTO projets (name, desc, id_cat, src1, src2, src3) 
                    VALUES (:name, :desc, :id_cat, :src1, :src2, :src3)";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindValue(':id_cat', $id_cat, PDO::PARAM_INT);
        $stmt->bindValue(':src1', $src1, PDO::PARAM_STR);
        $stmt->bindValue(':src2', $src2, PDO::PARAM_STR);
        $stmt->bindValue(':src3', $src3, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    /**
     * Mettre à jour un projet existant
     */
    public function updateProject($id, $name, $desc, $id_cat, $src1, $src2 = null, $src3 = null) {
        $requete = "UPDATE projets 
                    SET name = :name, desc = :desc, id_cat = :id_cat, 
                        src1 = :src1, src2 = :src2, src3 = :src3 
                    WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindValue(':id_cat', $id_cat, PDO::PARAM_INT);
        $stmt->bindValue(':src1', $src1, PDO::PARAM_STR);
        $stmt->bindValue(':src2', $src2, PDO::PARAM_STR);
        $stmt->bindValue(':src3', $src3, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    /**
     * Supprimer un projet
     */
    public function deleteProject($id) {
        $requete = "DELETE FROM projets WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}