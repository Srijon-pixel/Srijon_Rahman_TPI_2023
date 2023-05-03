<?php
/**
 * Auteur: Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */

require_once './fonctions/fonction_utilisateur.php';

/**
 * @var string la clé utilisé pour identifier l'utilisateur par sa session
 */
define('SESSION_KEY_ID_USER', 'idUtilisateur');


function RecupereUtilisateurParSession()
{
    if (!DebutSession()) {
        return false;
    }
    if (isset($_SESSION[SESSION_KEY_ID_USER])) {
        return RecuperationDonneeUtilisateur(intval($_SESSION[SESSION_KEY_ID_USER]));
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

