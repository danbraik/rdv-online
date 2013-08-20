-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'account''s id',
  `mail` varchar(255) NOT NULL COMMENT 'login and mail',
  `password` varchar(64) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) DEFAULT CHARSET=utf8 COMMENT='Accounts : secure group of patients' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_account` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `fname` varchar(64) NOT NULL COMMENT 'firstname',
  `phone` varchar(16) DEFAULT '',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COMMENT='Patients, people who be placed in a slot' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `slots`
--

CREATE TABLE IF NOT EXISTS `slots` (
  `id` int(11) NOT NULL COMMENT 'no auto-increment because discontinu id',
  `datetm_start` datetime NOT NULL,
  `id_patient` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COMMENT='Slots of the agenda';

