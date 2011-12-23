DROP TABLE IF EXISTS test_content;

CREATE TABLE test_content (
    id INT(10) PRIMARY KEY AUTO_INCREMENT,
    id_person INT(10),
    id_content_field INT(10),
    version INT(1),
    content VARCHAR(255),
    checksum VARCHAR(40)
);

# Person
# 1 = John
# 2 = Bob
# 3 = Sally

# content_id_field
# 1 = Fav fruit
# 2 = Fav Colour
# 3 = Fav Movie

# version
# 1 = main version
# 2 = unmoderated version 

# Johns main content 
INSERT INTO test_content VALUES (null, 1, 1, 1, 'apple', 'checksum1-aa');
INSERT INTO test_content VALUES (null, 1, 2, 1, 'blue', 'checksum1-ab');
INSERT INTO test_content VALUES (null, 1, 3, 1, 'shawkshank', 'checksum1-ac');

# Johns unmoderated content - note his fav films remains same
# but now he likes banana, red
INSERT INTO test_content VALUES (null, 1, 1, 2, 'banana', 'checksum1-aa2');
INSERT INTO test_content VALUES (null, 1, 2, 2, 'red', 'checksum1-ab2');
INSERT INTO test_content VALUES (null, 1, 3, 2, 'shawshank', 'checksum1-ac');

# Sally
INSERT INTO test_content VALUES (null, 3, 1, 1, 'mango', 'checksum3-aa');
INSERT INTO test_content VALUES (null, 3, 2, 1, 'yellow', 'checksum3-ab');
INSERT INTO test_content VALUES (null, 3, 3, 1, 'gladiator', 'checksum3-ac');

INSERT INTO test_content VALUES (null, 3, 1, 1, 'mango', 'checksum3-aa');
INSERT INTO test_content VALUES (null, 3, 2, 1, 'yellow', 'checksum3-ab');
INSERT INTO test_content VALUES (null, 3, 3, 1, 'gladiator', 'checksum3-ac');


# find all fields that need to be updated for John
#SELECT DISTINCT t1.id, t2.content, t2.checksum
#FROM test_content AS t1 JOIN test_content AS t2 
#ON t1.id_person=t2.id_person AND t1.id_content_field=t2.id_content_field
#WHERE t1.id_person = 1 AND t1.checksum!=t2.checksum AND t1.version = 1;


# update version 1 with version 2 data and checksums

UPDATE test_content AS c JOIN
(SELECT DISTINCT t1.id, t2.content, t2.checksum
FROM test_content AS t1 JOIN test_content AS t2 
ON t1.id_person=t2.id_person AND t1.id_content_field=t2.id_content_field
WHERE t1.id_person = 1 AND t1.checksum!=t2.checksum AND t1.version = 1) as tm
ON tm.id=c.id SET c.content=tm.content, c.checksum=tm.checksum;
