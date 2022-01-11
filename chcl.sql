-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 02 Novembre 2021 à 00:52
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `chcl`
--

-- --------------------------------------------------------

--
-- Structure de la table `annee_academique`
--

CREATE TABLE `annee_academique` (
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `annee_debut` date NOT NULL,
  `annee_fin` date NOT NULL,
  `etat` varchar(40) NOT NULL,
  `annee_academique` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `annee_academique`
--

INSERT INTO `annee_academique` (`date_debut`, `date_fin`, `annee_debut`, `annee_fin`, `etat`, `annee_academique`) VALUES
('2021-10-06', '2021-10-04', '2021-10-15', '2021-10-28', 'E', '2020-2021'),
('2021-10-13', '2021-10-20', '2021-10-16', '2021-10-22', 'E', '2021-2022'),
('2016-06-13', '2021-08-16', '2016-07-10', '2021-09-16', 'E', '2016-2021'),
('2020-10-04', '2021-05-23', '2020-10-20', '2021-05-23', 'E', '2020-2021'),
('2020-10-04', '2021-05-23', '2020-10-20', '2021-05-23', 'E', '2020-2021'),
('2020-10-04', '2021-05-23', '2020-10-20', '2021-05-23', 'E', '2020-2021');

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `codeCours` int(14) NOT NULL,
  `nom_cours` varchar(40) NOT NULL,
  `filiere` varchar(40) NOT NULL,
  `niveau` varchar(40) NOT NULL,
  `session` varchar(40) NOT NULL,
  `coefficient` varchar(40) NOT NULL,
  `professeur_titulaire` varchar(40) NOT NULL,
  `professeur_supleant` varchar(40) NOT NULL,
  `jour_cours` varchar(40) NOT NULL,
  `heure_debut` varchar(14) NOT NULL,
  `heure_fin` varchar(14) NOT NULL,
  `etat` varchar(40) NOT NULL,
  `operation` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `codeEtudiant` int(14) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `prenom` varchar(40) NOT NULL,
  `sexe` varchar(14) NOT NULL,
  `lieu_naissance` int(11) NOT NULL,
  `date_naissance` date NOT NULL,
  `telephone` int(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `IdNiveau` int(11) NOT NULL,
  `nif_cin` int(30) NOT NULL,
  `personne_ref` varchar(50) NOT NULL,
  `annee_academique` varchar(30) NOT NULL,
  `etat` varchar(30) NOT NULL,
  `memo` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `idFiliere` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau` (
  `idNiveau` int(11) NOT NULL,
  `idFiliere` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id_note` int(14) NOT NULL,
  `session` varchar(40) NOT NULL,
  `codeEtudiant` int(14) NOT NULL,
  `codeCours` int(14) NOT NULL,
  `note` varchar(50) NOT NULL,
  `annee_academique` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `poste`
--

CREATE TABLE `poste` (
  `idPoste` int(11) NOT NULL,
  `nomPoste` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `codeProfesseur` int(14) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `prenom` varchar(40) NOT NULL,
  `sexe` varchar(14) NOT NULL,
  `adresse` varchar(40) NOT NULL,
  `telephone` varchar(40) NOT NULL,
  `statut_matrimonial` varchar(40) NOT NULL,
  `lieu_naissance` varchar(40) NOT NULL,
  `date_naissance` date NOT NULL,
  `cours_esseigner` varchar(40) NOT NULL,
  `salaire` int(40) NOT NULL,
  `poste` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `nif_cin` varchar(40) NOT NULL,
  `etat` varchar(40) NOT NULL,
  `memo` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `codeUtilisateur` int(14) NOT NULL,
  `nomUser` varchar(40) NOT NULL,
  `prenomUser` varchar(40) NOT NULL,
  `poste` varchar(40) NOT NULL,
  `pseudo` varchar(40) NOT NULL,
  `pass` int(30) NOT NULL,
  `etat` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`codeCours`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`codeEtudiant`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`codeProfesseur`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
