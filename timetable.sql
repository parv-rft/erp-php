-- Table structure for table `timetable`
CREATE TABLE IF NOT EXISTS `timetable` (
  `timetable_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `starting_time` varchar(20) NOT NULL,
  `ending_time` varchar(20) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`timetable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Drop the table if it exists to avoid conflicts
DROP TABLE IF EXISTS `calendar_timetable`;

-- Create the calendar_timetable table
CREATE TABLE `calendar_timetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_slot` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `class_section_idx` (`class_id`, `section_id`),
  KEY `date_idx` (`date`),
  UNIQUE KEY `unique_slot` (`class_id`, `section_id`, `date`, `time_slot`),
  CONSTRAINT `fk_calendar_timetable_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_calendar_timetable_section` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_calendar_timetable_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_calendar_timetable_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 