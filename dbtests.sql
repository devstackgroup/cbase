CREATE DATABASE IF NOT EXISTS dbtests DEFAULT CHARACTER SET utf8;
CREATE USER 'dbunit'@'localhost';
GRANT ALL ON dbtests.* to 'dbunit'@'localhost';

CREATE TABLE IF NOT EXISTS dbtests.testTable (
    id INT NOT NULL AUTO_INCREMENT,
    field INT,
    PRIMARY KEY (id)
);