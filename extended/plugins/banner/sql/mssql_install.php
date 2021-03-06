<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Banner Plugin 1.1                                                          |
// +---------------------------------------------------------------------------+
// | Installation SQL                                                          |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000-2009 by the following authors:                         |
// |                                                                           |
// | Authors: Hiroron            - hiroron AT hiroron DOT com                  |
// |          Tony Bibbs         - tony AT tonybibbs DOT com                   |
// |          Mark Limburg      - mlimburg AT users DOT sourceforge DOT net    |
// |          Jason Whittenburg - jwhitten AT securitygeeks DOT com            |
// |          Dirk Haun         - dirk AT haun-online DOT de                   |
// |          Trinity Bays      - trinity93 AT gmail DOT com                   |
// |          Randy Kolenko     - randy AT nextide DOT ca                      |
// | Presented by:IvyWe          - http://www.ivywe.co.jp                      |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is licensed under the terms of the GNU General Public License|
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                      |
// | See the GNU General Public License for more details.                      |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+


$_SQL[] = "
CREATE TABLE [dbo].[{$_TABLES['bannercategories']}] (
    [cid] [varchar] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [pid] [varchar] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [category] [varchar] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [description] [varchar] (5000) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [tid] [varchar] (20) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [created] [datetime] NULL ,
    [modified] [datetime] NULL ,
    [owner_id] [numeric](8, 0) NOT NULL ,
    [group_id] [numeric](8, 0) NOT NULL ,
    [perm_owner] [tinyint] NOT NULL ,
    [perm_group] [tinyint] NOT NULL ,
    [perm_members] [tinyint] NOT NULL ,
    [perm_anon] [tinyint] NOT NULL
) ON [PRIMARY]
";

$_SQL[] = "
CREATE TABLE [dbo].[{$_TABLES['banner']}] (
    [bid] [varchar] (40) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [cid] [varchar] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [url] [varchar] (255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [description] [varchar] (5000) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [title] [varchar] (96) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [hits] [int] NOT NULL ,
    [publishstart] [datetime] NULL ,
    [publishend] [datetime] NULL ,
    [date] [datetime] NULL ,
    [owner_id] [numeric](8, 0) NOT NULL ,
    [group_id] [numeric](8, 0) NOT NULL ,
    [perm_owner] [tinyint] NOT NULL ,
    [perm_group] [tinyint] NOT NULL ,
    [perm_members] [tinyint] NOT NULL ,
    [perm_anon] [tinyint] NOT NULL
) ON [PRIMARY]
";

$_SQL[] = "
CREATE TABLE [dbo].[{$_TABLES['bannersubmission']}] (
    [bid] [varchar] (40) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [cid] [varchar] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [url] [varchar] (255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [description] [varchar] (5000) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [title] [varchar] (96) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [hits] [int] NULL ,
    [date] [datetime] NULL ,
    [owner_id] [numeric](8, 0) NOT NULL
) ON [PRIMARY]
";

$_SQL[] = "ALTER TABLE [dbo].[{$_TABLES['bannercategories']}] ADD
    CONSTRAINT [PK_{$_TABLES['bannercategories']}] PRIMARY KEY  CLUSTERED
    (
        [pid]
    )  ON [PRIMARY]
";

$_SQL[] = "ALTER TABLE [dbo].[{$_TABLES['banner']}] ADD
    CONSTRAINT [DF_{$_TABLES['banner']}_perm_group] DEFAULT (3) FOR [perm_group],
    CONSTRAINT [DF_{$_TABLES['banner']}_perm_anon] DEFAULT (2) FOR [perm_anon],
    CONSTRAINT [DF_{$_TABLES['banner']}_perm_members] DEFAULT (2) FOR [perm_members],
    CONSTRAINT [DF_{$_TABLES['banner']}_perm_owner] DEFAULT (3) FOR [perm_owner],
    CONSTRAINT [DF_{$_TABLES['banner']}_hits] DEFAULT (0) FOR [hits],
    CONSTRAINT [PK_{$_TABLES['banner']}] PRIMARY KEY  CLUSTERED
    (
        [bid]
    )  ON [PRIMARY]
";

$_SQL[] = "ALTER TABLE [dbo].[{$_TABLES['bannersubmission']}] ADD
    CONSTRAINT [PK_{$_TABLES['bannersubmission']}] PRIMARY KEY  CLUSTERED
    (
        [bid]
    )  ON [PRIMARY]
";

?>
