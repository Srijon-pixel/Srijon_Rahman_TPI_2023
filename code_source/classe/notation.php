<?php

/**
 * 
 */
class ENotation
{

    /**
     * Constructeur permettant de créer une nouvelle note
     * @param integer $InIdNotation L'identifiant de la note
     * @param double $InNote la note donnée par un utilisateur à un jeu
     * @param integer $InNombrePersonneNote le nombre de personnes ayant noter le jeu
     * @param integer $InIdUtilisateur l'identifiant de l'utilisateur
     * @param integer $InIdJeuVideo l'identifiant du je vidéo
     */
    public function __construct($InIdNotation, $InNote, $InNombrePersonneNote, $InIdUtilisateur, $InIdJeuVideo)
    {
        $this->idNotation = $InIdNotation;
        $this->note = $InNote;
        $this->nombrePersonneNote = $InNombrePersonneNote;
        $this->idUtilisateur = $InIdUtilisateur;
        $this->idJeuVideo = $InIdJeuVideo;
    }



    /**
     * @var integer L'identifiant de la note
     */
    public $idNotation;
    /**
     * @var double la note donnée par un utilisateur à un jeu
     */
    public $note; 
    /**
    * @var integer le nombre de personnes ayant noter le jeu
    */
   public $nombrePersonneNote;
    /**
     * @var integer l'identifiant de l'utilisateur
     */
    public $idUtilisateur;
    /**
     * @var integer l'identifiant du je vidéo
     */
    public $idJeuVideo;
}
