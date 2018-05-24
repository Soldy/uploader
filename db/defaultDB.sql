
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `uploader`
--

-- --------------------------------------------------------

--
-- Table structure for table `fileAccesKey`
--

CREATE TABLE `fileAccesKey` (
  `serial` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `fileId` varchar(200) NOT NULL COMMENT 'A file id ja amit a keresre a szerver kuld',
  `useragent` varchar(500) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT 'a kero ip cime',
  `creationStamp` int(11) NOT NULL COMMENT 'A crealas unix timestampja',
  `timeoutStamp` int(11) NOT NULL COMMENT 'Az automatikus timeout unix time Stamp',
  `secretKey` varchar(1024) NOT NULL COMMENT 'A hozzaferes hulcsa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fileGroupKey`
--

CREATE TABLE `fileGroupKey` (
  `serial` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `fileIdLista` text NOT NULL COMMENT 'A file id lista ["", "", ""]',
  `useragent` varchar(500) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT 'a kero ip cime',
  `creationStamp` int(11) NOT NULL COMMENT 'A crealas unix timestampja',
  `timeoutStamp` int(11) NOT NULL COMMENT 'Az automatikus timeout unix time Stamp',
  `secretKey` varchar(1024) NOT NULL COMMENT 'A hozzaferes hulcsa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fileList`
--

CREATE TABLE `fileList` (
  `serial` bigint(20) NOT NULL,
  `id` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'upload status',
  `pieceStatus` int(10) UNSIGNED NOT NULL,
  `creationTimestamp` int(11) NOT NULL COMMENT 'a bejegyzes kreallasanak unix timestampja',
  `size` int(11) NOT NULL COMMENT 'file merete',
  `type` varchar(200) NOT NULL COMMENT 'file tipusa',
  `filePieceNumber` int(200) NOT NULL COMMENT 'file darabok szama',
  `filePieceNumberStatus` bigint(20) NOT NULL COMMENT '2 es szamrendszerbe megadott file darabok osszesitese',
  `uploadFinishedStamp` int(11) NOT NULL COMMENT 'A file feltoltes befejezesenek datuma',
  `access` int(11) NOT NULL COMMENT 'hozza feres egyenlore 0 publikus 1 privat',
  `fileName` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fileMasterKey`
--

CREATE TABLE `fileMasterKey` (
  `serial` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `useragent` varchar(500) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT 'a kero ip cime',
  `creationStamp` int(11) NOT NULL COMMENT 'A crealas unix timestampja',
  `timeoutStamp` int(11) NOT NULL COMMENT 'Az automatikus timeout unix time Stamp',
  `secretKey` varchar(1024) NOT NULL COMMENT 'A hozzaferes hulcsa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `filePieces`
--

CREATE TABLE `filePieces` (
  `serial` bigint(20) UNSIGNED NOT NULL,
  `id` varchar(20) NOT NULL,
  `fileId` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logAccess`
--

CREATE TABLE `logAccess` (
  `serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logAccessAccessKey`
--

CREATE TABLE `logAccessAccessKey` (
  `serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `accessKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logAccessDeneid`
--

CREATE TABLE `logAccessDeneid` (
  `serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `groupKey` varchar(2000) NOT NULL,
  `accessKey` varchar(2000) NOT NULL,
  `masterKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logAccessGroupKey`
--

CREATE TABLE `logAccessGroupKey` (
  `serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `groupKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logAccessMasterKey`
--

CREATE TABLE `logAccessMasterKey` (
  `serial` int(11) NOT NULL,
  `accessTime` int(11) NOT NULL COMMENT 'hozzaferes unix timestampja',
  `address` varchar(200) NOT NULL COMMENT 'kliens ip cime',
  `userAgent` varchar(500) NOT NULL COMMENT 'a kliens userAgent',
  `fileId` varchar(500) NOT NULL,
  `masterKey` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `serial`
--

CREATE TABLE `serial` (
  `serial` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fileAccesKey`
--
ALTER TABLE `fileAccesKey`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `fileGroupKey`
--
ALTER TABLE `fileGroupKey`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `fileList`
--
ALTER TABLE `fileList`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `fileMasterKey`
--
ALTER TABLE `fileMasterKey`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `filePieces`
--
ALTER TABLE `filePieces`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `logAccess`
--
ALTER TABLE `logAccess`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `logAccessAccessKey`
--
ALTER TABLE `logAccessAccessKey`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `logAccessDeneid`
--
ALTER TABLE `logAccessDeneid`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `logAccessGroupKey`
--
ALTER TABLE `logAccessGroupKey`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `serial`
--
ALTER TABLE `serial`
  ADD KEY `index` (`serial`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fileAccesKey`
--
ALTER TABLE `fileAccesKey`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fileGroupKey`
--
ALTER TABLE `fileGroupKey`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fileList`
--
ALTER TABLE `fileList`
  MODIFY `serial` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `fileMasterKey`
--
ALTER TABLE `fileMasterKey`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filePieces`
--
ALTER TABLE `filePieces`
  MODIFY `serial` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logAccess`
--
ALTER TABLE `logAccess`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logAccessAccessKey`
--
ALTER TABLE `logAccessAccessKey`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logAccessDeneid`
--
ALTER TABLE `logAccessDeneid`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logAccessGroupKey`
--
ALTER TABLE `logAccessGroupKey`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serial`
--
ALTER TABLE `serial`
  MODIFY `serial` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;
