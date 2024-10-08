-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2024 at 03:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autentificacao`
--

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `sobre` varchar(45) NOT NULL,
  `email` varchar(110) NOT NULL,
  `senha` varchar(45) NOT NULL,
  `data_nasc` varchar(45) NOT NULL,
  `nome_arquivo` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `niveldeacesso` tinyint(4) NOT NULL DEFAULT 1,
  `data_upload` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(32) NOT NULL,
  `ativo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `sobre`, `email`, `senha`, `data_nasc`, `nome_arquivo`, `path`, `niveldeacesso`, `data_upload`, `token`, `ativo`) VALUES
(1, 'admin', 'admin', 'Admin@gmail.com', 'admin', '2000-12-12', '', '', 3, '2024-09-26 10:16:30', '', 0),
(4, 'Leonardo', 'Carmo', 'leonardocarmoc3@gmail.com', 'testeconta@gmail.com', '2010-12-12', '', '', 1, '2024-09-26 10:44:47', '', 0),
(5, 'leonardocarmoc5@gmail.com', 'leonardocarmoc5@gmail.com', 'leonardocarmoc5@gmail.com', 'leonardocarmoc5@gmail.com', '1920-10-12', '', '', 2, '2024-09-26 13:26:10', '', 0),
(16, 'Leonardo', 'Carmo', 'leonardocarmoc@gmail.com', 'leonardocarmoc@gmail.com', '2000-02-19', '', '', 1, '2024-10-05 09:46:30', '2a5d2ac973390d0d5d957e44d2b2417e', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
