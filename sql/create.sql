CREATE DATABASE movies;

use movies;


CREATE TABLE user (
  user_id int unsigned NOT NULL auto_increment,
  username varchar(30) NOT NULL unique,
  password varchar(30) NOT NULL,
  PRIMARY KEY (user_id)
) ENGINE=InnoDB;

CREATE TABLE list (
  list_id int unsigned NOT NULL auto_increment,
  list_name varchar(60) NOT NULL,
  user_id int unsigned,
  PRIMARY KEY (list_id),
  FOREIGN KEY (user_id) REFERENCES user(user_id)
    ON DELETE CASCADE -- delete user and list is deleted
) ENGINE=InnoDB;

CREATE TABLE movie (
  movie_id int unsigned NOT NULL auto_increment,
  tmdb_id int unsigned NOT NULL,
  title varchar(60) NOT NULL,
  year year NOT NULL,
  PRIMARY KEY (movie_id)
) ENGINE=InnoDB;

CREATE TABLE entry_in_list (
  entry_id int unsigned NOT NULL auto_increment,
  list_id int unsigned,
  movie_id int unsigned,
  timestamp timestamp CURRENT_TIMESTAMP,
  PRIMARY KEY (entry_id),
  FOREIGN KEY (list_id) REFERENCES list(list_id)
    ON DELETE CASCADE,
  FOREIGN KEY (movie_id) REFERENCES movie(movie_id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE comment (
  comment_id int unsigned NOT NULL auto_increment,
  comment text NOT NULL,
  user varchar(30) NOT NULL,
  movie_id int unsigned NOT NULL,
  entry_id int unsigned,
  timestamp timestamp CURRENT_TIMESTAMP,
  PRIMARY KEY (comment_id),
  FOREIGN KEY (entry_id) REFERENCES entry_in_list(entry_id)
    ON DELETE CASCADE
) ENGINE=InnoDB;
