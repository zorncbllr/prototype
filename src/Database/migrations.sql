USE aroroy_database;

CREATE TABLE voters (
    voterId CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(150) NOT NULL,
    precinct VARCHAR(50) NOT NULL,
    address VARCHAR(200) NOT NULL,
    isGiven BOOLEAN DEFAULT false
);
