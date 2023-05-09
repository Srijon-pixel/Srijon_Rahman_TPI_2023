-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 09 mai 2023 à 09:07
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `video_game_club`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `idComentaire` int NOT NULL,
  `commentaire` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateCommentaire` date NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idJeuVideo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`idComentaire`, `commentaire`, `dateCommentaire`, `idUtilisateur`, `idJeuVideo`) VALUES
(1, 'Wow incroyable le jeu !!!', '2023-05-10', 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `idGenre` int NOT NULL,
  `nomGenre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`idGenre`, `nomGenre`) VALUES
(1, 'Action'),
(2, 'Aventure'),
(3, 'Course'),
(4, 'Action-aventure'),
(5, 'Jeu de rôle'),
(6, 'Réflexion'),
(7, 'Simulation'),
(8, 'Stratégie'),
(9, 'Rythme'),
(10, 'Sport');

-- --------------------------------------------------------

--
-- Structure de la table `jeuvideo`
--

CREATE TABLE `jeuvideo` (
  `idJeuVideo` int NOT NULL,
  `titre` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateSortie` date NOT NULL,
  `datePublication` date NOT NULL,
  `imageEncode` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `trancheAge` int NOT NULL,
  `proposition` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `jeuvideo`
--

INSERT INTO `jeuvideo` (`idJeuVideo`, `titre`, `version`, `dateSortie`, `datePublication`, `imageEncode`, `description`, `trancheAge`, `proposition`) VALUES
(1, 'Game1', '1.0', '2023-05-01', '2023-05-01', '', 'lkahfdslksahfdsd', 16, 0),
(2, 'Game2', '2.0', '2023-05-04', '2023-05-04', '', 'agfdgsdfg', 18, 0);

-- --------------------------------------------------------

--
-- Structure de la table `liaison_genre_jeu`
--

CREATE TABLE `liaison_genre_jeu` (
  `idGenre` int NOT NULL,
  `idJeuVideo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liaison_genre_jeu`
--

INSERT INTO `liaison_genre_jeu` (`idGenre`, `idJeuVideo`) VALUES
(1, 1),
(2, 2),
(3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `liaison_pegi_jeu`
--

CREATE TABLE `liaison_pegi_jeu` (
  `idPegi` int NOT NULL,
  `idJeuVideo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liaison_pegi_jeu`
--

INSERT INTO `liaison_pegi_jeu` (`idPegi`, `idJeuVideo`) VALUES
(2, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `liaison_plateforme_jeu`
--

CREATE TABLE `liaison_plateforme_jeu` (
  `idPlateforme` int NOT NULL,
  `idJeuVideo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liaison_plateforme_jeu`
--

INSERT INTO `liaison_plateforme_jeu` (`idPlateforme`, `idJeuVideo`) VALUES
(2, 1),
(3, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `notation`
--

CREATE TABLE `notation` (
  `idNotation` int NOT NULL,
  `note` double NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idJeuVideo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notation`
--

INSERT INTO `notation` (`idNotation`, `note`, `idUtilisateur`, `idJeuVideo`) VALUES
(1, 7.7, 5, 2),
(2, 6.5, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pegi`
--

CREATE TABLE `pegi` (
  `idPegi` int NOT NULL,
  `contenuSensible` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pegi`
--

INSERT INTO `pegi` (`idPegi`, `contenuSensible`) VALUES
(1, 'Discrimination'),
(2, 'Peur'),
(3, 'Langage grossier'),
(4, 'Sexe'),
(5, 'Scène de violence'),
(6, 'Drogue'),
(7, 'Online'),
(8, 'Achat Intégrés'),
(9, 'Jeux du hasard');

-- --------------------------------------------------------

--
-- Structure de la table `plateforme`
--

CREATE TABLE `plateforme` (
  `idPlateforme` int NOT NULL,
  `nomPlateforme` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plateforme`
--

INSERT INTO `plateforme` (`idPlateforme`, `nomPlateforme`) VALUES
(1, 'IOS'),
(2, 'Android'),
(3, 'PS5'),
(4, 'Nintendo switch'),
(5, 'XBOX SERIES X'),
(6, 'PC');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` int NOT NULL,
  `nom` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pseudo` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `motDePasse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `nom`, `prenom`, `pseudo`, `email`, `statut`, `motDePasse`) VALUES
(1, 'adasSADF', 'ASDsfg', 'DSAFsdfg', 'poke@poke.poke', 0, '$2y$10$Dw6iEgC82O/Gq108YPLWJOG/daSZpazr4E1xqAPsgHn7IaNADpewy'),
(2, 'SsSas', 'ADasd', 'BG', 'bg@bggmail.com', 0, '$2y$10$8A4Q6WINiuwxKc3n4eK/4.P.VkcWVk2F6Zxp.wxKx81HQ6iVHD5Ti'),
(3, 'Beaudgfh', 'Beaudfgh', 'Beaudgh', 'beau@beaugmail.com', 0, '$2y$10$Q62V.XM6deMTzzb5Ykwry.j/UNCnm4dsqfu6KnXZ53G8L3zSd90ky'),
(4, 'We', 'Wewe', 'Weee', 'we.we@gmail.com', 0, '$2y$10$m.6F7Q5CjCMv/SsBcOmkbu65ZUA9rvOz8AsagSJuyl7edPaWp4BAW'),
(5, 'ASDfdghsdgffds', 'Srijondfghsdgfg', 'adfsdgf', 'userg@gmail.com', 0, '$2y$10$8IeUt5m8e3YVn5yD81nRf.YnRgxINE5qG1Dj84Bck7usujBW4UNoe');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`idComentaire`),
  ADD KEY `idUtilisateur` (`idUtilisateur`,`idJeuVideo`),
  ADD KEY `idJeuVideo` (`idJeuVideo`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`idGenre`);

--
-- Index pour la table `jeuvideo`
--
ALTER TABLE `jeuvideo`
  ADD PRIMARY KEY (`idJeuVideo`);

--
-- Index pour la table `liaison_genre_jeu`
--
ALTER TABLE `liaison_genre_jeu`
  ADD PRIMARY KEY (`idGenre`,`idJeuVideo`),
  ADD KEY `idJeuVideo` (`idJeuVideo`);

--
-- Index pour la table `liaison_pegi_jeu`
--
ALTER TABLE `liaison_pegi_jeu`
  ADD PRIMARY KEY (`idPegi`,`idJeuVideo`),
  ADD KEY `idJeuVideo` (`idJeuVideo`);

--
-- Index pour la table `liaison_plateforme_jeu`
--
ALTER TABLE `liaison_plateforme_jeu`
  ADD PRIMARY KEY (`idPlateforme`,`idJeuVideo`),
  ADD KEY `idJeuVideo` (`idJeuVideo`);

--
-- Index pour la table `notation`
--
ALTER TABLE `notation`
  ADD PRIMARY KEY (`idNotation`),
  ADD KEY `idUtilisateur` (`idUtilisateur`,`idJeuVideo`),
  ADD KEY `idJeuVideo` (`idJeuVideo`);

--
-- Index pour la table `pegi`
--
ALTER TABLE `pegi`
  ADD PRIMARY KEY (`idPegi`);

--
-- Index pour la table `plateforme`
--
ALTER TABLE `plateforme`
  ADD PRIMARY KEY (`idPlateforme`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `idComentaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `genre`
--
ALTER TABLE `genre`
  MODIFY `idGenre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `jeuvideo`
--
ALTER TABLE `jeuvideo`
  MODIFY `idJeuVideo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `notation`
--
ALTER TABLE `notation`
  MODIFY `idNotation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `pegi`
--
ALTER TABLE `pegi`
  MODIFY `idPegi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `plateforme`
--
ALTER TABLE `plateforme`
  MODIFY `idPlateforme` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`idJeuVideo`) REFERENCES `jeuvideo` (`idJeuVideo`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `liaison_genre_jeu`
--
ALTER TABLE `liaison_genre_jeu`
  ADD CONSTRAINT `liaison_genre_jeu_ibfk_1` FOREIGN KEY (`idGenre`) REFERENCES `genre` (`idGenre`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `liaison_genre_jeu_ibfk_2` FOREIGN KEY (`idJeuVideo`) REFERENCES `jeuvideo` (`idJeuVideo`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `liaison_pegi_jeu`
--
ALTER TABLE `liaison_pegi_jeu`
  ADD CONSTRAINT `liaison_pegi_jeu_ibfk_1` FOREIGN KEY (`idJeuVideo`) REFERENCES `jeuvideo` (`idJeuVideo`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `liaison_pegi_jeu_ibfk_2` FOREIGN KEY (`idPegi`) REFERENCES `pegi` (`idPegi`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `liaison_plateforme_jeu`
--
ALTER TABLE `liaison_plateforme_jeu`
  ADD CONSTRAINT `liaison_plateforme_jeu_ibfk_1` FOREIGN KEY (`idJeuVideo`) REFERENCES `jeuvideo` (`idJeuVideo`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `liaison_plateforme_jeu_ibfk_2` FOREIGN KEY (`idPlateforme`) REFERENCES `plateforme` (`idPlateforme`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `notation`
--
ALTER TABLE `notation`
  ADD CONSTRAINT `notation_ibfk_1` FOREIGN KEY (`idJeuVideo`) REFERENCES `jeuvideo` (`idJeuVideo`) ON DELETE CASCADE,
  ADD CONSTRAINT `notation_ibfk_2` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
