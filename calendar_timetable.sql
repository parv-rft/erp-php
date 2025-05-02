-- Drop the table if it exists to avoid conflicts
DROP TABLE IF EXISTS `calendar_timetable`;

-- Create the calendar_timetable table
CREATE TABLE `calendar_timetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `day_of_week` varchar(20) NOT NULL, -- 'monday', 'tuesday', etc.
  `time_slot_start` time NOT NULL,
  `time_slot_end` time NOT NULL,
  `month` int(2) NOT NULL, -- 1-12 for January-December
  `year` int(4) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `class_section_idx` (`class_id`, `section_id`),
  KEY `day_time_idx` (`day_of_week`, `time_slot_start`),
  KEY `month_year_idx` (`month`, `year`),
  UNIQUE KEY `unique_slot` (`class_id`, `section_id`, `day_of_week`, `time_slot_start`, `month`, `year`),
  CONSTRAINT `fk_calendar_timetable_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_calendar_timetable_section` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_calendar_timetable_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_calendar_timetable_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 