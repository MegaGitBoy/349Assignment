CREATE TABLE First(
  code varchar(15),
  name varchar(50) NOT NULL,
  PRIMARY KEY (code)
);

INSERT INTO First VALUES ('Happy','This code is working');


CREATE TABLE Second(
  code varchar(15),
  name varchar(50) NOT NULL,
  PRIMARY KEY (code)
);

INSERT INTO Second VALUES ('Happy','The Haikus are being stored');


CREATE TABLE Third(
  code varchar(15),
  name varchar(50) NOT NULL,
  PRIMARY KEY (code)
);

INSERT INTO Third VALUES ('Happy','And its amazing');
