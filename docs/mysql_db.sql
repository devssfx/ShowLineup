USE [Evp2014]
GO
/****** Object:  Table [dbo].[evpLabelFor]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpLabelFor](
	[ForId] [uniqueidentifier] NOT NULL,
	[LabelId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpLabelFor] PRIMARY KEY CLUSTERED 
(
	[ForId] ASC,
	[LabelId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpLabel]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpLabel](
	[LabelId] [uniqueidentifier] NOT NULL,
	[LabelName] [varchar](50) NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpLabel] PRIMARY KEY CLUSTERED 
(
	[LabelId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpLocationCountry]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpLocationCountry](
	[LocationCountryId] [uniqueidentifier] NOT NULL,
	[LocationCountryName] [nvarchar](200) NOT NULL,
	[LocationCountryCode] [char](2) NULL,
 CONSTRAINT [PK_evpLocationCountry] PRIMARY KEY CLUSTERED 
(
	[LocationCountryId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpConversation]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpConversation](
	[ConversationId] [uniqueidentifier] NOT NULL,
	[ConversationSubject] [varchar](100) NULL,
	[LastSentDate] [int] NULL,
	[LastSentTime] [int] NULL,
 CONSTRAINT [PK_evpConversation] PRIMARY KEY CLUSTERED 
(
	[ConversationId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpFeedback]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpFeedback](
	[FeedbackId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
	[Feedback] [ntext] NOT NULL,
	[Email] [varchar](255) NOT NULL,
	[SentDate] [int] NOT NULL,
	[SentTime] [int] NOT NULL,
	[ReadDate] [int] NOT NULL,
	[ReadTime] [int] NOT NULL,
	[ClosedDate] [int] NOT NULL,
	[ClosedTime] [int] NOT NULL,
 CONSTRAINT [PK_evpFeedback] PRIMARY KEY CLUSTERED 
(
	[FeedbackId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpMember]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpMember](
	[MemberId] [uniqueidentifier] NOT NULL,
	[MemberName] [nvarchar](200) NOT NULL,
	[MemberLogin] [nvarchar](100) NOT NULL,
	[MemberPassword] [nvarchar](50) NOT NULL,
	[MemberCookie] [varchar](50) NULL,
	[MemberEmail] [nvarchar](200) NULL,
	[LocationId] [uniqueidentifier] NULL,
	[LocationType] [int] NOT NULL,
	[MemberSalt] [char](24) NULL,
 CONSTRAINT [PK_evpMember] PRIMARY KEY CLUSTERED 
(
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpPerformer]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpPerformer](
	[PerformerId] [uniqueidentifier] NOT NULL,
	[PerformerName] [nvarchar](200) NULL,
	[PerformerStatus] [int] NULL,
	[PerformerUrlFriendlyName] [varchar](200) NOT NULL,
	[PerformerDesc] [ntext] NULL,
	[PicSmall] [varchar](50) NULL,
	[PicLarge] [varchar](50) NULL,
 CONSTRAINT [PK_evpPerformer] PRIMARY KEY CLUSTERED 
(
	[PerformerId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpNewsletter]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpNewsletter](
	[Email] [varchar](255) NOT NULL,
	[MemberId] [uniqueidentifier] NULL,
 CONSTRAINT [PK_evpNewsletter] PRIMARY KEY CLUSTERED 
(
	[Email] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpShow]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpShow](
	[ShowId] [uniqueidentifier] NOT NULL,
	[ShowName] [nvarchar](100) NULL,
	[ShowUrlFriendlyName] [varchar](100) NULL,
	[FestivalId] [uniqueidentifier] NULL,
	[ShowDesc] [ntext] NULL,
	[PicSmall] [varchar](50) NULL,
	[PicLarge] [varchar](50) NULL,
	[PriceRange] [varchar](50) NULL,
 CONSTRAINT [PK_Show] PRIMARY KEY CLUSTERED 
(
	[ShowId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpSettings]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpSettings](
	[BreakPerformerId] [uniqueidentifier] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpPerformerLiked]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpPerformerLiked](
	[MemberId] [uniqueidentifier] NOT NULL,
	[LikedId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpPerformerLiked] PRIMARY KEY CLUSTERED 
(
	[MemberId] ASC,
	[LikedId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpPerformerAdmin]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpPerformerAdmin](
	[AdminForId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_PerformerAdmin] PRIMARY KEY CLUSTERED 
(
	[AdminForId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpFestival]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpFestival](
	[FestivalId] [uniqueidentifier] NOT NULL,
	[FestivalName] [nvarchar](100) NULL,
	[FestivalStatus] [int] NOT NULL,
	[FestivalUrlFriendlyName] [varchar](100) NULL,
	[FestivalDateOpen] [int] NULL,
	[FestivalDateClose] [int] NULL,
	[LocationCountryId] [uniqueidentifier] NOT NULL,
	[FestivalDesc] [ntext] NULL,
 CONSTRAINT [PK_Festival] PRIMARY KEY CLUSTERED 
(
	[FestivalId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpPerformerMember]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpPerformerMember](
	[PerformerId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpPerformerMember] PRIMARY KEY CLUSTERED 
(
	[PerformerId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpShowDate]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpShowDate](
	[ShowDateId] [uniqueidentifier] NOT NULL,
	[ShowId] [uniqueidentifier] NOT NULL,
	[ShowDate] [int] NULL,
	[ShowTime] [int] NULL,
	[ShowDateStatus] [int] NOT NULL,
	[VenueId] [uniqueidentifier] NULL,
	[VenueConfirmation] [int] NULL,
	[ShowLength] [varchar](50) NULL,
 CONSTRAINT [PK_evpShowDate] PRIMARY KEY CLUSTERED 
(
	[ShowDateId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpShowAdmin]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpShowAdmin](
	[AdminForId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_ShowAdmin] PRIMARY KEY CLUSTERED 
(
	[AdminForId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpMemberFB]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpMemberFB](
	[MemberId] [uniqueidentifier] NOT NULL,
	[FacebookId] [bigint] NULL,
 CONSTRAINT [PK_evpMemberFB] PRIMARY KEY CLUSTERED 
(
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpLocationRegion]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpLocationRegion](
	[LocationRegionId] [uniqueidentifier] NOT NULL,
	[LocationRegionName] [nvarchar](200) NOT NULL,
	[LocationRegionCode] [varchar](3) NULL,
	[LocationCountryId] [uniqueidentifier] NULL,
 CONSTRAINT [PK_evpLocationRegion] PRIMARY KEY CLUSTERED 
(
	[LocationRegionId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpConvMessage]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpConvMessage](
	[ConvMessageId] [uniqueidentifier] NOT NULL,
	[ConversationId] [uniqueidentifier] NOT NULL,
	[MessageText] [ntext] NULL,
	[SentDate] [int] NULL,
	[SentTime] [int] NULL,
	[FromId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpConvMessage] PRIMARY KEY CLUSTERED 
(
	[ConvMessageId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpConvMember]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpConvMember](
	[ConversationId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
	[PartOfId] [uniqueidentifier] NOT NULL,
	[PartOfType] [int] NOT NULL,
	[LeaveDate] [int] NULL,
	[LeaveTime] [int] NULL,
	[ReadDate] [int] NULL,
	[ReadTime] [int] NULL,
 CONSTRAINT [PK_evpConvMember] PRIMARY KEY CLUSTERED 
(
	[ConversationId] ASC,
	[MemberId] ASC,
	[PartOfId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpHost]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpHost](
	[HostId] [uniqueidentifier] NOT NULL,
	[HostName] [nvarchar](100) NOT NULL,
	[HostStatus] [int] NULL,
	[HostUrlFriendlyName] [varchar](100) NULL,
	[LocationCountryId] [uniqueidentifier] NOT NULL,
	[HostDesc] [ntext] NULL,
 CONSTRAINT [PK_evpHost] PRIMARY KEY CLUSTERED 
(
	[HostId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpLocationArea]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpLocationArea](
	[LocationAreaId] [uniqueidentifier] NOT NULL,
	[LocationAreaName] [nvarchar](200) NOT NULL,
	[Latitude] [float] NULL,
	[Longitude] [float] NULL,
	[LocationRegionId] [uniqueidentifier] NULL,
	[LocationAreaCode] [char](2) NULL,
 CONSTRAINT [PK_evpLocationArea] PRIMARY KEY CLUSTERED 
(
	[LocationAreaId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpHostLiked]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpHostLiked](
	[MemberId] [uniqueidentifier] NOT NULL,
	[LikedId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpHostLiked] PRIMARY KEY CLUSTERED 
(
	[MemberId] ASC,
	[LikedId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpHostAdmin]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpHostAdmin](
	[AdminForId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpHostAdmin] PRIMARY KEY CLUSTERED 
(
	[AdminForId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpGoingTo]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpGoingTo](
	[ShowDateId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpGoingTo] PRIMARY KEY CLUSTERED 
(
	[ShowDateId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpFestivalAdmin]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpFestivalAdmin](
	[AdminForId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_FestivalAdmin] PRIMARY KEY CLUSTERED 
(
	[AdminForId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpShowHost]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpShowHost](
	[ShowId] [uniqueidentifier] NOT NULL,
	[HostId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpShowHost] PRIMARY KEY CLUSTERED 
(
	[ShowId] ASC,
	[HostId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpShowDatePerformer]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpShowDatePerformer](
	[ShowDatePerformerId] [uniqueidentifier] NOT NULL,
	[ShowDateId] [uniqueidentifier] NOT NULL,
	[PerformerId] [uniqueidentifier] NOT NULL,
	[DisplayOrder] [int] NOT NULL,
	[Title] [nvarchar](50) NULL,
	[PerformerConfirmed] [int] NULL,
	[PerformanceLength] [varchar](20) NULL,
	[IsGuest] [bit] NULL,
 CONSTRAINT [PK_evpShowDatePerformer] PRIMARY KEY CLUSTERED 
(
	[ShowDatePerformerId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpVenue]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[evpVenue](
	[VenueId] [uniqueidentifier] NOT NULL,
	[VenueName] [nvarchar](100) NULL,
	[VenueStatus] [int] NOT NULL,
	[VenueUrlFriendlyName] [varchar](100) NULL,
	[LocationAreaId] [uniqueidentifier] NULL,
	[IsDefault] [bit] NULL,
	[VenueDesc] [ntext] NULL,
	[Address1] [nvarchar](100) NULL,
	[Address2] [nvarchar](100) NULL,
	[AddressSuburb] [nvarchar](100) NULL,
	[AddressState] [nvarchar](100) NULL,
	[AddressPostcode] [nvarchar](20) NULL,
 CONSTRAINT [PK_Venue] PRIMARY KEY CLUSTERED 
(
	[VenueId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[evpPerformerLocation]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpPerformerLocation](
	[PerformerId] [uniqueidentifier] NOT NULL,
	[LocationAreaId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpPerformerLocation] PRIMARY KEY CLUSTERED 
(
	[PerformerId] ASC,
	[LocationAreaId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpVenueLiked]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpVenueLiked](
	[MemberId] [uniqueidentifier] NOT NULL,
	[LikedId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_evpVenueLiked] PRIMARY KEY CLUSTERED 
(
	[MemberId] ASC,
	[LikedId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[evpVenueAdmin]    Script Date: 11/25/2015 14:22:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[evpVenueAdmin](
	[AdminForId] [uniqueidentifier] NOT NULL,
	[MemberId] [uniqueidentifier] NOT NULL,
 CONSTRAINT [PK_VenueAdmin] PRIMARY KEY CLUSTERED 
(
	[AdminForId] ASC,
	[MemberId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  ForeignKey [FK_evpConvMember_evpConversation]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpConvMember]  WITH CHECK ADD  CONSTRAINT [FK_evpConvMember_evpConversation] FOREIGN KEY([ConversationId])
REFERENCES [dbo].[evpConversation] ([ConversationId])
GO
ALTER TABLE [dbo].[evpConvMember] CHECK CONSTRAINT [FK_evpConvMember_evpConversation]
GO
/****** Object:  ForeignKey [FK_evpConvMember_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpConvMember]  WITH CHECK ADD  CONSTRAINT [FK_evpConvMember_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpConvMember] CHECK CONSTRAINT [FK_evpConvMember_evpMember]
GO
/****** Object:  ForeignKey [FK_evpConvMessage_evpConversation]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpConvMessage]  WITH CHECK ADD  CONSTRAINT [FK_evpConvMessage_evpConversation] FOREIGN KEY([ConversationId])
REFERENCES [dbo].[evpConversation] ([ConversationId])
GO
ALTER TABLE [dbo].[evpConvMessage] CHECK CONSTRAINT [FK_evpConvMessage_evpConversation]
GO
/****** Object:  ForeignKey [FK_evpConvMessage_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpConvMessage]  WITH CHECK ADD  CONSTRAINT [FK_evpConvMessage_evpMember] FOREIGN KEY([FromId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpConvMessage] CHECK CONSTRAINT [FK_evpConvMessage_evpMember]
GO
/****** Object:  ForeignKey [FK_evpFestival_evpLocationCountry]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpFestival]  WITH CHECK ADD  CONSTRAINT [FK_evpFestival_evpLocationCountry] FOREIGN KEY([LocationCountryId])
REFERENCES [dbo].[evpLocationCountry] ([LocationCountryId])
GO
ALTER TABLE [dbo].[evpFestival] CHECK CONSTRAINT [FK_evpFestival_evpLocationCountry]
GO
/****** Object:  ForeignKey [FK_evpFestivalAdmin_evpFestival]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpFestivalAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpFestivalAdmin_evpFestival] FOREIGN KEY([AdminForId])
REFERENCES [dbo].[evpFestival] ([FestivalId])
GO
ALTER TABLE [dbo].[evpFestivalAdmin] CHECK CONSTRAINT [FK_evpFestivalAdmin_evpFestival]
GO
/****** Object:  ForeignKey [FK_evpFestivalAdmin_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpFestivalAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpFestivalAdmin_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpFestivalAdmin] CHECK CONSTRAINT [FK_evpFestivalAdmin_evpMember]
GO
/****** Object:  ForeignKey [FK_evpGoingTo_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpGoingTo]  WITH CHECK ADD  CONSTRAINT [FK_evpGoingTo_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpGoingTo] CHECK CONSTRAINT [FK_evpGoingTo_evpMember]
GO
/****** Object:  ForeignKey [FK_evpGoingTo_evpShowDate]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpGoingTo]  WITH CHECK ADD  CONSTRAINT [FK_evpGoingTo_evpShowDate] FOREIGN KEY([ShowDateId])
REFERENCES [dbo].[evpShowDate] ([ShowDateId])
GO
ALTER TABLE [dbo].[evpGoingTo] CHECK CONSTRAINT [FK_evpGoingTo_evpShowDate]
GO
/****** Object:  ForeignKey [FK_evpHost_evpLocationCountry]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpHost]  WITH CHECK ADD  CONSTRAINT [FK_evpHost_evpLocationCountry] FOREIGN KEY([LocationCountryId])
REFERENCES [dbo].[evpLocationCountry] ([LocationCountryId])
GO
ALTER TABLE [dbo].[evpHost] CHECK CONSTRAINT [FK_evpHost_evpLocationCountry]
GO
/****** Object:  ForeignKey [FK_evpHostAdmin_evpHost]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpHostAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpHostAdmin_evpHost] FOREIGN KEY([AdminForId])
REFERENCES [dbo].[evpHost] ([HostId])
GO
ALTER TABLE [dbo].[evpHostAdmin] CHECK CONSTRAINT [FK_evpHostAdmin_evpHost]
GO
/****** Object:  ForeignKey [FK_evpHostAdmin_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpHostAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpHostAdmin_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpHostAdmin] CHECK CONSTRAINT [FK_evpHostAdmin_evpMember]
GO
/****** Object:  ForeignKey [FK_evpHostLiked_evpHost]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpHostLiked]  WITH CHECK ADD  CONSTRAINT [FK_evpHostLiked_evpHost] FOREIGN KEY([LikedId])
REFERENCES [dbo].[evpHost] ([HostId])
GO
ALTER TABLE [dbo].[evpHostLiked] CHECK CONSTRAINT [FK_evpHostLiked_evpHost]
GO
/****** Object:  ForeignKey [FK_evpLocationArea_evpLocationRegion]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpLocationArea]  WITH CHECK ADD  CONSTRAINT [FK_evpLocationArea_evpLocationRegion] FOREIGN KEY([LocationRegionId])
REFERENCES [dbo].[evpLocationRegion] ([LocationRegionId])
GO
ALTER TABLE [dbo].[evpLocationArea] CHECK CONSTRAINT [FK_evpLocationArea_evpLocationRegion]
GO
/****** Object:  ForeignKey [FK_evpLocationRegion_evpLocationCountry]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpLocationRegion]  WITH CHECK ADD  CONSTRAINT [FK_evpLocationRegion_evpLocationCountry] FOREIGN KEY([LocationCountryId])
REFERENCES [dbo].[evpLocationCountry] ([LocationCountryId])
GO
ALTER TABLE [dbo].[evpLocationRegion] CHECK CONSTRAINT [FK_evpLocationRegion_evpLocationCountry]
GO
/****** Object:  ForeignKey [FK_evpMemberFB_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpMemberFB]  WITH CHECK ADD  CONSTRAINT [FK_evpMemberFB_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpMemberFB] CHECK CONSTRAINT [FK_evpMemberFB_evpMember]
GO
/****** Object:  ForeignKey [FK_evpPerformerAdmin_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerAdmin_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpPerformerAdmin] CHECK CONSTRAINT [FK_evpPerformerAdmin_evpMember]
GO
/****** Object:  ForeignKey [FK_evpPerformerAdmin_evpPerformer]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerAdmin_evpPerformer] FOREIGN KEY([AdminForId])
REFERENCES [dbo].[evpPerformer] ([PerformerId])
GO
ALTER TABLE [dbo].[evpPerformerAdmin] CHECK CONSTRAINT [FK_evpPerformerAdmin_evpPerformer]
GO
/****** Object:  ForeignKey [FK_evpPerformerLiked_evpPerformer]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerLiked]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerLiked_evpPerformer] FOREIGN KEY([LikedId])
REFERENCES [dbo].[evpPerformer] ([PerformerId])
GO
ALTER TABLE [dbo].[evpPerformerLiked] CHECK CONSTRAINT [FK_evpPerformerLiked_evpPerformer]
GO
/****** Object:  ForeignKey [FK_evpPerformerLocation_evpLocationArea]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerLocation]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerLocation_evpLocationArea] FOREIGN KEY([LocationAreaId])
REFERENCES [dbo].[evpLocationArea] ([LocationAreaId])
GO
ALTER TABLE [dbo].[evpPerformerLocation] CHECK CONSTRAINT [FK_evpPerformerLocation_evpLocationArea]
GO
/****** Object:  ForeignKey [FK_evpPerformerLocation_evpPerformer]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerLocation]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerLocation_evpPerformer] FOREIGN KEY([PerformerId])
REFERENCES [dbo].[evpPerformer] ([PerformerId])
GO
ALTER TABLE [dbo].[evpPerformerLocation] CHECK CONSTRAINT [FK_evpPerformerLocation_evpPerformer]
GO
/****** Object:  ForeignKey [FK_evpPerformerMember_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerMember]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerMember_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpPerformerMember] CHECK CONSTRAINT [FK_evpPerformerMember_evpMember]
GO
/****** Object:  ForeignKey [FK_evpPerformerMember_evpPerformer]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpPerformerMember]  WITH CHECK ADD  CONSTRAINT [FK_evpPerformerMember_evpPerformer] FOREIGN KEY([PerformerId])
REFERENCES [dbo].[evpPerformer] ([PerformerId])
GO
ALTER TABLE [dbo].[evpPerformerMember] CHECK CONSTRAINT [FK_evpPerformerMember_evpPerformer]
GO
/****** Object:  ForeignKey [FK_evpShowAdmin_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpShowAdmin_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpShowAdmin] CHECK CONSTRAINT [FK_evpShowAdmin_evpMember]
GO
/****** Object:  ForeignKey [FK_evpShowAdmin_evpShow]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpShowAdmin_evpShow] FOREIGN KEY([AdminForId])
REFERENCES [dbo].[evpShow] ([ShowId])
GO
ALTER TABLE [dbo].[evpShowAdmin] CHECK CONSTRAINT [FK_evpShowAdmin_evpShow]
GO
/****** Object:  ForeignKey [FK_evpShowDate_evpShow]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowDate]  WITH CHECK ADD  CONSTRAINT [FK_evpShowDate_evpShow] FOREIGN KEY([ShowId])
REFERENCES [dbo].[evpShow] ([ShowId])
GO
ALTER TABLE [dbo].[evpShowDate] CHECK CONSTRAINT [FK_evpShowDate_evpShow]
GO
/****** Object:  ForeignKey [FK_evpShowDatePerformer_evpPerformer]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowDatePerformer]  WITH CHECK ADD  CONSTRAINT [FK_evpShowDatePerformer_evpPerformer] FOREIGN KEY([PerformerId])
REFERENCES [dbo].[evpPerformer] ([PerformerId])
GO
ALTER TABLE [dbo].[evpShowDatePerformer] CHECK CONSTRAINT [FK_evpShowDatePerformer_evpPerformer]
GO
/****** Object:  ForeignKey [FK_evpShowDatePerformer_evpShowDate]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowDatePerformer]  WITH CHECK ADD  CONSTRAINT [FK_evpShowDatePerformer_evpShowDate] FOREIGN KEY([ShowDateId])
REFERENCES [dbo].[evpShowDate] ([ShowDateId])
GO
ALTER TABLE [dbo].[evpShowDatePerformer] CHECK CONSTRAINT [FK_evpShowDatePerformer_evpShowDate]
GO
/****** Object:  ForeignKey [FK_evpShowHost_evpHost]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowHost]  WITH CHECK ADD  CONSTRAINT [FK_evpShowHost_evpHost] FOREIGN KEY([HostId])
REFERENCES [dbo].[evpHost] ([HostId])
GO
ALTER TABLE [dbo].[evpShowHost] CHECK CONSTRAINT [FK_evpShowHost_evpHost]
GO
/****** Object:  ForeignKey [FK_evpShowHost_evpShow]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpShowHost]  WITH CHECK ADD  CONSTRAINT [FK_evpShowHost_evpShow] FOREIGN KEY([ShowId])
REFERENCES [dbo].[evpShow] ([ShowId])
GO
ALTER TABLE [dbo].[evpShowHost] CHECK CONSTRAINT [FK_evpShowHost_evpShow]
GO
/****** Object:  ForeignKey [FK_evpVenue_evpLocationArea]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpVenue]  WITH CHECK ADD  CONSTRAINT [FK_evpVenue_evpLocationArea] FOREIGN KEY([LocationAreaId])
REFERENCES [dbo].[evpLocationArea] ([LocationAreaId])
GO
ALTER TABLE [dbo].[evpVenue] CHECK CONSTRAINT [FK_evpVenue_evpLocationArea]
GO
/****** Object:  ForeignKey [FK_evpVenueAdmin_evpMember]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpVenueAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpVenueAdmin_evpMember] FOREIGN KEY([MemberId])
REFERENCES [dbo].[evpMember] ([MemberId])
GO
ALTER TABLE [dbo].[evpVenueAdmin] CHECK CONSTRAINT [FK_evpVenueAdmin_evpMember]
GO
/****** Object:  ForeignKey [FK_evpVenueAdmin_evpVenue]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpVenueAdmin]  WITH CHECK ADD  CONSTRAINT [FK_evpVenueAdmin_evpVenue] FOREIGN KEY([AdminForId])
REFERENCES [dbo].[evpVenue] ([VenueId])
GO
ALTER TABLE [dbo].[evpVenueAdmin] CHECK CONSTRAINT [FK_evpVenueAdmin_evpVenue]
GO
/****** Object:  ForeignKey [FK_evpVenueLiked_evpVenue]    Script Date: 11/25/2015 14:22:00 ******/
ALTER TABLE [dbo].[evpVenueLiked]  WITH CHECK ADD  CONSTRAINT [FK_evpVenueLiked_evpVenue] FOREIGN KEY([LikedId])
REFERENCES [dbo].[evpVenue] ([VenueId])
GO
ALTER TABLE [dbo].[evpVenueLiked] CHECK CONSTRAINT [FK_evpVenueLiked_evpVenue]
GO
