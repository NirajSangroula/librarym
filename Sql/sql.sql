create database library;
create table user(id int not null auto_increment primary key, username varchar(50) not null, 
	password varchar(50) not null, name varchar(50) not null, address varchar(50) not null, 
	phone_no varchar(20) not null, 
	email varchar(50), created_date datetime not null, type varchar(10) not null);
create table subject(id int not null auto_increment primary key, description varchar(100) not null, 
	creator_id int not null);
create table book(id int not null auto_increment primary key, book_name varchar(50) not null, sub_id int not null, author varchar(100) not null,
 publication varchar(100) not null, created_date timestamp not null, creator_id int not null);
create table book_location(id int not null primary key auto_increment, rack_no varchar(10),
 description varchar(100) not null, book_id int not null, availability varchar(6)  not null,fine_rate float not null);
 create table borrow(id int not null auto_increment primary key, book_id int not null, user_id int not null, 
 	borrowed_date timestamp not null, till_date timestamp not null default current_timestamp, status varchar(10) not null);
 create table returns(id int not null auto_increment primary key, borrow_id int not null, return_date timestamp, finebilled float);

 	alter table user add column authID varchar(100) null;
INSERT INTO `user` (`id`, `username`, `password`, `name`, `address`, `phone_no`, `email`, `created_date`, `type`, `authID`) VALUES (NULL, 'admin', 'sa3tHJ3/KuYvI', 'admin', 'admin address', '984393459', NULL, '2020-07-11 15:53:54', 'admin', NULL);
