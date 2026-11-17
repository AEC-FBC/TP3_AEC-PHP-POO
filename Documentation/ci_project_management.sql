-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 17 nov. 2026 à 19:55
-- Version du serveur : 8.4.13-0ubuntu0.26.04.2
-- Version de PHP : 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ci_project_management`
--
CREATE DATABASE IF NOT EXISTS `ci_project_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ci_project_management`;

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id`, `name`, `website`) VALUES
(1, 'Test Curl Inc', 'https://testcurl.example.com'),
(3, 'entrebien', 'https://www.elegoo.com/pages/download');

-- --------------------------------------------------------

--
-- Structure de la table `company_locations`
--

DROP TABLE IF EXISTS `company_locations`;
CREATE TABLE `company_locations` (
  `id` int NOT NULL,
  `company_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `company_locations`
--

INSERT INTO `company_locations` (`id`, `company_id`, `name`, `address`, `unit`, `postal_code`, `country`, `region`, `city`, `phone`, `email`) VALUES
(1, 1, 'Bureau Test', '1 rue Test', NULL, NULL, NULL, NULL, 'Laval', '1 450-555-0000', 'bureau@testcurl.example.com'),
(6, 1, 't123', '123', '123', 'J5J0M3', 'Canada', 'Quebec', 'Sainte-Sophie', '5145145145', 'fred_pnc_37658@asdw.ca'),
(7, 3, 'awdws', '123 dupot-Crevier', NULL, 'J4J6M3', 'Canada', 'Quebec', 'Sainte-Sophie', '5145145145', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` int NOT NULL,
  `company_id` int NOT NULL,
  `company_location_id` int DEFAULT NULL,
  `first_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `company_id`, `company_location_id`, `first_name`, `last_name`, `job_title`, `phone`, `email`) VALUES
(1, 1, 1, 'Jean', 'Test', 'CTO', '1 450-555-0001', 'jean@testcurl.example.com'),
(5, 1, 1, 'tester', 'ters', '123', '514-555-8799', 'fred@pixelncraft.ca'),
(6, 1, 1, 'Chris', 'Christy', 'awf', '5145145145', 'awf@awd.cq'),
(7, 3, 7, 'asda', 'asd', 'adw', '514-555-8799', 'awd@sfe.ca');

-- --------------------------------------------------------

--
-- Structure de la table `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int NOT NULL,
  `company_id` int NOT NULL,
  `company_location_id` int DEFAULT NULL,
  `primary_contact_id` int NOT NULL,
  `status_id` int NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `planned_start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `project`
--

INSERT INTO `project` (`id`, `company_id`, `company_location_id`, `primary_contact_id`, `status_id`, `title`, `description`, `planned_start_date`, `planned_end_date`, `image_path`) VALUES
(3, 1, NULL, 1, 1, 'Projet Test', 'sfaw', '2026-09-15', '2026-09-29', NULL),
(4, 1, 1, 1, 2, 'Test prj 123', 'adwwa', '2026-09-28', '2026-10-07', NULL),
(5, 3, 7, 7, 1, 'awdaw', 'adawd', '2026-10-18', '2026-12-24', NULL),
(6, 1, 1, 1, 2, 'test dun projet', 'explication du projet', '2026-11-16', '2026-11-28', 'uploads/projects/3a1f400ace4367835a750f8dba487305.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `project_service`
--

DROP TABLE IF EXISTS `project_service`;
CREATE TABLE `project_service` (
  `project_id` int NOT NULL,
  `service_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `project_service`
--

INSERT INTO `project_service` (`project_id`, `service_id`) VALUES
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(3, 5),
(6, 5);

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE `service` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `name`, `description`) VALUES
(1, 'Développement web', 'Conception et développement de sites et applications web.'),
(2, 'Design UI/UX', 'Création d\'interfaces et d\'expériences utilisateur.'),
(3, 'Hébergement', 'Hébergement et maintenance de l\'infrastructure.'),
(4, 'Consultation', 'Analyse des besoins et accompagnement stratégique.'),
(5, 'Support technique', 'Support et maintenance continue après livraison.');

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#667579',
  `display_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`id`, `name`, `color`, `display_order`) VALUES
(1, 'À faire', '#667579', 1),
(2, 'En cours', '#1d6fd6', 2),
(3, 'En pause', '#c77d13', 3),
(4, 'Terminé', '#1a8f4c', 4),
(5, 'Annulé', '#b3261e', 5);

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `status_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `planned_start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `project_id`, `status_id`, `title`, `description`, `planned_start_date`, `planned_end_date`) VALUES
(2, 3, 4, 'test task 1', 'test task 1', '2026-09-17', '2026-09-08'),
(3, 3, 4, 'test task 2', 'test task 2', '2026-09-01', '2026-09-22'),
(6, 5, 1, 'awdaw entrebien', 'awdaw', '2026-10-19', '2026-10-28');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Administrateur', 'admin@craft-industria.test', '$2y$12$OmrL/bNi8Fe.fUo/a9TLvur2WgJG1Xh5Mpt4yfuKOkX3vN3GHuzQm', 'admin'),
(2, 'Client Test', 'client@craft-industria.test', '$2y$12$83C23pfFj9GY/tDdeI/90OIUiq3g.Rfy.p.lTwbTeXFv6K6EugYfO', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `visit_log`
--

DROP TABLE IF EXISTS `visit_log`;
CREATE TABLE `visit_log` (
  `id` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visited_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_url` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `visit_log`
