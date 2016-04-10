SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE IF NOT EXISTS `fileAccesKey` (
`serial` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `fileId` varchar(200) NOT NULL COMMENT 'A file id ja amit a keresre a szerver kuld',
  `useragent` varchar(500) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT 'a kero ip cime',
  `creationStamp` int(11) NOT NULL COMMENT 'A crealas unix timestampja',
  `timeoutStamp` int(11) NOT NULL COMMENT 'Az automatikus timeout unix time Stamp',
  `secretKey` varchar(1024) NOT NULL COMMENT 'A hozzaferes hulcsa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `fileGroupKey` (
`serial` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `fileIdLista` text NOT NULL COMMENT 'A file id lista ["", "", ""]',
  `useragent` varchar(500) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT 'a kero ip cime',
  `creationStamp` int(11) NOT NULL COMMENT 'A crealas unix timestampja',
  `timeoutStamp` int(11) NOT NULL COMMENT 'Az automatikus timeout unix time Stamp',
  `secretKey` varchar(1024) NOT NULL COMMENT 'A hozzaferes hulcsa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `fileList` (
`serial` bigint(20) NOT NULL,
  `id` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'upload status',
  `creationTimestamp` int(11) NOT NULL COMMENT 'a bejegyzes kreallasanak unix timestampja',
  `size` int(11) NOT NULL COMMENT 'file merete',
  `type` varchar(200) NOT NULL COMMENT 'file tipusa',
  `filePieceNumber` int(200) NOT NULL COMMENT 'file darabok szama',
  `filePieceNumberStatus` bigint(20) NOT NULL COMMENT '2 es szamrendszerbe megadott file darabok osszesitese',
  `uploadFinishedStamp` int(11) NOT NULL COMMENT 'A file feltoltes befejezesenek datuma',
  `access` int(11) NOT NULL COMMENT 'hozza feres egyenlore 0 publikus 1 privat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `fileMasterKey` (
`serial` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `useragent` varchar(500) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT 'a kero ip cime',
  `creationStamp` int(11) NOT NULL COMMENT 'A crealas unix timestampja',
  `timeoutStamp` int(11) NOT NULL COMMENT 'Az automatikus timeout unix time Stamp',
  `secretKey` varchar(1024) NOT NULL COMMENT 'A hozzaferes hulcsa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `logAccess` (
`serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `logAccessAccessKey` (
`serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `accessKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `logAccessDeneid` (
`serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `groupKey` varchar(2000) NOT NULL,
  `accessKey` varchar(2000) NOT NULL,
  `masterKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `logAccessGroupKey` (
`serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `groupKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `logAccessMasterKey` (
  `serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `masterKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `serial` (
`serial` bigint(20) unsigned NOT NULL,
  `name` int(11) NOT NULL,
  `value` bigint(20) unsigned NOT NULL,
  `comment` text CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `fileAccesKey`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);


ALTER TABLE `fileGroupKey`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);


ALTER TABLE `fileList`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `fileMasterKey`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `logAccess`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `logAccessAccessKey`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `logAccessDeneid`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `logAccessGroupKey`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `serial`
 ADD PRIMARY KEY (`serial`), ADD KEY `serial` (`serial`);

ALTER TABLE `fileAccesKey`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fileGroupKey`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fileList`
MODIFY `serial` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fileMasterKey`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logAccess`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logAccessAccessKey`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logAccessDeneid`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logAccessGroupKey`
MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `serial`
MODIFY `serial` bigint(20) unsigned NOT NULL AUTO_INCREMENT;