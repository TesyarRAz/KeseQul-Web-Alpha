-- MySQL dump 10.16  Distrib 10.1.30-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: db_kesequl
-- ------------------------------------------------------
-- Server version	10.1.30-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_absensi`
--

DROP TABLE IF EXISTS `tbl_absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_absensi` (
  `id_absensi` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `id_pengabsen` int(11) NOT NULL,
  `kehadiran` enum('A','S','I') NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_absensi`),
  KEY `id_siswa` (`id_siswa`),
  KEY `id_pengabsen` (`id_pengabsen`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_absensi`
--

LOCK TABLES `tbl_absensi` WRITE;
/*!40000 ALTER TABLE `tbl_absensi` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_absensi_guru`
--

DROP TABLE IF EXISTS `tbl_absensi_guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_absensi_guru` (
  `id_absensi_guru` int(11) NOT NULL AUTO_INCREMENT,
  `id_guru` int(11) NOT NULL,
  `id_pengabsen` int(11) NOT NULL,
  `kehadiran` enum('A','S','I') NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_absensi_guru`),
  KEY `id_pengabsen` (`id_pengabsen`),
  KEY `tbl_absensi_guru_ibfk_2` (`id_guru`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_absensi_guru`
--

LOCK TABLES `tbl_absensi_guru` WRITE;
/*!40000 ALTER TABLE `tbl_absensi_guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_absensi_guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_absensi_pelajaran`
--

DROP TABLE IF EXISTS `tbl_absensi_pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_absensi_pelajaran` (
  `id_absensi` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `id_pengabsen` int(11) NOT NULL,
  `kehadiran` enum('A','S','I') NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_absensi`),
  KEY `id_siswa` (`id_siswa`),
  KEY `id_pengabsen` (`id_pengabsen`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_absensi_pelajaran`
--

LOCK TABLES `tbl_absensi_pelajaran` WRITE;
/*!40000 ALTER TABLE `tbl_absensi_pelajaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_absensi_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_admin`
--

DROP TABLE IF EXISTS `tbl_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_admin`
--

LOCK TABLES `tbl_admin` WRITE;
/*!40000 ALTER TABLE `tbl_admin` DISABLE KEYS */;
INSERT INTO `tbl_admin` VALUES (1,1,'kosim',NULL),(2,2,'HyungReborn',NULL),(3,14,'Syarif',NULL);
/*!40000 ALTER TABLE `tbl_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_dagangan`
--

DROP TABLE IF EXISTS `tbl_dagangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_dagangan` (
  `id_dagangan` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjual` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_dagangan`),
  KEY `id_penjual` (`id_penjual`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_dagangan`
--

LOCK TABLES `tbl_dagangan` WRITE;
/*!40000 ALTER TABLE `tbl_dagangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_dagangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_event_voting`
--

DROP TABLE IF EXISTS `tbl_event_voting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_event_voting` (
  `id_event_voting` int(11) NOT NULL AUTO_INCREMENT,
  `id_pembuat` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(8) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `keterangan` text,
  PRIMARY KEY (`id_event_voting`),
  KEY `id_pembuat` (`id_pembuat`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_event_voting`
--

LOCK TABLES `tbl_event_voting` WRITE;
/*!40000 ALTER TABLE `tbl_event_voting` DISABLE KEYS */;
INSERT INTO `tbl_event_voting` VALUES (1,1,'Testing Aplikasi Q-Vote','7KybBqgD','2019-12-08','2019-12-09',0,NULL),(2,1,'Pemilihan Ket','7gnqr5BX','2019-05-08','2019-05-09',1,NULL),(3,2,'pemilihan smea','P8pE7fOM','2019-10-10','2019-10-11',1,NULL),(4,2,'tam','LbjZakJ3','2019-09-10','2019-09-11',1,NULL),(5,3,'pemilihan ketua OSIS','COk7YqIr','2019-09-11','2019-09-12',1,NULL);
/*!40000 ALTER TABLE `tbl_event_voting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_guru`
--

DROP TABLE IF EXISTS `tbl_guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_guru` (
  `id_guru` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `image_link` varchar(50) DEFAULT NULL,
  `nip` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `gender` enum('L','P') NOT NULL DEFAULT 'L',
  `tanggal_lahir` date NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_guru`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_guru`
--

LOCK TABLES `tbl_guru` WRITE;
/*!40000 ALTER TABLE `tbl_guru` DISABLE KEYS */;
INSERT INTO `tbl_guru` VALUES (1,7,NULL,6138168,'Lord Umam','L','2092-07-01',NULL),(3,18,NULL,131621,'Um','L','1998-02-01',NULL),(14,30,NULL,32112,'gaben','L','2000-01-01',NULL),(10,26,NULL,321121,'Doyok','L','1988-01-01',NULL),(15,42,'5d7fc6ea847fb.jpg',123123,'Kiko','L','1988-09-09',NULL);
/*!40000 ALTER TABLE `tbl_guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_izin`
--

DROP TABLE IF EXISTS `tbl_izin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_izin` (
  `id_izin` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_izin`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_izin`
--

LOCK TABLES `tbl_izin` WRITE;
/*!40000 ALTER TABLE `tbl_izin` DISABLE KEYS */;
INSERT INTO `tbl_izin` VALUES (1,'W_SISWA',NULL),(2,'R_SISWA',NULL),(3,'W_BAN',NULL),(4,'R_BAN',NULL),(5,'W_GURU',NULL),(6,'R_GURU',NULL),(7,'W_TU',NULL),(8,'R_TU',NULL),(9,'W_ADMIN',NULL),(10,'R_ADMIN',NULL),(11,'W_PENJUAL',NULL),(12,'R_PENJUAL',NULL),(13,'W_ABSEN_KELAS',NULL),(14,'R_ABSEN_KELAS',NULL);
/*!40000 ALTER TABLE `tbl_izin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_izin_level`
--

DROP TABLE IF EXISTS `tbl_izin_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_izin_level` (
  `id_izin_level` int(11) NOT NULL AUTO_INCREMENT,
  `id_level` int(11) NOT NULL,
  `id_izin` int(11) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_izin_level`),
  KEY `id_level` (`id_level`),
  KEY `id_izin` (`id_izin`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_izin_level`
--

LOCK TABLES `tbl_izin_level` WRITE;
/*!40000 ALTER TABLE `tbl_izin_level` DISABLE KEYS */;
INSERT INTO `tbl_izin_level` VALUES (1,1,1,NULL),(2,1,2,NULL),(3,1,3,NULL),(4,1,4,NULL),(5,1,5,NULL),(6,1,6,NULL),(7,1,7,NULL),(8,1,8,NULL),(9,1,9,NULL),(10,1,10,NULL),(11,1,11,NULL),(12,1,12,NULL);
/*!40000 ALTER TABLE `tbl_izin_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_jurusan`
--

DROP TABLE IF EXISTS `tbl_jurusan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_jurusan` (
  `id_jurusan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jurusan` varchar(50) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_jurusan`),
  UNIQUE KEY `nama` (`nama_jurusan`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_jurusan`
--

LOCK TABLES `tbl_jurusan` WRITE;
/*!40000 ALTER TABLE `tbl_jurusan` DISABLE KEYS */;
INSERT INTO `tbl_jurusan` VALUES (1,'RPL',NULL),(2,'TKJ',NULL),(3,'BDP',NULL),(4,'OTKP',NULL),(5,'AKL',NULL);
/*!40000 ALTER TABLE `tbl_jurusan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_level`
--

DROP TABLE IF EXISTS `tbl_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_level` (
  `id_level` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_level`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_level`
--

LOCK TABLES `tbl_level` WRITE;
/*!40000 ALTER TABLE `tbl_level` DISABLE KEYS */;
INSERT INTO `tbl_level` VALUES (1,'SUPERADMIN',NULL),(2,'ADMIN',NULL),(3,'GURU',NULL),(4,'TU',NULL),(5,'SISWA',NULL),(6,'PENJUAL',NULL);
/*!40000 ALTER TABLE `tbl_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_nominasi_team`
--

DROP TABLE IF EXISTS `tbl_nominasi_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_nominasi_team` (
  `id_nominasi_team` int(11) NOT NULL AUTO_INCREMENT,
  `id_event_voting` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_ketua` int(11) NOT NULL,
  `id_wakil` int(11) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_nominasi_team`),
  KEY `id_event_voting` (`id_event_voting`),
  KEY `id_ketua` (`id_ketua`),
  KEY `id_wakil` (`id_wakil`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_nominasi_team`
--

LOCK TABLES `tbl_nominasi_team` WRITE;
/*!40000 ALTER TABLE `tbl_nominasi_team` DISABLE KEYS */;
INSERT INTO `tbl_nominasi_team` VALUES (1,1,'Kandidat 01',3,2,NULL),(2,1,'Kandidat 02',5,1,NULL),(3,2,'Kandidat 01',6,7,NULL),(4,2,'Kandidat 02',2,8,NULL),(5,3,'1',5,1,NULL),(6,3,'2',1,2,NULL),(8,5,'KANDIDAT 1',5,3,NULL),(9,5,'KANDIDAT 2',9,1,NULL),(10,5,'KANDIDAT 3',2,6,NULL);
/*!40000 ALTER TABLE `tbl_nominasi_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_penjual`
--

DROP TABLE IF EXISTS `tbl_penjual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_penjual` (
  `id_penjual` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  PRIMARY KEY (`id_penjual`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_penjual`
--

LOCK TABLES `tbl_penjual` WRITE;
/*!40000 ALTER TABLE `tbl_penjual` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_penjual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_peran`
--

DROP TABLE IF EXISTS `tbl_peran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_peran` (
  `id_peran` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_level` int(11) NOT NULL,
  `id_pengizin` int(11) NOT NULL,
  PRIMARY KEY (`id_peran`),
  KEY `id_user` (`id_user`),
  KEY `id_level` (`id_level`),
  KEY `id_pengizin` (`id_pengizin`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_peran`
--

LOCK TABLES `tbl_peran` WRITE;
/*!40000 ALTER TABLE `tbl_peran` DISABLE KEYS */;
INSERT INTO `tbl_peran` VALUES (1,1,1,1),(2,2,1,2),(3,1,2,1),(4,2,2,2),(5,1,5,1),(6,2,5,2),(7,3,5,1),(9,5,4,1),(11,7,3,1),(13,10,5,1),(14,11,5,1),(25,18,3,1),(16,13,5,1),(17,14,5,1),(20,14,1,14),(21,14,2,14),(22,15,5,14),(23,16,5,1),(37,30,3,1),(32,25,5,1),(33,26,3,1),(38,42,3,1);
/*!40000 ALTER TABLE `tbl_peran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_sequlcash`
--

DROP TABLE IF EXISTS `tbl_sequlcash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sequlcash` (
  `id_uang` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `uang` int(11) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_uang`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_sequlcash`
--

LOCK TABLES `tbl_sequlcash` WRITE;
/*!40000 ALTER TABLE `tbl_sequlcash` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_sequlcash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_setting_angkatan`
--

DROP TABLE IF EXISTS `tbl_setting_angkatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_setting_angkatan` (
  `id_setting_angkatan` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `nilai` varchar(50) NOT NULL,
  `kelas` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_setting_angkatan`),
  KEY `id_jurusan` (`id_jurusan`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_setting_angkatan`
--

LOCK TABLES `tbl_setting_angkatan` WRITE;
/*!40000 ALTER TABLE `tbl_setting_angkatan` DISABLE KEYS */;
INSERT INTO `tbl_setting_angkatan` VALUES (1,'HARGA_SPP','300000',11,1,NULL),(2,'HARGA_SPP','300000',11,2,NULL),(3,'HARGA_SPP','300000',11,3,NULL),(4,'HARGA_SPP','300000',11,4,NULL),(5,'HARGA_SPP','300000',11,5,NULL),(6,'HARGA_SPP','300000',10,1,NULL),(7,'HARGA_SPP','300000',10,2,NULL),(8,'HARGA_SPP','300000',10,3,NULL),(9,'HARGA_SPP','300000',10,4,NULL),(10,'HARGA_SPP','300000',10,5,NULL),(11,'HARGA_SPP','300000',12,1,NULL),(12,'HARGA_SPP','300000',12,2,NULL),(13,'HARGA_SPP','300000',12,3,NULL),(14,'HARGA_SPP','300000',12,4,NULL),(15,'HARGA_SPP','300000',12,5,NULL);
/*!40000 ALTER TABLE `tbl_setting_angkatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_siswa`
--

DROP TABLE IF EXISTS `tbl_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_siswa` (
  `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `image_link` varchar(50) DEFAULT NULL,
  `nisn` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `gender` enum('L','P') NOT NULL DEFAULT 'L',
  `tanggal_lahir` date NOT NULL,
  `kelas` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `index_jurusan` int(11) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nisn` (`nisn`),
  KEY `id_jurusan` (`id_jurusan`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_siswa`
--

LOCK TABLES `tbl_siswa` WRITE;
/*!40000 ALTER TABLE `tbl_siswa` DISABLE KEYS */;
INSERT INTO `tbl_siswa` VALUES (1,1,NULL,1807446,'Mohamad Tesyar Razbani','L','2002-12-11',11,1,1,NULL),(2,2,NULL,11807445,'Mochammad Faisal Mahromi','L','2003-03-04',11,1,1,NULL),(3,3,NULL,123123123,'Dean Ramhan','L','2002-03-02',11,1,1,NULL),(5,10,NULL,1123312,'Adli','L','2002-10-08',11,1,2,NULL),(6,11,NULL,3213123,'Agung Syahidan','L','2002-10-08',11,1,1,NULL),(8,13,NULL,3123121,'Rusdiansyah','L','2002-07-05',11,1,1,NULL),(9,14,NULL,321123,'Syarif Hidayatullah','L','2003-05-20',11,1,1,NULL),(10,15,NULL,124345,'haikal','L','2003-03-01',11,1,1,NULL),(11,16,NULL,12312312,'Ilham','L','2002-09-09',11,1,2,NULL),(12,25,NULL,685123,'Luthfi','L','2003-04-08',11,1,1,NULL);
/*!40000 ALTER TABLE `tbl_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_spp`
--

DROP TABLE IF EXISTS `tbl_spp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_spp` (
  `id_spp` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `bayar` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_spp`),
  KEY `tbl_spp_ibfk_1` (`id_siswa`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_spp`
--

LOCK TABLES `tbl_spp` WRITE;
/*!40000 ALTER TABLE `tbl_spp` DISABLE KEYS */;
INSERT INTO `tbl_spp` VALUES (1,3,300000,1,'2019-09-08 11:05:20',NULL),(2,2,600000,2,'2019-09-08 12:05:29',NULL),(3,2,3000000,10,'2019-09-08 12:10:38',NULL),(4,2,300000,1,'2019-09-08 19:41:25',NULL),(5,1,600000,2,'2019-09-09 00:11:11',NULL),(6,3,600000,2,'2019-09-09 06:51:47',NULL),(7,2,300000,1,'2019-09-10 13:01:55',NULL),(8,1,300000,1,'2019-09-10 13:20:44',NULL),(9,1,300000,1,'2019-09-10 13:20:53',NULL),(10,2,600000,2,'2019-09-10 13:30:44',NULL),(11,2,600000,2,'2019-09-10 13:30:49',NULL),(12,2,300000,1,'2019-09-10 13:31:09',NULL),(13,2,300000,1,'2019-09-10 13:31:16',NULL),(14,2,300000,1,'2019-09-10 13:38:11',NULL),(15,2,300000,1,'2019-09-10 13:55:47',NULL),(16,2,300000,1,'2019-09-10 13:55:53',NULL),(17,2,600000,2,'2019-09-10 14:08:03',NULL),(18,2,1500000,5,'2019-09-10 14:29:26',NULL),(19,2,300000,1,'2019-09-10 15:38:47',NULL),(20,1,300000,1,'2019-09-10 15:44:13',NULL),(21,3,900000,3,'2019-09-10 21:07:46',NULL),(22,3,300000,1,'2019-09-10 21:07:51',NULL),(23,3,300000,1,'2019-09-10 22:07:58',NULL),(24,5,300000,1,'2019-09-11 07:52:41',NULL),(25,3,300000,1,'2019-09-11 09:07:48',NULL),(26,3,600000,2,'2019-09-11 09:11:37',NULL),(27,5,300000,1,'2019-09-11 10:14:04',NULL),(28,5,300000,1,'2019-09-11 10:36:42',NULL),(29,5,3000000,10,'2019-09-11 11:05:39',NULL),(30,3,300000,1,'2019-09-11 13:29:24',NULL),(31,3,1500000,5,'2019-09-11 13:29:35',NULL),(32,3,300000,1,'2019-09-11 13:32:54',NULL),(33,9,300000,1,'2019-09-11 14:00:22',NULL),(34,9,300000,1,'2019-09-11 14:06:13',NULL),(35,9,300000,1,'2019-09-11 14:14:31',NULL),(36,9,300000,1,'2019-09-11 14:30:51',NULL),(37,9,300000,1,'2019-09-11 14:41:50',NULL),(38,9,300000,1,'2019-09-11 15:12:49',NULL);
/*!40000 ALTER TABLE `tbl_spp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_token`
--

DROP TABLE IF EXISTS `tbl_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_token` (
  `id_token` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `waktu_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keterangan` text,
  PRIMARY KEY (`id_token`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_token`
--

LOCK TABLES `tbl_token` WRITE;
/*!40000 ALTER TABLE `tbl_token` DISABLE KEYS */;
INSERT INTO `tbl_token` VALUES (1,2,'Tbqlnvweax4lQ9HinLmBWHaOFqzOtQjmZtM7igx8oLGhBVekUp2yZc5R2fKc03Jz','::1','2019-09-14 10:11:34',NULL),(2,1,'Uzdq4XYWJkjfBCiElc2qvyubmaY7lwGj9DQ3CHiIGpheFQ4rKf6PxTDAIon1y5V0','127.0.0.1','2019-09-17 10:40:11',NULL),(3,3,'zT2OjuCzSYhG8TPav31QqbKikdpRRCttK73Vm926I7ry8xn5VcbEDIBswnArs0oi','::1','2019-09-14 10:11:34',NULL),(4,5,'4wY5KAoQNB1Z8FgaPqRh47WuXrlLFIpUmVeWU8oVY0JMxHtpcs6QhyDfRiHeNcE9','127.0.0.1','2019-09-17 10:40:51',NULL),(6,14,'HY49GdWCdoc1CSkGZKE0LqRYSeFwvXAluBTE48L3Ht6mnhgcrUzRiW190jkoFJ5e','::1','2019-09-14 10:11:34',NULL),(5,10,'nT76DH4h4VTZq3NjLpeRafP5vAAzmXOUgQ0edGxBkIyY1vESKUoNBLidqurFEftw','::1','2019-09-14 10:11:34',NULL),(7,7,'ujhbENR1CaIFMLgnzNhWT9U3EJLRb2PATFw4lM0rikGoHyQ8tvxWm5vSGjXcsPzZ','::1','2019-09-14 10:11:34',NULL),(8,15,'OHjU1a0IgfV5ZdSJkN3l2LEkQxTPsW4ohnHBQe2hXWoeCRwV94Yzy5UAx7vKcuXB','::1','2019-09-14 10:11:34',NULL);
/*!40000 ALTER TABLE `tbl_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_topup`
--

DROP TABLE IF EXISTS `tbl_topup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_topup` (
  `id_topup` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengirim` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `bayar` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_topup`),
  KEY `tbl_topup_ibfk_1` (`id_pengirim`),
  KEY `tbl_topup_ibfk_2` (`id_penerima`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_topup`
--

LOCK TABLES `tbl_topup` WRITE;
/*!40000 ALTER TABLE `tbl_topup` DISABLE KEYS */;
INSERT INTO `tbl_topup` VALUES (1,5,3,100000,'2019-09-08 11:00:45',NULL),(2,5,3,500000,'2019-09-08 11:01:38',NULL),(3,5,2,1600000,'2019-09-08 12:03:52',NULL),(4,5,2,1600000,'2019-09-08 12:04:06',NULL),(5,5,2,1600000,'2019-09-08 12:04:21',NULL),(6,5,2,1600000,'2019-09-08 12:04:34',NULL),(7,5,2,1600000,'2019-09-08 12:04:45',NULL),(8,5,2,1600000,'2019-09-08 13:06:11',NULL),(9,5,2,1600000,'2019-09-08 13:08:35',NULL),(10,5,2,1600000,'2019-09-08 13:08:51',NULL),(11,5,2,1600000,'2019-09-08 13:09:05',NULL),(12,5,2,1600000,'2019-09-08 13:09:38',NULL),(13,5,2,1600000,'2019-09-08 13:09:51',NULL),(14,5,10,100000,'2019-09-08 20:03:41',NULL),(15,5,3,500000,'2019-09-09 06:51:32',NULL),(16,5,10,950000,'2019-09-10 14:31:01',NULL),(17,5,2,1600000,'2019-09-10 21:05:02',NULL),(18,5,3,1600000,'2019-09-10 21:05:37',NULL),(19,5,3,1600000,'2019-09-10 21:05:49',NULL),(20,5,10,1600000,'2019-09-10 21:06:03',NULL),(21,5,10,1600000,'2019-09-10 21:06:14',NULL),(22,5,10,100000,'2019-09-11 07:53:30',NULL),(23,5,3,1600000,'2019-09-11 09:13:17',NULL),(24,5,10,100000,'2019-09-11 10:14:56',NULL),(25,5,10,900000,'2019-09-11 10:35:59',NULL),(26,5,10,50000,'2019-09-11 11:23:10',NULL),(27,5,14,1600000,'2019-09-11 13:37:02',NULL);
/*!40000 ALTER TABLE `tbl_topup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_transaksi`
--

DROP TABLE IF EXISTS `tbl_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `id_pemberi` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL DEFAULT '0',
  `harga` int(11) NOT NULL,
  `waktu_transaksi` datetime NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_transaksi`),
  KEY `tbl_transaksi_ibfk_1` (`id_pemberi`),
  KEY `tbl_transaksi_ibfk_2` (`id_penerima`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_transaksi`
--

LOCK TABLES `tbl_transaksi` WRITE;
/*!40000 ALTER TABLE `tbl_transaksi` DISABLE KEYS */;
INSERT INTO `tbl_transaksi` VALUES (1,3,10,10000,'2019-09-08 20:07:14',NULL),(2,3,2,200000,'2019-09-08 21:13:11',NULL),(3,2,1,50000,'2019-09-08 22:27:43',NULL),(4,2,1,13000000,'2019-09-08 23:23:38',NULL),(5,2,1,850000,'2019-09-08 23:25:32',NULL),(6,1,2,1600000,'2019-09-09 00:10:41',NULL),(7,3,10,50000,'2019-09-09 06:46:42',NULL),(8,3,2,25000,'2019-09-09 06:48:34',NULL),(9,10,3,100000,'2019-09-09 06:49:53',NULL),(10,2,1,25000,'2019-09-10 13:01:48',NULL),(11,1,2,10000,'2019-09-10 13:21:07',NULL),(12,1,2,1600000,'2019-09-10 13:22:05',NULL),(13,1,2,1600000,'2019-09-10 13:22:13',NULL),(14,1,2,1600000,'2019-09-10 13:22:22',NULL),(15,1,2,1600000,'2019-09-10 13:22:30',NULL),(16,2,1,10000,'2019-09-10 13:30:35',NULL),(17,2,1,10000,'2019-09-10 13:38:27',NULL),(18,2,1,200000,'2019-09-10 14:08:30',NULL),(19,1,2,10000,'2019-09-10 15:44:22',NULL),(20,10,2,500000,'2019-09-10 21:06:42',NULL),(21,10,2,10000,'2019-09-11 06:44:09',NULL),(22,10,5,25000,'2019-09-11 10:33:27',NULL),(23,10,5,100000,'2019-09-11 10:35:21',NULL),(24,2,1,1600000,'2019-09-11 12:37:24',NULL),(25,3,10,100000,'2019-09-11 13:24:07',NULL),(26,10,3,10000,'2019-09-11 13:32:35',NULL),(27,14,10,25000,'2019-09-11 14:05:48',NULL),(28,14,10,75000,'2019-09-11 14:15:41',NULL),(29,10,14,1000000,'2019-09-11 14:48:30',NULL),(30,14,10,10000,'2019-09-11 14:53:03',NULL),(31,14,10,10000,'2019-09-11 15:00:36',NULL),(32,14,10,10000,'2019-09-11 15:13:42',NULL);
/*!40000 ALTER TABLE `tbl_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_tu`
--

DROP TABLE IF EXISTS `tbl_tu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tu` (
  `id_tu` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `image_link` varchar(50) DEFAULT NULL,
  `nip` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `gender` enum('L','P') NOT NULL DEFAULT 'L',
  `tanggal_lahir` date NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_tu`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_tu`
--

LOCK TABLES `tbl_tu` WRITE;
/*!40000 ALTER TABLE `tbl_tu` DISABLE KEYS */;
INSERT INTO `tbl_tu` VALUES (1,5,NULL,321321,'Rafli Ferdia','L','1989-06-01',NULL);
/*!40000 ALTER TABLE `tbl_tu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_tugas`
--

DROP TABLE IF EXISTS `tbl_tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_tugas` (
  `id_tugas` int(11) NOT NULL AUTO_INCREMENT,
  `id_guru` int(11) NOT NULL,
  `perihal` text NOT NULL,
  `tugas` text NOT NULL,
  `kelas` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `index_jurusan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_tugas`),
  KEY `id_guru` (`id_guru`),
  KEY `id_jurusan` (`id_jurusan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_tugas`
--

LOCK TABLES `tbl_tugas` WRITE;
/*!40000 ALTER TABLE `tbl_tugas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_tugas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('AKTIF','BAN','AKTIFASI') NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_user`
--

LOCK TABLES `tbl_user` WRITE;
/*!40000 ALTER TABLE `tbl_user` DISABLE KEYS */;
INSERT INTO `tbl_user` VALUES (1,'ecanet11','mohamadtesyar@gmail.com','79deeaf19757ac4d025334dc81f202ae','AKTIF',NULL),(2,'faisal01','faizalmahromi35@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(3,'deanramhan','deanramhan@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(5,'rafli1','rafli@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(7,'umam','umam@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(9,'adli','adlimaung@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(10,'adli1','adliaja@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(11,'agung','agung@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(18,'udin','udin@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(13,'rusdi','rusdi@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(14,'StingBugz','hidayatullahsyarif4185@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(15,'haikal','haikal@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','AKTIF',NULL),(16,'ilham','ilhan@gmail.com','f8afa6d0c87a74a1cd94e6e23bcf849d','AKTIF',NULL),(30,'2be004988d16f50756ec2f41487b606a','manusia@localhost','45e1353c677f28c8e7ea8b7091da98ab','AKTIFASI','cb78c68ca3c121b01c40249edea51907'),(25,'dika','luthfi@localhost','3fe97ae38e85d460daa09903dcffecb1','AKTIF',NULL),(36,'4e17245ddd6a4aa1b522f7d6cd92d794','32112@lc.com','0dc2da1eaaec08ec8ec16e9c12f42536','AKTIFASI','0be82b863aaa057d6ddc210bad288b14'),(42,'1e516133608d0d19fa7367b04a63b19e','kiko@google.com','d71c24296725cc36302a8058597ffddc','AKTIFASI','a0eecfb082c9124d1996366f22ad302d');
/*!40000 ALTER TABLE `tbl_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_voting`
--

DROP TABLE IF EXISTS `tbl_voting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_voting` (
  `id_voting` int(11) NOT NULL AUTO_INCREMENT,
  `id_event_voting` int(11) NOT NULL,
  `id_pemilih` int(11) NOT NULL,
  `id_nominasi_team` int(11) NOT NULL,
  `waktu_pilih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan` text,
  PRIMARY KEY (`id_voting`),
  KEY `id_event_voting` (`id_event_voting`),
  KEY `id_pemilih` (`id_pemilih`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_voting`
--

LOCK TABLES `tbl_voting` WRITE;
/*!40000 ALTER TABLE `tbl_voting` DISABLE KEYS */;
INSERT INTO `tbl_voting` VALUES (1,1,3,1,'2019-09-08 04:53:26',NULL),(2,1,5,1,'2019-09-08 05:46:21',NULL),(3,1,2,2,'2019-09-08 06:05:24',NULL),(4,2,5,4,'2019-09-08 14:19:03',NULL),(5,2,2,3,'2019-09-08 15:25:46',NULL),(6,2,3,3,'2019-09-08 23:45:33',NULL),(7,2,1,3,'2019-09-10 06:21:33',NULL),(8,3,5,5,'2019-09-10 07:50:02',NULL),(9,3,1,6,'2019-09-10 08:45:12',NULL),(10,5,5,8,'2019-09-11 03:30:45',NULL),(11,5,3,8,'2019-09-11 06:25:23',NULL),(12,5,9,8,'2019-09-11 07:06:54',NULL),(13,3,9,5,'2019-09-11 08:01:59',NULL);
/*!40000 ALTER TABLE `tbl_voting` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-16 21:04:37
