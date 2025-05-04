-- Drop the table if it exists to avoid conflicts
DROP TABLE IF EXISTS `teacher_attendance`;

-- Create the teacher_attendance table
CREATE TABLE `teacher_attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1=present, 2=absent, 3=late',
  `remark` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`attendance_id`),
  UNIQUE KEY `teacher_date` (`teacher_id`,`date`),
  KEY `teacher_id` (`teacher_id`),
  KEY `date` (`date`),
  CONSTRAINT `teacher_attendance_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 