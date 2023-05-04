<?php

/**
 * 
 */
class EUtilisateur
{

    /**
     * Constructeur permettant de créer un nouveau compte utilisateur
     * @param integer $InIdUtilisateur L'identifiant de l'utilisateur
     * @param string $InPseudo le pseudonyme de l'utilisateur
     * @param string $InNom le nom de l'utilisateur
     * @param string $InPrenom le prénom de l'utilisateur
     * @param string $InEmail l'email de l'utilisateur
     * @param bool $InStatut le statut que porte l'utilisateur
     * @param string $InMotDePasse le mot de passe de l'utilisateur
     */
    public function __construct($InIdUtilisateur, $InNom, $InPrenom, $InPseudo, $InEmail, $InStatut, $InMotDePasse)
    {

        $this->idUtilisateur = $InIdUtilisateur;
        $this->nom = $InNom;
        $this->prenom = $InPrenom;
        $this->pseudo = $InPseudo;
        $this->email = $InEmail;
        $this->statut = $InStatut;
        $this->motDePasse = $InMotDePasse;
    }



    /**
     * @var integer L'identifiant de l'utilisateur
     */
    public $idUtilisateur;
    /**
     * @var string le nom de l'utilisateur
     */
    public $nom;
    /**
     * @var string le prénom de l'utilisateur
     */
    public $prenom;
    /**
     * @var string le pseudo de l'utilisateur
     */
    public $pseudo;
    /**
     * @var string l'email de l'utilisateur
     */
    public $email;
    /**
     * @var bool le statut de l'utilisateur
     */
    public $statut;
    /**
     * @var string le mot de passe de l'utilisateur
     */
    public $motDePasse;
}
