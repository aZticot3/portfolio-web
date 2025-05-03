<?php
require_once 'Database.php';

class Collaboration {
    private $db;
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Récupérer toutes les collaborations
     */
    public function getAllCollaborations() {
        $requete = "SELECT * FROM Collaboration ORDER BY id DESC";
        $stmt = $this->db->query($requete);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer une collaboration par son ID
     */
    public function getCollaborationById($id) {
        $requete = "SELECT * FROM Collaboration WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Ajouter une nouvelle demande de collaboration
     */
    public function addCollaboration($desc, $user_name, $tel, $email) {
        $requete = "INSERT INTO Collaboration (desc, user_name, tel, email) 
                    VALUES (:desc, :user_name, :tel, :email)";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Mettre à jour une collaboration existante
     */
    public function updateCollaboration($id, $desc, $user_name, $tel, $email) {
        $requete = "UPDATE Collaboration 
                    SET desc = :desc, user_name = :user_name, tel = :tel, email = :email 
                    WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Supprimer une collaboration
     */
    public function deleteCollaboration($id) {
        $requete = "DELETE FROM Collaboration WHERE id = :id";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Rechercher des collaborations par nom d'utilisateur
     */
    public function searchCollaborationsByUserName($search) {
        $requete = "SELECT * FROM Collaboration WHERE user_name LIKE :search ORDER BY id DESC";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Rechercher des collaborations par email
     */
    public function searchCollaborationsByEmail($email) {
        $requete = "SELECT * FROM Collaboration WHERE email LIKE :email ORDER BY id DESC";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':email', '%' . $email . '%', PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compter le nombre total de collaborations
     */
    public function countCollaborations() {
        $requete = "SELECT COUNT(*) as total FROM Collaboration";
        $stmt = $this->db->query($requete);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Récupérer les collaborations avec pagination
     */
    public function getCollaborationsWithPagination($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $requete = "SELECT * FROM Collaboration ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($requete);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Valider les données d'une collaboration
     * Retourne un tableau d'erreurs (vide si aucune erreur)
     */
    public function validateCollaborationData($desc, $user_name, $tel, $email) {
        $errors = [];
        
        // Validation de la description
        if (empty($desc)) {
            $errors['desc'] = "La description est obligatoire";
        } elseif (strlen($desc) < 10) {
            $errors['desc'] = "La description doit contenir au moins 10 caractères";
        }
        
        // Validation du nom d'utilisateur
        if (empty($user_name)) {
            $errors['user_name'] = "Le nom est obligatoire";
        }
        
        // Validation du téléphone (format français simple)
        if (empty($tel)) {
            $errors['tel'] = "Le numéro de téléphone est obligatoire";
        } elseif (!preg_match("/^[0-9]{10}$/", str_replace(' ', '', $tel))) {
            $errors['tel'] = "Le format du numéro de téléphone n'est pas valide";
        }
        
        // Validation de l'email
        if (empty($email)) {
            $errors['email'] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
        }
        
        return $errors;
    }
}