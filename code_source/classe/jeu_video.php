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
     * @param string $ImageEncode le statut que porte l'utilisateur
     * @param string $Description le mot de passe de l'utilisateur
     * @param integer $InIdPegi L'identifiant des tranches d'âge PEGI et de ses contenus au jeu
     * @param integer $InIdGenre L'identifiant des genres du jeu vidéo
     * @param integer $InIdPlateforme L'identifiant des plateformes du jeu vidéo
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
        $InIdPegi,
        $InIdGenre,
        $InIdPlateforme,
    ) {

        $this->idJeuVideo = $InIdJeuVideo;
        $this->titre = $InTitre;
        $this->version = $InVersion;
        $this->dateSortie = $InDateSortie;
        $this->datePublication = $InDatePublication;
        $this->imageEncode = $InImageEncode;
        $this->description = $InDescription;
        $this->proposition = $InProposition;
        $this->idPegi = $InIdPegi;
        $this->idGenre = $InIdGenre;
        $this->idPlateforme = $InIdPlateforme;
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
     * @var integer l'identifiant PEGI du jeu
     */
    public $idPegi;
    /**
     * @var integer l'identifiant des genres du jeu
     */
    public $idGenre;
    /**
     * @var integer l'identifiant des plateformes du jeu
     */
    public $idPlateforme;
}
