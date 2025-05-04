-- Teacher Attendance Table
-- This SQL file creates the teacher_attendance table structure
-- Used for tracking teacher attendance in the PHP ERP system

CREATE TABLE IF NOT EXISTS `teacher_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=present, 2=absent, 3=late',
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Status codes:
-- 1 = Present
-- 2 = Absent
-- 3 = Late 