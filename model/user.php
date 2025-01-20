<?php
class User {
    // Définir les propriétés publiques
    private $id;
    private $name;
    private $password;
    private $email;

    // Constructeur pour initialiser un utilisateur
    public function __construct($name, $password, $email, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
    }

    // Méthodes d'accès (Getters et Setters)

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    // Méthode pour afficher les informations de l'utilisateur
    public function displayUserInfo() {
        echo "ID: " . $this->id . "<br>";
        echo "Nom: " . $this->name . "<br>";
        echo "Email: " . $this->email . "<br>";
    }

    // Méthode pour sécuriser le mot de passe avant de l'enregistrer
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    // Vérification du mot de passe
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
?>
