-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 06:01 AM
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
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `isbn` bigint(13) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `penerbit` varchar(50) NOT NULL,
  `pengarang` varchar(50) NOT NULL,
  `stok` int(5) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `id_kategori` int(8) NOT NULL,
  `id_rak` int(4) NOT NULL,
  `sampul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`isbn`, `judul`, `penerbit`, `pengarang`, `stok`, `tgl_masuk`, `id_kategori`, `id_rak`, `sampul`) VALUES
(1010101010101, 'Kambing Jantan', 'Gagas Media', 'Raditya Dika', 15, '2024-05-27', 10, 5, 'uploads/sampul/6653e6bff08db.jpg'),
(1010101010103, 'Bumi', 'Gramedia', 'Tereliye', 20, '2024-05-30', 6, 4, 'uploads/sampul/66533615c410e.jpg'),
(1010101010105, 'Anchika', 'ddd', 'Pidi Baiq', 100, '2024-05-21', 6, 4, 'uploads/sampul/6651697c64400.jpg'),
(1010101010109, 'Atomic Habits', 'mamang', 'siapa', 20, '2024-05-27', 6, 5, 'uploads/sampul/6654350451c4e.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `denda`
--

CREATE TABLE `denda` (
  `id_denda` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `no_pinjam` int(7) NOT NULL,
  `harga_denda` int(255) NOT NULL,
  `status` enum('Hilang','Telat','Rusak') NOT NULL,
  `tgl_kembali` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denda`
--

INSERT INTO `denda` (`id_denda`, `nama`, `judul`, `no_pinjam`, `harga_denda`, `status`, `tgl_kembali`) VALUES
(9, '', '', 27, 5000, 'Telat', '2024-05-29'),
(10, '', '', 29, 10000, 'Rusak', '2024-05-29'),
(11, '', '', 30, 5000, 'Rusak', '2024-05-30');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(8) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(6, 'teknologi'),
(7, 'Sains'),
(8, 'FIksi'),
(10, 'Novel'),
(11, 'Finansial');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `n_id` bigint(13) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tlp` int(12) NOT NULL,
  `status` enum('admin','anggota') NOT NULL,
  `pp` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`n_id`, `pass`, `nama`, `email`, `tlp`, `status`, `pp`) VALUES
(2210631170001, '$2y$10$QUF7zf4fPhYe4AbPLCb5JuV/YN1pTVywh30q8sMTvLBCscYb16pjW', 'andi', 'andi@lolo', 93485345, 'anggota', 'uploads/profil/backgroundnew.jpeg'),
(2210631170004, '$2y$10$frggQZBqSZxZ85snLbziJ.wusQwVMFWZ4nXGJvQAuv80tJs5VUAz.', 'yoga', 'yoga@asd', 93475985, 'anggota', 'uploads/profil/backgroundnew.jpeg'),
(2210631170005, '$2y$10$jMWWMX0Tp/PTFgLCJ18MgODEy5SymevjeFUUFri2s354dnCFh4oAy', 'mamat', 'mamat@asd', 456546, 'anggota', 'uploads/profil/backgroundnew.jpeg'),
(2210631170006, '$2y$10$wC9hOYt7IgtK83II20fV4OBsWsq/Br6HdUAqBvwbjBGa.LgDqXpD.', 'melisa', 'melisa@asd', 3498753, 'anggota', 'uploads/profil/backgroundnew.jpeg'),
(2210631170011, '$2y$10$gEhoUiKH0MtsHLOgNwGSWORJCEUpf4dDWONNCq3fdLyGbG9vJNEJq', 'Leonardo', 'leonardo123@gmail.com', 895756787, 'anggota', 'uploads/profil/leonardo.jpg'),
(2210631170015, '$2y$10$.U9l3WT4j45V8U0Ft/CV9usbWjU.rKoxJ7y0BOwUYTyE9rRaSP/Cu', 'dodo', 'dodo@gmail.com', 982734, 'admin', 'uploads/profil/ronaldo.jpg'),
(2210631170016, '$2y$10$5npjSfqY1SVGOnstM.TApOs2srNhVNQHgNsXPAxkGDKI2A6WIpn1.', 'messi', 'messi@gmail.com', 982734, 'anggota', 'uploads/profil/messi.jpg'),
(2210631170050, '$2y$10$2MU/75jCoAIRkJPBDVGr2.TGBe54qCFL5KltLf81ij.jQJVI360EK', 'Taufiqurrohman Yuares', 'taufiq@jsdah', 93583443, 'admin', 'uploads/profil/logonew.png'),
(2210631170055, '$2y$10$BqK3aLZf4EHlGaIiSZxdeeff3.8l7bzy0xnYj0FNtS5ltryoyaCQ6', 'Ahmad Zacky Taufiqul Hakim', 'zackyalhakim24@gmail.com', 82934723, 'admin', 'uploads/profil/univ.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `no_pinjam` int(7) NOT NULL,
  `n_id` bigint(13) NOT NULL,
  `isbn` bigint(13) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `status` enum('Dipinjam','Tersedia','Hilang','Telat','Dikembalikan') NOT NULL,
  `jumlah_pengembalian` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`no_pinjam`, `n_id`, `isbn`, `tgl_pinjam`, `tgl_kembali`, `status`, `jumlah_pengembalian`) VALUES
(25, 2210631170001, 1010101010101, '2024-05-27', '2024-05-29', 'Dipinjam', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `no_pinjam` int(7) NOT NULL,
  `n_id` bigint(13) NOT NULL,
  `isbn` bigint(13) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `status` enum('DIkembalikan','Telat','Rusak','Hilang') NOT NULL,
  `jumlah_pengembalian` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`no_pinjam`, `n_id`, `isbn`, `tgl_pinjam`, `tgl_kembali`, `status`, `jumlah_pengembalian`) VALUES
(27, 2210631170001, 1010101010101, '2024-05-27', '2024-05-29', 'Telat', 1),
(28, 2210631170004, 1010101010105, '2024-05-27', '2024-05-29', 'DIkembalikan', 1),
(29, 2210631170011, 1010101010109, '2024-05-27', '2024-05-29', 'Rusak', 1),
(30, 2210631170001, 1010101010101, '2024-05-27', '2024-05-30', 'Rusak', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rak`
--

