CREATE TABLE users
(
	id int(11) NOT NULL AUTO_INCREMENT,
	username varchar(30) NOT NULL,
	password varchar(40) NOT NULL,
	first_name varchar(30),
	last_name varchar(30),
	city varchar(30),
	adress varchar(100),
	post_number int(10),
	phone_number varchar(30),
	email varchar(30),
	PRIMARY KEY (ID)
);

CREATE TABLE authors
(
	id int(11) NOT NULL AUTO_INCREMENT,
	first_name varchar(30),
	last_name varchar(30),
	about text,
	PRIMARY KEY (ID)
);
ALTER TABLE authors ADD about text;

CREATE TABLE books
(
	id int(11) NOT NULL AUTO_INCREMENT,
	author_id int(11) NOT NULL,
	name varchar(30) not null,
	short_info varchar(200),
	about_book text,
	about_authors text,
	book_photo varchar(200),
	PRIMARY KEY (ID),
	FOREIGN key ( author_id) references authors(id)
);

alter table books add category_id int(11);

CREATE TABLE publishers
(
	id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(30),
	about text,
	PRIMARY KEY (ID)
);
ALTER TABLE authors ADD about text;





-- CREATE TABLE categories
-- 	(
-- 		id int(11) NOT NULL AUTO_INCREMENT,
-- 		parent_cat_id int(11) not null,
-- 		name varchar(50) NOT NULL,
-- 		description varchar(100),

-- 		PRIMARY KEY (ID),
-- 		FOREIGN key ( parent_cat_id) references categories(id)
-- 	);

-- CREATE TABLE users
-- (
-- id int(11) NOT NULL AUTO_INCREMENT,
-- username varchar(30) NOT NULL,
-- password varchar(40) NOT NULL,
-- first_name varchar(30),
-- last_name varchar(30),
-- city varchar(30),
-- adress varchar(100),
-- post_number int(10),
-- phone_number varchar(30),
-- email varchar(30),
-- PRIMARY KEY (ID)
-- );


-- CREATE TABLE ads
-- 	(
-- 		id int(11) NOT NULL AUTO_INCREMENT,
-- 		category_id int(11) not null,
-- 		user_id int(11) not null,
		
-- 		title varchar(100) NOT NULL,
-- 		description text,

-- 		created_at datetime not null DEFAULT CURRENT_TIMESTAMP,
-- 		updated_at datetime not null DEFAULT CURRENT_TIMESTAMP,

-- 		PRIMARY KEY (ID),
-- 		FOREIGN key ( category_id) references categories(id),
-- 		FOREIGN key ( user_id) references users(id)
-- 	);


-- CREATE TABLE common_fields
-- (
-- 	id int(11) NOT NULL AUTO_INCREMENT,
	
-- 	name varchar(100) NOT NULL,
-- 	template_type varchar(100),
-- 	template_lov varchar(200),

-- 	PRIMARY KEY (ID)
-- );

-- CREATE TABLE category_common_fields
-- (
-- 	id int(11) NOT NULL AUTO_INCREMENT,
-- 	category_id int(11) not null,
-- 	common_field_id int(11) not null,
	
-- 	name varchar(50),
-- 	template_type varchar(100),
-- 	template_lov varchar(200),

-- 	PRIMARY KEY (ID),
-- 	FOREIGN key ( category_id ) references categories(id),
-- 	FOREIGN key ( common_field_id ) references common_fields(id)
-- );


-- CREATE TABLE ad_common_fields
-- (
-- 	id int(11) NOT NULL AUTO_INCREMENT,
-- 	ad_id int(11) not null,
-- 	common_field_id int(11) not null,
	
-- 	name varchar(50),
-- 	value varchar(100),

-- 	PRIMARY KEY (ID),
-- 	FOREIGN key ( ad_id ) references ads(id),
-- 	FOREIGN key ( common_field_id ) references category_common_fields(id) on delete cascade
-- );

-- CREATE TABLE photographs
-- (
-- 	id int(11) NOT NULL AUTO_INCREMENT,
-- 	ad_id int(11) not null,
	
-- 	filename varchar(50),
-- 	type varchar(20),
-- 	size int(11),
-- 	caption varchar(50),
-- 	PRIMARY KEY (ID),
-- 	FOREIGN key ( ad_id ) references ads(id)
-- );


"id", "filename", "type", "size", "caption"

