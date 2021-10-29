CREATE TABLE IF NOT EXISTS database_shoper_distance_api.`office`
(
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `city` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `street` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `office` (`city`, `street`, `latitude`, `longitude`) VALUES
('Szczecin', 'Cyfrowa 8', '53.45086095', '14.53644447'),
('Kraków', 'Pawia 9', '50.07048609', '19.94635587');
