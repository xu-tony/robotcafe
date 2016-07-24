CREATE TABLE shops (
 id int NOT NULL auto_increment,
 width int NOT NULL,
 height int NOT NULL,
 PRIMARY KEY (id)
);


CREATE TABLE robots (
 id int(11) NOT NULL auto_increment,
 sid int NOT NULL,
 x int NOT NULL,
 y int NOT NULL,
 heading VARCHAR(1) NOT NULL,
 commands VARCHAR(255) NOT NULL,
 PRIMARY KEY (id),
 FOREIGN KEY (sid) REFERENCES shops(id)
);