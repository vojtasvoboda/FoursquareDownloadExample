SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `foursquare` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;

USE `foursquare`;

CREATE TABLE IF NOT EXISTS `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `contact_formattedPhone` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `contact_twitter` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_address` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_crossStreet` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_lat` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_lng` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_distance` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_postalCode` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_cc` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_city` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_state` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_country` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_formattedAddress_0` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `location_formattedAddress_1` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `categories_0_id` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `categories_0_name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  `stats_checkinsCount` int(11) DEFAULT NULL,
  `stats_usersCount` int(11) DEFAULT NULL,
  `stats_tipCount` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `specials_count` int(11) DEFAULT NULL,
  `specials_items` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `hereNow_count` int(11) DEFAULT NULL,
  `hereNow_summary` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `referralId` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
