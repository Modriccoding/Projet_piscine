-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 mai 2024 à 10:00
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ece_in`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

DROP TABLE IF EXISTS `amis`;
CREATE TABLE IF NOT EXISTS `amis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `friend_id` int NOT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `amis`
--

INSERT INTO `amis` (`id`, `user_id`, `friend_id`, `date_ajout`) VALUES
(1, 0, 13, '2024-05-28 14:13:23'),
(2, 13, 0, '2024-05-28 14:13:23'),
(3, 0, 13, '2024-05-28 14:13:46'),
(4, 13, 0, '2024-05-28 14:13:46'),
(5, 0, 13, '2024-05-28 14:14:03'),
(6, 13, 0, '2024-05-28 14:14:03'),
(7, 6, 13, '2024-05-28 14:16:26'),
(16, 6, 14, '2024-05-28 15:30:02');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_amis`
--

DROP TABLE IF EXISTS `demandes_amis`;
CREATE TABLE IF NOT EXISTS `demandes_amis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demandeur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `date_demande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `demandeur_id` (`demandeur_id`),
  KEY `destinataire_id` (`destinataire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandes_amis`
--

INSERT INTO `demandes_amis` (`id`, `demandeur_id`, `destinataire_id`, `date_demande`) VALUES
(2, 14, 13, '2024-05-28 15:08:39');

-- --------------------------------------------------------

--
-- Structure de la table `emplois`
--

DROP TABLE IF EXISTS `emplois`;
CREATE TABLE IF NOT EXISTS `emplois` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `localisation` varchar(100) NOT NULL,
  `type` enum('CDI','CDD','stage','apprentissage') NOT NULL,
  `date_publication` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `emplois`
--

INSERT INTO `emplois` (`id`, `titre`, `description`, `entreprise`, `localisation`, `type`, `date_publication`) VALUES
(1, 'Développeur Web', 'Recherche développeur web pour un projet innovant.', 'Tech Corp', 'Paris', 'CDI', '2024-05-30 00:00:00'),
(2, 'Data Scientist', 'Nous cherchons un data scientist pour notre équipe de recherche.', 'Data Inc', 'Lyon', 'CDI', '2024-05-28 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_event` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `description`, `date_event`, `image_path`) VALUES
(1, 'Conférence IA', 'Une conférence sur les dernières avancées en intelligence artificielle.', '2024-06-01', 'image1.jpg'),
(2, 'Atelier Développement Web', 'Un atelier pratique sur le développement web moderne.', '2024-06-15', 'image2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `destinataire_id` (`destinataire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `utilisateur_id`, `destinataire_id`, `contenu`, `date_envoi`) VALUES
(1, 1, 2, 'Bonjour Marie, bienvenue sur ECE In!', '2024-06-01 14:00:00'),
(2, 2, 1, 'Merci Antoine, heureuse d\'être ici!', '2024-06-01 15:00:00'),
(3, 14, 6, 'coucou antoine ', '0000-00-00 00:00:00'),
(4, 6, 14, 'ca va chef ?', '0000-00-00 00:00:00'),
(5, 14, 6, 'salut chef ', '0000-00-00 00:00:00'),
(6, 14, 6, 'hello\r\n', '0000-00-00 00:00:00'),
(7, 14, 6, 'salut ', '0000-00-00 00:00:00'),
(8, 14, 6, 'cc', '0000-00-00 00:00:00'),
(9, 14, 6, 'gg', '0000-00-00 00:00:00'),
(10, 14, 6, 'good luck', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_notification` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `utilisateur_id`, `contenu`, `date_notification`) VALUES
(1, 6, 'Bienvenue sur ECE In, Antoine Dupont!', '2024-06-01 10:00:00'),
(2, 6, 'Nouvel événement ajouté : Conférence IA.', '2024-06-01 12:00:00'),
(3, 1, 'Bienvenue sur ECE In, Antoine Dupont!', '2024-06-01 10:00:00'),
(4, 1, 'Nouvel événement ajouté : Conférence IA.', '2024-06-01 12:00:00'),
(5, 2, 'Bienvenue sur ECE In, Marie Curie!', '2024-06-02 10:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `photos_videos`
--

DROP TABLE IF EXISTS `photos_videos`;
CREATE TABLE IF NOT EXISTS `photos_videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `statut_id` int DEFAULT NULL,
  `type` enum('photo','video') DEFAULT NULL,
  `chemin_fichier` varchar(255) NOT NULL,
  `date_publication` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `statut_id` (`statut_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `postulations`
--

DROP TABLE IF EXISTS `postulations`;
CREATE TABLE IF NOT EXISTS `postulations` (
  `utilisateur_id` int NOT NULL,
  `emploi_id` int NOT NULL,
  `date_postulation` datetime NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`emploi_id`),
  KEY `emploi_id` (`emploi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `postulations`
--

INSERT INTO `postulations` (`utilisateur_id`, `emploi_id`, `date_postulation`) VALUES
(6, 1, '2024-05-29 09:50:59');

-- --------------------------------------------------------

--
-- Structure de la table `statuts`
--

DROP TABLE IF EXISTS `statuts`;
CREATE TABLE IF NOT EXISTS `statuts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` datetime NOT NULL,
  `visibilite` enum('public','privé') DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `statuts`
--

INSERT INTO `statuts` (`id`, `utilisateur_id`, `contenu`, `date_publication`, `visibilite`) VALUES
(1, 1, 'Excité de commencer à utiliser ECE In!', '2024-06-01 09:00:00', 'public'),
(2, 2, 'Hâte de participer à la Conférence IA!', '2024-06-01 11:00:00', 'public');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text,
  `date_naissance` date DEFAULT NULL,
  `role` enum('auteur','admin') DEFAULT 'auteur',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `email`, `mot_de_passe`, `nom`, `photo`, `bio`, `date_naissance`, `role`) VALUES
(6, 'AntoineDupont', 'antoine.dupont@example.com', '$2y$10$oxa57tMbfElat4dGGzqEReWyWrP.6WCjP843XIYWXvQjeU31xmpKK', 'Antoine Dupont', '', 'Bio de Antoine f', '1990-01-01', 'auteur'),
(13, 'test1', 'test1@gmail.com', '$2y$10$1o7RPPrRaTSEoN9oSr5kXeWf10WhXZTyQ7qBuQyqD.TpFAXPIzuJi', 'test1', NULL, NULL, NULL, 'auteur'),
(14, 'test2', 'test2@gmail.com', '$2y$10$QCCtUbZhNYah0ptrmEb/hOHXwtnWWbYu8mh4tFZfuGcNH0FCvCgJC', 'test2', NULL, NULL, NULL, 'auteur');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
