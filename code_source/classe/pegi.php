<?php

/**
 * 
 */
class EPegi
{

    /**
     * Constructeur permettant de créer un nouveau objet de type EPegi
     * @param integer $InIdPegi L'identifiant du pegi du jeu vidéo
     * @param string $InContenuSensible les contenus sensible que propose le jeu vidéo
     */
    public function __construct($InIdPegi, $InContenuSensible)
    {
        $this->idPegi = $InIdPegi;
        $this->contenuSensible = $InContenuSensible;
    }



    /**
     * @var integer L'identifiant du pegi du jeu vidéo
     */
    public $idPegi;
    /**
     * @var string les contenus sensible que propose le jeu vidéo
     */
    public $contenuSensible;
}
