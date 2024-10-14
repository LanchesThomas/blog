-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2024 at 09:24 AM
-- Server version: 10.5.21-MariaDB-log
-- PHP Version: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `statut` enum('valid','delete','waiting','') DEFAULT 'waiting',
  `createdAt` datetime NOT NULL,
  `content` text NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `statut`, `createdAt`, `content`, `post_id`, `user_id`) VALUES
(58, 'valid', '2024-09-30 08:37:30', '            test new com', 6, 1),
(60, 'valid', '2024-09-30 08:39:03', '            test new com 2', 8, 1),
(63, 'waiting', '2024-09-30 10:05:53', '            com editor \r\n', 6, 44),
(64, 'waiting', '2024-09-30 10:06:31', '            com editor \r\n', 6, 44),
(65, 'delete', '2024-09-30 10:09:43', '            com editor to cvalidate\r\n', 6, 44);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `chapo` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `statut` enum('published','draft') NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `chapo`, `content`, `statut`, `createdAt`, `updatedAt`, `user_id`) VALUES
(6, 'Hôtel :comment bien communiquer avec les voyageurs ?', 'Mesurer l’intérêt d’une communication d’hôtel efficace', '\r\n\r\nAD\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Proin faucibus nunc lacus, eget convallis arcu mollis vitae. Aenean et nisi ex. Aliquam dignissim lacus nec ipsum lacinia pretium. Duis ac ipsum interdum, dapibus risus ac, rutrum nisi. Morbi tincidunt ultrices imperdiet. Duis felis neque, ullamcorper quis velit at, rutrum hendrerit velit. Fusce sagittis in enim fringilla tristique. Fusce erat nisi, aliquet maximus lorem at, maximus feugiat arcu. Ut mattis est fringilla, suscipit neque ut, semper odio. Mauris dignissim, magna ut laoreet egestas, ligula sem interdum massa, ac dignissim est erat et elit. Nulla facilisi.\r\n\r\nIn tempor sed urna et feugiat. Ut ut pellentesque arcu. Curabitur dictum neque nec viverra convallis. Maecenas auctor leo velit, at blandit metus ultricies sit amet. Duis blandit tristique nibh, quis convallis arcu. Proin viverra purus elit, ut ornare leo tristique et. In vehicula eget purus vitae porta. Vestibulum semper fermentum risus in congue. Sed rhoncus elit imperdiet, scelerisque lacus ut, posuere ligula. Fusce mattis leo vitae eros vulputate faucibus.\r\n\r\nProin suscipit id nunc sed ultrices. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et egestas lacus. Ut facilisis odio massa, vel tristique turpis consequat id. Donec sem nisi, porttitor vel vestibulum vitae, porta vestibulum arcu. Fusce et condimentum risus. Nunc tempus sed mauris ut malesuada. Sed vel vulputate justo. Sed fermentum felis quis quam porta, aliquet dictum velit consectetur. Vestibulum porta venenatis lorem, ut aliquam nisl lobortis congue. Etiam nulla tellus, rhoncus a mollis non, tincidunt quis felis. Fusce porttitor consectetur nulla eu hendrerit.\r\n\r\nDuis convallis placerat ex, et ultrices ipsum luctus in. Morbi eleifend diam quis eleifend tempor. Sed ligula odio, commodo ut dolor at, aliquet venenatis metus. Mauris ullamcorper non ex eget egestas. In vulputate, lorem sed laoreet tempus, dolor odio fermentum elit, molestie mollis diam urna eu dolor. Mauris facilisis, lectus dapibus consequat porta, mi nisl venenatis metus, vel finibus massa libero fringilla nunc. Nulla convallis ultricies nisi, a pharetra lectus eleifend egestas. Aenean hendrerit quam vitae faucibus malesuada. Nullam fringilla, urna sed tincidunt rutrum, tortor erat condimentum ante, nec tincidunt enim elit vitae sapien. Sed mollis tellus cursus risus commodo, a sagittis quam viverra. Nam est eros, suscipit a ante sed, porta fringilla lorem. Pellentesque sapien elit, luctus sed lobortis ut, convallis ac augue. Nullam vitae tortor maximus, imperdiet est vitae, laoreet justo. Duis at erat urna.\r\n\r\nMauris et volutpat nibh, vel sollicitudin urna. Curabitur vel ex ut nisi laoreet posuere. Etiam malesuada et lacus ac eleifend. Duis sodales tincidunt aliquam. Mauris consectetur libero sem, sed ornare lectus aliquam ac. Nulla et lacus semper, iaculis neque sit amet, pulvinar lacus. Maecenas eget quam a quam posuere pretium. Vestibulum sit amet iaculis massa. Sed posuere faucibus tortor lobortis tincidunt. Phasellus eget eros sed odio semper pharetra. Nam mi urna, venenatis egestas felis nec, fermentum pellentesque tellus. Vestibulum malesuada nulla vel justo porttitor, non hendrerit sem posuere. Proin placerat tellus nec egestas vestibulum. Praesent vitae tristique tellus. Phasellus dictum volutpat dui quis pellentesque. Nulla facilisi.\r\n\r\nFusce sodales sapien a erat eleifend accumsan. In varius mauris sit amet leo auctor, quis porttitor augue placerat. Ut id dui tempor, consequat est non, egestas sapien. Fusce eget consequat dui, id euismod ex. Aenean nec tempus massa. Duis vel libero non metus placerat posuere. Nulla at laoreet tellus.\r\n\r\nEtiam cursus dictum sollicitudin. Donec molestie enim vel orci vehicula, ut mattis libero pellentesque. Vestibulum porttitor ipsum nunc, eu convallis libero vulputate eu. Morbi non tellus nec risus consequat vulputate nec et odio. Phasellus rutrum est ut ipsum iaculis, et finibus est vehicula. Nullam sed ipsum ac nisi interdum laoreet in vel nulla. Vivamus vitae tellus interdum, placerat sem sed, rutrum arcu.\r\n\r\nIn viverra justo vel pretium iaculis. Nunc pretium feugiat leo, eget molestie leo elementum quis. Mauris et turpis augue. Pellentesque cursus fermentum elit non vulputate. Ut scelerisque, quam vitae congue scelerisque, arcu mauris suscipit lorem, tempus molestie ante sem sollicitudin eros. Praesent pretium commodo malesuada. Praesent sit amet leo semper, lobortis enim in, ornare mi. Sed mollis pulvinar nunc, tempor gravida lorem laoreet sit amet. Nulla accumsan diam sed libero imperdiet dictum. Sed ut tortor ipsum. Vivamus in tortor non ligula mollis suscipit.\r\n\r\nVivamus vulputate eleifend commodo. Etiam urna massa, dignissim semper est vel, egestas sollicitudin orci. Nullam vulputate rutrum erat eget elementum. Nunc et tellus malesuada, malesuada velit in, maximus nunc. Vestibulum euismod, justo sed molestie tempor, mi arcu hendrerit enim, vitae dapibus quam tellus nec ante. Sed suscipit ex sem, quis gravida ante pretium quis. Sed ut tortor sed justo luctus consectetur. Ut nec nunc turpis. Nunc at velit elit. Donec pharetra massa vitae nisl facilisis consectetur. Sed vitae dapibus felis, vel vestibulum sapien.\r\n\r\nPellentesque lorem justo, suscipit et blandit at, eleifend et quam. Proin non elit at ex volutpat tristique. Maecenas pellentesque dignissim mauris sed aliquam. Nullam vestibulum arcu quis vulputate viverra. Praesent feugiat, erat non varius auctor, nulla dolor tempus eros, at lobortis nisi dolor vel ex. Aliquam id est sem. Nullam eget nisi et ante venenatis consequat sed et nisl. Cras pharetra nulla id erat tincidunt rutrum. Vestibulum eget tincidunt nunc. Phasellus pretium faucibus facilisis. Sed vel elit commodo mi rutrum faucibus vel non mauris. Cras volutpat cursus nulla, eget varius magna malesuada vitae. Interdum et malesuada fames ac ante ipsum primis in faucibus. Fusce luctus leo non arcu hendrerit condimentum. Ut vitae sapien a nulla viverra feugiat. Etiam eget ex eu turpis interdum rhoncus nec nec ligula.', 'published', '2024-07-29 10:00:40', '2024-07-29 10:00:40', 1),
(8, 'article 2', 'chapopo article 2', 'conteun article 2', 'published', '2024-08-12 13:49:10', '2024-08-12 13:49:10', 1),
(9, 'titre 1', 'chapo 1                ', 'contenu 1                ', 'published', '2024-09-14 14:48:54', '2024-09-14 14:48:54', NULL),
(10, 'Nouvel article', 'chapo Nouvel article                ', 'contenu Nouvel article                ', 'published', '2024-09-16 09:33:21', '2024-09-16 09:33:21', NULL),
(11, 'Nouvel article', 'chapo Nouvel article                ', 'contenu Nouvel article                ', 'draft', '2024-09-16 09:34:46', '2024-09-16 09:34:46', 1),
(12, 'hello', 'heloo chapo                ', 'hello contenu                ', 'draft', '2024-09-16 09:35:15', '2024-09-16 09:35:15', 1),
(13, 'Titre 12', 'Chapo 12                ', 'contenu 12                ', 'draft', '2024-09-16 15:26:03', '2024-09-16 15:26:03', 1),
(14, 'article test', 'chapo test                ', 'CONTENU TEST                ', 'draft', '2024-09-23 13:50:54', '2024-09-23 13:50:54', 1),
(15, 'Titre modif', 'Chapo autre                ', 'contenu autre modifié       \r\n                \r\n                \r\n                ', 'published', '2024-09-30 12:14:29', '2024-09-30 12:14:29', 44),
(16, 'test statut modifié', 'chapo test staut                ', '                contenu test stat000\r\n                \r\n                \r\n                ', 'published', '2024-09-30 13:09:18', '2024-09-30 13:09:18', 44);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','editor','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `mail`, `password`, `role`) VALUES
(1, 'Admin', 'admin@mail.com', '123456', 'admin'),
(44, 'editor', 'editor@mail.com', '123456', 'editor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_ibfk_1` (`post_id`),
  ADD KEY `comments_ibfk_2` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `users_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
