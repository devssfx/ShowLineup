CREATE TABLE `evpconversation` (
  `ConversationId` varchar(64) NOT NULL,
  `ConversationSubject` varchar(100) DEFAULT NULL,
  `LastSentDate` int(11) DEFAULT NULL,
  `LastSentTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`ConversationId`),
  UNIQUE KEY `ConversationId` (`ConversationId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpconvmember` (
  `ConversationId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  `PartOfId` varchar(64) NOT NULL,
  `PartOfType` int(11) NOT NULL,
  `LeaveDate` int(11) DEFAULT NULL,
  `LeaveTime` int(11) DEFAULT NULL,
  `ReadDate` int(11) DEFAULT NULL,
  `ReadTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`ConversationId`,`MemberId`,`PartOfId`),
  UNIQUE KEY `ConversationId` (`ConversationId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  UNIQUE KEY `PartOfId` (`PartOfId`),
  CONSTRAINT `FK_evpConvMember_evpConversation` FOREIGN KEY (`ConversationId`) REFERENCES `evpconversation` (`ConversationId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpConvMember_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpconvmessage` (
  `ConvMessageId` varchar(64) NOT NULL,
  `ConversationId` varchar(64) NOT NULL,
  `MessageText` longtext,
  `SentDate` int(11) DEFAULT NULL,
  `SentTime` int(11) DEFAULT NULL,
  `FromId` varchar(64) NOT NULL,
  PRIMARY KEY (`ConvMessageId`),
  UNIQUE KEY `ConvMessageId` (`ConvMessageId`),
  UNIQUE KEY `ConversationId` (`ConversationId`),
  UNIQUE KEY `FromId` (`FromId`),
  CONSTRAINT `FK_evpConvMessage_evpConversation` FOREIGN KEY (`ConversationId`) REFERENCES `evpconversation` (`ConversationId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpConvMessage_evpMember` FOREIGN KEY (`FromId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpfeedback` (
  `FeedbackId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  `Feedback` longtext NOT NULL,
  `Email` varchar(255) NOT NULL,
  `SentDate` int(11) NOT NULL,
  `SentTime` int(11) NOT NULL,
  `ReadDate` int(11) NOT NULL,
  `ReadTime` int(11) NOT NULL,
  `ClosedDate` int(11) NOT NULL,
  `ClosedTime` int(11) NOT NULL,
  PRIMARY KEY (`FeedbackId`),
  UNIQUE KEY `FeedbackId` (`FeedbackId`),
  UNIQUE KEY `MemberId` (`MemberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpfestival` (
  `FestivalId` varchar(64) NOT NULL,
  `FestivalName` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `FestivalStatus` int(11) NOT NULL,
  `FestivalUrlFriendlyName` varchar(100) DEFAULT NULL,
  `FestivalDateOpen` int(11) DEFAULT NULL,
  `FestivalDateClose` int(11) DEFAULT NULL,
  `LocationCountryId` varchar(64) DEFAULT NULL,
  `FestivalDesc` longtext,
  PRIMARY KEY (`FestivalId`),
  UNIQUE KEY `FestivalId` (`FestivalId`),
  KEY `FK_evpFestival_evpLocationCountry` (`LocationCountryId`),
  CONSTRAINT `FK_evpFestival_evpLocationCountry` FOREIGN KEY (`LocationCountryId`) REFERENCES `evplocationcountry` (`LocationCountryId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpfestivaladmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`),
  UNIQUE KEY `AdminForId` (`AdminForId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpFestivalAdmin_evpFestival` FOREIGN KEY (`AdminForId`) REFERENCES `evpfestival` (`FestivalId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpFestivalAdmin_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpgoingto` (
  `ShowDateId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`ShowDateId`,`MemberId`),
  UNIQUE KEY `ShowDateId` (`ShowDateId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpGoingTo_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpGoingTo_evpShowDate` FOREIGN KEY (`ShowDateId`) REFERENCES `evpshowdate` (`ShowDateId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evphost` (
  `HostId` varchar(64) NOT NULL,
  `HostName` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `HostStatus` int(11) DEFAULT NULL,
  `HostUrlFriendlyName` varchar(100) DEFAULT NULL,
  `LocationCountryId` varchar(64) NOT NULL,
  `HostDesc` longtext,
  PRIMARY KEY (`HostId`),
  UNIQUE KEY `HostId` (`HostId`),
  UNIQUE KEY `LocationCountryId` (`LocationCountryId`),
  CONSTRAINT `FK_evpHost_evpLocationCountry` FOREIGN KEY (`LocationCountryId`) REFERENCES `evplocationcountry` (`LocationCountryId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evphostadmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`),
  UNIQUE KEY `AdminForId` (`AdminForId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpHostAdmin_evpHost` FOREIGN KEY (`AdminForId`) REFERENCES `evphost` (`HostId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpHostAdmin_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evphostliked` (
  `MemberId` varchar(64) NOT NULL,
  `LikedId` varchar(64) NOT NULL,
  PRIMARY KEY (`MemberId`,`LikedId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  UNIQUE KEY `LikedId` (`LikedId`),
  CONSTRAINT `FK_evpHostLiked_evpHost` FOREIGN KEY (`LikedId`) REFERENCES `evphost` (`HostId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evplabel` (
  `LabelId` varchar(64) NOT NULL,
  `LabelName` varchar(50) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`LabelId`),
  UNIQUE KEY `LabelId` (`LabelId`),
  UNIQUE KEY `MemberId` (`MemberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evplabelfor` (
  `ForId` varchar(64) NOT NULL,
  `LabelId` varchar(64) NOT NULL,
  PRIMARY KEY (`ForId`,`LabelId`),
  UNIQUE KEY `ForId` (`ForId`),
  UNIQUE KEY `LabelId` (`LabelId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evplocationarea` (
  `LocationAreaId` varchar(64) NOT NULL,
  `LocationAreaName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `Latitude` double DEFAULT NULL,
  `Longitude` double DEFAULT NULL,
  `LocationRegionId` varchar(64) DEFAULT NULL,
  `LocationAreaCode` char(2) DEFAULT NULL,
  PRIMARY KEY (`LocationAreaId`),
  UNIQUE KEY `LocationAreaId` (`LocationAreaId`),
  UNIQUE KEY `LocationRegionId` (`LocationRegionId`),
  CONSTRAINT `FK_evpLocationArea_evpLocationRegion` FOREIGN KEY (`LocationRegionId`) REFERENCES `evplocationregion` (`LocationRegionId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evplocationcountry` (
  `LocationCountryId` varchar(64) NOT NULL,
  `LocationCountryName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `LocationCountryCode` char(2) DEFAULT NULL,
  PRIMARY KEY (`LocationCountryId`),
  UNIQUE KEY `LocationCountryId` (`LocationCountryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evplocationregion` (
  `LocationRegionId` varchar(64) NOT NULL,
  `LocationRegionName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `LocationRegionCode` varchar(3) DEFAULT NULL,
  `LocationCountryId` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`LocationRegionId`),
  UNIQUE KEY `LocationRegionId` (`LocationRegionId`),
  UNIQUE KEY `LocationCountryId` (`LocationCountryId`),
  CONSTRAINT `FK_evpLocationRegion_evpLocationCountry` FOREIGN KEY (`LocationCountryId`) REFERENCES `evplocationcountry` (`LocationCountryId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpmember` (
  `MemberId` varchar(64) NOT NULL,
  `MemberName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `MemberLogin` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `MemberPassword` varchar(60) CHARACTER SET utf8mb4 NOT NULL,
  `MemberCookie` varchar(50) DEFAULT NULL,
  `MemberEmail` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `LocationId` varchar(64) DEFAULT NULL,
  `LocationType` int(11) NOT NULL,
  PRIMARY KEY (`MemberId`),
  UNIQUE KEY `MemberId` (`MemberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpmemberfb` (
  `MemberId` varchar(64) NOT NULL,
  `FacebookId` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`MemberId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpMemberFB_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpnewsletter` (
  `Email` varchar(255) NOT NULL,
  `MemberId` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`Email`),
  UNIQUE KEY `MemberId` (`MemberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpperformer` (
  `PerformerId` varchar(64) NOT NULL,
  `PerformerName` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `PerformerStatus` int(11) DEFAULT NULL,
  `PerformerUrlFriendlyName` varchar(200) NOT NULL,
  `PerformerDesc` longtext,
  `PicSmall` varchar(50) DEFAULT NULL,
  `PicLarge` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PerformerId`),
  UNIQUE KEY `PerformerId` (`PerformerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpperformeradmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`),
  UNIQUE KEY `AdminForId` (`AdminForId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpPerformerAdmin_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpPerformerAdmin_evpPerformer` FOREIGN KEY (`AdminForId`) REFERENCES `evpperformer` (`PerformerId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpperformerliked` (
  `MemberId` varchar(64) NOT NULL,
  `LikedId` varchar(64) NOT NULL,
  PRIMARY KEY (`MemberId`,`LikedId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  UNIQUE KEY `LikedId` (`LikedId`),
  CONSTRAINT `FK_evpPerformerLiked_evpPerformer` FOREIGN KEY (`LikedId`) REFERENCES `evpperformer` (`PerformerId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpperformerlocation` (
  `PerformerId` varchar(64) NOT NULL,
  `LocationAreaId` varchar(64) NOT NULL,
  PRIMARY KEY (`PerformerId`,`LocationAreaId`),
  UNIQUE KEY `PerformerId` (`PerformerId`),
  UNIQUE KEY `LocationAreaId` (`LocationAreaId`),
  CONSTRAINT `FK_evpPerformerLocation_evpLocationArea` FOREIGN KEY (`LocationAreaId`) REFERENCES `evplocationarea` (`LocationAreaId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpPerformerLocation_evpPerformer` FOREIGN KEY (`PerformerId`) REFERENCES `evpperformer` (`PerformerId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpperformermember` (
  `PerformerId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`PerformerId`,`MemberId`),
  UNIQUE KEY `PerformerId` (`PerformerId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpPerformerMember_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpPerformerMember_evpPerformer` FOREIGN KEY (`PerformerId`) REFERENCES `evpperformer` (`PerformerId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpsettings` (
  `BreakPerformerId` varchar(64) DEFAULT NULL,
  UNIQUE KEY `BreakPerformerId` (`BreakPerformerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpshow` (
  `ShowId` varchar(64) NOT NULL,
  `ShowName` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `ShowUrlFriendlyName` varchar(100) DEFAULT NULL,
  `FestivalId` varchar(64) DEFAULT NULL,
  `ShowDesc` longtext,
  `PicSmall` varchar(50) DEFAULT NULL,
  `PicLarge` varchar(50) DEFAULT NULL,
  `PriceRange` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ShowId`),
  UNIQUE KEY `ShowId` (`ShowId`),
  UNIQUE KEY `FestivalId` (`FestivalId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpshowadmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`),
  UNIQUE KEY `AdminForId` (`AdminForId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpShowAdmin_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpShowAdmin_evpShow` FOREIGN KEY (`AdminForId`) REFERENCES `evpshow` (`ShowId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  UNIQUE KEY `ShowDateId` (`ShowDateId`),
  UNIQUE KEY `ShowId` (`ShowId`),
  UNIQUE KEY `VenueId` (`VenueId`),
  CONSTRAINT `FK_evpShowDate_evpShow` FOREIGN KEY (`ShowId`) REFERENCES `evpshow` (`ShowId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpshowdateperformer` (
  `ShowDatePerformerId` varchar(64) NOT NULL,
  `ShowDateId` varchar(64) NOT NULL,
  `PerformerId` varchar(64) NOT NULL,
  `DisplayOrder` int(11) NOT NULL,
  `Title` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `PerformerConfirmed` int(11) DEFAULT NULL,
  `PerformanceLength` varchar(20) DEFAULT NULL,
  `IsGuest` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ShowDatePerformerId`),
  UNIQUE KEY `ShowDatePerformerId` (`ShowDatePerformerId`),
  UNIQUE KEY `ShowDateId` (`ShowDateId`),
  UNIQUE KEY `PerformerId` (`PerformerId`),
  CONSTRAINT `FK_evpShowDatePerformer_evpPerformer` FOREIGN KEY (`PerformerId`) REFERENCES `evpperformer` (`PerformerId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpShowDatePerformer_evpShowDate` FOREIGN KEY (`ShowDateId`) REFERENCES `evpshowdate` (`ShowDateId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpshowhost` (
  `ShowId` varchar(64) NOT NULL,
  `HostId` varchar(64) NOT NULL,
  PRIMARY KEY (`ShowId`,`HostId`),
  UNIQUE KEY `ShowId` (`ShowId`),
  UNIQUE KEY `HostId` (`HostId`),
  CONSTRAINT `FK_evpShowHost_evpHost` FOREIGN KEY (`HostId`) REFERENCES `evphost` (`HostId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpShowHost_evpShow` FOREIGN KEY (`ShowId`) REFERENCES `evpshow` (`ShowId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpvenue` (
  `VenueId` varchar(64) NOT NULL,
  `VenueName` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `VenueStatus` int(11) NOT NULL,
  `VenueUrlFriendlyName` varchar(100) DEFAULT NULL,
  `LocationAreaId` varchar(64) DEFAULT NULL,
  `IsDefault` tinyint(1) DEFAULT NULL,
  `VenueDesc` longtext,
  `Address1` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Address2` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `AddressSuburb` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `AddressState` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `AddressPostcode` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`VenueId`),
  UNIQUE KEY `VenueId` (`VenueId`),
  UNIQUE KEY `LocationAreaId` (`LocationAreaId`),
  CONSTRAINT `FK_evpVenue_evpLocationArea` FOREIGN KEY (`LocationAreaId`) REFERENCES `evplocationarea` (`LocationAreaId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpvenueadmin` (
  `AdminForId` varchar(64) NOT NULL,
  `MemberId` varchar(64) NOT NULL,
  PRIMARY KEY (`AdminForId`,`MemberId`),
  UNIQUE KEY `AdminForId` (`AdminForId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  CONSTRAINT `FK_evpVenueAdmin_evpMember` FOREIGN KEY (`MemberId`) REFERENCES `evpmember` (`MemberId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpVenueAdmin_evpVenue` FOREIGN KEY (`AdminForId`) REFERENCES `evpvenue` (`VenueId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `evpvenueliked` (
  `MemberId` varchar(64) NOT NULL,
  `LikedId` varchar(64) NOT NULL,
  PRIMARY KEY (`MemberId`,`LikedId`),
  UNIQUE KEY `MemberId` (`MemberId`),
  UNIQUE KEY `LikedId` (`LikedId`),
  CONSTRAINT `FK_evpVenueLiked_evpVenue` FOREIGN KEY (`LikedId`) REFERENCES `evpvenue` (`VenueId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
