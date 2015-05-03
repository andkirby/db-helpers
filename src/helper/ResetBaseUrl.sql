# Reset base URLs in the core_config_data
delimiter //
DROP PROCEDURE IF EXISTS ResetBaseUrl//
CREATE PROCEDURE ResetBaseUrl (source_domain VARCHAR(255), target_domain VARCHAR(255))
BEGIN
    UPDATE `core_config_data` AS `cnf`
    INNER JOIN
        (
          SELECT
            REPLACE(value, source_domain, target_domain) AS value,
                config_id
            FROM core_config_data
            WHERE value LIKE CONCAT("%", source_domain, "%")
        ) AS cnf_source ON cnf_source.config_id = cnf.config_id
    SET cnf.value = cnf_source.value;
END//
delimiter ;
