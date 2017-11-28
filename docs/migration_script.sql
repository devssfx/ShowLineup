-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: Evp2014
-- Source Schemata: Evp2014
-- Created: Mon Sep 26 14:36:07 2016
-- Workbench Version: 6.3.7
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema Evp2014
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `Evp2014` ;
CREATE SCHEMA IF NOT EXISTS `Evp2014` ;

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpLabelFor
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpLabelFor` (
  `ForId` VARCHAR(64) UNIQUE NOT NULL,
  `LabelId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`ForId`, `LabelId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpLabel
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpLabel` (
  `LabelId` VARCHAR(64) UNIQUE NOT NULL,
  `LabelName` VARCHAR(50) NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`LabelId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpLocationCountry
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpLocationCountry` (
  `LocationCountryId` VARCHAR(64) UNIQUE NOT NULL,
  `LocationCountryName` VARCHAR(200) CHARACTER SET 'utf8mb4' NOT NULL,
  `LocationCountryCode` CHAR(2) NULL,
  PRIMARY KEY (`LocationCountryId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpConversation
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpConversation` (
  `ConversationId` VARCHAR(64) UNIQUE NOT NULL,
  `ConversationSubject` VARCHAR(100) NULL,
  `LastSentDate` INT NULL,
  `LastSentTime` INT NULL,
  PRIMARY KEY (`ConversationId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpFeedback
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpFeedback` (
  `FeedbackId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `Feedback` LONGTEXT NOT NULL,
  `Email` VARCHAR(255) NOT NULL,
  `SentDate` INT NOT NULL,
  `SentTime` INT NOT NULL,
  `ReadDate` INT NOT NULL,
  `ReadTime` INT NOT NULL,
  `ClosedDate` INT NOT NULL,
  `ClosedTime` INT NOT NULL,
  PRIMARY KEY (`FeedbackId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpMember
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpMember` (
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberName` VARCHAR(200) CHARACTER SET 'utf8mb4' NOT NULL,
  `MemberLogin` VARCHAR(100) CHARACTER SET 'utf8mb4' NOT NULL,
  `MemberPassword` VARCHAR(50) CHARACTER SET 'utf8mb4' NOT NULL,
  `MemberCookie` VARCHAR(50) NULL,
  `MemberEmail` VARCHAR(200) CHARACTER SET 'utf8mb4' NULL,
  `LocationId` VARCHAR(64) UNIQUE NULL,
  `LocationType` INT NOT NULL,
  `MemberSalt` CHAR(24) NULL,
  PRIMARY KEY (`MemberId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpPerformer
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpPerformer` (
  `PerformerId` VARCHAR(64) UNIQUE NOT NULL,
  `PerformerName` VARCHAR(200) CHARACTER SET 'utf8mb4' NULL,
  `PerformerStatus` INT NULL,
  `PerformerUrlFriendlyName` VARCHAR(200) NOT NULL,
  `PerformerDesc` LONGTEXT NULL,
  `PicSmall` VARCHAR(50) NULL,
  `PicLarge` VARCHAR(50) NULL,
  PRIMARY KEY (`PerformerId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpNewsletter
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpNewsletter` (
  `Email` VARCHAR(255) NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NULL,
  PRIMARY KEY (`Email`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.titpPlace
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`titpPlace` (
  `PlaceId` VARCHAR(64) UNIQUE NOT NULL,
  `PlaceName` VARCHAR(200) CHARACTER SET 'utf8mb4' NULL,
  `PlaceDate` INT NULL,
  `CreateDate` INT NULL,
  `PlaceAddress` VARCHAR(500) CHARACTER SET 'utf8mb4' NULL,
  `PlaceLat` VARCHAR(20) NULL,
  `PlaceLong` VARCHAR(20) NULL,
  `AdminPassword` VARCHAR(100) NULL,
  `UserPassword` VARCHAR(100) NULL,
  `ModCount` INT NULL,
  `PlaceCode` VARCHAR(20) NULL,
  PRIMARY KEY (`PlaceId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpShow
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpShow` (
  `ShowId` VARCHAR(64) UNIQUE NOT NULL,
  `ShowName` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `ShowUrlFriendlyName` VARCHAR(100) NULL,
  `FestivalId` VARCHAR(64) UNIQUE NULL,
  `ShowDesc` LONGTEXT NULL,
  `PicSmall` VARCHAR(50) NULL,
  `PicLarge` VARCHAR(50) NULL,
  `PriceRange` VARCHAR(50) NULL,
  PRIMARY KEY (`ShowId`));

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpSettings
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpSettings` (
  `BreakPerformerId` VARCHAR(64) UNIQUE NULL);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpPerformerLiked
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpPerformerLiked` (
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `LikedId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`MemberId`, `LikedId`),
  CONSTRAINT `FK_evpPerformerLiked_evpPerformer`
    FOREIGN KEY (`LikedId`)
    REFERENCES `Evp2014`.`evpPerformer` (`PerformerId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpPerformerAdmin
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpPerformerAdmin` (
  `AdminForId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`AdminForId`, `MemberId`),
  CONSTRAINT `FK_evpPerformerAdmin_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpPerformerAdmin_evpPerformer`
    FOREIGN KEY (`AdminForId`)
    REFERENCES `Evp2014`.`evpPerformer` (`PerformerId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpFestival
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpFestival` (
  `FestivalId` VARCHAR(64) UNIQUE NOT NULL,
  `FestivalName` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `FestivalStatus` INT NOT NULL,
  `FestivalUrlFriendlyName` VARCHAR(100) NULL,
  `FestivalDateOpen` INT NULL,
  `FestivalDateClose` INT NULL,
  `LocationCountryId` VARCHAR(64) UNIQUE NOT NULL,
  `FestivalDesc` LONGTEXT NULL,
  PRIMARY KEY (`FestivalId`),
  CONSTRAINT `FK_evpFestival_evpLocationCountry`
    FOREIGN KEY (`LocationCountryId`)
    REFERENCES `Evp2014`.`evpLocationCountry` (`LocationCountryId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpPerformerMember
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpPerformerMember` (
  `PerformerId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`PerformerId`, `MemberId`),
  CONSTRAINT `FK_evpPerformerMember_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpPerformerMember_evpPerformer`
    FOREIGN KEY (`PerformerId`)
    REFERENCES `Evp2014`.`evpPerformer` (`PerformerId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpShowDate
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpShowDate` (
  `ShowDateId` VARCHAR(64) UNIQUE NOT NULL,
  `ShowId` VARCHAR(64) UNIQUE NOT NULL,
  `ShowDate` INT NULL,
  `ShowTime` INT NULL,
  `ShowDateStatus` INT NOT NULL,
  `VenueId` VARCHAR(64) UNIQUE NULL,
  `VenueConfirmation` INT NULL,
  `ShowLength` VARCHAR(50) NULL,
  PRIMARY KEY (`ShowDateId`),
  CONSTRAINT `FK_evpShowDate_evpShow`
    FOREIGN KEY (`ShowId`)
    REFERENCES `Evp2014`.`evpShow` (`ShowId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpShowAdmin
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpShowAdmin` (
  `AdminForId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`AdminForId`, `MemberId`),
  CONSTRAINT `FK_evpShowAdmin_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpShowAdmin_evpShow`
    FOREIGN KEY (`AdminForId`)
    REFERENCES `Evp2014`.`evpShow` (`ShowId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpMemberFB
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpMemberFB` (
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `FacebookId` BIGINT NULL,
  PRIMARY KEY (`MemberId`),
  CONSTRAINT `FK_evpMemberFB_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpLocationRegion
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpLocationRegion` (
  `LocationRegionId` VARCHAR(64) UNIQUE NOT NULL,
  `LocationRegionName` VARCHAR(200) CHARACTER SET 'utf8mb4' NOT NULL,
  `LocationRegionCode` VARCHAR(3) NULL,
  `LocationCountryId` VARCHAR(64) UNIQUE NULL,
  PRIMARY KEY (`LocationRegionId`),
  CONSTRAINT `FK_evpLocationRegion_evpLocationCountry`
    FOREIGN KEY (`LocationCountryId`)
    REFERENCES `Evp2014`.`evpLocationCountry` (`LocationCountryId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpConvMessage
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpConvMessage` (
  `ConvMessageId` VARCHAR(64) UNIQUE NOT NULL,
  `ConversationId` VARCHAR(64) UNIQUE NOT NULL,
  `MessageText` LONGTEXT NULL,
  `SentDate` INT NULL,
  `SentTime` INT NULL,
  `FromId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`ConvMessageId`),
  CONSTRAINT `FK_evpConvMessage_evpConversation`
    FOREIGN KEY (`ConversationId`)
    REFERENCES `Evp2014`.`evpConversation` (`ConversationId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpConvMessage_evpMember`
    FOREIGN KEY (`FromId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpConvMember
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpConvMember` (
  `ConversationId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `PartOfId` VARCHAR(64) UNIQUE NOT NULL,
  `PartOfType` INT NOT NULL,
  `LeaveDate` INT NULL,
  `LeaveTime` INT NULL,
  `ReadDate` INT NULL,
  `ReadTime` INT NULL,
  PRIMARY KEY (`ConversationId`, `MemberId`, `PartOfId`),
  CONSTRAINT `FK_evpConvMember_evpConversation`
    FOREIGN KEY (`ConversationId`)
    REFERENCES `Evp2014`.`evpConversation` (`ConversationId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpConvMember_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpHost
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpHost` (
  `HostId` VARCHAR(64) UNIQUE NOT NULL,
  `HostName` VARCHAR(100) CHARACTER SET 'utf8mb4' NOT NULL,
  `HostStatus` INT NULL,
  `HostUrlFriendlyName` VARCHAR(100) NULL,
  `LocationCountryId` VARCHAR(64) UNIQUE NOT NULL,
  `HostDesc` LONGTEXT NULL,
  PRIMARY KEY (`HostId`),
  CONSTRAINT `FK_evpHost_evpLocationCountry`
    FOREIGN KEY (`LocationCountryId`)
    REFERENCES `Evp2014`.`evpLocationCountry` (`LocationCountryId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpLocationArea
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpLocationArea` (
  `LocationAreaId` VARCHAR(64) UNIQUE NOT NULL,
  `LocationAreaName` VARCHAR(200) CHARACTER SET 'utf8mb4' NOT NULL,
  `Latitude` DOUBLE NULL,
  `Longitude` DOUBLE NULL,
  `LocationRegionId` VARCHAR(64) UNIQUE NULL,
  `LocationAreaCode` CHAR(2) NULL,
  PRIMARY KEY (`LocationAreaId`),
  CONSTRAINT `FK_evpLocationArea_evpLocationRegion`
    FOREIGN KEY (`LocationRegionId`)
    REFERENCES `Evp2014`.`evpLocationRegion` (`LocationRegionId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpHostLiked
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpHostLiked` (
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `LikedId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`MemberId`, `LikedId`),
  CONSTRAINT `FK_evpHostLiked_evpHost`
    FOREIGN KEY (`LikedId`)
    REFERENCES `Evp2014`.`evpHost` (`HostId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpHostAdmin
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpHostAdmin` (
  `AdminForId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`AdminForId`, `MemberId`),
  CONSTRAINT `FK_evpHostAdmin_evpHost`
    FOREIGN KEY (`AdminForId`)
    REFERENCES `Evp2014`.`evpHost` (`HostId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpHostAdmin_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpGoingTo
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpGoingTo` (
  `ShowDateId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`ShowDateId`, `MemberId`),
  CONSTRAINT `FK_evpGoingTo_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpGoingTo_evpShowDate`
    FOREIGN KEY (`ShowDateId`)
    REFERENCES `Evp2014`.`evpShowDate` (`ShowDateId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpFestivalAdmin
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpFestivalAdmin` (
  `AdminForId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`AdminForId`, `MemberId`),
  CONSTRAINT `FK_evpFestivalAdmin_evpFestival`
    FOREIGN KEY (`AdminForId`)
    REFERENCES `Evp2014`.`evpFestival` (`FestivalId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpFestivalAdmin_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpShowHost
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpShowHost` (
  `ShowId` VARCHAR(64) UNIQUE NOT NULL,
  `HostId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`ShowId`, `HostId`),
  CONSTRAINT `FK_evpShowHost_evpHost`
    FOREIGN KEY (`HostId`)
    REFERENCES `Evp2014`.`evpHost` (`HostId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpShowHost_evpShow`
    FOREIGN KEY (`ShowId`)
    REFERENCES `Evp2014`.`evpShow` (`ShowId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpShowDatePerformer
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpShowDatePerformer` (
  `ShowDatePerformerId` VARCHAR(64) UNIQUE NOT NULL,
  `ShowDateId` VARCHAR(64) UNIQUE NOT NULL,
  `PerformerId` VARCHAR(64) UNIQUE NOT NULL,
  `DisplayOrder` INT NOT NULL,
  `Title` VARCHAR(50) CHARACTER SET 'utf8mb4' NULL,
  `PerformerConfirmed` INT NULL,
  `PerformanceLength` VARCHAR(20) NULL,
  `IsGuest` TINYINT(1) NULL,
  PRIMARY KEY (`ShowDatePerformerId`),
  CONSTRAINT `FK_evpShowDatePerformer_evpPerformer`
    FOREIGN KEY (`PerformerId`)
    REFERENCES `Evp2014`.`evpPerformer` (`PerformerId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpShowDatePerformer_evpShowDate`
    FOREIGN KEY (`ShowDateId`)
    REFERENCES `Evp2014`.`evpShowDate` (`ShowDateId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpVenue
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpVenue` (
  `VenueId` VARCHAR(64) UNIQUE NOT NULL,
  `VenueName` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `VenueStatus` INT NOT NULL,
  `VenueUrlFriendlyName` VARCHAR(100) NULL,
  `LocationAreaId` VARCHAR(64) UNIQUE NULL,
  `IsDefault` TINYINT(1) NULL,
  `VenueDesc` LONGTEXT NULL,
  `Address1` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `Address2` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `AddressSuburb` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `AddressState` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL,
  `AddressPostcode` VARCHAR(20) CHARACTER SET 'utf8mb4' NULL,
  PRIMARY KEY (`VenueId`),
  CONSTRAINT `FK_evpVenue_evpLocationArea`
    FOREIGN KEY (`LocationAreaId`)
    REFERENCES `Evp2014`.`evpLocationArea` (`LocationAreaId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpPerformerLocation
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpPerformerLocation` (
  `PerformerId` VARCHAR(64) UNIQUE NOT NULL,
  `LocationAreaId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`PerformerId`, `LocationAreaId`),
  CONSTRAINT `FK_evpPerformerLocation_evpLocationArea`
    FOREIGN KEY (`LocationAreaId`)
    REFERENCES `Evp2014`.`evpLocationArea` (`LocationAreaId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpPerformerLocation_evpPerformer`
    FOREIGN KEY (`PerformerId`)
    REFERENCES `Evp2014`.`evpPerformer` (`PerformerId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpVenueLiked
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpVenueLiked` (
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  `LikedId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`MemberId`, `LikedId`),
  CONSTRAINT `FK_evpVenueLiked_evpVenue`
    FOREIGN KEY (`LikedId`)
    REFERENCES `Evp2014`.`evpVenue` (`VenueId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- ----------------------------------------------------------------------------
-- Table Evp2014.evpVenueAdmin
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Evp2014`.`evpVenueAdmin` (
  `AdminForId` VARCHAR(64) UNIQUE NOT NULL,
  `MemberId` VARCHAR(64) UNIQUE NOT NULL,
  PRIMARY KEY (`AdminForId`, `MemberId`),
  CONSTRAINT `FK_evpVenueAdmin_evpMember`
    FOREIGN KEY (`MemberId`)
    REFERENCES `Evp2014`.`evpMember` (`MemberId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_evpVenueAdmin_evpVenue`
    FOREIGN KEY (`AdminForId`)
    REFERENCES `Evp2014`.`evpVenue` (`VenueId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
SET FOREIGN_KEY_CHECKS = 1;