--

INSERT INTO `visit_log` (`id`, `ip_address`, `visited_at`, `username`, `page_url`) VALUES
(1, '127.0.0.1', '2026-11-17 13:00:16', 'Client Test', '/tasks'),
(2, '127.0.0.1', '2026-11-17 13:00:17', 'Client Test', '/'),
(3, '127.0.0.1', '2026-11-17 13:00:17', 'Client Test', '/projects'),
(4, '127.0.0.1', '2026-11-17 13:00:18', 'Client Test', '/companies'),
(5, '127.0.0.1', '2026-11-17 13:00:19', 'Client Test', '/'),
(6, '127.0.0.1', '2026-11-17 13:00:19', 'Client Test', '/projects'),
(7, '127.0.0.1', '2026-11-17 13:00:20', 'Client Test', '/projects'),
(8, '127.0.0.1', '2026-11-17 13:00:22', 'Client Test', '/companies'),
(9, '127.0.0.1', '2026-11-17 13:04:05', 'Client Test', '/companies'),
(10, '127.0.0.1', '2026-11-17 13:04:07', 'Client Test', '/logout'),
(11, '127.0.0.1', '2026-11-17 13:04:07', 'Visiteur', '/login'),
(12, '127.0.0.1', '2026-11-17 13:04:19', 'Visiteur', '/login'),
(13, '127.0.0.1', '2026-11-17 13:04:19', 'Administrateur', '/admin'),
(14, '127.0.0.1', '2026-11-17 13:04:21', 'Administrateur', '/admin/logs'),
(15, '127.0.0.1', '2026-11-17 13:05:04', 'Administrateur', '/logout'),
(16, '127.0.0.1', '2026-11-17 13:05:04', 'Visiteur', '/login'),
(17, '127.0.0.1', '2026-11-17 13:05:15', 'Visiteur', '/login'),
(18, '127.0.0.1', '2026-11-17 13:05:15', 'Client Test', '/projects'),
(19, '127.0.0.1', '2026-11-17 13:05:22', 'Client Test', '/admin/logs'),
(20, '127.0.0.1', '2026-11-17 13:14:43', 'Client Test', '/admin/logs'),
(21, '127.0.0.1', '2026-11-17 13:14:45', 'Client Test', '/projects'),
(22, '127.0.0.1', '2026-11-17 13:14:47', 'Client Test', '/projects/show?id=6'),
(23, '127.0.0.1', '2026-11-17 13:14:52', 'Client Test', '/projects/edit?id=6'),
(24, '127.0.0.1', '2026-11-17 13:15:22', 'Client Test', '/projects/edit?id=6'),
(25, '127.0.0.1', '2026-11-17 13:15:22', 'Client Test', '/projects/show?id=6&notice=updated'),
(26, '127.0.0.1', '2026-11-17 13:15:26', 'Client Test', '/projects/show?id=6&notice=updated'),
(27, '127.0.0.1', '2026-11-17 13:15:29', 'Client Test', '/projects'),
(28, '127.0.0.1', '2026-11-17 13:17:09', 'Client Test', '/projects'),
(29, '127.0.0.1', '2026-11-17 13:17:10', 'Client Test', '/projects/show?id=6'),
(30, '127.0.0.1', '2026-11-17 13:17:10', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(31, '127.0.0.1', '2026-11-17 13:17:14', 'Client Test', '/projects/show?id=5'),
(32, '127.0.0.1', '2026-11-17 13:17:18', 'Client Test', '/projects/show?id=4'),
(33, '127.0.0.1', '2026-11-17 13:17:22', 'Client Test', '/projects/show?id=3'),
(34, '127.0.0.1', '2026-11-17 13:17:25', 'Client Test', '/projects/show?id=6'),
(35, '127.0.0.1', '2026-11-17 13:17:25', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(36, '127.0.0.1', '2026-11-17 13:17:48', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(37, '127.0.0.1', '2026-11-17 13:21:41', 'Client Test', '/projects/show?id=6'),
(38, '127.0.0.1', '2026-11-17 13:21:41', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(39, '127.0.0.1', '2026-11-17 13:21:41', 'Client Test', '/public/assets/css/style.css'),
(40, '127.0.0.1', '2026-11-17 13:21:41', 'Client Test', '/public/assets/icons/folder.svg'),
(41, '127.0.0.1', '2026-11-17 13:21:41', 'Client Test', '/public/assets/icons/building.svg'),
(42, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/projects'),
(43, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/public/assets/css/style.css'),
(44, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/public/assets/icons/folder.svg'),
(45, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/public/assets/icons/building.svg'),
(46, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/projects/show?id=6&notice=updated'),
(47, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/public/assets/css/style.css'),
(48, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/public/assets/icons/folder.svg'),
(49, '127.0.0.1', '2026-11-17 13:21:46', 'Client Test', '/public/assets/icons/building.svg'),
(50, '127.0.0.1', '2026-11-17 13:22:31', 'Client Test', '/projects/show?id=6'),
(51, '127.0.0.1', '2026-11-17 13:22:31', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(52, '127.0.0.1', '2026-11-17 13:22:32', 'Client Test', '/projects/show?id=6'),
(53, '127.0.0.1', '2026-11-17 13:22:32', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(54, '127.0.0.1', '2026-11-17 13:22:39', 'Client Test', '/projects/show?id=6'),
(55, '127.0.0.1', '2026-11-17 13:22:40', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(56, '127.0.0.1', '2026-11-17 13:22:40', 'Client Test', '/favicon.ico'),
(57, '127.0.0.1', '2026-11-17 13:22:40', 'Client Test', '/projects/show?id=6'),
(58, '127.0.0.1', '2026-11-17 13:22:40', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(59, '127.0.0.1', '2026-11-17 13:22:40', 'Client Test', '/favicon.ico'),
(60, '127.0.0.1', '2026-11-17 13:23:34', 'Client Test', '/projects/show?id=6'),
(61, '127.0.0.1', '2026-11-17 13:23:37', 'Client Test', '/projects/show?id=6'),
(62, '127.0.0.1', '2026-11-17 13:23:37', 'Client Test', '/favicon.ico'),
(63, '127.0.0.1', '2026-11-17 13:23:43', 'Client Test', '/'),
(64, '127.0.0.1', '2026-11-17 13:23:44', 'Client Test', '/'),
(65, '127.0.0.1', '2026-11-17 13:23:47', 'Client Test', '/'),
(66, '127.0.0.1', '2026-11-17 13:23:53', 'Client Test', '/'),
(67, '127.0.0.1', '2026-11-17 13:23:54', 'Client Test', '/'),
(68, '127.0.0.1', '2026-11-17 13:24:54', 'Client Test', '/'),
(69, '127.0.0.1', '2026-11-17 13:24:56', 'Client Test', '/projects'),
(70, '127.0.0.1', '2026-11-17 13:24:57', 'Client Test', '/companies'),
(71, '127.0.0.1', '2026-11-17 13:24:59', 'Client Test', '/projects'),
(72, '127.0.0.1', '2026-11-17 13:25:00', 'Client Test', '/projects/show?id=6'),
(73, '127.0.0.1', '2026-11-17 13:25:00', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(74, '127.0.0.1', '2026-11-17 13:25:22', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(75, '127.0.0.1', '2026-11-17 13:27:01', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(76, '127.0.0.1', '2026-11-17 13:27:03', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(77, '127.0.0.1', '2026-11-17 13:27:05', 'Client Test', '/projects/show?id=6'),
(78, '127.0.0.1', '2026-11-17 13:27:05', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(79, '127.0.0.1', '2026-11-17 13:27:05', 'Client Test', '/projects/show?id=6'),
(80, '127.0.0.1', '2026-11-17 13:27:05', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(81, '127.0.0.1', '2026-11-17 13:27:06', 'Client Test', '/projects/show?id=6'),
(82, '127.0.0.1', '2026-11-17 13:27:06', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(83, '127.0.0.1', '2026-11-17 13:27:06', 'Client Test', '/projects/show?id=6'),
(84, '127.0.0.1', '2026-11-17 13:27:06', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(85, '127.0.0.1', '2026-11-17 13:27:12', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(86, '127.0.0.1', '2026-11-17 13:27:34', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(87, '127.0.0.1', '2026-11-17 13:28:14', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(88, '127.0.0.1', '2026-11-17 13:28:15', 'Client Test', '/tasks'),
(89, '127.0.0.1', '2026-11-17 13:28:17', 'Client Test', '/tasks/show?id=6'),
(90, '127.0.0.1', '2026-11-17 13:28:19', 'Client Test', '/projects'),
(91, '127.0.0.1', '2026-11-17 13:28:20', 'Client Test', '/projects/show?id=6'),
(92, '127.0.0.1', '2026-11-17 13:28:21', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(93, '127.0.0.1', '2026-11-17 13:28:24', 'Client Test', '/'),
(94, '127.0.0.1', '2026-11-17 13:28:44', 'Client Test', '/'),
(95, '127.0.0.1', '2026-11-17 13:28:52', 'Client Test', '/'),
(96, '127.0.0.1', '2026-11-17 13:28:52', 'Client Test', '/'),
(97, '127.0.0.1', '2026-11-17 13:28:54', 'Client Test', '/'),
(98, '127.0.0.1', '2026-11-17 13:29:04', 'Client Test', '/'),
(99, '127.0.0.1', '2026-11-17 13:29:37', 'Client Test', '/'),
(100, '127.0.0.1', '2026-11-17 13:29:39', 'Client Test', '/projects'),
(101, '127.0.0.1', '2026-11-17 13:29:41', 'Client Test', '/projects/show?id=6'),
(102, '127.0.0.1', '2026-11-17 13:30:21', 'Client Test', '/projects/show?id=6'),
(103, '127.0.0.1', '2026-11-17 13:30:28', 'Client Test', '/'),
(104, '127.0.0.1', '2026-11-17 13:30:30', 'Client Test', '/projects'),
(105, '127.0.0.1', '2026-11-17 13:30:32', 'Client Test', '/projects/show?id=6'),
(106, '127.0.0.1', '2026-11-17 13:30:32', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(107, '127.0.0.1', '2026-11-17 13:30:45', 'Client Test', '/projects/show?id=6'),
(108, '127.0.0.1', '2026-11-17 13:32:19', 'Client Test', '/projects/show?id=6'),
(109, '127.0.0.1', '2026-11-17 13:32:20', 'Client Test', '/projects/show?id=6'),
(110, '127.0.0.1', '2026-11-17 13:32:36', 'Client Test', '/projects/show?id=6'),
(111, '127.0.0.1', '2026-11-17 13:32:36', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(112, '127.0.0.1', '2026-11-17 13:32:42', 'Client Test', '/projects/show?id=6'),
(113, '127.0.0.1', '2026-11-17 13:32:42', 'Client Test', '/uploads/projects/98c06cf1a4aead287f5a1257ba598abb.jpg'),
(114, '127.0.0.1', '2026-11-17 13:32:53', 'Client Test', '/projects/show?id=6'),
(115, '127.0.0.1', '2026-11-17 13:33:12', 'Client Test', '/projects/show?id=6'),
(116, '127.0.0.1', '2026-11-17 13:33:13', 'Client Test', '/projects/show?id=6'),
(117, '127.0.0.1', '2026-11-17 13:33:26', 'Client Test', '/projects/show?id=6'),
(118, '127.0.0.1', '2026-11-17 13:33:28', 'Client Test', '/tasks'),
(119, '127.0.0.1', '2026-11-17 13:33:30', 'Client Test', '/tasks'),
(120, '127.0.0.1', '2026-11-17 13:33:30', 'Client Test', '/favicon.ico'),
(121, '127.0.0.1', '2026-11-17 13:34:14', 'Client Test', '/projects/show?id=6'),
(122, '127.0.0.1', '2026-11-17 13:34:18', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(123, '127.0.0.1', '2026-11-17 13:36:00', 'Client Test', '/projects/show?id=6'),
(124, '127.0.0.1', '2026-11-17 13:36:00', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(125, '127.0.0.1', '2026-11-17 13:36:16', 'Client Test', '/projects/show?id=6'),
(126, '127.0.0.1', '2026-11-17 13:36:16', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(127, '127.0.0.1', '2026-11-17 13:38:06', 'Client Test', '/projects/show?id=6'),
(128, '127.0.0.1', '2026-11-17 13:38:06', 'Client Test', '/.well-known/appspecific/com.chrome.devtools.json'),
(129, '127.0.0.1', '2026-11-17 13:38:44', 'Client Test', '/projects/show?id=6'),
(130, '127.0.0.1', '2026-11-17 13:43:04', 'Client Test', '/projects/show?id=6'),
(131, '127.0.0.1', '2026-11-17 13:43:06', 'Client Test', '/projects/edit?id=6'),
(132, '127.0.0.1', '2026-11-17 13:43:23', 'Client Test', '/projects/edit?id=6'),
(133, '127.0.0.1', '2026-11-17 13:43:23', 'Client Test', '/projects/show?id=6&notice=updated'),
(134, '127.0.0.1', '2026-11-17 13:46:08', 'Client Test', '/projects'),
(135, '127.0.0.1', '2026-11-17 13:46:14', 'Client Test', '/logout'),
(136, '127.0.0.1', '2026-11-17 13:46:14', 'Visiteur', '/login'),
(137, '127.0.0.1', '2026-11-17 13:46:53', 'Visiteur', '/login'),
(138, '127.0.0.1', '2026-11-17 13:46:53', 'Administrateur', '/admin'),
(139, '127.0.0.1', '2026-11-17 13:46:55', 'Administrateur', '/projects'),
(140, '127.0.0.1', '2026-11-17 13:46:56', 'Administrateur', '/projects/create'),
(141, '127.0.0.1', '2026-11-17 13:48:05', 'Administrateur', '/projects/create'),
(142, '127.0.0.1', '2026-11-17 13:48:05', 'Administrateur', '/projects/show?id=7&notice=created'),
(143, '127.0.0.1', '2026-11-17 13:48:11', 'Administrateur', '/projects'),
(144, '127.0.0.1', '2026-11-17 13:48:34', 'Administrateur', '/projects/show?id=7'),
(145, '127.0.0.1', '2026-11-17 13:48:37', 'Administrateur', '/projects/delete'),
(146, '127.0.0.1', '2026-11-17 13:48:37', 'Administrateur', '/projects?notice=deleted'),
(147, '127.0.0.1', '2026-11-17 14:31:45', 'Administrateur', '/projects?notice=deleted'),
(148, '127.0.0.1', '2026-11-17 14:31:47', 'Administrateur', '/projects'),
(149, '127.0.0.1', '2026-11-17 14:31:49', 'Administrateur', '/companies'),
(150, '127.0.0.1', '2026-11-17 14:31:51', 'Administrateur', '/companies/show?id=1'),
(151, '127.0.0.1', '2026-11-17 14:32:00', 'Administrateur', '/locations/edit?id=1'),
(152, '127.0.0.1', '2026-11-17 14:32:10', 'Administrateur', '/locations/edit?id=1'),
(153, '127.0.0.1', '2026-11-17 14:32:35', 'Administrateur', '/projects'),
(154, '127.0.0.1', '2026-11-17 14:32:37', 'Administrateur', '/projects/edit?id=6'),
(155, '127.0.0.1', '2026-11-17 14:32:51', 'Administrateur', '/projects/edit?id=6');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `company_locations`
--
ALTER TABLE `company_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_locations_company_id` (`company_id`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_company_id` (`company_id`),
  ADD KEY `fk_contact_company_location_id` (`company_location_id`);

--
-- Index pour la table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_company_id` (`company_id`),
  ADD KEY `fk_project_company_location_id` (`company_location_id`),
  ADD KEY `fk_project_primary_contact_id` (`primary_contact_id`),
  ADD KEY `fk_projects_status_id` (`status_id`);

--
-- Index pour la table `project_service`
--
ALTER TABLE `project_service`
  ADD PRIMARY KEY (`project_id`,`service_id`),
  ADD KEY `fk_project_service_service_id` (`service_id`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_task_project_id` (`project_id`),
  ADD KEY `fk_task_status_id` (`status_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `visit_log`
--
ALTER TABLE `visit_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `company_locations`
--
ALTER TABLE `company_locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `project`
--
ALTER TABLE `project`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `task`
--
ALTER TABLE `task`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `visit_log`
--
ALTER TABLE `visit_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `company_locations`
--
ALTER TABLE `company_locations`
  ADD CONSTRAINT `fk_company_locations_company_id` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_contact_company_id` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_contact_company_location_id` FOREIGN KEY (`company_location_id`) REFERENCES `company_locations` (`id`);

--
-- Contraintes pour la table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_company_id` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_project_company_location_id` FOREIGN KEY (`company_location_id`) REFERENCES `company_locations` (`id`),
  ADD CONSTRAINT `fk_project_primary_contact_id` FOREIGN KEY (`primary_contact_id`) REFERENCES `contact` (`id`),
  ADD CONSTRAINT `fk_projects_status_id` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Contraintes pour la table `project_service`
--
ALTER TABLE `project_service`
  ADD CONSTRAINT `fk_project_service_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `fk_project_service_service_id` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `fk_task_status_id` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
