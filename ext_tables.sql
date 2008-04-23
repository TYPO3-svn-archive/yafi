# $Id: $

#
# Table structure for table 'tt_news'
#
CREATE TABLE tt_news (
	tx_yafi_import_id varchar(255) DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_yafi_feed'
#
CREATE TABLE tx_yafi_feed (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	url varchar(255) DEFAULT '' NOT NULL,
	import_interval varchar(255) DEFAULT '' NOT NULL,
	expires varchar(255) DEFAULT '' NOT NULL,
	last_import int(11) DEFAULT '0' NOT NULL,
	last_import_localtime int(11) DEFAULT '0' NOT NULL,
	importer_config text NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_yafi_importer'
#
CREATE TABLE tx_yafi_importer (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	importer_type varchar(255) DEFAULT '0' NOT NULL,
	importer_conf mediumtext,
	irre_parent_uid int(11) DEFAULT '0' NOT NULL,
	irre_parent_table tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);