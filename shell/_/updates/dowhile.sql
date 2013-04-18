DROP PROCEDURE IF EXISTS dowhile;
DELIMITER //
CREATE PROCEDURE dowhile(arg text, cnt INT(10))
BEGIN
  DECLARE v1 INT DEFAULT cnt;
  SET @stat = arg;
  PREPARE smtn FROM @stat;
  WHILE v1 > 0 DO
    EXECUTE smtn;
    SET v1 = v1 - 1;
  END WHILE;
END;
DELIMITER ;