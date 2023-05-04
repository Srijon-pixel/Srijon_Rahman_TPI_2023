<?php

/**
 * 
 */
class ECommentaire
{

    /**
     * Constructeur permettant de créer un nouveau commenatire
     * @param integer $InIdCommentaire L'identifiant du commentaire
     * @param string $InCommentaire l'avis donné par un utilisateur à un jeu
     * @param string $InDateCommentaire la date du commentaire
     */
    public function __construct($InIdCommentaire, $InCommentaire, $InDateCommentaire, $InIdUtilisateur, $InIdJeuVideo)
    {
        $this->idCommentaire = $InIdCommentaire;
        $this->commentaire = $InCommentaire;
        $this->dateCommentaire = $InDateCommentaire;
        $this->idUtilisateur = $InIdUtilisateur;
        $this->idJeuVideo = $InIdJeuVideo;
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
     * @var integer l'identifiant de l'utilisateur
     */
    public $idUtilisateur;
    /**
     * @var integer l'identifiant du jeu vidéo
     */
    public $idJeuVideo;
}
