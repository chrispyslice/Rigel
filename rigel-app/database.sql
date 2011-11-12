delimiter $$

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_email` varchar(45) NOT NULL,
  `user_avatar` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`),
  UNIQUE KEY `user_avatar_UNIQUE` (`user_avatar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `group` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(45) NOT NULL,
  `group_description` varchar(100) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `group_member` (
  `group_member_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`group_member_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `group_id` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `expences` (
  `expences_id` int(11) NOT NULL,
  `group_member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`expences_id`),
  KEY `group_member_id` (`group_member_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `group_member_id_expences` FOREIGN KEY (`group_member_id`) REFERENCES `group_member` (`group_member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `group_id_expences` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$delimiter $$


delimiter $$

CREATE TABLE `theme` (
  `theme_id` int(11) NOT NULL,
  `css_file` varchar(50) NOT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

CREATE TABLE `user_theme` (
  `user_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  KEY `user_id_user_theme` (`user_id`),
  KEY `theme_id_user_theme` (`theme_id`),
  CONSTRAINT `user_id_user_theme` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `theme_id_user_theme` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`theme_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$


