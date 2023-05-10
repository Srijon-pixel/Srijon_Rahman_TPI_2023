<?php

/**
 * Auteur: Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les seesions d'utilisateurs du sites
 */

require_once './fonctions/fonction_utilisateur.php';
require_once './fonctions/fonction_jeuVideo.php';

/**
 * @var string la clé utilisé pour identifier l'utilisateur par sa session
 */
const SESSION_CLE_ID_UTILISATEUR = "idUtilisateur";
const SESSION_CLE_ID_JEU = "idJeu";


function RecupereUtilisateurParSession()
{
    if (!DebutSession()) {
        return false;
    }
    if (isset($_SESSION[SESSION_CLE_ID_UTILISATEUR])) {
        return RecuperationDonneeUtilisateur(intval($_SESSION[SESSION_CLE_ID_UTILISATEUR]));
    }
    return false;
}

function RecupereJeuParSession()
{
    if (!DebutSession()) {
        return false;
    }
    if (isset($_SESSION[SESSION_CLE_ID_JEU])) {
        return RecupereJeuVideoParId(intval($_SESSION[SESSION_CLE_ID_JEU]));
    }
    return false;
}

function DebutSession()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    } else if (session_status() === PHP_SESSION_DISABLED) {
        return false;
    } else if (session_status() === PHP_SESSION_NONE) {
        session_start();
        return true;
    }
}
