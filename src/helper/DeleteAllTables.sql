# Drop all tables in the current db
DROP PROCEDURE IF EXISTS DropAllTables;
DELIMITER //
CREATE PROCEDURE DropAllTables()
BEGIN
    SET FOREIGN_KEY_CHECKS = 0;
    SELECT DATABASE() FROM DUAL INTO @current_dbname;
    SET @tables = NULL;
    SET SESSION group_concat_max_len = 1000000;
    SELECT GROUP_CONCAT(table_schema, '.', table_name) INTO @tables
      FROM information_schema.tables
      WHERE table_schema = @current_dbname; -- specify DB name here.
    SET @tables = CONCAT('DROP TABLE ', @tables);
    PREPARE stmt FROM @tables;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    SET FOREIGN_KEY_CHECKS = 1;
END //
DELIMITER ;
