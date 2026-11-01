-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bubee_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned DEFAULT NULL,
  `aksi` varchar(50) NOT NULL,
  `kategori` varchar(20) NOT NULL DEFAULT 'sistem',
  `level` varchar(10) NOT NULL DEFAULT 'info',
  `subjek` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `tautan` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_actor_id_foreign` (`actor_id`),
  KEY `activity_cari_idx` (`created_at`,`kategori`,`level`),
  CONSTRAINT `activity_logs_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,NULL,'login_gagal','auth','waspada','user@bubee.id','IP 127.0.0.1',NULL,NULL,'2026-10-06 01:32:16','2026-10-06 01:32:16'),(2,NULL,'login_gagal','auth','waspada','hikarinokokyu@gmail.com','IP 127.0.0.1',NULL,NULL,'2026-10-06 21:10:09','2026-10-06 21:10:09'),(3,NULL,'login_gagal','auth','waspada','hikarinokokyu@gmail.com','IP 127.0.0.1',NULL,NULL,'2026-10-06 21:10:23','2026-10-06 21:10:23'),(4,NULL,'login_gagal','auth','waspada','reseller@bubbe.id','IP 127.0.0.1',NULL,NULL,'2026-10-06 23:46:37','2026-10-06 23:46:37'),(5,NULL,'login_gagal','auth','waspada','reseller@bubbe.id','IP 127.0.0.1',NULL,NULL,'2026-10-06 23:46:50','2026-10-06 23:46:50'),(6,NULL,'login_gagal','auth','waspada','reihananggaci1@gmail.com','IP 127.0.0.1',NULL,NULL,'2026-10-08 06:54:09','2026-10-08 06:54:09'),(7,NULL,'login_gagal','auth','waspada','reihananggaci1@gmail.com','IP 127.0.0.1',NULL,NULL,'2026-10-08 06:54:20','2026-10-08 06:54:20'),(8,1,'opname_mulai','stok','info','Sesi #1','Cakupan: Tumbler','127.0.0.1','/admin/opname/1','2026-10-08 11:21:24','2026-10-08 11:21:24'),(9,1,'login_sukses','auth','info','admin@bubee.id','Role admin','127.0.0.1',NULL,'2026-10-09 06:38:54','2026-10-09 06:38:54'),(10,NULL,'login_gagal','auth','bahaya','reseller@bubbe.id','IP 127.0.0.1','127.0.0.1',NULL,'2026-10-09 06:59:58','2026-10-09 06:59:58'),(11,NULL,'login_gagal','auth','bahaya','reseller@bubbe.id','IP 127.0.0.1','127.0.0.1',NULL,'2026-10-09 07:00:11','2026-10-09 07:00:11'),(12,2,'login_sukses','auth','info','reseller@bubee.id','Role reseller','127.0.0.1',NULL,'2026-10-09 07:00:29','2026-10-09 07:00:29'),(13,1,'login_sukses','auth','info','admin@bubee.id','Role admin','127.0.0.1',NULL,'2026-10-13 01:23:56','2026-10-13 01:23:56'),(14,1,'varian_ubah','stok','info','HQ-096 Botol Mini Tali Kepang','All Size / Pink: 20 → 23','127.0.0.1','/admin/produk','2026-10-13 01:29:17','2026-10-13 01:29:17'),(15,1,'order_status','order','info','BB-2026100508200231','menunggu_bayar -> dikirim','127.0.0.1','/admin/order/13','2026-10-13 01:38:38','2026-10-13 01:38:38'),(16,1,'user_hapus','user','waspada','reihananggaci9@gmail.com',NULL,'127.0.0.1','/admin/users','2026-10-13 01:45:53','2026-10-13 01:45:53'),(17,1,'retur_update','order','info','Retur #1','→ selesai','127.0.0.1','/admin/retur/1','2026-10-13 03:32:03','2026-10-13 03:32:03'),(18,4,'login_sukses','auth','info','reihananggaci1@gmail.com','Role enduser','127.0.0.1',NULL,'2026-10-13 03:33:25','2026-10-13 03:33:25'),(19,4,'order_buat','order','info','BB-2026101310345245','Rp 139,000','127.0.0.1','/admin/order/18','2026-10-13 03:34:52','2026-10-13 03:34:52'),(20,4,'order_batal','order','waspada','BB-2026101310345245','Alasan: Salah ukuran/warna','127.0.0.1','/admin/order/18','2026-10-13 03:37:31','2026-10-13 03:37:31'),(21,1,'login_sukses','auth','info','admin@bubee.id','Role admin','127.0.0.1',NULL,'2026-10-13 03:44:15','2026-10-13 03:44:15'),(22,4,'login_sukses','auth','info','reihananggaci1@gmail.com','Role enduser','127.0.0.1',NULL,'2026-10-13 05:05:16','2026-10-13 05:05:16'),(23,1,'login_sukses','auth','info','admin@bubee.id','Role admin','127.0.0.1',NULL,'2026-10-13 05:07:18','2026-10-13 05:07:18'),(24,1,'login_sukses','auth','info','admin@bubee.id','Role admin','127.0.0.1',NULL,'2026-10-13 22:28:01','2026-10-13 22:28:01'),(25,1,'login_sukses','auth','info','admin@bubee.id','Role admin','103.154.110.194',NULL,'2026-10-14 00:43:08','2026-10-14 00:43:08'),(26,1,'produk_hapus','produk','waspada','Celana Pendek Dry',NULL,'103.154.110.194','/admin/produk','2026-10-14 00:58:09','2026-10-14 00:58:09'),(27,1,'produk_nonaktif','produk','info','Kemeja Flannel Kotak','Pernah terjual','103.154.110.194','/admin/produk','2026-10-14 00:58:24','2026-10-14 00:58:24'),(28,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.110.194','/admin/produk','2026-10-14 00:58:43','2026-10-14 00:58:43'),(29,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.110.194','/admin/produk','2026-10-14 00:59:00','2026-10-14 00:59:00'),(30,NULL,'login_gagal','auth','bahaya','bot@test.id','IP 103.154.110.194','103.154.110.194',NULL,'2026-10-14 01:04:00','2026-10-14 01:04:00'),(31,NULL,'login_gagal','auth','bahaya','bot@test.id','IP 103.154.110.194','103.154.110.194',NULL,'2026-10-14 01:04:01','2026-10-14 01:04:01'),(32,NULL,'login_gagal','auth','bahaya','bot@test.id','IP 103.154.110.194','103.154.110.194',NULL,'2026-10-14 01:04:02','2026-10-14 01:04:02'),(33,NULL,'login_gagal','auth','bahaya','bot@test.id','IP 103.154.110.194','103.154.110.194',NULL,'2026-10-14 01:04:03','2026-10-14 01:04:03'),(34,NULL,'login_gagal','auth','bahaya','bot@test.id','IP 103.154.110.194','103.154.110.194',NULL,'2026-10-14 01:04:04','2026-10-14 01:04:04'),(35,4,'login_sukses','auth','info','reihananggaci1@gmail.com','Role enduser','103.154.110.194',NULL,'2026-10-14 01:05:03','2026-10-14 01:05:03'),(36,NULL,'login_gagal','auth','bahaya','admin@bubee.id','IP 157.85.208.156','157.85.208.156',NULL,'2026-10-14 01:59:03','2026-10-14 01:59:03'),(37,NULL,'login_gagal','auth','bahaya','admin@bubee.id','IP 157.85.208.156','157.85.208.156',NULL,'2026-10-14 01:59:05','2026-10-14 01:59:05'),(38,1,'login_sukses','auth','info','admin@bubee.id','Role admin','182.253.96.51',NULL,'2026-10-14 20:12:12','2026-10-14 20:12:12'),(39,1,'login_sukses','auth','info','admin@bubee.id','Role admin','182.253.96.51',NULL,'2026-10-14 20:14:39','2026-10-14 20:14:39'),(40,1,'login_sukses','auth','info','admin@bubee.id','Role admin','157.85.208.156',NULL,'2026-10-14 20:48:45','2026-10-14 20:48:45'),(41,1,'login_sukses','auth','info','admin@bubee.id','Role admin','2404:c0:ca03:9e81:78a1:1b63:b8c7:26c4',NULL,'2026-10-15 19:22:07','2026-10-15 19:22:07'),(42,1,'login_sukses','auth','info','admin@bubee.id','Role admin','2404:c0:ca03:9e81:4dfc:f08d:5ee:e324',NULL,'2026-10-15 19:24:27','2026-10-15 19:24:27'),(43,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','2404:c0:ca03:9e81:4dfc:f08d:5ee:e324','/admin/produk','2026-10-15 19:25:56','2026-10-15 19:25:56'),(44,1,'login_sukses','auth','info','admin@bubee.id','Role admin','2400:9800:c60:f8e6:152:80a5:7136:4355',NULL,'2026-10-20 23:53:05','2026-10-20 23:53:05'),(45,4,'login_sukses','auth','info','reihananggaci1@gmail.com','Role enduser','2400:9800:c60:f8e6:152:80a5:7136:4355',NULL,'2026-10-20 23:57:29','2026-10-20 23:57:29'),(46,2,'login_sukses','auth','info','reseller@bubee.id','Role reseller','2400:9800:c60:f8e6:152:80a5:7136:4355',NULL,'2026-10-20 23:59:25','2026-10-20 23:59:25'),(47,1,'login_sukses','auth','info','admin@bubee.id','Role admin','2400:9800:c60:f8e6:152:80a5:7136:4355',NULL,'2026-10-21 00:01:12','2026-10-21 00:01:12'),(48,1,'rekonsiliasi','stok','info',NULL,'0 produk diselaraskan','2400:9800:c60:f8e6:152:80a5:7136:4355','/admin/produk','2026-10-21 00:01:28','2026-10-21 00:01:28'),(49,1,'login_sukses','auth','info','admin@bubee.id','Role admin','103.154.139.190',NULL,'2026-10-24 23:43:46','2026-10-24 23:43:46'),(50,NULL,'login_gagal','auth','bahaya','reseller@bubbe.id','IP 103.154.139.190','103.154.139.190',NULL,'2026-10-24 23:50:28','2026-10-24 23:50:28'),(51,NULL,'login_gagal','auth','bahaya','reseller@bubbe.id','IP 103.154.139.190','103.154.139.190',NULL,'2026-10-24 23:50:46','2026-10-24 23:50:46'),(52,2,'login_sukses','auth','info','reseller@bubee.id','Role reseller','103.154.139.190',NULL,'2026-10-24 23:51:03','2026-10-24 23:51:03'),(53,2,'order_buat','order','info','BB-2026102507012160','Rp 140,000','103.154.139.190','/admin/order/19','2026-10-25 00:01:21','2026-10-25 00:01:21'),(54,2,'order_buat','order','info','BB-2026102507033872','Rp 723,000','103.154.139.190','/admin/order/20','2026-10-25 00:03:38','2026-10-25 00:03:38'),(55,2,'order_batal','order','waspada','BB-2026102507033872','Alasan: Berubah pikiran','103.154.139.190','/admin/order/20','2026-10-25 00:05:52','2026-10-25 00:05:52'),(56,1,'login_sukses','auth','info','admin@bubee.id','Role admin','103.154.139.190',NULL,'2026-10-25 00:15:45','2026-10-25 00:15:45'),(57,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.139.190','/admin/produk','2026-10-25 00:17:03','2026-10-25 00:17:03'),(58,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.139.190','/admin/produk','2026-10-25 00:17:16','2026-10-25 00:17:16'),(59,1,'produk_nonaktif','produk','info','Kemeja Flannel Kotak','Pernah terjual','103.154.139.190','/admin/produk','2026-10-25 00:17:41','2026-10-25 00:17:41'),(60,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.139.190','/admin/produk','2026-10-25 00:17:59','2026-10-25 00:17:59'),(61,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.139.190','/admin/produk','2026-10-25 00:18:15','2026-10-25 00:18:15'),(62,1,'produk_nonaktif','produk','info','Jaket Parka Hangat','Pernah terjual','103.154.139.190','/admin/produk','2026-10-25 00:18:28','2026-10-25 00:18:28');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('bubee-cache-3f742a2b223dcb15a0dfc0c5ab575e900e865107','i:1;',1792912605),('bubee-cache-3f742a2b223dcb15a0dfc0c5ab575e900e865107:timer','i:1792912605;',1792912605),('bubee-cache-c2601a0720f2ac8e536c11f538cfe0155cccec92','i:1;',1792566132),('bubee-cache-c2601a0720f2ac8e536c11f538cfe0155cccec92:timer','i:1792566132;',1792566132),('bubee-cache-login:reseller@bubbe.id|103.154.139.190','i:2;',1792911088),('bubee-cache-login:reseller@bubbe.id|103.154.139.190:timer','i:1792911088;',1792911088);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_product_variant_unique` (`user_id`,`product_id`,`variant_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_variant_id_foreign` (`variant_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (25,1,9,17,1,'2026-10-13 05:14:47','2026-10-13 05:14:47');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `topik` varchar(30) NOT NULL DEFAULT 'Order',
  `pertanyaan` varchar(255) NOT NULL,
  `jawaban` text NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'Order','Bagaimana cara order?','Pilih barang di katalog → pilih ukuran/warna → + Keranjang → Checkout → isi alamat & pilih kurir → Buat Order → Bayar. Stok berkurang otomatis saat order dibuat.',1,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(2,'Order','Bagaimana cara cek stok?','Stok tampil real-time di tiap produk: hijau tersedia, kuning menipis, hitam habis. Tidak perlu tanya admin lagi.',2,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(3,'Bayar','Bagaimana cara bayar?','Buka Pesanan → Bayar. Tersedia QRIS/VA/e-wallet (Midtrans) atau upload bukti transfer manual untuk verifikasi admin.',3,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(4,'Ongkir','Kurir apa saja dan bagaimana hitung ongkir?','JNE, J&T, SPX. Pilih provinsi → kota saat checkout, ongkir dihitung otomatis dari berat barang.',4,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(5,'Reseller','Apa beda harga reseller dan user?','Reseller yang sudah di-approve mendapat harga khusus otomatis jika qty per produk mencapai minimal (default 6 pcs). Daftar via menu Register → Reseller.',5,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(6,'Reseller','Bagaimana jadi reseller?','Daftar akun pilih Reseller, lalu tunggu approval admin (maks 1x24 jam). Status bisa dicek di menu Akun.',6,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(7,'Retur','Bagaimana cara retur barang?','Buka Pesanan yang sudah selesai → Ajukan Retur → pilih barang & alasan + foto. Stok kembali setelah admin menyelesaikan retur.',7,1,'2026-10-03 12:36:21','2026-10-03 12:36:21'),(8,'Akun','Lupa password / ganti password?','Buka Akun (ikon gerigi kanan atas) → Pengaturan → Ganti Password. Hubungi admin jika tidak bisa login sama sekali.',8,1,'2026-10-03 12:36:21','2026-10-03 12:36:21');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_logs`
--

DROP TABLE IF EXISTS `financial_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `financial_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `category` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_logs`
--

LOCK TABLES `financial_logs` WRITE;
/*!40000 ALTER TABLE `financial_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `financial_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_02_121810_create_products_table',1),(5,'2026_10_02_121811_create_carts_table',1),(6,'2026_10_02_121812_create_orders_table',1),(7,'2026_10_02_121813_create_order_items_table',1),(8,'2026_10_02_121814_create_stock_mutations_table',1),(9,'2026_10_02_121815_add_role_to_users_table',1),(10,'2026_10_02_125543_create_settings_table',2),(11,'2026_10_02_132830_create_product_variants_table',3),(12,'2026_10_02_132831_add_variant_to_order_tables',4),(13,'2026_10_02_150218_create_vouchers_table',5),(14,'2026_10_02_150220_add_voucher_to_orders_table',5),(15,'2026_10_02_150246_create_returs_table',5),(16,'2026_10_02_150247_create_retur_items_table',5),(17,'2026_10_02_153717_create_notification_logs_table',6),(18,'2026_10_02_155422_add_kota_to_orders_table',7),(19,'2026_10_02_161816_create_reviews_table',8),(20,'2026_10_02_161817_add_kategori_to_products_table',8),(21,'2026_10_03_080417_add_settings_to_users_table',9),(22,'2026_10_03_193529_create_faqs_table',10),(23,'2026_10_03_200733_add_alasan_batal_to_orders_table',11),(24,'2026_10_05_081455_create_activity_logs_table',12),(25,'2026_10_05_082155_create_product_prices_table',13),(26,'2026_10_06_080751_add_dimensi_to_products_table',14),(27,'2026_10_06_080752_add_berat_tagih_to_orders_table',14),(28,'2026_10_06_094432_create_panduans_table',15),(29,'2026_10_07_100000_create_otps_table',16),(30,'2026_10_07_110000_otp_retry_limit',17),(31,'2026_10_07_120000_add_nik_to_users_table',18),(32,'2026_10_07_130000_perkaya_activity_logs',19),(33,'2026_10_07_140000_create_opname_sesi',20),(34,'2026_10_07_150000_create_penerimaan',21),(35,'2026_10_13_171645_add_kategori_to_returs_table',22),(36,'2026_10_25_000000_add_deleted_at_to_products_table',23);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_logs`
--

DROP TABLE IF EXISTS `notification_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipe` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'dummy',
  `response` text DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_logs_order_id_foreign` (`order_id`),
  CONSTRAINT `notification_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_logs`
--

LOCK TABLES `notification_logs` WRITE;
/*!40000 ALTER TABLE `notification_logs` DISABLE KEYS */;
INSERT INTO `notification_logs` VALUES (1,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100215420222\n- Celana Chino Slim ×1\n- Jaket Parka Hangat ×1\nTotal: Rp 594,000 (JNT ECO)\nPenerima: User Demo (081234567892)\nSegera verifikasi di admin ya!','dummy',NULL,4,'2026-10-02 08:42:02','2026-10-02 08:42:02'),(2,'status','6281234567892','🐝 *Bubee - BB-2026100215420222*\nPesanan dikirim! Resi: JNT999 🚚\nTotal: Rp 594,000','dummy',NULL,4,'2026-10-02 08:42:27','2026-10-02 08:42:27'),(3,'status','6281234567892','🐝 *Bubee - BB-2026100215420222*\nPesanan dikirim! Resi: JNT999 🚚\nTotal: Rp 594,000','dummy',NULL,4,'2026-10-02 08:42:28','2026-10-02 08:42:28'),(4,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100216011811\n- Celana Chino Slim ×1\nTotal: Rp 238,000 (SPX Standard)\nPenerima: U (0812)\nSegera verifikasi di admin ya!','dummy',NULL,5,'2026-10-02 09:01:18','2026-10-02 09:01:18'),(5,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100305435633\n- Kaos Dry Fit Pria (Uniqlo style) ×1\nTotal: Rp 139,000 (JNT REG)\nPenerima: reihan (08128897287)\nSegera verifikasi di admin ya!','dummy',NULL,6,'2026-10-02 22:43:56','2026-10-02 22:43:56'),(6,'bayar','6281234567890','💳 *Pembayaran BB-2026100305435633* via simulasi (Rp 139,000). Cek & verifikasi ya!','dummy',NULL,6,'2026-10-02 22:44:06','2026-10-02 22:44:06'),(7,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100305501417\n- Kaos Dry Fit Pria (Uniqlo style) ×1\nTotal: Rp 139,000 (JNT YES)\nPenerima: gilang (089520351162)\nSegera verifikasi di admin ya!','dummy',NULL,7,'2026-10-02 22:50:14','2026-10-02 22:50:14'),(8,'bayar','6281234567890','💳 *Pembayaran BB-2026100305501417* via simulasi (Rp 139,000). Cek & verifikasi ya!','dummy',NULL,7,'2026-10-02 22:50:24','2026-10-02 22:50:24'),(9,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100308360617\n- Kaos Dry Fit Pria (Uniqlo style) ×1\nTotal: Rp 139,000 (JNE SameDay)\nPenerima: reihan (08128897287)\nSegera verifikasi di admin ya!','dummy',NULL,8,'2026-10-03 01:36:06','2026-10-03 01:36:06'),(10,'bayar','6281234567890','💳 *Pembayaran BB-2026100308360617* via simulasi (Rp 139,000). Cek & verifikasi ya!','dummy',NULL,8,'2026-10-03 01:36:55','2026-10-03 01:36:55'),(11,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100309285568\n- Kaos Dry Fit Pria (Uniqlo style) ×1\nTotal: Rp 139,000 (JNT REG)\nPenerima: gilang (089520351162)\nSegera verifikasi di admin ya!','dummy',NULL,9,'2026-10-03 02:28:55','2026-10-03 02:28:55'),(12,'reseller','6289520351162','🎉 *Selamat gilang!* Akun reseller Bubee-mu *DISETUJUI*. Harga khusus otomatis aktif. Happy cuan!','dummy',NULL,NULL,'2026-10-03 02:33:19','2026-10-03 02:33:19'),(13,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100320102569\n- Kaos Dry Fit Pria (Uniqlo style) ×6\nTotal: Rp 556,000 (JNE REG)\nPenerima: R (0812)\nSegera verifikasi di admin ya!','dummy',NULL,10,'2026-10-03 13:10:25','2026-10-03 13:10:25'),(14,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100320111482\n- Kaos Dry Fit Pria (Uniqlo style) ×6\nTotal: Rp 556,000 (JNE REG)\nPenerima: R (0812)\nSegera verifikasi di admin ya!','dummy',NULL,11,'2026-10-03 13:11:14','2026-10-03 13:11:14'),(15,'batal','6281234567890','⚠️ *Order batal BB-2026100320111482*\nOleh: reseller Reseller Demo\nAlasan: Pelanggan saya batal\n6 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,11,'2026-10-03 13:11:43','2026-10-03 13:11:43'),(16,'status','62812','🐝 *Bubee - BB-2026100320111482*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 556,000','dummy',NULL,11,'2026-10-03 13:11:43','2026-10-03 13:11:43'),(17,'batal','6281234567890','⚠️ *Order batal BB-2026100213470657*\nOleh: reihan\nAlasan: Salah alamat\n1 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,2,'2026-10-03 13:14:38','2026-10-03 13:14:38'),(18,'status','628128897287','🐝 *Bubee - BB-2026100213470657*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 140,000','dummy',NULL,2,'2026-10-03 13:14:38','2026-10-03 13:14:38'),(19,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100413213973\n- Celana Chino Slim ×1\nTotal: Rp 220,000 (JNE REG)\nPenerima: U (0812)\nSegera verifikasi di admin ya!','dummy',NULL,12,'2026-10-04 06:21:39','2026-10-04 06:21:39'),(20,'batal','6281234567890','⚠️ *Order batal BB-2026100320102569*\nOleh: reseller Reseller Demo\nAlasan: Salah alamat\n6 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,10,'2026-10-04 21:35:18','2026-10-04 21:35:18'),(21,'status','62812','🐝 *Bubee - BB-2026100320102569*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 556,000','dummy',NULL,10,'2026-10-04 21:35:18','2026-10-04 21:35:18'),(22,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100508200231\n- Kaos Dry Fit Pria (Uniqlo style) ×6\nTotal: Rp 556,000 (JNE REG)\nPenerima: R (0812)\nSegera verifikasi di admin ya!','dummy',NULL,13,'2026-10-05 01:20:02','2026-10-05 01:20:02'),(29,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026100706412316\n- Kemeja Flannel Kotak ×32\n- HQ-001 Botol Chicutom Sedotan ×1\nTotal: Rp 6,739,000 (JNE YES)\nPenerima: reihan (628128897287)\nSegera verifikasi di admin ya!','dummy',NULL,17,'2026-10-06 23:41:23','2026-10-06 23:41:23'),(30,'bayar','6281234567890','💳 *Pembayaran BB-2026100706412316* via simulasi (Rp 6,739,000). Cek & verifikasi ya!','dummy',NULL,17,'2026-10-06 23:41:36','2026-10-06 23:41:36'),(31,'batal','6281234567890','⚠️ *Order batal BB-2026100706412316*\nOleh: reihan\nAlasan: Salah alamat\n33 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,17,'2026-10-06 23:42:01','2026-10-06 23:42:01'),(32,'status','628128897287','🐝 *Bubee - BB-2026100706412316*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 6,739,000','dummy',NULL,17,'2026-10-06 23:42:01','2026-10-06 23:42:01'),(33,'batal','6281234567890','⚠️ *Order batal BB-2026100706412316*\nOleh: reihan\nAlasan: Salah alamat\n33 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,17,'2026-10-07 00:20:04','2026-10-07 00:20:04'),(34,'status','62812','🐝 *Bubee - BB-2026100508200231*\nPesanan dikirim! Resi: - 🚚\nTotal: Rp 556,000','dummy',NULL,13,'2026-10-13 01:38:38','2026-10-13 01:38:38'),(35,'retur','6281234567892','🔄 *Retur #1* (BB-2026100215114144) → selesai.\nCatatan: Barang diterima, dana diproses','dummy',NULL,3,'2026-10-13 03:32:03','2026-10-13 03:32:03'),(36,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026101310345245\n- HQ-001 Botol Chicutom Sedotan ×1\nTotal: Rp 139,000 (SPX REG)\nPenerima: reihan (628128897287)\nSegera verifikasi di admin ya!','dummy',NULL,18,'2026-10-13 03:34:52','2026-10-13 03:34:52'),(37,'batal','6281234567890','⚠️ *Order batal BB-2026101310345245*\nOleh: reihan\nAlasan: Salah ukuran/warna\n1 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,18,'2026-10-13 03:37:30','2026-10-13 03:37:30'),(38,'status','628128897287','🐝 *Bubee - BB-2026101310345245*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 139,000','dummy',NULL,18,'2026-10-13 03:37:31','2026-10-13 03:37:31'),(39,'status','628128897287','🐝 *Bubee - BB-2026101310345245*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 139,000','dummy',NULL,18,'2026-10-13 03:44:35','2026-10-13 03:44:35'),(40,'batal','6281234567890','⚠️ *Order batal BB-2026101310345245*\nOleh: reihan\nAlasan: Salah ukuran/warna\n1 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,18,'2026-10-13 03:45:12','2026-10-13 03:45:12'),(41,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026102507012160\n- HQ-001 Botol Chicutom Sedotan ×1\nTotal: Rp 140,000 (JNE REG)\nPenerima: Reseller Demo (6281234567891)\nSegera verifikasi di admin ya!','dummy',NULL,19,'2026-10-25 00:01:21','2026-10-25 00:01:21'),(42,'order_baru','6281234567890','🐝 *Order baru Bubee!*\nKode: BB-2026102507033872\n- HQ-096 Botol Mini Tali Kepang ×10\nTotal: Rp 723,000 (JNE REG)\nPenerima: Reseller Demo (6281234567891)\nSegera verifikasi di admin ya!','dummy',NULL,20,'2026-10-25 00:03:38','2026-10-25 00:03:38'),(43,'bayar','6281234567890','💳 *Pembayaran BB-2026102507033872* via simulasi (Rp 723,000). Cek & verifikasi ya!','dummy',NULL,20,'2026-10-25 00:04:45','2026-10-25 00:04:45'),(44,'batal','6281234567890','⚠️ *Order batal BB-2026102507033872*\nOleh: reseller Reseller Demo\nAlasan: Berubah pikiran\n10 pcs kembali ke stok. Follow-up yuk!','dummy',NULL,20,'2026-10-25 00:05:52','2026-10-25 00:05:52'),(45,'status','6281234567891','🐝 *Bubee - BB-2026102507033872*\nYah, order dibatalkan. Stok sudah dikembalikan.\nTotal: Rp 723,000','dummy',NULL,20,'2026-10-25 00:05:52','2026-10-25 00:05:52');
/*!40000 ALTER TABLE `notification_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opname_items`
--

DROP TABLE IF EXISTS `opname_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opname_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sesi_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `snapshot` int(11) NOT NULL DEFAULT 0,
  `fisik` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opname_items_sesi_id_foreign` (`sesi_id`),
  KEY `opname_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `opname_items_sesi_id_foreign` FOREIGN KEY (`sesi_id`) REFERENCES `opname_sesi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `opname_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opname_items`
--

LOCK TABLES `opname_items` WRITE;
/*!40000 ALTER TABLE `opname_items` DISABLE KEYS */;
INSERT INTO `opname_items` VALUES (1,1,12,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(2,1,10,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(3,1,9,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(4,1,11,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(5,1,15,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(6,1,13,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(7,1,14,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(8,1,16,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(9,1,19,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(10,1,17,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(11,1,18,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(12,1,22,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(13,1,21,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(14,1,20,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(15,1,23,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(16,1,27,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(17,1,24,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(18,1,26,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(19,1,25,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(20,1,28,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(21,1,31,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(22,1,30,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(23,1,29,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(24,1,33,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(25,1,35,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(26,1,34,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(27,1,32,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(28,1,39,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(29,1,38,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(30,1,36,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(31,1,37,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(32,1,43,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(33,1,42,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(34,1,40,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(35,1,41,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(36,1,44,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(37,1,46,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(38,1,45,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(39,1,47,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(40,1,49,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(41,1,50,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(42,1,48,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(43,1,51,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(44,1,54,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(45,1,53,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(46,1,52,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(47,1,56,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(48,1,57,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(49,1,58,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(50,1,55,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(51,1,62,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(52,1,59,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(53,1,60,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(54,1,61,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(55,1,64,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(56,1,63,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(57,1,65,17,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(58,1,68,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(59,1,67,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(60,1,69,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(61,1,66,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(62,1,70,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(63,1,72,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(64,1,74,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(65,1,73,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(66,1,71,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(67,1,77,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(68,1,75,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(69,1,78,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(70,1,76,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(71,1,82,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(72,1,80,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(73,1,81,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24'),(74,1,79,20,NULL,NULL,'2026-10-08 11:21:24','2026-10-08 11:21:24');
/*!40000 ALTER TABLE `opname_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opname_sesi`
--

DROP TABLE IF EXISTS `opname_sesi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opname_sesi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `cakupan` varchar(50) NOT NULL DEFAULT 'semua',
  `status` varchar(20) NOT NULL DEFAULT 'jalan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opname_sesi_user_id_foreign` (`user_id`),
  CONSTRAINT `opname_sesi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opname_sesi`
--

LOCK TABLES `opname_sesi` WRITE;
/*!40000 ALTER TABLE `opname_sesi` DISABLE KEYS */;
INSERT INTO `opname_sesi` VALUES (1,1,'Tumbler','jalan','2026-10-08 11:21:24','2026-10-08 11:21:24');
/*!40000 ALTER TABLE `opname_sesi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `harga_satuan` int(11) NOT NULL DEFAULT 0,
  `subtotal` int(11) NOT NULL DEFAULT 0,
  `tipe_harga` varchar(255) NOT NULL DEFAULT 'enduser',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,7,2,129000,258000,'enduser','2026-10-02 06:37:18','2026-10-02 06:37:18'),(2,2,1,1,1,129000,129000,'enduser','2026-10-02 06:47:06','2026-10-02 06:47:06'),(3,3,2,2,1,199000,199000,'enduser','2026-10-02 08:11:41','2026-10-02 08:11:41'),(4,4,3,3,1,229000,229000,'enduser','2026-10-02 08:42:02','2026-10-02 08:42:02'),(5,4,4,4,1,349000,349000,'enduser','2026-10-02 08:42:02','2026-10-02 08:42:02'),(6,5,3,3,1,229000,229000,'enduser','2026-10-02 09:01:18','2026-10-02 09:01:18'),(7,6,1,8,1,129000,129000,'enduser','2026-10-02 22:43:56','2026-10-02 22:43:56'),(8,7,1,7,1,129000,129000,'enduser','2026-10-02 22:50:14','2026-10-02 22:50:14'),(9,8,1,7,1,129000,129000,'enduser','2026-10-03 01:36:06','2026-10-03 01:36:06'),(10,9,1,7,1,129000,129000,'enduser','2026-10-03 02:28:55','2026-10-03 02:28:55'),(11,10,1,1,6,89000,534000,'reseller','2026-10-03 13:10:25','2026-10-03 13:10:25'),(12,11,1,1,6,89000,534000,'reseller','2026-10-03 13:11:14','2026-10-03 13:11:14'),(13,12,3,3,1,229000,229000,'enduser','2026-10-04 06:21:39','2026-10-04 06:21:39'),(14,13,1,1,6,89000,534000,'reseller','2026-10-05 01:20:02','2026-10-05 01:20:02'),(18,17,2,2,32,199000,6368000,'enduser','2026-10-06 23:41:23','2026-10-06 23:41:23'),(19,17,7,10,1,129000,129000,'enduser','2026-10-06 23:41:23','2026-10-06 23:41:23'),(20,18,7,9,1,129000,129000,'enduser','2026-10-13 03:34:52','2026-10-13 03:34:52'),(21,19,7,12,1,129000,129000,'enduser','2026-10-25 00:01:21','2026-10-25 00:01:21'),(22,20,25,71,10,69000,690000,'reseller','2026-10-25 00:03:38','2026-10-25 00:03:38');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `kode` varchar(255) NOT NULL,
  `total_produk` int(11) NOT NULL DEFAULT 0,
  `ongkir` int(11) NOT NULL DEFAULT 0,
  `ongkir_sumber` varchar(255) NOT NULL DEFAULT 'dummy',
  `berat_tagih_gram` int(11) NOT NULL DEFAULT 0,
  `voucher_id` bigint(20) unsigned DEFAULT NULL,
  `diskon` int(11) NOT NULL DEFAULT 0,
  `total_bayar` int(11) NOT NULL DEFAULT 0,
  `kurir` varchar(255) NOT NULL DEFAULT 'JNE',
  `layanan` varchar(255) NOT NULL DEFAULT 'REG',
  `resi` varchar(255) DEFAULT NULL,
  `alasan_batal` varchar(100) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu_bayar',
  `alamat` text DEFAULT NULL,
  `kota_tujuan` varchar(255) DEFAULT NULL,
  `city_id` varchar(255) DEFAULT NULL,
  `nama_penerima` varchar(255) DEFAULT NULL,
  `hp_penerima` varchar(255) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_kode_unique` (`kode`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_voucher_id_foreign` (`voucher_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,3,'BB-2026100213371874',258000,11000,'dummy',0,NULL,0,269000,'JNE','REG',NULL,NULL,'batal','Jl Test',NULL,NULL,'User Demo','0812',NULL,'DUMMY-BB-2026100213371874','2026-10-02 06:37:18','2026-10-02 06:37:37'),(2,4,'BB-2026100213470657',129000,11000,'dummy',0,NULL,0,140000,'JNE','REG',NULL,'Salah alamat','batal','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124',NULL,NULL,'reihan','08128897287',NULL,'DUMMY-BB-2026100213470657','2026-10-02 06:47:06','2026-10-03 13:14:38'),(3,3,'BB-2026100215114144',199000,11000,'dummy',0,1,19900,190100,'JNE','REG','JNE123',NULL,'selesai','Jl Test',NULL,NULL,'User Demo','0812',NULL,'DUMMY-BB-2026100215114144','2026-10-02 08:11:41','2026-10-02 08:13:09'),(4,3,'BB-2026100215420222',578000,16000,'dummy',0,NULL,0,594000,'JNT','ECO','JNT999',NULL,'dikirim','Jl Test',NULL,NULL,'User Demo','081234567892',NULL,'DUMMY-BB-2026100215420222','2026-10-02 08:42:02','2026-10-02 08:42:27'),(5,3,'BB-2026100216011811',229000,9000,'dummy',0,NULL,0,238000,'SPX','Standard',NULL,NULL,'menunggu_bayar','Jl','Kota Jakarta Timur','151','U','0812',NULL,'DUMMY-BB-2026100216011811','2026-10-02 09:01:18','2026-10-02 09:01:18'),(6,4,'BB-2026100305435633',129000,10000,'dummy',0,NULL,0,139000,'JNT','REG',NULL,NULL,'batal','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124','Kota Semarang','445','reihan','08128897287',NULL,'DUMMY-BB-2026100305435633','2026-10-02 22:43:56','2026-10-02 22:44:25'),(7,5,'BB-2026100305501417',129000,10000,'dummy',0,NULL,0,139000,'JNT','YES',NULL,NULL,'diproses','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124','Kota Bandung','23','gilang','089520351162',NULL,'DUMMY-BB-2026100305501417','2026-10-02 22:50:14','2026-10-02 22:50:24'),(8,4,'BB-2026100308360617',129000,10000,'dummy',0,NULL,0,139000,'JNE','SameDay',NULL,NULL,'batal','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124','Kota Bandung','23','reihan','08128897287',NULL,'DUMMY-BB-2026100308360617','2026-10-03 01:36:06','2026-10-03 13:01:30'),(9,5,'BB-2026100309285568',129000,10000,'dummy',0,NULL,0,139000,'JNT','REG',NULL,NULL,'menunggu_bayar','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124','Kota Bandung','23','gilang','089520351162',NULL,'DUMMY-BB-2026100309285568','2026-10-03 02:28:55','2026-10-03 02:28:55'),(10,2,'BB-2026100320102569',534000,22000,'dummy',0,NULL,0,556000,'JNE','REG',NULL,'Salah alamat','batal','Jl','Kota Bandung','23','R','0812',NULL,'DUMMY-BB-2026100320102569','2026-10-03 13:10:25','2026-10-04 21:35:18'),(11,2,'BB-2026100320111482',534000,22000,'dummy',0,NULL,0,556000,'JNE','REG',NULL,'Pelanggan saya batal','batal','Jl','Kota Bandung','23','R','0812',NULL,'DUMMY-BB-2026100320111482','2026-10-03 13:11:14','2026-10-03 13:11:43'),(12,3,'BB-2026100413213973',229000,11000,'dummy',0,1,20000,220000,'JNE','REG',NULL,NULL,'menunggu_bayar','Jl','Kota Bandung','23','U','0812',NULL,'DUMMY-BB-2026100413213973','2026-10-04 06:21:39','2026-10-04 06:21:39'),(13,2,'BB-2026100508200231',534000,22000,'dummy',0,NULL,0,556000,'JNE','REG',NULL,NULL,'dikirim','Jl','Kota Bandung','23','R','0812',NULL,'DUMMY-BB-2026100508200231','2026-10-05 01:20:02','2026-10-13 01:38:38'),(17,4,'BB-2026100706412316',6497000,242000,'dummy',10100,NULL,0,6739000,'JNE','YES',NULL,'Salah alamat','batal','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124','Kota Bandung','23','reihan','628128897287',NULL,'DUMMY-BB-2026100706412316','2026-10-06 23:41:23','2026-10-06 23:42:01'),(18,4,'BB-2026101310345245',129000,10000,'dummy',500,NULL,0,139000,'SPX','REG',NULL,'Salah ukuran/warna','batal','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124','Kab. Bekasi','22','reihan','628128897287',NULL,'DUMMY-BB-2026101310345245','2026-10-13 03:34:52','2026-10-13 03:37:30'),(19,2,'BB-2026102507012160',129000,11000,'dummy',500,NULL,0,140000,'JNE','REG',NULL,NULL,'menunggu_bayar','Jl. Reseller No. 1','Kota Medan','101','Reseller Demo','6281234567891',NULL,'DUMMY-BB-2026102507012160','2026-10-25 00:01:21','2026-10-25 00:01:21'),(20,2,'BB-2026102507033872',690000,33000,'dummy',3000,NULL,0,723000,'JNE','REG',NULL,'Berubah pikiran','batal','Jl. Reseller No. 1','Kota Bandung','23','Reseller Demo','6281234567891',NULL,'DUMMY-BB-2026102507033872','2026-10-25 00:03:38','2026-10-25 00:05:52');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otps`
--

DROP TABLE IF EXISTS `otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `kode_hash` varchar(255) NOT NULL,
  `percobaan` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `kedaluwarsa_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dipakai_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otps_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otps`
--

LOCK TABLES `otps` WRITE;
/*!40000 ALTER TABLE `otps` DISABLE KEYS */;
INSERT INTO `otps` VALUES (1,'hikarinokokyu@gmail.com','$2y$12$YTNcYI.HJFPvYKQz2W7c8eacjEZzgBUgKIXmooDVhMz2H2e2A08pO',0,'2026-10-06 21:11:26',NULL,'2026-10-06 21:01:26','2026-10-06 21:01:26'),(2,'reihananggaci9@gmail.com','$2y$12$5d1K6RLOifqlMTAkVNEQ7eNDXirNS/cK.gmY.bB028caw/plxdPc2',0,'2026-10-07 05:31:14','2026-10-06 22:31:14','2026-10-06 22:28:44','2026-10-06 22:31:14'),(3,'reihananggaci9@gmail.com','$2y$12$xdUX2YQQJ5ULBkmNSR4FA.8ufDGPOdUITO1KFyHpvWsVscz6QRyGO',0,'2026-10-06 22:41:15',NULL,'2026-10-06 22:31:15','2026-10-06 22:31:15');
/*!40000 ALTER TABLE `otps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `panduans`
--

DROP TABLE IF EXISTS `panduans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `panduans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kunci` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tips` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `panduans_kunci_unique` (`kunci`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `panduans`
--

LOCK TABLES `panduans` WRITE;
/*!40000 ALTER TABLE `panduans` DISABLE KEYS */;
INSERT INTO `panduans` VALUES (1,'GET /','Beranda Katalog','Halaman utama berisi semua barang yang dijual. Stok tampil real-time: hijau tersedia, kuning menipis, hitam habis. Harga baru terlihat setelah login.','Gunakan pencarian, filter stok, kategori, dan urutan termurah/termahal untuk menemukan barang cepat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(2,'GET /produk/{id}','Detail Barang','Menampilkan foto, deskripsi, rating, ulasan, pilihan ukuran/warna beserta sisa stok tiap varian, dan harga sesuai akunmu (reseller approved otomatis dapat harga grosir).','Perhatikan label sisa tiap varian sebelum memesan. Tombol Bagikan bisa kirim info barang ke WA.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(3,'POST /keranjang/{id}','Masuk Keranjang','Menaruh barang + varian + jumlah pilihanmu ke keranjang. Stok BELUM berkurang di tahap ini, jadi aman untuk ubah-ubah.','Masukkan jumlah di atas minimal reseller sekaligus agar harga grosir langsung aktif.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(4,'GET /keranjang','Keranjang Belanja','Daftar belanja sementaramu: subtotal dan berat total terhitung otomatis. Stok masih aman, belum berkurang.','Kurangi jumlah atau hapus barang yang tidak jadi dibeli sebelum checkout.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(5,'PUT /keranjang-item/{id}','Ubah Jumlah','Mengubah jumlah pcs barang di keranjang. Sistem menolak jika melebihi sisa stok varian tersebut.','Naikkan ke atas minimal agar harga grosir aktif otomatis.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(6,'DELETE /keranjang-item/{id}','Hapus dari Keranjang','Mengeluarkan satu baris barang dari keranjang. Tidak mengutak-atik stok dan tidak bisa dibatalkan, tapi barangnya tetap ada di katalog.','Salah varian? Hapus lalu tambah ulang dengan varian yang benar.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(7,'GET /checkout','Form Checkout','Isi nama penerima, HP, alamat, pilih provinsi lalu kota, kurir, layanan, dan kode voucher bila ada. Ongkir dihitung dari berat terbesar antara berat asli vs volumetrik.','Untuk barang besar tapi ringan, isi dimensi di data barang agar hitungan ongkir akurat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(8,'POST /checkout','Buat Order','Membuat order resmi: stok varian dikunci lalu dikurangi, harga dibekukan, kuota voucher dipakai. Setelah ini order tercatat permanen dan tidak bisa dihapus.','Periksa ulang alamat dan kota sebelum menekan tombol ini.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(9,'GET /bayar/{id}','Halaman Bayar','Menampilkan total yang harus dibayar beserta pilihan bayar: QRIS/VA/e-wallet otomatis (Midtrans) atau upload bukti transfer manual.','Simpan bukti transfer (screenshot) sebelum upload agar verifikasi admin cepat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(10,'POST /bayar/simulasi','Simulasi Bayar Berhasil','Tombol latihan ini menandai order sebagai sudah dibayar dan berstatus diproses. Di mode live digantikan tombol bayar Midtrans asli.','Untuk transaksi sungguhan, gunakan tombol Bayar Sekarang agar status terverifikasi otomatis.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(11,'POST /bayar/bukti','Upload Bukti Transfer','Mengirim foto bukti transfer ke admin. Status order berubah menjadi diproses dan menunggu verifikasi. Hanya bisa dipakai saat status masih menunggu bayar.','Foto harus jelas: nominal, tanggal, dan nomor tujuan terlihat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(12,'GET /pesanan','Pesanan Saya','Riwayat semua ordermu beserta statusnya: menunggu bayar, diproses, dikirim (ada resi), selesai, atau batal. Klik untuk detail dan tracking.','Order yang belum dibayar bisa dibatalkan sendiri dari sini.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(13,'GET /pesanan/{id}','Tracking Order','Linimasa perjalanan order: bayar, diproses, dikirim, selesai. Jika ada resi, tersedia tombol lacak paket ke situs kurir. Setelah selesai bisa beri ulasan, ajukan retur, atau unduh invoice.','Simpan nomor resi. Jika paket macet lebih dari estimasi, chat admin dari tombol yang tersedia.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(14,'POST /pesanan/batal','Batalkan Order','Membatalkan order yang belum dikirim. Stok otomatis kembali. Wajib pilih alasan; admin menerima notifikasi WA.','Hanya bisa saat menunggu bayar/diproses. Setelah dikirim, gunakan retur.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(15,'POST /pesanan/selesai','Pesanan Diterima','Menandai paket sudah sampai. Setelah ini terbuka tombol ulasan, retur, dan invoice.','Periksa isi paket dulu sebelum menekan tombol ini.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(16,'GET /pesanan/invoice','Unduh Invoice','Mengunduh bukti pembelian resmi (PDF): rincian barang, ongkir, voucher, dan total. Simpan sebagai arsip atau bukti klaim.','Lampirkan invoice saat mengajukan retur agar diproses lebih cepat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(17,'GET /pesanan/retur','Form Retur','Mengembalikan barang bermasalah (cacat/salah). Pilih barang + jumlah + alasan + foto. Hanya untuk order yang sudah selesai.','Isi 0 untuk barang yang tidak diretur. Stok kembali setelah admin menyelesaikan retur.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(18,'GET /retur','Retur Saya','Daftar semua pengajuan returmu dan statusnya: diajukan, disetujui, ditolak, selesai.','Pantau catatan admin jika retur ditolak agar tahu alasannya.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(19,'POST /produk/{id}/ulasan','Tulis Ulasan','Memberi bintang 1-5 plus cerita pengalaman, boleh tambah foto. Hanya pembeli yang ordernya selesai yang bisa mengulas, satu kali per produk.','Ulasan jujur membantu pembeli lain dan menaikkan kepercayaan toko.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(20,'GET /akun','Akun Saya','Pusat profilmu: foto inisial, badge peran/tier, statistik order-pcs-hemat, dan menu ke pesanan, keranjang, retur, pengaturan.','Lengkapi HP dan alamat agar checkout lebih cepat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(21,'GET /akun/pengaturan','Pengaturan Akun','Ubah nama, HP, alamat, nama toko (reseller), notifikasi WA, ganti password, sampai hapus akun. Perubahan tersimpan per akun.','Aktifkan notif WA agar update order masuk otomatis ke HP-mu.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(22,'DELETE /akun','Hapus Akun','Menghapus akunmu secara permanen (dianonimkan). Ditolak jika masih ada order aktif atau kamu satu-satunya admin. Riwayat order tetap tersimpan anonim demi pembukuan.','Selesaikan atau batalkan semua order aktif dulu sebelum menghapus.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(23,'GET /bantuan','Pusat Bantuan','Kumpulan jawaban (FAQ) yang dikelola admin: cara order, bayar, ongkir, reseller, retur. Bisa dicari dan difilter per topik, plus tombol chat admin.','Cek sini dulu sebelum chat admin — kebanyakan pertanyaan sudah terjawab.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(24,'GET /reseller','Reseller Center','Dasbor khusus reseller approved: total hemat kumulatif, tier Bronze/Silver/Gold, order menunggu bayar, voucher khusus, dan peringatan stok menipis.','Pantau margin di etalase, pilih yang terbesar untuk cuan maksimal.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(25,'GET /reseller/etalase','Etalase Modal','Katalog versi reseller: harga modal besar, harga normal dicoret, margin rupiah dan persen, bisa diurutkan. Ada tombol order dan share per barang.','Urutkan margin terbesar untuk tahu barang paling menguntungkan.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(26,'POST /login','Tombol Login','Masuk dengan email dan password. Salah 5 kali berturut-turut dikunci sementara anti robot. Robot otomatis ditolak diam-diam.','Centang captcha \"Saya manusia\" jika muncul. Lupa password? Hubungi admin untuk reset.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(27,'POST /register','Tombol Daftar','Membuat akun baru sebagai End User (langsung aktif) atau Reseller (menunggu approval admin). Password minimal 8 karakter.','Pilih Reseller jika belanja untuk dijual lagi agar dapat harga grosir setelah di-approve.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(28,'GET /admin','Dashboard Admin','Ruang komando: omzet, progres harian vs target, grafik 7 hari, order terbaru, peringatan stok kritis dan selisih, serta upaya login gagal.','Cek kotak merah setiap pagi: stok kritis, selisih, dan upaya login mencurigakan.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(29,'GET /admin/produk','Kelola Barang','Tambah barang (bisa banyak ukuran sekaligus), atur varian dan stok, tier harga, kategori, foto, dan arsipkan yang sudah tidak dijual.','Gunakan Duplikat untuk barang mirip agar tidak input ulang. Nonaktifkan, jangan hapus, barang yang pernah terjual.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(30,'GET /admin/order','Kelola Order','Semua order masuk: ubah status, isi resi, export CSV. Klik kode untuk detail lengkap dan cetak invoice/label/struk.','Isi resi segera setelah kirim agar pembeli bisa tracking dan tombol Selesai aktif.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(31,'PUT /admin/order','Update Status Order','Mengubah status order. Jika dibatalkan, stok otomatis kembali. Setiap perubahan tercatat di log aktivitas dan dikirim notif WA ke pembeli.','Jangan mundurkan status (mis. selesai ke diproses) kecuali untuk koreksi kesalahan.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(32,'GET /admin/verifikasi','Verifikasi Bayar','Antrean bukti transfer yang menunggu dicek. Setujui jika uang masuk (lanjut kirim), tolak jika tidak valid (order batal, stok kembali).','Cocokkan nominal dan tanggal bukti dengan total order sebelum menyetujui.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(33,'GET /admin/reseller','Approval Reseller','Daftar pendaftar reseller: setujui yang valid, tolak yang meragukan. Keduanya otomatis dapat notifikasi WA.','Cek kontak dan alamat pendaftar sebelum menyetujui.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(34,'GET /admin/laporan','Laporan','Omzet total, per kurir, produk terlaris, progres 14 hari, dan mutasi stok terakhir. Bisa export CSV untuk Excel.','Bandingkan terlaris vs stok menipis untuk putuskan restock.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(35,'GET /admin/users','Kelola Pengguna','Cari user, ubah peran, reset password, hapus user yang belum pernah order. Tidak bisa menurunkan atau menghapus diri sendiri.','Jadikan admin hanya orang terpercaya. Reset password lalu suruh ganti segera.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(36,'GET /admin/mutasi','Mutasi Stok','Jejak audit semua keluar-masuk stok: kapan, berapa, keterangan apa. Tidak bisa dihapus agar audit tetap utuh.','Selisih fisik vs sistem? Cocokkan di sini dulu sebelum opname.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(37,'GET /admin/opname','Stok Opname','Isi hasil hitung fisik gudang per varian sekaligus. Selisih tercatat otomatis sebagai mutasi. Ada tombol rekonsiliasi untuk menyelaraskan total.','Opname rutin tiap minggu agar selisih tidak menumpuk.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(38,'GET /admin/pengaturan','Pengaturan Toko','Nama toko, WA admin, target harian, pengumuman, token API (Fonnte, RajaOngkir, captcha), dan mode demo. Satu tempat untuk semua saklar.','Isi kota asal RajaOngkir agar ongkir real-time akurat.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(39,'GET /admin/notifikasi','Log Notifikasi','Semua pesan WA keluar: order baru, status, retur, reseller. Bisa cari, lihat isi, dan kirim ulang yang gagal.','Jika pembeli mengaku tak dapat WA, cek di sini dulu sebelum kirim manual.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(40,'GET /admin/voucher','Kelola Voucher','Buat kode promo: persen/nominal, maks diskon, min belanja, kuota, khusus user/reseller, tanggal berlaku. Yang sudah dipakai tidak bisa dihapus, hanya dinonaktifkan.','Buat voucher khusus reseller untuk mendorong repeat order.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(41,'GET /admin/retur','Kelola Retur','Pengajuan retur dari pembeli: setujui, tolak dengan catatan, atau selesaikan (stok varian bertambah otomatis).','Minta foto jelas sebelum menyetujui agar tidak salah.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(42,'GET /admin/ulasan','Moderasi Ulasan','Hapus ulasan spam atau tidak pantas. Ulasan jujur sebaiknya dipertahankan demi kepercayaan toko.','Jangan hapus kritik membangun — balas dengan perbaikan justru menaikkan reputasi.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(43,'GET /admin/faq','Kelola FAQ','Tulis-ubah-hapus jawaban bantuan yang tampil di popup, panel, dan halaman bantuan. Atur urutan dan topiknya.','Tambahkan FAQ setiap ada pertanyaan yang ditanyakan berulang kali.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(44,'GET /admin/aktivitas','Log Aktivitas','Catatan siapa (admin mana) melakukan apa: approve, ubah status, ubah peran. Tidak bisa dihapus.','Curiga ada perubahan aneh? Lacak pelakunya di sini.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53'),(45,'POST /admin/demo/bersihkan','Bersihkan Demo','Menghapus SEMUA order berkode DEMO- sekaligus mengembalikan stoknya. Data asli tidak tersentuh.','Jalankan ini setiap selesai demo ke mitra agar laporan tetap bersih.',1,'2026-10-06 02:45:53','2026-10-06 02:45:53');
/*!40000 ALTER TABLE `panduans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penerimaan`
--

DROP TABLE IF EXISTS `penerimaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penerimaan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `no_surat_jalan` varchar(50) NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'dicatat',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penerimaan_user_id_foreign` (`user_id`),
  CONSTRAINT `penerimaan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penerimaan`
--

LOCK TABLES `penerimaan` WRITE;
/*!40000 ALTER TABLE `penerimaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `penerimaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penerimaan_items`
--

DROP TABLE IF EXISTS `penerimaan_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penerimaan_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `qty_klaim` int(11) NOT NULL DEFAULT 0,
  `qty_lolos` int(11) NOT NULL DEFAULT 0,
  `qty_rusak` int(11) NOT NULL DEFAULT 0,
  `catatan` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penerimaan_items_penerimaan_id_foreign` (`penerimaan_id`),
  KEY `penerimaan_items_product_id_foreign` (`product_id`),
  KEY `penerimaan_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `penerimaan_items_penerimaan_id_foreign` FOREIGN KEY (`penerimaan_id`) REFERENCES `penerimaan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penerimaan_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penerimaan_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penerimaan_items`
--

LOCK TABLES `penerimaan_items` WRITE;
/*!40000 ALTER TABLE `penerimaan_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `penerimaan_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_prices`
--

DROP TABLE IF EXISTS `product_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `level` varchar(20) NOT NULL,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `price` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_prices_product_id_level_min_qty_unique` (`product_id`,`level`,`min_qty`),
  CONSTRAINT `product_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_prices`
--

LOCK TABLES `product_prices` WRITE;
/*!40000 ALTER TABLE `product_prices` DISABLE KEYS */;
INSERT INTO `product_prices` VALUES (1,1,'user',1,129000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(2,1,'reseller',6,89000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(3,2,'user',1,199000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(4,2,'reseller',6,149000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(5,3,'user',1,229000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(6,3,'reseller',6,169000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(7,4,'user',1,349000,'2026-10-05 01:24:28','2026-10-05 01:24:28'),(8,4,'reseller',6,269000,'2026-10-05 01:24:28','2026-10-05 01:24:28');
/*!40000 ALTER TABLE `product_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_pricing`
--

DROP TABLE IF EXISTS `product_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `original_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'CNY',
  `exchange_rate` decimal(15,2) NOT NULL DEFAULT 1.00,
  `shipping_cost_share` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_share` decimal(15,2) NOT NULL DEFAULT 0.00,
  `hpp_final` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retail_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `reseller_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_pricing`
--

LOCK TABLES `product_pricing` WRITE;
/*!40000 ALTER TABLE `product_pricing` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_pricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `ukuran` varchar(20) NOT NULL DEFAULT 'All Size',
  `warna` varchar(30) NOT NULL DEFAULT '-',
  `stok` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_product_id_ukuran_warna_unique` (`product_id`,`ukuran`,`warna`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'All Size','-',114,'BB-1-OS','2026-10-02 06:30:18','2026-10-06 03:06:32'),(2,2,'All Size','-',45,'BB-2-OS','2026-10-02 06:30:18','2026-10-06 23:42:01'),(3,3,'All Size','-',57,'BB-3-OS','2026-10-02 06:30:18','2026-10-04 06:21:39'),(4,4,'All Size','-',19,'BB-4-OS','2026-10-02 06:30:18','2026-10-02 08:42:02'),(6,1,'S','Hitam',10,'BB-1-2','2026-10-02 06:36:59','2026-10-02 06:36:59'),(7,1,'M','Hitam',8,'BB-1-3','2026-10-02 06:37:00','2026-10-03 13:01:30'),(8,1,'L','Hitam',10,'BB-1-4','2026-10-02 06:37:00','2026-10-02 22:44:25'),(9,7,'All Size','Pink',20,'HQ-001-1','2026-10-06 21:53:08','2026-10-13 03:37:30'),(10,7,'All Size','Kuning',20,'HQ-001-2','2026-10-06 21:53:08','2026-10-06 23:42:01'),(11,7,'All Size','Toska',20,'HQ-001-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(12,7,'All Size','Hitam',19,'HQ-001-4','2026-10-06 21:53:08','2026-10-25 00:01:21'),(13,8,'All Size','Merah Apel',20,'HQ-002-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(14,8,'All Size','Pink Super Girl',20,'HQ-002-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(15,8,'All Size','Kuning Flower',20,'HQ-002-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(16,8,'All Size','Ungu Nice',20,'HQ-002-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(17,9,'All Size','Hitam',20,'HQ-004-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(18,9,'All Size','Silver',20,'HQ-004-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(19,9,'All Size','Biru Muda',20,'HQ-004-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(20,10,'All Size','Pink',20,'HQ-006-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(21,10,'All Size','Hitam',20,'HQ-006-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(22,10,'All Size','Bronze',20,'HQ-006-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(23,10,'All Size','Putih',20,'HQ-006-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(24,11,'All Size','Mangga',20,'HQ-012-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(25,11,'All Size','Yummy',20,'HQ-012-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(26,11,'All Size','Peach',20,'HQ-012-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(27,11,'All Size','Apel',20,'HQ-012-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(28,12,'All Size','Biru',20,'HQ-014-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(29,12,'All Size','Pink',20,'HQ-014-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(30,12,'All Size','Kuning',20,'HQ-014-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(31,12,'All Size','Biru Tua',20,'HQ-014-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(32,13,'All Size','Skateboard',20,'HQ-021-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(33,13,'All Size','Kelinci',20,'HQ-021-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(34,13,'All Size','Pisang',20,'HQ-021-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(35,13,'All Size','Lucky',20,'HQ-021-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(36,14,'All Size','Kelinci',20,'HQ-031-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(37,14,'All Size','Kucing',20,'HQ-031-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(38,14,'All Size','Kapibara',20,'HQ-031-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(39,14,'All Size','Anjing',20,'HQ-031-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(40,15,'All Size','Kuning Jeruk',20,'HQ-034-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(41,15,'All Size','Sakura',20,'HQ-034-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(42,15,'All Size','Hitam Abu',20,'HQ-034-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(43,15,'All Size','Biru Muda',20,'HQ-034-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(44,16,'All Size','Hijau',20,'HQ-035-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(45,16,'All Size','Putih',20,'HQ-035-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(46,16,'All Size','Kuning',20,'HQ-035-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(47,16,'All Size','Ungu',20,'HQ-035-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(48,17,'All Size','Kuning',20,'HQ-039-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(49,17,'All Size','Abu',20,'HQ-039-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(50,17,'All Size','Hijau',20,'HQ-039-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(51,17,'All Size','Ungu',20,'HQ-039-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(52,18,'All Size','Vegetable',20,'HQ-039B-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(53,18,'All Size','Tomato',20,'HQ-039B-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(54,18,'All Size','Strawberry',20,'HQ-039B-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(55,19,'All Size','Puppy',20,'HQ-051-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(56,19,'All Size','Kucing',20,'HQ-051-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(57,19,'All Size','Merah',20,'HQ-051-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(58,19,'All Size','Pink',20,'HQ-051-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(59,20,'All Size','Kuning',20,'HQ-056-1','2026-10-06 21:53:08','2026-10-06 21:53:08'),(60,20,'All Size','Pink',20,'HQ-056-2','2026-10-06 21:53:08','2026-10-06 21:53:08'),(61,20,'All Size','Putih',20,'HQ-056-3','2026-10-06 21:53:08','2026-10-06 21:53:08'),(62,20,'All Size','Hitam',20,'HQ-056-4','2026-10-06 21:53:08','2026-10-06 21:53:08'),(63,21,'All Size','Steel',20,'HQ-057-1','2026-10-06 21:53:09','2026-10-06 21:53:09'),(64,21,'All Size','Putih',20,'HQ-057-2','2026-10-06 21:53:09','2026-10-06 21:53:09'),(65,22,'All Size','Hijau Puff',17,'HQ-085-1','2026-10-06 21:53:09','2026-10-08 08:34:07'),(66,23,'All Size','Sweet',20,'HQ-091-1','2026-10-06 21:53:09','2026-10-06 21:53:09'),(67,23,'All Size','Puppy',20,'HQ-091-2','2026-10-06 21:53:09','2026-10-06 21:53:09'),(68,23,'All Size','Beruang',20,'HQ-091-3','2026-10-06 21:53:09','2026-10-06 21:53:09'),(69,23,'All Size','Silver',20,'HQ-091-4','2026-10-06 21:53:09','2026-10-06 21:53:09'),(70,24,'All Size','Steel 09',20,'HQ-093-1','2026-10-06 21:53:09','2026-10-06 21:53:09'),(71,25,'All Size','Pink',23,'HQ-096-1','2026-10-06 21:53:09','2026-10-25 00:05:52'),(72,25,'All Size','Krem',20,'HQ-096-2','2026-10-06 21:53:09','2026-10-06 21:53:09'),(73,25,'All Size','Merah',20,'HQ-096-3','2026-10-06 21:53:09','2026-10-06 21:53:09'),(74,25,'All Size','Kuning',20,'HQ-096-4','2026-10-06 21:53:09','2026-10-06 21:53:09'),(75,26,'All Size','Macan',20,'HQ-097-1','2026-10-06 21:53:09','2026-10-06 21:53:09'),(76,26,'All Size','Sapi',20,'HQ-097-2','2026-10-06 21:53:09','2026-10-06 21:53:09'),(77,26,'All Size','Kucing',20,'HQ-097-3','2026-10-06 21:53:09','2026-10-06 21:53:09'),(78,26,'All Size','Panda',20,'HQ-097-4','2026-10-06 21:53:09','2026-10-06 21:53:09'),(79,27,'All Size','Panda',20,'HQ-098-1','2026-10-06 21:53:09','2026-10-06 21:53:09'),(80,27,'All Size','Kelinci',20,'HQ-098-2','2026-10-06 21:53:09','2026-10-06 21:53:09'),(81,27,'All Size','Kucing',20,'HQ-098-3','2026-10-06 21:53:09','2026-10-06 21:53:09'),(82,27,'All Size','Anjing',20,'HQ-098-4','2026-10-06 21:53:09','2026-10-06 21:53:09');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(50) NOT NULL DEFAULT 'Fashion',
  `foto` varchar(255) DEFAULT NULL,
  `berat_gram` int(11) NOT NULL DEFAULT 250,
  `panjang_cm` int(11) NOT NULL DEFAULT 0,
  `lebar_cm` int(11) NOT NULL DEFAULT 0,
  `tinggi_cm` int(11) NOT NULL DEFAULT 0,
  `stok` int(11) NOT NULL DEFAULT 0,
  `harga_enduser` int(11) NOT NULL DEFAULT 0,
  `harga_reseller` int(11) NOT NULL DEFAULT 0,
  `min_reseller` int(11) NOT NULL DEFAULT 6,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Kaos Dry Fit Pria (Uniqlo style)','Kaos adem, cocok harian.','Fashion',NULL,200,0,0,0,142,129000,89000,6,0,'2026-10-02 05:23:12','2026-10-25 00:22:48','2026-10-25 00:22:48'),(2,'Kemeja Flannel Kotak','Flannel premium.','Fashion',NULL,300,0,0,0,45,199000,149000,6,0,'2026-10-02 05:23:12','2026-10-25 00:22:48','2026-10-25 00:22:48'),(3,'Celana Chino Slim','Chino strech nyaman.','Fashion',NULL,400,0,0,0,57,229000,169000,6,0,'2026-10-02 05:23:12','2026-10-25 00:22:48','2026-10-25 00:22:48'),(4,'Jaket Parka Hangat','Parka tebal anti angin.','Fashion',NULL,800,0,0,0,19,349000,269000,6,0,'2026-10-02 05:23:12','2026-10-25 00:22:48','2026-10-25 00:22:48'),(7,'HQ-001 Botol Chicutom Sedotan','Botol minum sedotan, 4 pilihan warna.','Tumbler','produk/HQ-001.jpg',500,0,0,0,79,129000,99000,6,1,'2026-10-06 21:53:08','2026-10-25 00:01:21',NULL),(8,'HQ-002 Mug Anak Karakter','Mug anak motif Oh!e, Super Girl, Flower, Nice.','Tumbler','produk/HQ-002.jpg',400,0,0,0,80,99000,79000,6,1,'2026-10-06 21:53:08','2026-10-08 08:32:33',NULL),(9,'HQ-004 Tumbler OZBOBO Gantungan','Tumbler tali gantungan, 3 warna.','Tumbler','produk/HQ-004.jpg',500,0,0,0,60,149000,119000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(10,'HQ-006 Botol Sport ChiaTom Handle','Botol sport gagang pegangan.','Tumbler','produk/HQ-006.jpg',500,0,0,0,80,139000,109000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(11,'HQ-012 Tumbler Cheer You Buah','Tumbler motif buah Mango, Yummy, Peach, Apple.','Tumbler','produk/HQ-012.jpg',500,0,0,0,80,119000,89000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(12,'HQ-014 Botol Mini Chicutom Tutup Cangkir','Botol mini tutup model cangkir.','Tumbler','produk/HQ-014.jpg',300,0,0,0,80,89000,69000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(13,'HQ-021 Tumbler Es Fantasi 1000mL','Kapasitas 1000mL. Lapisan keramik, baja 316, PP, silikon.','Tumbler','produk/HQ-021.jpg',800,0,0,0,80,159000,129000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(14,'HQ-031 Mug Sweet Karakter','Mug motif Kelinci, Kucing, Kapibara, Anjing.','Tumbler','produk/HQ-031.jpg',400,0,0,0,80,99000,79000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(15,'HQ-034 Tumbler Isolasi Sedotan Gradasi 780mL','Kapasitas 780mL. Stainless 304/316 + lapisan keramik.','Tumbler','produk/HQ-034.jpg',600,0,0,0,80,149000,119000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(16,'HQ-035 Botol Mini Chicu Tom','Botol mini 4 warna.','Tumbler','produk/HQ-035.jpg',300,0,0,0,80,89000,69000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(17,'HQ-039 Mug Chicu Tom Polos','Mug polos 4 warna.','Tumbler','produk/HQ-039.jpg',400,0,0,0,80,99000,79000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(18,'HQ-039B Mug Sayur Chicu Tom','Mug motif sayur Vegetable, Tomato, Strawberry.','Tumbler','produk/HQ-039B.jpg',400,0,0,0,60,99000,79000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(19,'HQ-051 Botol Happy Kids Manik','Botol anak tali manik Happy Kids.','Tumbler','produk/HQ-051.jpg',400,0,0,0,80,109000,85000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(20,'HQ-056 Botol Boneka Salju Chicu Tom','Botol boneka salju 4 warna.','Tumbler','produk/HQ-056.jpg',400,0,0,0,80,109000,85000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:08',NULL),(21,'HQ-057 Tumbler Chicu Tom Gagang','Tumbler gagang untuk belajar & bekerja.','Tumbler','produk/HQ-057.jpg',600,0,0,0,40,139000,109000,6,1,'2026-10-06 21:53:08','2026-10-06 21:53:09',NULL),(22,'HQ-085 Mug Puff Hijau 500mL Keramik','Kapasitas 500mL. Lapisan dalam keramik.','Tumbler','produk/HQ-085.jpg',500,0,0,0,17,119000,95000,6,1,'2026-10-06 21:53:09','2026-10-08 08:34:07',NULL),(23,'HQ-091 Botol Apel Happy Puppy','Botol tutup apel Happy Puppy.','Tumbler','produk/HQ-091.jpg',400,0,0,0,80,109000,85000,6,1,'2026-10-06 21:53:09','2026-10-06 21:53:09',NULL),(24,'HQ-093 Tumbler Kantor Chicutom','Tumbler steel untuk belajar & bekerja.','Tumbler','produk/HQ-093.jpg',600,0,0,0,20,149000,119000,6,1,'2026-10-06 21:53:09','2026-10-06 21:53:09',NULL),(25,'HQ-096 Botol Mini Tali Kepang','Botol mini tali kepang.','Tumbler','produk/HQ-096.jpg',300,0,0,0,83,89000,69000,6,1,'2026-10-06 21:53:09','2026-10-25 00:05:52',NULL),(26,'HQ-097 Botol Minum IP Hewan 480mL','Kapasitas 480mL. Bagian dalam 304 + dasar 316, PP.','Tumbler','produk/HQ-097.jpg',400,0,0,0,80,119000,95000,6,1,'2026-10-06 21:53:09','2026-10-06 21:53:09',NULL),(27,'HQ-098 Tumbler Hewan Gantungan','Tumbler gantungan motif Panda, Kelinci, Kucing, Anjing.','Tumbler','produk/HQ-098.jpg',500,0,0,0,80,109000,85000,6,1,'2026-10-06 21:53:09','2026-10-06 21:53:09',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retur_items`
--

DROP TABLE IF EXISTS `retur_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retur_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `retur_id` bigint(20) unsigned NOT NULL,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retur_items_retur_id_foreign` (`retur_id`),
  KEY `retur_items_order_item_id_foreign` (`order_item_id`),
  CONSTRAINT `retur_items_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `retur_items_retur_id_foreign` FOREIGN KEY (`retur_id`) REFERENCES `returs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retur_items`
--

LOCK TABLES `retur_items` WRITE;
/*!40000 ALTER TABLE `retur_items` DISABLE KEYS */;
INSERT INTO `retur_items` VALUES (1,1,3,1,'2026-10-02 08:13:11','2026-10-02 08:13:11');
/*!40000 ALTER TABLE `retur_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `returs`
--

DROP TABLE IF EXISTS `returs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `returs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `alasan` text NOT NULL,
  `kategori` varchar(30) NOT NULL DEFAULT 'retur',
  `foto` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'diajukan',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `returs_order_id_foreign` (`order_id`),
  KEY `returs_user_id_foreign` (`user_id`),
  CONSTRAINT `returs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `returs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returs`
--

LOCK TABLES `returs` WRITE;
/*!40000 ALTER TABLE `returs` DISABLE KEYS */;
INSERT INTO `returs` VALUES (1,3,3,'Size kebesaran','retur',NULL,'selesai','Barang diterima, dana diproses','2026-10-02 08:13:11','2026-10-02 08:13:36');
/*!40000 ALTER TABLE `returs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `ulasan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_order_id_product_id_user_id_unique` (`order_id`,`product_id`,`user_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0CNSF4coXsvBYI1mFNfzPVz9MRohDdMnswVx2u5p',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoia1I3MGVmaG1tMjhvMHlTQldQbGZiN0hVWFU1N0tVdDNxZDBhVEZENyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565015),('3KI2Gj9ASmS8w5LHThBIubZenfcN0Phi2EhH0moQ',NULL,'2400:9800:c60:f8e6:152:80a5:7136:4355','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaktMTm1DT0VXNDB1U3pGRm1wZ1l1TXN6am03cm5WRTRTclpMNHlHYyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU2OiJodHRwczovL3RyaWFscy1yZWxheGF0aW9uLXd3dy1wcmV2aWV3cy50cnljbG91ZGZsYXJlLmNvbSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1792565502),('3X8cay0hsJg4TTCKnmWPoescRLDtpnCikJNItEgK',NULL,'2400:9800:c60:f8e6:152:80a5:7136:4355','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVFRyMmxBVXNHVzR5RU5sMFZnbnE3SjJIOEFURzdWdFVBT1hhYUowTiI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjY1OiJodHRwczovL3RyaWFscy1yZWxheGF0aW9uLXd3dy1wcmV2aWV3cy50cnljbG91ZGZsYXJlLmNvbS9yZWdpc3RlciI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1792565505),('6XRjgIZOuDkXyfcitMy5qTM000u533iiDW55Ezdn',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicUwxWHZjYm9wMEdiaGowMXppU2tTR21zUmZVRHhseXVRY1F6eEpVUyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792912984),('BcG5682SwbHbd5iFsUhC37PxjVQ0TidcWwiZtHTQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkxRaTlhZHZJNlhQMTZMRWpLV1l5d0VJdURlUTFNM1dvQWp3TGRzeCI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565501),('c59BYw6l5GL1IRj8Zdj5b7lOEESxzUY77weRiRx5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMGdRT29UckVLemFZa3cxRUkxR0lzZWlrTWpuTnFxSmNhVTM5Z043SiI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792910043),('cRI4voCwToa6cxgihWyTW6t7vDdAvAVWe63bt5jI',1,'2400:9800:c60:f8e6:152:80a5:7136:4355','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNk1qazJuNEZjcHJQdEhOVVFJeTBFd0xCUEg3UHAxYzBUek9yMVJGbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjc1OiJodHRwczovL3RyaWFscy1yZWxheGF0aW9uLXd3dy1wcmV2aWV3cy50cnljbG91ZGZsYXJlLmNvbS9hZG1pbi9vcG5hbWUvMT9wPTAiO3M6NToicm91dGUiO047fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6Ijk0OTZlNDE4N2U3M2Y2MGI0MDM1MTI3ZGI5YTZhYTg4NjYzMWQxZWY2YjgzM2QzOTBhNzQxZjE4MTU0Nzg0ODQiO30=',1792566189),('FEYYUvqRkkt5OGXd9A35kHjW7K55F8KlwpyxkAHT',NULL,'10.10.11.57','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRnVmZ2prVTBUcEhMZHNqYWZTMEpsZHpRUERKMUh2OHJsM2ZwcHZVbyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTAuMTAuMTEuNTcvcHJvamVrJTIwd3AzL3B1YmxpYyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1792909160),('gqcrpyCwrqBizBtviY8AlNSHUJrx5GtDJJ0lEivd',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY1VITmpuY0Q2bzl1NGM4YlFpeFR3YXB0VDAxMWVEZll6UkpsU3RpSyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHJvZHVrLzc/dGFtcGlsYW49d2ViIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1792911477),('hentNRhs0G7NYRUkGdUkSk7eubvijWzc7o7GvcX3',NULL,'2400:9800:c60:f8e6:152:80a5:7136:4355','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXVsSEk0MWV1dHl6VU1QTFZDSHZBTUk5cURSbG41bFBDd2pQdnFISSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjYyOiJodHRwczovL3RyaWFscy1yZWxheGF0aW9uLXd3dy1wcmV2aWV3cy50cnljbG91ZGZsYXJlLmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565504),('juPHLwEFPS74a2UADdHjDohjXZ3HCOLwUaL9KeuY',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0RhVGZEU2RYSWhDT21NQkk3OTFrZHBNdW9HNzFDMFlDZTJ2YTNsZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWsvMSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1792912983),('MoPd7MS1JShj837Fga5VupkY7GfajlGmq0MmFQZt',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYVFrVnczckpScW1iQ1lmM3NCS2tma0IxajhhRENUQlZQa2hPR0F5ZSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vbG9jYWxob3N0L3Byb2playUyMHdwMy9wdWJsaWMvaW5kZXgucGhwIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1792909169),('nTlkaZ64Y5tjzk4LzP0sX9iLdQYo8DnsciFKpYo3',1,'103.154.139.190','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMmRXZkwyakkwSEd1Yk5SaUpPMWt6cFdQYnZQejhxTnI4TU9yUjNQbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjY4OiJodHRwczovL3JhdGlvbmFsLWhhbG8tZWFycmluZ3Mtc2ltaWxhcmx5LnRyeWNsb3VkZmxhcmUuY29tL3Byb2R1ay8yNyI7czo1OiJyb3V0ZSI7Tjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiOTQ5NmU0MTg3ZTczZjYwYjQwMzUxMjdkYjlhNmFhODg2NjMxZDFlZjZiODMzZDM5MGE3NDFmMTgxNTQ3ODQ4NCI7fQ==',1792913293),('nyW9AdXSeqlLUSjT97X1XF3KD8V0I3TAxQdmpkut',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS043cWE3aDNwQzBPa1FzbGN1eVFxRlo4SXRPc294WTE2cHhLdkx6UyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0L3Byb2playUyMHdwMy9wdWJsaWMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792909160),('OShgqpqd9DSWQSSN6EiqOqEXM40WcTerLxPG3sDQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNW9UcUI2MEhjRDZHd3Y3dGx4M1JFQkh2NElKZ25IekMzZUJUOFp2YyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565014),('Pgx8LvxkFtU1jlxrQBAAAtKDqViKgaDTE291HxTE',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTkNzekZEVHJFbExKeHN4ZmxOUExhWkdLTEIyT01Ldm9EQ0VOM3lQUSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565043),('qJy3p1nVyF9UB9BBguuPeZ4Vb6MKxjxSqD3nHlE8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMFlaZ0VYNGs3cnpybmx6Uk5yTWlLMGRRSzhwNkZ2S2ExM0FrV1NqMyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565370),('RLoZVqKliz5m78D33x5QwUSqBUnPx5PryiHCzjHb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoianZ1UVRxTTFOQjBaakUxWmVhNlNIbTZlMTZsaU5nVHp0bE5PQzVLYSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565370),('sPx69vNDOWy3avkW7PYA4m3DmflTIyO62kgg6LQr',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0RHRjBYN2daZG1zT0hXOTN5MUhGN1VieWtncElVV0loVXlKazlQcSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0L3Byb2playUyMHdwMy9wdWJsaWMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792909169),('sSFxv6MygLh5UWh4nRGrMXE6TQVvM1hWk3miXmeS',NULL,'2400:9800:c60:f8e6:152:80a5:7136:4355','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaFBaZ0F6bVpGcDkzU2pkU2M5N1d1ZElPZUFGUkxaZzJvNjdHbjZsbSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU2OiJodHRwczovL3RyaWFscy1yZWxheGF0aW9uLXd3dy1wcmV2aWV3cy50cnljbG91ZGZsYXJlLmNvbSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1792565474),('TM1M8DVTgwa5uzBp0WFobywGrpplc8pCKuI888Sj',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibEhzY2xOMXdpR3ZFS25lbEowZ0oxVmdiaWZUVWRrS1VCWVRPWTYyTSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0L3Byb2playUyMHdwMy9wdWJsaWMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565029),('v0BOiZXn69zqX6PN8dTYMGaNbmdPzuwy7fFP8sIz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaXpkaWxhRFdvcUVtVEdEQ0dSbU05WmtTQkI0TEtpRkozeUpsY2NLRiI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1792565090),('vH63SiGcAlWeZPIdXb8xBrDWvHw291a1USWYxbF2',NULL,'103.154.139.190','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOGV5TlJYVDZxUGZWWWFrQnBqZEo0NVp6WHR1dk5CZWNQY0drU1h2MyI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjgwOiJodHRwczovL3JhdGlvbmFsLWhhbG8tZWFycmluZ3Mtc2ltaWxhcmx5LnRyeWNsb3VkZmxhcmUuY29tL3Byb2R1ay83P3RhbXBpbGFuPXdlYiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1792911480),('yazn2gK6oAJuP45XFP0RDhkR0swEyQFiCUA38s2v',NULL,'2400:9800:c60:f8e6:152:80a5:7136:4355','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9549','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRFZsb1ZFa2lRdUtDb1BhRXZLSkJhNElQVk5QTHRab1FHSWtQR3ByYSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjYyOiJodHRwczovL3RyaWFscy1yZWxheGF0aW9uLXd3dy1wcmV2aWV3cy50cnljbG91ZGZsYXJlLmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792565513),('yQJOOPqhk754WrwmdsboaFBCYIZa7KUdpEvbBPgS',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieW0xUFBmWXhSR204bEJmdkloeDJoMTdtVGJuUEdCU1B5OUFSOWdHYSI7czo4OiJ0YW1waWxhbiI7czozOiJ3ZWIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0L3Byb2playUyMHdwMy9wdWJsaWMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792909168);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'nama_toko','Bubee','2026-10-02 05:57:10','2026-10-02 05:57:10'),(2,'wa_admin','6281234567890','2026-10-02 05:57:10','2026-10-02 05:57:10'),(3,'target_harian','1000000','2026-10-02 05:57:10','2026-10-02 05:57:10'),(4,'pengumuman','','2026-10-02 05:57:10','2026-10-02 05:57:10'),(5,'fonnte_token','','2026-10-06 02:14:09','2026-10-06 02:14:09'),(6,'turnstile_wajib','1','2026-10-06 02:14:09','2026-10-13 22:22:47'),(7,'turnstile_site_key','1x00000000000000000000AA','2026-10-06 02:14:10','2026-10-13 22:27:17'),(8,'turnstile_secret','1x0000000000000000000000000000000AA','2026-10-06 02:14:10','2026-10-13 22:27:17'),(9,'rajaongkir_key','','2026-10-06 02:14:10','2026-10-06 02:14:10'),(10,'rajaongkir_origin_city','','2026-10-06 02:14:10','2026-10-06 02:14:10'),(11,'rajaongkir_origin_name','','2026-10-06 02:14:10','2026-10-06 02:14:10'),(12,'demo_mode','0','2026-10-06 09:28:29','2026-10-06 03:06:32'),(13,'bank_nama','BCA','2026-10-13 22:23:12','2026-10-13 22:23:12'),(14,'bank_rekening','1234567890','2026-10-13 22:23:12','2026-10-13 22:23:12'),(15,'bank_atas_nama','Toko Bubee','2026-10-13 22:23:12','2026-10-13 22:23:12');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_mutations`
--

DROP TABLE IF EXISTS `stock_mutations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_mutations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `tipe` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `stok_sebelum` int(11) NOT NULL DEFAULT 0,
  `stok_sesudah` int(11) NOT NULL DEFAULT 0,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_mutations_product_id_foreign` (`product_id`),
  KEY `stock_mutations_variant_id_foreign` (`variant_id`),
  CONSTRAINT `stock_mutations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_mutations_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_mutations`
--

LOCK TABLES `stock_mutations` WRITE;
/*!40000 ALTER TABLE `stock_mutations` DISABLE KEYS */;
INSERT INTO `stock_mutations` VALUES (3,1,6,'masuk',10,0,10,'Varian baru oleh admin','2026-10-02 06:36:59','2026-10-02 06:36:59'),(4,1,7,'masuk',10,0,10,'Varian baru oleh admin','2026-10-02 06:37:00','2026-10-02 06:37:00'),(5,1,8,'masuk',10,0,10,'Varian baru oleh admin','2026-10-02 06:37:00','2026-10-02 06:37:00'),(6,1,7,'keluar',2,10,8,'Order BB-2026100213371874 (M / Hitam)','2026-10-02 06:37:18','2026-10-02 06:37:18'),(7,1,7,'masuk',2,148,150,'Batal user BB-2026100213371874','2026-10-02 06:37:37','2026-10-02 06:37:37'),(8,1,1,'keluar',1,120,119,'Order BB-2026100213470657 (All Size)','2026-10-02 06:47:06','2026-10-02 06:47:06'),(9,2,2,'keluar',1,45,44,'Order BB-2026100215114144 (All Size)','2026-10-02 08:11:41','2026-10-02 08:11:41'),(10,2,2,'masuk',1,44,45,'Retur selesai BB-2026100215114144','2026-10-02 08:13:36','2026-10-02 08:13:36'),(11,3,3,'keluar',1,60,59,'Order BB-2026100215420222 (All Size)','2026-10-02 08:42:02','2026-10-02 08:42:02'),(12,4,4,'keluar',1,20,19,'Order BB-2026100215420222 (All Size)','2026-10-02 08:42:02','2026-10-02 08:42:02'),(13,3,3,'keluar',1,59,58,'Order BB-2026100216011811 (All Size)','2026-10-02 09:01:18','2026-10-02 09:01:18'),(14,1,8,'keluar',1,10,9,'Order BB-2026100305435633 (L / Hitam)','2026-10-02 22:43:56','2026-10-02 22:43:56'),(15,1,8,'masuk',1,148,149,'Batal user BB-2026100305435633','2026-10-02 22:44:25','2026-10-02 22:44:25'),(16,1,7,'keluar',1,10,9,'Order BB-2026100305501417 (M / Hitam)','2026-10-02 22:50:14','2026-10-02 22:50:14'),(17,1,7,'keluar',1,9,8,'Order BB-2026100308360617 (M / Hitam)','2026-10-03 01:36:06','2026-10-03 01:36:06'),(18,1,7,'keluar',1,8,7,'Order BB-2026100309285568 (M / Hitam)','2026-10-03 02:28:55','2026-10-03 02:28:55'),(19,1,7,'masuk',1,146,147,'Batal user BB-2026100308360617','2026-10-03 13:01:30','2026-10-03 13:01:30'),(20,1,1,'keluar',6,119,113,'Order BB-2026100320102569 (All Size)','2026-10-03 13:10:25','2026-10-03 13:10:25'),(21,1,1,'keluar',6,113,107,'Order BB-2026100320111482 (All Size)','2026-10-03 13:11:14','2026-10-03 13:11:14'),(22,1,1,'masuk',6,135,141,'Batal user BB-2026100320111482','2026-10-03 13:11:43','2026-10-03 13:11:43'),(23,1,1,'masuk',1,141,142,'Batal user BB-2026100213470657','2026-10-03 13:14:38','2026-10-03 13:14:38'),(24,3,3,'keluar',1,58,57,'Order BB-2026100413213973 (All Size)','2026-10-04 06:21:39','2026-10-04 06:21:39'),(25,1,1,'masuk',6,142,148,'Batal user BB-2026100320102569','2026-10-04 21:35:18','2026-10-04 21:35:18'),(26,1,1,'keluar',6,120,114,'Order BB-2026100508200231 (All Size)','2026-10-05 01:20:02','2026-10-05 01:20:02'),(27,2,NULL,'keluar',954,999,45,'Rekonsiliasi otomatis','2026-10-06 01:17:45','2026-10-06 01:17:45'),(28,1,1,'keluar',1,114,113,'Order DEMO-2026100609382764 (All Size)','2026-10-06 02:38:27','2026-10-06 02:38:27'),(29,1,1,'keluar',1,113,112,'Order DEMO-2026100609400947 (All Size)','2026-10-06 02:40:09','2026-10-06 02:40:09'),(30,1,1,'keluar',1,112,111,'Order DEMO-2026100609550642 (All Size)','2026-10-06 02:55:06','2026-10-06 02:55:06'),(31,1,1,'masuk',1,139,140,'Bersih revert demo DEMO-2026100609382764','2026-10-06 03:06:32','2026-10-06 03:06:32'),(32,1,1,'masuk',1,140,141,'Bersih revert demo DEMO-2026100609400947','2026-10-06 03:06:32','2026-10-06 03:06:32'),(33,1,1,'masuk',1,141,142,'Bersih revert demo DEMO-2026100609550642','2026-10-06 03:06:32','2026-10-06 03:06:32'),(34,2,2,'keluar',32,45,13,'Order BB-2026100706412316 (All Size)','2026-10-06 23:41:23','2026-10-06 23:41:23'),(35,7,10,'keluar',1,20,19,'Order BB-2026100706412316 (All Size / Kuning)','2026-10-06 23:41:23','2026-10-06 23:41:23'),(36,2,2,'masuk',32,13,45,'Batal user BB-2026100706412316','2026-10-06 23:42:01','2026-10-06 23:42:01'),(37,7,10,'masuk',1,79,80,'Batal user BB-2026100706412316','2026-10-06 23:42:01','2026-10-06 23:42:01'),(38,22,65,'keluar',3,20,17,'Update stok varian oleh admin','2026-10-08 08:34:07','2026-10-08 08:34:07'),(39,25,71,'masuk',3,20,23,'Update stok varian oleh admin','2026-10-13 01:29:17','2026-10-13 01:29:17'),(40,7,9,'keluar',1,20,19,'Order BB-2026101310345245 (All Size / Pink)','2026-10-13 03:34:52','2026-10-13 03:34:52'),(41,7,9,'masuk',1,79,80,'Batal user BB-2026101310345245','2026-10-13 03:37:30','2026-10-13 03:37:30'),(42,7,12,'keluar',1,20,19,'Order BB-2026102507012160 (All Size / Hitam)','2026-10-25 00:01:21','2026-10-25 00:01:21'),(43,25,71,'keluar',10,23,13,'Order BB-2026102507033872 (All Size / Pink)','2026-10-25 00:03:38','2026-10-25 00:03:38'),(44,25,71,'masuk',10,73,83,'Batal user BB-2026102507033872','2026-10-25 00:05:52','2026-10-25 00:05:52');
/*!40000 ALTER TABLE `stock_mutations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'enduser',
  `status_reseller` varchar(255) NOT NULL DEFAULT 'none',
  `hp` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `toko_nama` varchar(60) DEFAULT NULL,
  `nik` varchar(25) DEFAULT NULL,
  `notif_wa` tinyint(1) NOT NULL DEFAULT 1,
  `ktp_foto` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `daftar_coba` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_hp_unique` (`hp`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Bubee','admin@bubee.id','admin','none','6281234567890',NULL,NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$4krEr/jBPQmleWm2frn1rehndGHQ2C7ctU.h4smboA7105rIgRdgK','R8Uw16LJaFcnjXYwduy1Ycw8DSGZi0C3ucwORSViR38SStg7L1jxekQ1B0tM','2026-10-02 05:23:11','2026-10-06 21:53:07'),(2,'Reseller Demo','reseller@bubee.id','reseller','approved','6281234567891','Jl. Reseller No. 1',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$t5olXZ8/tQi4dnW4jTJdJO/BH1JcHKgc/yB.v5cm9vwLt4qMA72va','gQg2VIlFEr0I1mG8ck1S0U05Hktn1ezUedCmvFDZ1hx1SXM72fqwAvmEoULF','2026-10-02 05:23:11','2026-10-06 21:53:08'),(3,'User Demo','user@bubee.id','enduser','none','6281234567892',NULL,NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$CFHEiEH5k.IMBqPjn9lWj.4p0zPr9wMqECx8J08MM5ZVcb4FjrqBK','xgyPvhtZzISla2XQAtub7QALX3WDIDR8pLfyvpxPs9ajETjqUzxahTUpivlJ','2026-10-02 05:23:12','2026-10-06 21:53:08'),(4,'reihan','reihananggaci1@gmail.com','enduser','none','628128897287','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$iSgIPsWveLs1eIKDDcNI/.arMuba8l/pU60x91ELixsDk7K4WpqT6','tUCAG3woMQdo0UESFd9QSMrrHVZgbLCcEzk6QiS9uNsxRa51HQyce6rbyPTa','2026-10-02 05:40:59','2026-10-02 05:40:59'),(5,'gilang','gilangramadanputra26@gmail.com','reseller','approved','6289520351162','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$ZMiI31A3UMhSF1n8PYRLGO/cOUJ.ebNgYhm6yTHj5Yv0.5EpnvXkO','KOanFyleZsF5n5beyD4NVFcraLZkgWAMAHJqcFJZUG5ojckxlXgOg3IdDPgz','2026-10-02 22:48:10','2026-10-03 02:33:19'),(6,'Cristya maya','cristyamaya461@gmail.com','enduser','none','6287895393221','jalan Wonodadi 2',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$l819lcXEyhYYT43lisi3NOtnxXeaVytS.YaW00FNx7MvmXGjItMO.',NULL,'2026-10-03 02:36:16','2026-10-03 02:36:16'),(7,'simfrosa aing','aingsimfrosa@gmail.com','enduser','none','6281528715677','paris 2',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$H8wr6HC0DoI6dvMj5fHYjeXLAfJ7WIvcDjdK/neDni4OQce5Ovpku','EaQixqKQxYKf2QHwTTqETLEp717k5H0CDF36TZzDUWRL0FbAGHxZabvCtF56','2026-10-03 03:09:15','2026-10-03 03:09:15'),(8,'reihan','reihan123@gmail.com','reseller','pending',NULL,'Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$wNNdy4DmMPyi.nJjUxXST.ED1cnd5LytYiNfCfG.Ac358bik.aIOC',NULL,'2026-10-04 23:15:33','2026-10-04 23:15:33'),(9,'koko','reihanmandor@gmail.com','enduser','none',NULL,'Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124',NULL,NULL,1,NULL,'2026-10-06 20:31:51',1,'$2y$12$oYE2rYnSHI7oSlMuVPV.Aelul35x42At7os00eeyeJ8pEq7Jwv6Ue',NULL,'2026-10-06 02:05:32','2026-10-06 02:05:32'),(14,'hafiz fahrozi','hikarinokokyu@gmail.com','enduser','none','6285750085939','Jl. Abdul Rahman Saleh No.18 A, Bangka Belitung Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124',NULL,NULL,1,NULL,NULL,1,'$2y$12$Nv.LX6JHwJKH207sZRESvemwORAfBwTRwQ9IV9jHse35Ho2DjE5AS',NULL,'2026-10-06 21:01:26','2026-10-06 21:01:26');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vouchers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tipe` varchar(255) NOT NULL DEFAULT 'persen',
  `nilai` int(11) NOT NULL DEFAULT 10,
  `maks_diskon` int(11) NOT NULL DEFAULT 0,
  `min_belanja` int(11) NOT NULL DEFAULT 0,
  `kuota` int(11) NOT NULL DEFAULT 100,
  `terpakai` int(11) NOT NULL DEFAULT 0,
  `khusus` varchar(255) NOT NULL DEFAULT 'semua',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `berlaku_sampai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vouchers_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (1,'HEMAT10','Hemat 10 persen','persen',10,20000,50000,50,2,'semua',1,NULL,'2026-10-02 08:11:18','2026-10-04 06:21:39');
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bubee_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-25 14:40:30
