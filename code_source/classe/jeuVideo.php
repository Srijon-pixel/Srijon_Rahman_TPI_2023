<?php

/**
 * 
 */
class EJeuVideo
{

    /**
     * Constructeur permettant d'ajouter un nouveau jeu vidéo
     * @param integer $InIdJeuVideo L'identifiant du jeu vidéo
     * @param string $InTitre le titre du jeu
     * @param string $InVersion le nom de l'utilisateur
     * @param string $InDateSortie le prénom de l'utilisateur
     * @param string $InDatePublication l'email de l'utilisateur
     * @param string $InImageEncode le statut que porte l'utilisateur
     * @param string $InDescription le mot de passe de l'utilisateur
     * @param bool $InProposition indique si le jeu est proposer ou non
     */
    public function __construct(
        $InIdJeuVideo,
        $InTitre,
        $InVersion,
        $InDateSortie,
        $InDatePublication,
        $InImageEncode,
        $InDescription,
        $InProposition,
        $InGenre,
        $InPlateforme,
        $InTrancheAge,
        $InContenuSensible

    ) {

        $this->idJeuVideo = $InIdJeuVideo;
        $this->titre = $InTitre;
        $this->version = $InVersion;
        $this->dateSortie = $InDateSortie;
        $this->datePublication = $InDatePublication;
        $this->imageEncode = $InImageEncode;
        $this->description = $InDescription;
        $this->proposition = $InProposition;
        $this->genre = $InGenre;
        $this->plateforme = $InPlateforme;
        $this->trancheAge = $InTrancheAge;
        $this->contenuSensible = $InContenuSensible;
    }



    /**
     * @var integer L'identifiant du jeu
     */
    public $idJeuVideo;
    /**
     * @var string le titre du jeu
     */
    public $titre;
    /**
     * @var string la version du jeu
     */
    public $version;
    /**
     * @var string la date de sortie du jeu
     */
    public $dateSortie;
    /**
     * @var string la date de publication du jeu
     */
    public $datePublication;
    /**
     * @var string l'image du jeu encodé
     */
    public $imageEncode;
    /**
     * @var string la description du jeu
     */
    public $description;
    /**
     * @var bool indique si le jeu est proposer ou non
     */
    public $proposition;
    /**
     * @var string les genres que le jeu possède
     */
    public $genre;
    /**
     * @var string les plateformes auquel on peu jouer avec le jeu
     */
    public $plateforme;
    /**
     * @var int indique l'âge conseillé pour jouer au jeu
     */
    public $trancheAge;
    /**
     * @var string indique les contenus potentiellement suceptilbles de sensibiliser la personne
     */
    public $contenuSensible;
}
