<?php

/**
 * 
 */
class EUtilisateur
{

    /**
     * Constructeur permettant de créer un nouveau compte utilisateur
     * @param integer $InIdUser L'identifiant de l'utilisateur
     * @param string $InUsername le nom de l'utilisateur
     * @param string $InEmail l'email de l'utilisateur
     * @param string $InPassword le mot de passe de l'utilisateur
     * @param bool $InActif Indique si le compte existe ou non
     * @param integer $InAdmin l'identifiant de l'administrateur
     */
    public function __construct($InIdUtilisateur, $InNom, $InPrenom, $InPseudo, $InEmail, $InMotDePasse)
    {

        $this->idUtilisateur = $InIdUtilisateur;
        $this->pseudo = $InPseudo;
        $this->email = $InEmail;
        $this->password = $InMotDePasse;
    }



    /**
     * @var integer L'identifiant du budget
     */
    public $idUtilisateur;
    /**
     * @var string nom de l'utilisateur
     */
    public $pseudo;
    /**
     * @var string email de l'utilisateur
     */
    public $email;
    /**
     * @var string mot de passe de l'utilisateur
     */
    public $password;
?>