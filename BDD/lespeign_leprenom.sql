-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 26 Août 2015 à 22:18
-- Version du serveur: 5.6.25-73.1
-- Version de PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `lespeign_leprenom`
--

-- --------------------------------------------------------

--
-- Structure de la table `indice`
--

CREATE TABLE IF NOT EXISTS `indice` (
  `no_indice` int(11) NOT NULL AUTO_INCREMENT,
  `datedebut` datetime NOT NULL,
  `datefin` datetime NOT NULL,
  `libelle` longtext NOT NULL,
  `commentaire` varchar(50) NOT NULL,
  PRIMARY KEY (`no_indice`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `indice`
--

INSERT INTO `indice` (`no_indice`, `datedebut`, `datefin`, `libelle`, `commentaire`) VALUES
(1, '2011-12-01 00:00:00', '2012-02-29 23:55:00', '<img src="Image/Indice/Enigme1.PNG">\r\n<br>\r\nLes rois te donne "le bel atout" mais ne comptent pas. Les cartes de même couleur ne demandent rien, celles de couleurs différentes appellent un produit. Les profils prennent l''annonce pour s''associer.', 'Enigme N°1'),
(2, '2011-12-05 00:00:00', '2012-02-29 23:55:00', '<img src="Image/Indice/Enigme2.PNG">\r\n<br>', 'Enigme N°2'),
(3, '2011-12-06 00:00:00', '2012-02-29 23:55:00', '<font face="Arial" size="5">Cherchant une source d''inspiration, j''erre en sortant de Cologne. <br>Il fait de plus en plus sombre, trop sombre pour voir. <br>J''ai l''impression de frapper à la porte.<br>La musique me fait chevaucher ton lit, tes yeux plissés m''accompagnent jusqu''à la fin...<br></font><br>', 'Enigme N°3');

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE IF NOT EXISTS `joueur` (
  `no_joueur` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `surnom` varchar(50) NOT NULL,
  `statut` int(1) NOT NULL,
  `datecreation` datetime NOT NULL,
  PRIMARY KEY (`no_joueur`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=129 ;

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `no_action` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) DEFAULT NULL,
  `priorite` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `dateaction` datetime NOT NULL,
  PRIMARY KEY (`no_action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3534 ;

--
-- Structure de la table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id_mail` int(11) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id_mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=150 ;


-- --------------------------------------------------------

--
-- Structure de la table `parametre`
--

CREATE TABLE IF NOT EXISTS `parametre` (
  `id_param` varchar(20) NOT NULL DEFAULT '',
  `lbparam` longtext NOT NULL,
  `descparam` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_param`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `parametre`
--

INSERT INTO `parametre` (`id_param`, `lbparam`, `descparam`) VALUES
('TITLE_PAGE', 'Le Prénom', 'Titre pour Affichage page WEB'),
('CHEMIN_STYLE', 'templates/style.css', 'Style de présentation'),
('STYLE_JQUERY', 'flick/jquery-ui-1.8.16.custom.css', 'Style de la bib Javascript JQUERY'),
('VERSION', 'Version Biberon 11.10.5', 'Version'),
('ADMIN', '#CryptedMDP#', 'Mot de passe du compte Admin'),
('PLAYER_STATUT_0', 'Utilisateur connu, INACTIF', 'Code Statut Utilisateur'),
('PLAYER_STATUT_1', 'Utilisateur connu, ACTIF', 'Code Statut Utilisateur'),
('PLAYER_STATUT_2', 'Utilisateur inconnu, ATTENTE AUTORISATION', 'Code Statut Utilisateur'),
('PLAYER_STATUT_3', 'Utilisateur interdit', 'Code Statut Utilisateur'),
('REPONSE', '#CryptedAnswer#', 'La réponse que tout le monde cherche'),
('GAME_STATUS', 'ANSWERED', 'Statut du jeu (ENCOURS/ANSWERED)'),
('ADMIN_TENTATIVE', '3', 'Nombre de tentative maximal pour accéder à la partie d''administration'),
('REPONSE_INTERVAL', '28800', 'Intervalle de saisie entre 2 réponses');

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE IF NOT EXISTS `reponse` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `reponse`
--

INSERT INTO `reponse` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-12-05 11:46:27'),
(2, 82, 'Enzo', '2011-12-06 12:26:07'),
(3, 24, 'Gérard', '2011-12-07 13:23:09'),
(4, 20, 'leon', '2011-12-07 14:26:02'),
(5, 115, 'jules', '2011-12-08 00:12:46'),
(6, 28, '******', '2011-12-13 17:49:33');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_ARISTIDE`
--

CREATE TABLE IF NOT EXISTS `reponse_ARISTIDE` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `reponse_ARISTIDE`
--

INSERT INTO `reponse_ARISTIDE` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 15, 'Paul', '2011-10-11 16:14:23'),
(2, 8, 'VINCENT', '2011-10-11 21:36:30'),
(3, 24, 'Charles', '2011-10-12 00:36:32'),
(4, 103, 'Albert', '2011-10-12 00:39:40'),
(5, 16, 'Borat', '2011-10-12 13:25:10'),
(6, 107, 'Hervé', '2011-10-12 13:49:25'),
(7, 82, 'Amusez-vous la bien!', '2011-10-12 18:02:28'),
(8, 8, 'René', '2011-10-12 19:47:57'),
(9, 107, 'Georges', '2011-10-13 15:20:39'),
(10, 103, 'Constantin', '2011-10-13 17:36:32'),
(11, 31, 'Marcel', '2011-10-13 18:52:04'),
(12, 8, 'THEODOSE', '2011-10-13 20:00:05'),
(13, 16, 'CASIMIR', '2011-10-13 20:04:48'),
(14, 24, 'Félix', '2011-10-13 23:44:31'),
(15, 105, 'pierre', '2011-10-14 09:15:40'),
(16, 107, 'Philippe', '2011-10-14 10:07:22'),
(17, 16, 'Jean Mich', '2011-10-14 13:29:26'),
(18, 8, 'nicolas', '2011-10-14 14:09:35'),
(19, 24, 'Jacques', '2011-10-14 14:28:04'),
(20, 103, 'Placide', '2011-10-14 20:56:17'),
(21, 107, 'Louis', '2011-10-14 22:48:41'),
(22, 8, 'théodore', '2011-10-15 09:19:57'),
(23, 24, 'Aristide', '2011-10-15 23:04:55'),
(24, 107, 'Jean', '2011-10-16 00:13:46'),
(25, 8, 'Aristide', '2011-10-16 08:29:44'),
(26, 103, 'Philippe', '2011-10-16 12:35:44'),
(27, 20, 'Claude', '2011-10-16 18:05:05'),
(28, 107, 'Valery', '2011-10-16 20:36:17');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_ARMAND`
--

CREATE TABLE IF NOT EXISTS `reponse_ARMAND` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `reponse_ARMAND`
--

INSERT INTO `reponse_ARMAND` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 20, 'Hubert', '2011-10-06 15:46:08'),
(2, 105, 'Claude', '2011-10-06 15:46:56'),
(4, 107, 'Néron', '2011-10-06 18:13:51'),
(5, 16, 'ROMAIN', '2011-10-06 20:18:58'),
(6, 111, 'caligula', '2011-10-06 21:57:15'),
(7, 24, 'Louis', '2011-10-07 13:20:31'),
(8, 25, 'Zephirin', '2011-10-07 17:42:31'),
(9, 107, 'Bérénice', '2011-10-08 22:04:34'),
(10, 107, 'Georges', '2011-10-10 10:23:02'),
(11, 15, 'armand', '2011-10-10 13:46:29');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_CAMILLE`
--

CREATE TABLE IF NOT EXISTS `reponse_CAMILLE` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `reponse_CAMILLE`
--

INSERT INTO `reponse_CAMILLE` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-10-18 14:44:43'),
(2, 8, 'Joseph', '2011-10-18 18:49:19'),
(3, 25, 'Perceval', '2011-10-18 18:50:31'),
(4, 115, 'Stephen', '2011-10-18 21:33:50'),
(5, 103, 'Howard', '2011-10-18 22:54:07'),
(6, 96, 'maurice', '2011-10-19 10:48:30'),
(7, 107, 'François', '2011-10-19 11:28:49'),
(8, 8, 'Willie', '2011-10-19 20:57:58'),
(9, 24, 'Malcolm', '2011-10-19 23:50:44'),
(10, 96, 'rené', '2011-10-20 10:49:52'),
(11, 20, 'germain', '2011-10-20 11:41:09'),
(12, 115, 'kurt', '2011-10-20 12:41:11'),
(13, 107, 'Stanislas', '2011-10-20 14:04:38'),
(14, 24, 'Louis', '2011-10-20 16:40:34'),
(15, 107, 'Laurent', '2011-10-21 11:52:17'),
(16, 24, 'Horace', '2011-10-21 12:15:02'),
(17, 20, 'tristan', '2011-10-21 12:16:38'),
(18, 105, 'Camille', '2011-10-21 12:20:16'),
(19, 96, 'René-Charles', '2011-10-21 15:51:55'),
(20, 8, 'Virgile', '2011-10-21 17:18:02');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_DAPHNE`
--

CREATE TABLE IF NOT EXISTS `reponse_DAPHNE` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `reponse_DAPHNE`
--

INSERT INTO `reponse_DAPHNE` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-10-10 14:18:41'),
(2, 16, 'MERCEDES', '2011-10-10 15:27:08'),
(3, 105, 'angelina', '2011-10-10 16:10:38'),
(4, 107, 'NICOLAS', '2011-10-10 16:29:13'),
(5, 8, 'evan', '2011-10-10 21:03:38'),
(6, 111, 'natacha', '2011-10-10 21:46:31'),
(7, 24, 'gabriel', '2011-10-10 22:23:14'),
(8, 82, 'Mickael', '2011-10-11 10:56:50'),
(9, 107, 'Michelle', '2011-10-11 11:55:01'),
(10, 105, 'daphnée', '2011-10-11 12:59:39'),
(11, 24, 'Daphné', '2011-10-11 13:13:11');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_FAUSTINE`
--

CREATE TABLE IF NOT EXISTS `reponse_FAUSTINE` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `reponse_FAUSTINE`
--

INSERT INTO `reponse_FAUSTINE` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-10-17 12:27:25'),
(3, 24, 'Nathaniel', '2011-10-17 19:49:31'),
(4, 8, 'Jean', '2011-10-17 20:10:05'),
(5, 24, 'Chloë', '2011-10-17 21:28:59'),
(6, 111, 'Mario', '2011-10-17 22:12:58'),
(7, 31, 'Adam', '2011-10-17 22:26:33'),
(8, 24, 'Louis', '2011-10-17 22:55:45'),
(9, 24, 'Nova', '2011-10-18 00:11:21'),
(10, 107, 'Faustine', '2011-10-18 13:52:31');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_OCTAVE`
--

CREATE TABLE IF NOT EXISTS `reponse_OCTAVE` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Contenu de la table `reponse_OCTAVE`
--

INSERT INTO `reponse_OCTAVE` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-10-21 21:07:10'),
(2, 8, 'Louis', '2011-10-22 08:51:46'),
(3, 25, 'Guilia', '2011-10-22 11:02:50'),
(4, 8, 'Andréa', '2011-10-24 09:27:53'),
(5, 96, 'paco', '2011-10-24 11:39:43'),
(6, 105, 'rita', '2011-10-24 12:16:58'),
(7, 24, 'Alessandro', '2011-10-24 13:56:53'),
(8, 20, 'alexandre', '2011-10-24 15:58:28'),
(9, 111, 'Jésus', '2011-10-24 21:11:56'),
(10, 16, 'François', '2011-10-24 21:48:30'),
(11, 96, 'julian', '2011-10-25 10:51:13'),
(12, 111, 'Pascal', '2011-10-25 20:53:43'),
(13, 107, 'Christophe', '2011-10-26 08:51:46'),
(14, 20, 'john', '2011-10-26 10:15:47'),
(15, 105, 'fleur', '2011-10-26 10:34:25'),
(16, 24, 'Jean', '2011-10-26 11:10:02'),
(17, 98, 'Arthur', '2011-10-26 14:22:35'),
(18, 115, 'Joseph', '2011-10-26 15:46:54'),
(19, 96, 'lucrezia', '2011-10-26 16:12:08'),
(20, 25, 'Charles', '2011-10-26 20:49:14'),
(21, 8, 'Ferdinand', '2011-10-26 21:30:18'),
(22, 107, 'Thomas', '2011-10-27 09:19:39'),
(23, 96, 'lorène', '2011-10-27 09:28:10'),
(24, 8, 'Ernest', '2011-10-27 10:19:28'),
(25, 105, 'baptiste', '2011-10-27 15:46:33'),
(26, 24, 'Tom', '2011-10-27 17:48:11'),
(27, 103, 'Mathias', '2011-10-27 20:00:19'),
(28, 107, 'Paul', '2011-10-28 11:37:44'),
(29, 20, 'Alexandra', '2011-10-28 15:10:10'),
(31, 24, 'stanislas', '2011-10-28 15:41:07'),
(32, 105, 'auguste', '2011-10-28 15:47:44'),
(33, 98, 'Octave', '2011-10-28 15:51:35'),
(34, 24, 'Octave', '2011-10-29 10:19:08');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_SACHA`
--

CREATE TABLE IF NOT EXISTS `reponse_SACHA` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `reponse_SACHA`
--

INSERT INTO `reponse_SACHA` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-11-02 17:41:49'),
(2, 20, 'Marcello', '2011-11-02 18:01:35'),
(3, 105, 'Luigi', '2011-11-02 18:03:49'),
(4, 107, 'Marc', '2011-11-02 18:04:48'),
(5, 25, 'Laurent', '2011-11-02 21:30:57'),
(6, 8, 'Philippe', '2011-11-03 13:09:38'),
(7, 20, 'Benjamin', '2011-11-03 14:30:02'),
(8, 8, 'Pierre', '2011-11-04 07:30:36'),
(9, 8, 'Kenan', '2011-11-04 18:05:28'),
(10, 103, 'Jean-Baptiste', '2011-11-04 20:17:35'),
(11, 107, 'Raphaël', '2011-11-07 14:07:26'),
(12, 20, 'Adrien', '2011-11-07 17:56:17'),
(13, 107, 'Alexandre', '2011-11-09 14:06:53'),
(14, 20, 'Arthur', '2011-11-09 14:10:59'),
(15, 103, 'Hugues', '2011-11-10 21:30:45'),
(16, 8, 'Jean', '2011-11-11 10:02:10'),
(17, 8, 'Mathieu', '2011-11-12 12:59:26'),
(18, 8, 'Lucien', '2011-11-13 09:39:46'),
(19, 8, 'Sacha', '2011-11-13 17:39:47');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_ZADIG`
--

CREATE TABLE IF NOT EXISTS `reponse_ZADIG` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Contenu de la table `reponse_ZADIG`
--

INSERT INTO `reponse_ZADIG` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 24, 'Ines', '2011-09-27 20:44:43'),
(2, 24, 'Tom', '2011-09-28 22:40:24'),
(3, 24, 'Bojidar', '2011-09-28 22:40:42'),
(4, 24, 'Hector', '2011-09-28 22:40:51'),
(5, 24, 'Marie', '2011-09-28 22:40:59'),
(11, 96, 'Hugh', '2011-09-29 09:19:02'),
(12, 24, 'Charles', '2011-09-29 20:45:43'),
(13, 24, 'Daphné', '2011-09-29 20:46:04'),
(14, 24, 'Igor', '2011-09-29 20:46:24'),
(15, 24, 'Marine', '2011-09-29 20:46:47'),
(16, 24, 'Susanna', '2011-09-29 20:47:06'),
(27, 20, 'Johann', '2011-10-01 11:32:44'),
(28, 105, 'jo la frite', '2011-10-02 13:05:07'),
(26, 105, 'joséphine', '2011-09-30 16:16:26'),
(25, 103, 'Kadafi', '2011-09-30 14:33:04'),
(24, 20, 'Louis', '2011-09-30 09:18:44'),
(29, 20, 'Pasquale', '2011-10-03 12:21:59'),
(30, 107, 'Nicolas', '2011-10-04 13:29:50'),
(31, 96, 'Lucien', '2011-10-04 15:55:50'),
(32, 82, 'Serge', '2011-10-04 22:27:26'),
(33, 24, 'zadig', '2011-10-05 00:24:22'),
(34, 20, 'elie', '2011-10-05 08:50:58'),
(35, 8, 'c''est pas pascal quand même... trop simple...', '2011-10-05 11:09:58'),
(36, 107, 'Zadig', '2011-10-05 13:38:46'),
(37, 25, 'blaise (à l''aise ...)', '2011-10-05 19:15:40'),
(38, 31, 'François', '2011-10-05 19:17:40'),
(39, 16, 'voltaire', '2011-10-05 20:15:52');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_ZELIE`
--

CREATE TABLE IF NOT EXISTS `reponse_ZELIE` (
  `no_reponse` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_joueur` int(11) NOT NULL,
  `lbreponse` varchar(50) NOT NULL,
  `datereponse` datetime NOT NULL,
  PRIMARY KEY (`no_reponse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `reponse_ZELIE`
--

INSERT INTO `reponse_ZELIE` (`no_reponse`, `no_joueur`, `lbreponse`, `datereponse`) VALUES
(1, 101, 'Romain', '2011-10-06 01:25:39'),
(2, 107, 'Therese', '2011-10-06 07:58:21'),
(3, 96, 'simone', '2011-10-06 10:33:24'),
(4, 103, 'Thérèse', '2011-10-06 12:40:07'),
(5, 25, 'heidi', '2011-10-06 13:57:19'),
(6, 105, 'lola', '2011-10-06 14:52:28'),
(7, 20, 'marie', '2011-10-06 14:55:34'),
(8, 24, 'Zélie', '2011-10-06 15:35:53');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
