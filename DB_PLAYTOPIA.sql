-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 12:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `playtopia`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `id_album` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `release_year` year(4) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id_album`, `name`, `artist`, `genre`, `release_year`, `cover_path`) VALUES
(3, 'World of Walker', 'Alan Walker', 'Pop', '2021', '1748620099_Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id_user1` int(11) NOT NULL,
  `id_user2` int(11) NOT NULL,
  `status` enum('pending','accepted') DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id_user1`, `id_user2`, `status`, `created_at`) VALUES
(8, 7, 'accepted', '2025-05-31 17:37:56'),
(9, 7, 'accepted', '2025-05-31 17:46:17'),
(9, 8, 'accepted', '2025-05-31 17:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `id_playlist` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `playlist_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`id_playlist`, `id_user`, `playlist_name`, `description`, `cover_url`, `created_at`) VALUES
(2, 7, 'Roadtrip', 'This is my roadtrips playlist', 'uploads/covers/cover_683adba343eae.jpg', '2025-05-31 00:00:00'),
(3, 7, 'Alan Walker Lovers', 'Alan Walker ❤', 'uploads/covers/cover_683adbce9a1eb.jpg', '2025-05-31 00:00:00'),
(4, 8, 'Roadtrip (Shared)', 'Shared from user ID 7: This is my roadtrips playlist', 'uploads/covers/cover_683adba343eae.jpg', '2025-05-31 12:38:28'),
(5, 9, 'Alan Walker Lovers (Shared)', 'Shared from user ID 7: Alan Walker ❤', 'uploads/covers/cover_683adbce9a1eb.jpg', '2025-05-31 12:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `playlists_songs`
--

CREATE TABLE `playlists_songs` (
  `id` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL,
  `id_song` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlists_songs`
--

INSERT INTO `playlists_songs` (`id`, `id_playlist`, `id_song`) VALUES
(2, 2, 11),
(3, 2, 12),
(4, 2, 13),
(5, 2, 14),
(6, 3, 1),
(7, 3, 2),
(8, 3, 3),
(9, 3, 4),
(10, 3, 5),
(11, 3, 6),
(12, 4, 11),
(13, 4, 12),
(14, 4, 13),
(15, 4, 14),
(16, 5, 1),
(17, 5, 2),
(18, 5, 3),
(19, 5, 4),
(20, 5, 5),
(21, 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_likes`
--

CREATE TABLE `playlist_likes` (
  `id_user` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL,
  `liked_at` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlist_likes`
--

INSERT INTO `playlist_likes` (`id_user`, `id_playlist`, `liked_at`) VALUES
(7, 2, '2025-05-31');

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id_song` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `album` varchar(100) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `release_year` year(4) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  `plays` int(11) DEFAULT 0,
  `id_album` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id_song`, `title`, `artist`, `album`, `genre`, `release_year`, `duration`, `file_path`, `cover_path`, `plays`, `id_album`) VALUES
(1, 'Alone', 'Alan Walker', '-', 'Pop', '2016', 164, 'Alan_Walker_-_Alone.mp3', 'Alan_Walker_-_Alone.jpg', 0, NULL),
(2, 'Alone, Pt. II', 'Alan Walker & Ava Max', '-', 'Pop', '2019', 179, 'Alan_Walker_Ava_Max_-_Alone_Pt_II.mp3', 'Alan_Walker_Ava_Max_-_Alone_Pt_II.jpg', 0, NULL),
(3, 'Faded', 'Alan Walker', '-', 'Pop', '2015', 212, 'Alan_Walker_-_Faded.mp3', 'Alan_Walker_-_Faded.jpg', 0, NULL),
(4, 'The Spectre', 'Alan Walker', NULL, '0', '2017', 193, 'Alan_Walker_-_The_Spectre.mp3', 'Alan_Walker_-_The_Spectre.jpg', 0, NULL),
(5, 'PLAY (Alan Walker x Niya Remix)', 'Alan Walker, K-391, Tungevaag, Mangoo', NULL, '0', '2019', 167, 'Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.mp3', 'Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg', 0, NULL),
(6, 'On My Way', 'Alan Walker, Sabrina Carpenter & Farruko', NULL, '0', '2019', 193, 'Alan_Walker_Sabrina_Carpenter_Farruko_-_On_My_Way.mp3', 'Alan_Walker_Sabrina_Carpenter_Farruko_-_On_My_Way.jpg', 0, NULL),
(7, 'So Am I', 'Ava Max', NULL, '0', '2019', 183, 'Ava_Max_-_So_Am_I.mp3', 'Ava_Max_-_So_Am_I.jpg', 0, NULL),
(8, 'I Want It That Way', 'Backstreet Boys', NULL, '0', '1999', 213, 'Backstreet_Boys_-_I_Want_It_That_Way.mp3', 'Backstreet_Boys_-_I_Want_It_That_Way.jpg', 0, NULL),
(9, 'Done For Me', 'Charlie Puth feat. Kehlani', NULL, '0', '2018', 180, 'Charlie_Puth_feat_Kehlani_-_Done_For_Me.mp3', 'Charlie_Puth_feat_Kehlani_-_Done_For_Me.jpg', 0, NULL),
(10, 'How Long', 'Charlie Puth', NULL, '0', '2017', 198, 'Charlie_Puth_-_How_Long.mp3', 'Charlie_Puth_-_How_Long.jpg', 0, NULL),
(11, 'Good Life', 'Kehlani & G-Eazy', NULL, '0', '2017', 225, 'Kehlani_G_Eazy_-_Good_Life.mp3', 'Kehlani_G_Eazy_-_Good_Life.jpg', 0, NULL),
(12, 'Show You', 'Shawn Mendes', NULL, '0', '2014', 180, 'Shawn_Mendes_-_Show_You.mp3', 'Shawn_Mendes_-_Show_You.jpg', 0, NULL),
(13, 'APT', 'Rose & Bruno Mars', '-', 'Pop', '2024', 173, 'APT - ROSÉ & Bruno Mars.mp3', 'cover3.jpg', 5763, NULL),
(14, 'Youre Mine', 'Vincentius Bryan', '-', 'Pop', '2025', 272, 'Youre Mine - Vincentius Bryan.mp3', 'cover1.png', 964, NULL),
(15, 'Alone', 'Alan Walker', 'World of Walker', 'Pop', '2021', 231, 'Alan_Walker_-_Alone.mp3', '1748620099_Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg', 0, 3),
(16, 'Faded', 'Alan Walker', 'World of Walker', 'Pop', '2021', 231, 'Alan_Walker_-_Faded.mp3', '1748620099_Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg', 0, 3),
(17, 'The Spectre', 'Alan Walker', 'World of Walker', 'Pop', '2021', 231, 'Alan_Walker_-_The_Spectre.mp3', '1748620099_Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg', 0, 3),
(18, 'On My Way', 'Alan Walker', 'World of Walker', 'Pop', '2021', 231, 'Alan_Walker_Sabrina_Carpenter_Farruko_-_On_My_Way.mp3', '1748620099_Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `song_likes`
--

CREATE TABLE `song_likes` (
  `id_user` int(11) NOT NULL,
  `id_song` int(11) NOT NULL,
  `liked_at` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `song_likes`
--

INSERT INTO `song_likes` (`id_user`, `id_song`, `liked_at`) VALUES
(7, 21, '2025-05-31'),
(8, 13, '2025-05-31'),
(8, 14, '2025-05-31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(26) NOT NULL,
  `email` varchar(100) NOT NULL CHECK (`email` like '%@%'),
  `password` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT curdate(),
  `user_type` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `created_at`, `user_type`) VALUES
(6, 'Bobby', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', '2025-05-31', 'admin'),
(7, 'Patrick Star', 'patrick@gmail.com', '202cb962ac59075b964b07152d234b70', '2025-05-31', 'user'),
(8, 'Squarepants', 'spongebob@gmail.com', '202cb962ac59075b964b07152d234b70', '2025-05-31', 'user'),
(9, 'Tuan Crab', 'crab@gmail.com', '202cb962ac59075b964b07152d234b70', '2025-05-31', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id_album`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id_user1`,`id_user2`),
  ADD KEY `id_user2` (`id_user2`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id_playlist`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `playlists_songs`
--
ALTER TABLE `playlists_songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_playlist` (`id_playlist`),
  ADD KEY `id_song` (`id_song`);

--
-- Indexes for table `playlist_likes`
--
ALTER TABLE `playlist_likes`
  ADD PRIMARY KEY (`id_user`,`id_playlist`),
  ADD KEY `id_playlist` (`id_playlist`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id_song`),
  ADD KEY `id_album` (`id_album`);

--
-- Indexes for table `song_likes`
--
ALTER TABLE `song_likes`
  ADD PRIMARY KEY (`id_user`,`id_song`),
  ADD KEY `id_song` (`id_song`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `id_album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id_playlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `playlists_songs`
--
ALTER TABLE `playlists_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id_song` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`id_user1`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`id_user2`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `playlists_songs`
--
ALTER TABLE `playlists_songs`
  ADD CONSTRAINT `playlists_songs_ibfk_1` FOREIGN KEY (`id_playlist`) REFERENCES `playlists` (`id_playlist`) ON DELETE CASCADE,
  ADD CONSTRAINT `playlists_songs_ibfk_2` FOREIGN KEY (`id_song`) REFERENCES `songs` (`id_song`) ON DELETE CASCADE;

--
-- Constraints for table `playlist_likes`
--
ALTER TABLE `playlist_likes`
  ADD CONSTRAINT `playlist_likes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `playlist_likes_ibfk_2` FOREIGN KEY (`id_playlist`) REFERENCES `playlists` (`id_playlist`);

--
-- Constraints for table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`id_album`) REFERENCES `albums` (`id_album`) ON DELETE SET NULL;

--
-- Constraints for table `song_likes`
--
ALTER TABLE `song_likes`
  ADD CONSTRAINT `song_likes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `song_likes_ibfk_2` FOREIGN KEY (`id_song`) REFERENCES `songs` (`id_song`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
