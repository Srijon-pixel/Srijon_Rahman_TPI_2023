<?php

/**
 * Classe container user
 */
class EUser
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
    public function __construct($InIdUser, $InUsername, $InEmail, $InPassword, $InActif, $InAdmin)
    {

        $this->id_user = $InIdUser;
        $this->username = $InUsername;
        $this->email = $InEmail;
        $this->password = $InPassword;
        $this->actif = $InActif;
        $this->admin = $InAdmin;
    }



    /**
     * @var integer L'identifiant du budget
     */
    public $id_user;
    /**
     * @var string nom de l'utilisateur
     */
    public $username;
    /**
     * @var string email de l'utilisateur
     */
    public $email;
    /**
     * @var string mot de passe de l'utilisateur
     */
    public $password;

    /**
     * @var bool indique l'existance du compte
     */
    public $actif;
    /**
     * @var integer identifiant de l'administrateur
     */
    public $admin;
}
?>