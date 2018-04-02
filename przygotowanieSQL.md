CREATE DATABASE IF NOT EXISTS LobsterStorage;
USE LobsterStorage;
CREATE TABLE login(id INT(11) PRIMARY KEY AUTO_INCREMENT, username VARCHAR(20) NOT NULL, password VARCHAR(11) NOT NULL, ugroup INT(2) DEFAULT 5);
CREATE TABLE files(fileId INT(11) PRIMARY KEY AUTO_INCREMENT, userId INT(11) NOT NULL, name VARCHAR(200), type VARCHAR(11), isPublic tinyint(1), discName varchar(150), virtualPath NOT NULL VARCHAR(300) DEFAULT '/', discType VARCHAR(25) NOT NULL DEFAULT 'GENERAL');
SELECT login.id
FROM login
INNER JOIN files on login.id = files.userId;
INSERT INTO login(id, username, password, ugroup) VALUES (NULL, 'abc', '123', '1');