CREATE TABLE `rak` (
  `id_rak` int(4) NOT NULL,
  `nama_rak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rak`
--

INSERT INTO `rak` (`id_rak`, `nama_rak`) VALUES
(4, 'Rak 1'),
(5, 'Rak 3'),
(6, 'Rak 4'),
(8, 'Rak 5'),
(9, 'Rak 2'),
(10, 'Rak 6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`isbn`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_rak` (`id_rak`);

--
-- Indexes for table `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id_denda`),
  ADD KEY `no_pinjam` (`no_pinjam`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`n_id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`no_pinjam`),
  ADD KEY `n_id` (`n_id`),
  ADD KEY `isbn` (`isbn`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`no_pinjam`),
  ADD KEY `pengembalian_ibfk_3` (`n_id`),
  ADD KEY `pengembalian_ibfk_4` (`isbn`);

--
-- Indexes for table `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`id_rak`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `no_pinjam` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `no_pinjam` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `rak`
--
ALTER TABLE `rak`
  MODIFY `id_rak` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`id_rak`) REFERENCES `rak` (`id_rak`);

--
-- Constraints for table `denda`
--
ALTER TABLE `denda`
  ADD CONSTRAINT `denda_ibfk_1` FOREIGN KEY (`no_pinjam`) REFERENCES `pengembalian` (`no_pinjam`) ON DELETE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_3` FOREIGN KEY (`n_id`) REFERENCES `login` (`n_id`),
  ADD CONSTRAINT `pengembalian_ibfk_4` FOREIGN KEY (`isbn`) REFERENCES `buku` (`isbn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
