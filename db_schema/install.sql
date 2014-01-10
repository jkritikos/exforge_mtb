#Users table
CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `fname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
 `lname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
 `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
 `active` int(1) DEFAULT '1',
 `external` int(1),
 `role_id` int(1),
 PRIMARY KEY (`id`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#insert root user
insert into users (fname,lname,email,created,modified,password,active,external,role_id) values ('Jason', 'Kritikos', 'iakritikos@cosmote.gr',now(),now(),'81dc9bdb52d04dc20036dbd8313ed055',1,0,2)

#User roles table
CREATE TABLE `roles` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#User roles data
insert into roles (id,name) values (1,'Content Editor');
insert into roles (id,name) values (2,'Admin');

#Question categories table
CREATE TABLE `categories` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#Question categories data
insert into categories (id,name) values (1,'Επιστήμη');
insert into categories (id,name) values (2,'Κινηματογράφος');
insert into categories (id,name) values (3,'Γεωγραφία');
insert into categories (id,name) values (4,'Αθλητικά');
insert into categories (id,name) values (5,'Τεχνολογία');
insert into categories (id,name) values (6,'Ιστορία');
insert into categories (id,name) values (7,'Μουσική');
insert into categories (id,name) values (8,'Τέχνες');
insert into categories (id,name) values (9,'Ζώα/Φυτά');
insert into categories (id,name) values (10,'Lifestyle');

#Question
CREATE TABLE `questions` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `category_id` int(11) NOT NULL,
 `question` varchar(378) COLLATE utf8_unicode_ci NOT NULL,
 `answer_a` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
 `answer_b` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
 `answer_c` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
 `answer_d` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
 `correct` int(1),
 `user_id` int(11) NOT NULL,
 `language_id` int(11),
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#tags table
CREATE TABLE `tags` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `tag` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#Question tags table
CREATE TABLE `question_tags` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `question_id` int(11) NOT NULL,
 `tag_id` int(11) NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#High scores table
CREATE TABLE `scores` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `category_id` int(11) NOT NULL,
 `player_id` int(11) NOT NULL,
 `score` int(11) NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#Players table
CREATE TABLE `players` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(256) NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

#Feedback table
CREATE TABLE `feedback` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(256) NOT NULL,
 `feedback` text COLLATE utf8_unicode_ci NOT NULL,
 `player_id` int(11) not null,
 `app_type_id` int(4) not null,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 primary key(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci