DROP TABLE IF EXISTS Assemblies;
CREATE TABLE Assemblies (
    node varchar(255) PRIMARY KEY,
    lft INT(2) NOT NULL,
    rgt INT(2) NOT NULL
);

INSERT INTO Assemblies VALUES ('A', 1, 28);
INSERT INTO Assemblies VALUES ('B', 2, 5);
INSERT INTO Assemblies VALUES ('C', 6, 19);
INSERT INTO Assemblies VALUES ('D', 20, 27);
INSERT INTO Assemblies VALUES ('E', 3, 4);
INSERT INTO Assemblies VALUES ('F', 7, 16);
INSERT INTO Assemblies VALUES ('G', 17, 18);
INSERT INTO Assemblies VALUES ('H', 21, 26);
INSERT INTO Assemblies VALUES ('I', 8, 9);
INSERT INTO Assemblies VALUES ('J', 10, 15);
INSERT INTO Assemblies VALUES ('K', 22, 23);
INSERT INTO Assemblies VALUES ('L', 24, 25);
INSERT INTO Assemblies VALUES ('M', 11, 12);
INSERT INTO Assemblies VALUES ('N', 13, 14);

