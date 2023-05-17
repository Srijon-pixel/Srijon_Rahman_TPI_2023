<?php

/**
 * Projet: TPI video game club
 * Auteur: Mofassel Haque Srijon Rahman
 * Détail: Classe pour crée un objet ECommentaire
 */
class ECommentaire
{

    /**
     * Constructeur permettant de créer un nouveau commenatire
     * @param integer $InIdCommentaire L'identifiant du commentaire
     * @param string $InCommentaire l'avis donné par un utilisateur à un jeu
     * @param string $InDateCommentaire la date du commentaire
     * @param string $InPseudo le pseudo de la personne ayant commentée
     */
    public function __construct($InIdCommentaire, $InCommentaire, $InDateCommentaire, $InPseudo)
    {
        $this->idCommentaire = $InIdCommentaire;
        $this->commentaire = $InCommentaire;
        $this->dateCommentaire = $InDateCommentaire;
        $this->pseudoUtilisateur = $InPseudo;
    }



    /**
     * @var integer l'identifiant du commentaire
     */
    public $idCommentaire;
    /**
     * @var string l'avis donné par un utilisateur à un jeu
     */
    public $commentaire;
    /**
     * @var string la date du commentaire
     */
    public $dateCommentaire;
    /**
     * @var integer le pseudo de la personne ayant commentée
     */
    public $pseudoUtilisateur;
}
