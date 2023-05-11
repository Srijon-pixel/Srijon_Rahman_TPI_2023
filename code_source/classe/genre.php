<?php

/**
 * 
 */
class EGenre
{

    /**
     * Constructeur permettant de créer un objet de type EGenre
     * @param integer $InIdGenre L'identifiant du genre du jeu vidéo
     * @param string $InNomGenre le nom du genre
     */
    public function __construct($InIdGenre, $InNomGenre)
    {
        $this->idGenre = $InIdGenre;
        $this->nomGenre = $InNomGenre;
    }



    /**
     * @var integer L'identifiant du genre du jeu vidéo
     */
    public $idGenre;
    /**
     * @var string le nom du genre
     */
    public $nomGenre;
}
