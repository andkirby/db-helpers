# Reset first admin user
delimiter //
DROP PROCEDURE IF EXISTS ResetAdmin//
CREATE PROCEDURE ResetAdmin (admin_password VARCHAR(255))
  BEGIN
    -- Update password
    UPDATE `admin_user` SET `username` = 'admin', `password` = md5(admin_password)
      LIMIT 1;
    -- Reset failures
    UPDATE `admin_user` SET `failures_num` = 0, `is_active` = 1, `first_failure` = NULL, `lock_expires` = NULL
      LIMIT 1;
  END//
delimiter ;
