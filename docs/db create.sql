CREATE TABLE `evplocationarea` (
  `LocationAreaId` varchar(64) NOT NULL,
  `LocationAreaName` varchar(200)  NOT NULL,
  `Latitude` double DEFAULT NULL,
  `Longitude` double DEFAULT NULL,
  `LocationRegionId` varchar(64) DEFAULT NULL,
  `LocationAreaCode` char(2) DEFAULT NULL,
  PRIMARY KEY (`LocationAreaId`),
  UNIQUE KEY `LocationAreaId` (`LocationAreaId`),
  KEY `LocationRegionId` (`LocationRegionId`),
  CONSTRAINT `FK_evpLocationArea_evpLocationRegion` FOREIGN KEY (`LocationRegionId`) REFERENCES `evplocationregion` (`LocationRegionId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `evplocationcountry` (
  `LocationCountryId` varchar(64) NOT NULL,
  `LocationCountryName` varchar(200)  NOT NULL,
  `LocationCountryCode` char(2) DEFAULT NULL,
  PRIMARY KEY (`LocationCountryId`),
  UNIQUE KEY `LocationCountryId` (`LocationCountryId`)
) ENGINE=InnoDB;

CREATE TABLE `evplocationregion` (
  `LocationRegionId` varchar(64) NOT NULL,
  `LocationRegionName` varchar(200)  NOT NULL,
  `LocationRegionCode` varchar(3) DEFAULT NULL,
  `LocationCountryId` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`LocationRegionId`),
  UNIQUE KEY `LocationRegionId` (`LocationRegionId`),
  KEY `LocationCountryId` (`LocationCountryId`),
  CONSTRAINT `FK_evpLocationRegion_evpLocationCountry` FOREIGN KEY (`LocationCountryId`) REFERENCES `evplocationcountry` (`LocationCountryId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;



CREATE TABLE `evpfestival` (
  `FestivalId` varchar(64) NOT NULL,
  `FestivalName` varchar(100) DEFAULT NULL,
  `FestivalStatus` int(11) NOT NULL,
  `FestivalUrlFriendlyName` varchar(100) DEFAULT NULL,
  `FestivalDateOpen` int(11) DEFAULT NULL,
  `FestivalDateClose` int(11) DEFAULT NULL,
  `LocationCountryId` varchar(64) DEFAULT NULL,
  `FestivalDesc` longtext,
  PRIMARY KEY (`FestivalId`),
  UNIQUE KEY `FestivalId` (`FestivalId`)
) ENGINE=InnoDB;

CREATE TABLE `evpfestivaladmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evphost` (
  `HostId` varchar(64) NOT NULL,
  `HostName` varchar(100) NOT NULL,
  `HostStatus` int(11) DEFAULT NULL,
  `HostUrlFriendlyName` varchar(100) DEFAULT NULL,
  `LocationCountryId` varchar(64) NOT NULL,
  `HostDesc` longtext,
  PRIMARY KEY (`HostId`),
  UNIQUE KEY `HostId` (`HostId`)
) ENGINE=InnoDB;

CREATE TABLE `evphostadmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evphostliked` (
  `MemberId` varchar(64) NOT NULL,
  `LikedId` varchar(64) NOT NULL,
  PRIMARY KEY (`MemberId`,`LikedId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  UNIQUE KEY `LikedId` (`LikedId`),
  CONSTRAINT `FK_evpHostLiked_evpHost` FOREIGN KEY (`LikedId`) REFERENCES `evphost` (`HostId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `evpmember` (
  `MemberId` varchar(64) NOT NULL,
  `MemberName` varchar(200)  NOT NULL,
  `MemberLogin` varchar(100) NOT NULL,
  `MemberPassword` varchar(60) NOT NULL,
  `MemberCookie` varchar(50) DEFAULT NULL,
  `MemberEmail` varchar(200) DEFAULT NULL,
  `LocationCountryId` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`MemberId`),
  UNIQUE KEY `MemberId` (`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evpperformer` (
  `PerformerId` varchar(64) NOT NULL,
  `PerformerName` varchar(200) DEFAULT NULL,
  `PerformerStatus` int(11) DEFAULT NULL,
  `PerformerUrlFriendlyName` varchar(200) NOT NULL,
  `PerformerDesc` longtext,
  `PicSmall` varchar(50) DEFAULT NULL,
  `PicLarge` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PerformerId`),
  UNIQUE KEY `PerformerId` (`PerformerId`)
) ENGINE=InnoDB;

CREATE TABLE `evpperformeradmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evpperformerliked` (
  `MemberId` varchar(64) NOT NULL,
  `LikedId` varchar(64) NOT NULL,
  PRIMARY KEY (`MemberId`,`LikedId`)
) ENGINE=InnoDB;

CREATE TABLE `evpperformerlocation` (
  `PerformerId` varchar(64) NOT NULL,
  `LocationAreaId` varchar(64) NOT NULL,
  PRIMARY KEY (`PerformerId`,`LocationAreaId`)
) ENGINE=InnoDB;

CREATE TABLE `evpperformermember` (
  `PerformerId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`PerformerId`,`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evpshow` (
  `ShowId` varchar(64) NOT NULL,
  `ShowName` varchar(100) DEFAULT NULL,
  `ShowStatus` INT NOT NULL,
  `ShowUrlFriendlyName` varchar(100) DEFAULT NULL,
  `FestivalId` varchar(64) DEFAULT NULL,
  `ShowDesc` longtext,
  `PicSmall` varchar(50) DEFAULT NULL,
  `PicLarge` varchar(50) DEFAULT NULL,
  `PriceRange` varchar(50) DEFAULT NULL,
  `CountryId` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ShowId`),
  UNIQUE KEY `ShowId` (`ShowId`)
) ENGINE=InnoDB;

CREATE TABLE `evpshowadmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evpshowdate` (
  `ShowDateId` varchar(64) NOT NULL,
  `ShowId` varchar(64) NOT NULL,
  `ShowDate` int(11) DEFAULT NULL,
  `ShowTime` int(11) DEFAULT NULL,
  `ShowDateStatus` int(11) NOT NULL,
  `VenueId` varchar(64) DEFAULT NULL,
  `VenueConfirmation` int(11) DEFAULT NULL,
  `ShowLength` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ShowDateId`),
  UNIQUE KEY `ShowDateId` (`ShowDateId`)
) ENGINE=InnoDB;

CREATE TABLE `evpshowdateperformer` (
  `ShowDatePerformerId` varchar(64) NOT NULL,
  `ShowDateId` varchar(64) NOT NULL,
  `PerformerId` varchar(64) NOT NULL,
  `DisplayOrder` int(11) NOT NULL,
  `Title` varchar(50) DEFAULT NULL,
  `PerformerConfirmed` int(11) DEFAULT NULL,
  `PerformanceLength` varchar(20) DEFAULT NULL,
  `IsGuest` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ShowDatePerformerId`),
  UNIQUE KEY `ShowDatePerformerId` (`ShowDatePerformerId`)
) ENGINE=InnoDB;

CREATE TABLE `evpshowhost` (
  `ShowId` varchar(64) NOT NULL,
  `HostId` varchar(64) NOT NULL,
  PRIMARY KEY (`ShowId`,`HostId`)
) ENGINE=InnoDB;

CREATE TABLE `evpvenue` (
  `VenueId` varchar(64) NOT NULL,
  `VenueName` varchar(100) DEFAULT NULL,
  `VenueStatus` int(11) NOT NULL,
  `VenueUrlFriendlyName` varchar(100) DEFAULT NULL,
  `LocationAreaId` varchar(64) DEFAULT NULL,
  `IsDefault` tinyint(1) DEFAULT NULL,
  `VenueDesc` longtext,
  `Address1` varchar(100) DEFAULT NULL,
  `Address2` varchar(100) DEFAULT NULL,
  `AddressSuburb` varchar(100) DEFAULT NULL,
  `AddressState` varchar(100) DEFAULT NULL,
  `AddressPostcode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`VenueId`),
  UNIQUE KEY `VenueId` (`VenueId`)
) ENGINE=InnoDB;

CREATE TABLE `evpvenueadmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`)
) ENGINE=InnoDB;

CREATE TABLE `evpvenueliked` (
  `MemberId` varchar(64) NOT NULL,
  `LikedId` varchar(64) NOT NULL,
  PRIMARY KEY (`MemberId`,`LikedId`)
) ENGINE=InnoDB;
