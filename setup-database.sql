CREATE TABLE First(
  code varchar(15),
  name varchar(50) NOT NULL,
  PRIMARY KEY (code)
);

INSERT INTO First VALUES ('COSC326','Effective Programming');
INSERT INTO First VALUES ('COSC349','Cloud Computing Architecture');

CREATE TABLE Second(
  code varchar(15),
  name varchar(50) NOT NULL,
  PRIMARY KEY (code)
);

INSERT INTO Second VALUES ('COSC326','Effective Programming');
INSERT INTO Second VALUES ('COSC349','Cloud Computing Architecture');


CREATE TABLE Third(
  code varchar(15),
  name varchar(50) NOT NULL,
  PRIMARY KEY (code)
);

INSERT INTO Third VALUES ('COSC326','Effective Programming');
INSERT INTO Third VALUES ('COSC349','Cloud Computing Architecture');
