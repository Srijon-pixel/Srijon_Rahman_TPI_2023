<?php

/**
 * 
 */
class EPlateforme
{

    /**
     * Constructeur permettant de créer un nouveau objet de type EPlateforme
     * @param integer $InIdPlateforme L'identifiant de la plateforme du jeu vidéo
     * @param string $InNomPlateforme le nom de la plateforme
     */
    public function __construct($InIdPlateforme, $InNomPlateforme)
    {
        $this->idPlateforme = $InIdPlateforme;
        $this->nomPlateforme = $InNomPlateforme;
    }



    /**
     * @var integer L'identifiant de la plateforme du jeu vidéo
     */
    public $idPlateforme;
    /**
     * @var string le nom de la plateforme
     */
    public $nomPlateforme;
}
