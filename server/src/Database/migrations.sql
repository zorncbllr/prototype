USE aroroy_database;

DROP INDEX name ON voters;
DROP TABLE IF EXISTS voters;
CREATE TABLE voters (
    voterId CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(150) NOT NULL,
    precinct VARCHAR(50) NOT NULL,
    isGiven BOOLEAN DEFAULT false
);
