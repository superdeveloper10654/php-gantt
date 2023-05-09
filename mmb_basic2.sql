/*
 Navicat Premium Data Transfer

 Source Server         : mySQL
 Source Server Type    : MySQL
 Source Server Version : 100427 (10.4.27-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : mmb_basic2

 Target Server Type    : MySQL
 Target Server Version : 100427 (10.4.27-MariaDB)
 File Encoding         : 65001

 Date: 25/04/2023 11:29:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `subdomain` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `administrator_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of accounts
-- ----------------------------
INSERT INTO `accounts` VALUES (1, 'My Company', 'ibex_1624961083', 1);

-- ----------------------------
-- Table structure for gantt_broadcasts
-- ----------------------------
DROP TABLE IF EXISTS `gantt_broadcasts`;
CREATE TABLE `gantt_broadcasts`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` int NULL DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `created` int NULL DEFAULT NULL,
  `programme_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_broadcasts
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_calendar_overrides
-- ----------------------------
DROP TABLE IF EXISTS `gantt_calendar_overrides`;
CREATE TABLE `gantt_calendar_overrides`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `calendar_id` int NOT NULL,
  `start_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `end_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_calendar_overrides
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_calendars
-- ----------------------------
DROP TABLE IF EXISTS `gantt_calendars`;
CREATE TABLE `gantt_calendars`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` int NOT NULL DEFAULT 1,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `start_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '07:00',
  `start_hour` int NOT NULL DEFAULT 7,
  `start_minute` int NOT NULL DEFAULT 0,
  `end_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '17:00',
  `end_hour` int NOT NULL DEFAULT 7,
  `end_minute` int NOT NULL DEFAULT 0,
  `working_day_monday` int NOT NULL DEFAULT 1,
  `working_day_tuesday` int NOT NULL DEFAULT 1,
  `working_day_wednesday` int NOT NULL DEFAULT 1,
  `working_day_thursday` int NOT NULL DEFAULT 1,
  `working_day_friday` int NOT NULL DEFAULT 1,
  `working_day_saturday` int NOT NULL DEFAULT 0,
  `working_day_sunday` int NOT NULL DEFAULT 0,
  `is_default_task_calendar` int NOT NULL DEFAULT 0,
  `is_default_resource_calendar` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_calendars
-- ----------------------------
INSERT INTO `gantt_calendars` VALUES (1, '1', 1, 'Default task calendar', '07:00', 7, 0, '17:00', 17, 0, 1, 1, 1, 1, 1, 0, 0, 1, 0);
INSERT INTO `gantt_calendars` VALUES (2, '1', 2, 'Default resource calendar', '07:00', 7, 0, '17:00', 17, 0, 1, 1, 1, 1, 1, 0, 0, 0, 1);

-- ----------------------------
-- Table structure for gantt_columns
-- ----------------------------
DROP TABLE IF EXISTS `gantt_columns`;
CREATE TABLE `gantt_columns`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` int NOT NULL,
  `task_columns` varchar(10000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '[{\"status\": true,\"wbs\":false,\"text\":true,\"start_date\":true,\"end_date\":false,\"duration_worked\":false,\"progress\":false,\"baseline_start\":false,\"constraint_date\":false,\"baseline_end\":false,\"constraint_type\":false,\"deadline\":false,\"task_calendar\":false,\"resource_id\":false}]',
  `resource_columns` varchar(10000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '[{\"name\":true,\"resource_calendar\":false,\"company\":true,\"notes\":false,\"cost_rate\":true}]',
  `wbs` int NOT NULL DEFAULT 42,
  `text` int NOT NULL DEFAULT 154,
  `start_date` int NOT NULL DEFAULT 110,
  `end_date` int NOT NULL DEFAULT 110,
  `progress` int NOT NULL DEFAULT 80,
  `duration_worked` int NOT NULL DEFAULT 80,
  `baseline_start` int NOT NULL DEFAULT 110,
  `baseline_end` int NOT NULL DEFAULT 110,
  `reference_number` int NOT NULL DEFAULT 80,
  `task_calendar` int NOT NULL DEFAULT 80,
  `deadline` int NOT NULL DEFAULT 110,
  `constraint_type` int NOT NULL DEFAULT 80,
  `constraint_date` int NOT NULL DEFAULT 110,
  `comments` int NOT NULL DEFAULT 80,
  `resource_id` int NOT NULL DEFAULT 110,
  `status` int NOT NULL DEFAULT 40,
  `resource_calendar` int NOT NULL DEFAULT 80,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_columns
-- ----------------------------
INSERT INTO `gantt_columns` VALUES (1, '1', 1, '[{\"status\": true, \"wbs\":false,\"text\":true,\"start_date\":true,\"end_date\":false,\"duration_worked\":false,\"progress\":false,\"baseline_start\":false,\"constraint_date\":false,\"baseline_end\":false,\"constraint_type\":false,\"deadline\":false,\"task_calendar\":false,\"resource_id\":false}]', '[{\"name\":true,\"resource_calendar\":false,\"company\":true,\"notes\":false,\"cost_rate\":true}]', 42, 154, 110, 110, 80, 80, 110, 110, 80, 80, 110, 80, 110, 80, 110, 40, 80);

-- ----------------------------
-- Table structure for gantt_config
-- ----------------------------
DROP TABLE IF EXISTS `gantt_config`;
CREATE TABLE `gantt_config`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `settings` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `columns_json` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `columns_resources_json` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_config
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_files
-- ----------------------------
DROP TABLE IF EXISTS `gantt_files`;
CREATE TABLE `gantt_files`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `hashed_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `extension` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `uploaded` int NULL DEFAULT NULL,
  `uploaded_by` int NULL DEFAULT NULL,
  `url` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_files
-- ----------------------------
INSERT INTO `gantt_files` VALUES (1, 0, 'DemoProject.mpp', 'fcc026ac5db1830c40f4c08f8b03204335b583bce50da3d8b712480c0b57cb0e.mpp', 'mpp', 1682107141, 1, 'https://beta.ibex.software/mmb-basic/files/fcc026ac5db1830c40f4c08f8b03204335b583bce50da3d8b712480c0b57cb0e.mpp');

-- ----------------------------
-- Table structure for gantt_files_pending_insert
-- ----------------------------
DROP TABLE IF EXISTS `gantt_files_pending_insert`;
CREATE TABLE `gantt_files_pending_insert`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `programme_id` int NULL DEFAULT NULL,
  `task_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `encoded_files` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `processed` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_files_pending_insert
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_links
-- ----------------------------
DROP TABLE IF EXISTS `gantt_links`;
CREATE TABLE `gantt_links`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `source` int NOT NULL,
  `source_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `target` int NOT NULL,
  `target_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `offset_minutes` int NOT NULL,
  `offset_type` int NOT NULL,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '#E9E9E9',
  `active` int NOT NULL DEFAULT 1,
  `created` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_links
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_messages
-- ----------------------------
DROP TABLE IF EXISTS `gantt_messages`;
CREATE TABLE `gantt_messages`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `programme_id` int NULL DEFAULT NULL,
  `sender_id` int NULL DEFAULT NULL,
  `recipient_id` int NULL DEFAULT NULL,
  `created` int NULL DEFAULT NULL,
  `unread` int NULL DEFAULT 0,
  `text` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_messages
-- ----------------------------
INSERT INTO `gantt_messages` VALUES (1, 1, 0, 1, 1624961084, 1, 'Welcome to Ibex! Thanks for joining us.<br>Please verify your email address by following the prompts on the email that we have sent to you.<br><br>Please ensure that you complete your profile by clicking on the button below.<br><br><a data-toggle=\'modal\' data-target=\'#modal_edit_profile\'><button>Complete your profile</button></a><br><br>');

-- ----------------------------
-- Table structure for gantt_pricing
-- ----------------------------
DROP TABLE IF EXISTS `gantt_pricing`;
CREATE TABLE `gantt_pricing`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` int NOT NULL,
  `section` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ref` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `quantity` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `rate` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sum` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_pricing
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_programmes
-- ----------------------------
DROP TABLE IF EXISTS `gantt_programmes`;
CREATE TABLE `gantt_programmes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `parent_id` int NOT NULL DEFAULT 0,
  `identifier` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `sharing_identifier` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `default_project_guid` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `last_save_time` int NULL DEFAULT NULL,
  `last_save_author_id` int NULL DEFAULT NULL,
  `created` int NOT NULL,
  `current_snapshot` int NOT NULL DEFAULT 0,
  `current_version_id` int NULL DEFAULT NULL,
  `undo_redo_version_id` int NULL DEFAULT NULL,
  `active` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_programmes
-- ----------------------------
INSERT INTO `gantt_programmes` VALUES (1, 1, 'Ibex', 0, NULL, '8359571D-C929-13CB-88C2-7C5D338D5FB7', NULL, 1682447137, 1, 1624961084, 0, 26, 26, 1);

-- ----------------------------
-- Table structure for gantt_resource_groups
-- ----------------------------
DROP TABLE IF EXISTS `gantt_resource_groups`;
CREATE TABLE `gantt_resource_groups`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `contains_human_resources` int NOT NULL DEFAULT 0,
  `is_group` int NOT NULL DEFAULT 1,
  `contains_consumable_resources` int NOT NULL,
  `outputs_unit` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `min_output_value` int NULL DEFAULT NULL,
  `max_output_value` int NULL DEFAULT NULL,
  `calendar_id` int NULL DEFAULT 2,
  `period` int NULL DEFAULT NULL,
  `period_minutes` int NULL DEFAULT NULL,
  `output_per_minute` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_resource_groups
-- ----------------------------
INSERT INTO `gantt_resource_groups` VALUES (1, 1, 'Surfacing Plant', 0, 1, 1, 'no', 100, 100, 2, 2, NULL, NULL);
INSERT INTO `gantt_resource_groups` VALUES (2, 0, 'qwe', 0, 1, 0, '', 0, 0, 0, 0, 0, '0');

-- ----------------------------
-- Table structure for gantt_resources
-- ----------------------------
DROP TABLE IF EXISTS `gantt_resources`;
CREATE TABLE `gantt_resources`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `group_id` int NULL DEFAULT NULL,
  `calendar_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `cost_rate` int NULL DEFAULT NULL,
  `created` int NOT NULL,
  `is_group` int NOT NULL DEFAULT 0,
  `unit_of_measure` int NOT NULL DEFAULT 1,
  `resource_image_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'https://beta.ibex.software/gantt/img/mountain.jpg',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_resources
-- ----------------------------
INSERT INTO `gantt_resources` VALUES (1, '0FB17C8D-79DC-465F-EC36-0CCB3F534A2C', '1', 1, '2', 'JCB 3CX Compact', 'JCB Hire', '2m wide and 2.74m high', 25, 1624961084, 0, 1, 'https://beta.ibex.software/gantt/img/mountain.jpg');
INSERT INTO `gantt_resources` VALUES (2, 'BC18A784-B65B-D6A3-5C32-33A7598318D9', '', 0, '1', 'asd', 'asd', 'asd', 0, 1682106862, 0, 0, 'https://beta.ibex.software/gantt/img/mountain.jpg');
INSERT INTO `gantt_resources` VALUES (3, 'B2C61605-4554-4085-FD57-814297E2027A', '', 2, '1', 'asd', 'asd', 'asd', 0, 1682107270, 0, 0, 'https://beta.ibex.software/gantt/img/mountain.jpg');

-- ----------------------------
-- Table structure for gantt_settings
-- ----------------------------
DROP TABLE IF EXISTS `gantt_settings`;
CREATE TABLE `gantt_settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `duration_unit` int NULL DEFAULT 1,
  `time_unit` int NULL DEFAULT 3,
  `automatic_scheduling_enabled` int NULL DEFAULT 1,
  `task_insertion_mode` int NULL DEFAULT 3,
  `default_permission_groups` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `default_permission_sets` varchar(10000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timing_unit` int NOT NULL DEFAULT 1,
  `period_descriptor` int NOT NULL DEFAULT 4,
  `period_descriptor_text_singular` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT 'aaa',
  `period_descriptor_text_plural` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT 'bbb',
  `default_task_editor_permission_sets` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_settings
-- ----------------------------
INSERT INTO `gantt_settings` VALUES (1, '1', NULL, NULL, 1, 3, '{\"1\": true}', '{\"group_1_set_1\":true,\"group_1_set_2\":true,\"group_1_set_3\":true,\"group_1_set_4\":true,\"group_1_set_5\":true,\"group_1_set_6\":true}', 1, 4, 'aaa', 'bbb', '');

-- ----------------------------
-- Table structure for gantt_sor
-- ----------------------------
DROP TABLE IF EXISTS `gantt_sor`;
CREATE TABLE `gantt_sor`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `people` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `equipment` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `plant` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `materials` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `rate` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sor_item` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_sor
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_task_progress
-- ----------------------------
DROP TABLE IF EXISTS `gantt_task_progress`;
CREATE TABLE `gantt_task_progress`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` int NULL DEFAULT NULL,
  `progress` float NULL DEFAULT NULL,
  `datetime_recorded` datetime NULL DEFAULT NULL,
  `date_recorded` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_task_progress
-- ----------------------------

-- ----------------------------
-- Table structure for gantt_tasks
-- ----------------------------
DROP TABLE IF EXISTS `gantt_tasks`;
CREATE TABLE `gantt_tasks`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `guid` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `order_ui` int NULL DEFAULT NULL,
  `parent` int NULL DEFAULT NULL,
  `parent_guid` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `programme_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `text` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `start_date` datetime NULL DEFAULT NULL,
  `end_date` datetime NULL DEFAULT NULL,
  `position` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `baseline_start` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `baseline_end` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `baseline_progress` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `deadline` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `duration` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `duration_unit` int NOT NULL DEFAULT 1,
  `duration_hours` int NULL DEFAULT NULL,
  `duration_worked` int NULL DEFAULT NULL,
  `baseline_duration_worked` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `opened` int NOT NULL DEFAULT 1,
  `progress` float NULL DEFAULT 0,
  `sortorder` int NOT NULL DEFAULT 0,
  `constraint_type` int NULL DEFAULT 0,
  `constraint_enforced` int NOT NULL DEFAULT 0,
  `constraint_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `color` varchar(7) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '#fff',
  `calendar_id` int NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'task',
  `is_summary` int NOT NULL DEFAULT 0,
  `comments` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `comments_activity` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `resources` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `completed` int NOT NULL DEFAULT 0,
  `resource_count` int NOT NULL DEFAULT 0,
  `resources_listed` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `reference_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `non_working_periods` int NOT NULL DEFAULT 0,
  `manually_delayed` int NOT NULL DEFAULT 0,
  `timing_overriden` int NULL DEFAULT 0,
  `actual_costs` int NULL DEFAULT NULL,
  `budget_at_completion` int NULL DEFAULT NULL,
  `reset_baselines_to_dates` int NOT NULL DEFAULT 0,
  `baseline_resource_cost` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `resource_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `custom_permission_groups` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `target` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `pending_deletion` int NOT NULL DEFAULT 0,
  `files` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `active` int NOT NULL DEFAULT 1,
  `status` int NULL DEFAULT 1,
  `workload_quantity` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `workload_quantity_unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `resource_group_id` int NULL DEFAULT 0,
  `budget_cost_completion` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `actual_cost_completion` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_tasks
-- ----------------------------
INSERT INTO `gantt_tasks` VALUES (2, 'A041EF4B-4310-9C59-8A4A-5E90B158918D', 0, 0, NULL, '1', 'Project_1', '2023-04-25 07:00:00', '2023-04-25 17:00:00', NULL, '2023-12-31 00:00:00', '2023-12-31 00:00:00', '0', '2023-12-31 00:00:00', '1', 2, NULL, NULL, NULL, 1, 0, 0, 0, 0, NULL, '#d6daff', 1, 'task', 1, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, 0, 0, NULL, 0, 0, NULL, '', NULL, NULL, 0, NULL, 1, 1, '', '', NULL, '', '', '');

-- ----------------------------
-- Table structure for gantt_user_groups
-- ----------------------------
DROP TABLE IF EXISTS `gantt_user_groups`;
CREATE TABLE `gantt_user_groups`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `is_admin_group` int NOT NULL DEFAULT 0,
  `scheduling_general` int NULL DEFAULT 2,
  `scheduling_workload` int NULL DEFAULT 2,
  `scheduling_dependencies` int NULL DEFAULT 2,
  `scheduling_constraints` int NULL DEFAULT 2,
  `scheduling_calendars` int NULL DEFAULT 2,
  `resources_general` int NULL DEFAULT 2,
  `resources_availability` int NULL DEFAULT 2,
  `resources_allocation` int NULL DEFAULT 2,
  `resources_financial` int NULL DEFAULT 2,
  `resources_groups` int NULL DEFAULT 2,
  `resources_calendars` int NULL DEFAULT 2,
  `collaboration_messaging` int NULL DEFAULT 2,
  `collaboration_commenting` int NULL DEFAULT 2,
  `collaboration_files` int NULL DEFAULT 2,
  `collaboration_team_members` int NULL DEFAULT 2,
  `version_control_project_data` int NULL DEFAULT 2,
  `version_control_demo_project_data` int NULL DEFAULT 2,
  `version_control_baselines` int NULL DEFAULT 2,
  `version_control_rollback` int NULL DEFAULT 2,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_user_groups
-- ----------------------------
INSERT INTO `gantt_user_groups` VALUES (1, 1, 'Administrators', 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2);

-- ----------------------------
-- Table structure for gantt_user_groups_links
-- ----------------------------
DROP TABLE IF EXISTS `gantt_user_groups_links`;
CREATE TABLE `gantt_user_groups_links`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_group_id` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_user_groups_links
-- ----------------------------
INSERT INTO `gantt_user_groups_links` VALUES (1, 1, 1);

-- ----------------------------
-- Table structure for gantt_user_programme_links
-- ----------------------------
DROP TABLE IF EXISTS `gantt_user_programme_links`;
CREATE TABLE `gantt_user_programme_links`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `programme_id` int NOT NULL,
  `permission_type` int NOT NULL,
  `date_range_start` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `date_range_end` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_user_programme_links
-- ----------------------------
INSERT INTO `gantt_user_programme_links` VALUES (1, 1, 1, 1, '2023-03-26', '2023-05-25');

-- ----------------------------
-- Table structure for gantt_version_comments
-- ----------------------------
DROP TABLE IF EXISTS `gantt_version_comments`;
CREATE TABLE `gantt_version_comments`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `programme_id` int NULL DEFAULT NULL,
  `version_id` int NULL DEFAULT NULL,
  `author_id` int NOT NULL,
  `in_response_to` int NOT NULL DEFAULT 0,
  `text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_version_comments
-- ----------------------------
INSERT INTO `gantt_version_comments` VALUES (1, 1, 25, 1, 0, 'asdqwe', 1682447176);

-- ----------------------------
-- Table structure for gantt_versions
-- ----------------------------
DROP TABLE IF EXISTS `gantt_versions`;
CREATE TABLE `gantt_versions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `programme_id` int NOT NULL,
  `gantt_data` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `aux_data` longtext CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `user_id` int NOT NULL,
  `created` int NOT NULL,
  `action` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `description_2` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `primary_object_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `secondary_object_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `active` int NOT NULL DEFAULT 1,
  `is_reference_version` int NOT NULL DEFAULT 0,
  `ui_string` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `to_finalise` int NULL DEFAULT 0,
  `pending` int NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_versions
-- ----------------------------
INSERT INTO `gantt_versions` VALUES (4, '7DC767E2-60D7-A266-720C-007BF9D7C436', 1, '{\"data\":[{\"id\":1682432199298,\"start_date\":\"2023-03-26 00:00:00\",\"text\":\"New task\",\"duration\":1,\"end_date\":\"2023-03-28 00:00:00\",\"parent\":0,\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"A041EF4B-4310-9C59-8A4A-5E90B158918D\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":0}],\"links\":[]}', 'Starts Tue 25 Apr (07:00)', 1, 1682432209, 'added', 'task', NULL, 'A041EF4B-4310-9C59-8A4A-5E90B158918D', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (5, 'F4B4D66E-14FE-7777-2443-9C7C0F60347C', 1, '{\"data\":[{\"id\":1682432199298,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"A041EF4B-4310-9C59-8A4A-5E90B158918D\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":0,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"},{\"id\":1682432199299,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"New task\",\"duration\":1,\"parent\":\"1682432199298\",\"end_date\":\"2023-04-26 07:00:00\",\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"E274729D-72AD-96BB-1FDB-709712EB1332\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":1}],\"links\":[]}', 'Starts Tue 25 Apr (07:00)', 1, 1682432221, 'added', 'task', NULL, 'E274729D-72AD-96BB-1FDB-709712EB1332', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (6, 'E87CB8D6-E6D8-DE17-FCA3-E5B31B376127', 1, '{\"data\":[{\"id\":1682432199298,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"A041EF4B-4310-9C59-8A4A-5E90B158918D\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":0,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682432199299,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682432199298\",\"end_date\":\"2023-04-26 02:03:25\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"E274729D-72AD-96BB-1FDB-709712EB1332\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":1,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"A041EF4B-4310-9C59-8A4A-5E90B158918D\"}],\"links\":[]}', 'Finish was Tue 25 Apr (17:00) and is now Wed 26 Apr (17:00)<br>', 1, 1682432231, 'edited', 'task', NULL, 'E274729D-72AD-96BB-1FDB-709712EB1332', '', 1, 0, 'edited task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (9, '56C8495E-2ACD-76CF-14BF-4295CB55CCE8', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682443344896,\"start_date\":\"2023-03-26 00:00:00\",\"text\":\"New task\",\"duration\":1,\"end_date\":\"2023-03-28 00:00:00\",\"parent\":0,\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"3E91F909-67B3-7171-E1B5-94641517DB17\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682443394, 'added', 'task', NULL, '3E91F909-67B3-7171-E1B5-94641517DB17', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (12, '1B4BDE42-DA41-2FCE-6613-A2ACF4C667A7', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682443410602,\"start_date\":\"2023-03-26 00:00:00\",\"text\":\"New task\",\"duration\":1,\"end_date\":\"2023-03-28 00:00:00\",\"parent\":0,\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"0410715A-2D43-7B72-F85B-F57354A4C3BE\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":3}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682443521, 'added', 'task', NULL, '0410715A-2D43-7B72-F85B-F57354A4C3BE', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (14, 'E081ED58-7556-EA21-7597-1957A83EE51E', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682443410602,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"0410715A-2D43-7B72-F85B-F57354A4C3BE\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Name was Project_1 and is now Project', 1, 1682443527, 'edited', 'task', NULL, '0410715A-2D43-7B72-F85B-F57354A4C3BE', '', 1, 0, 'edited task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (15, 'EE151A4D-FA60-BC6D-3FC7-394FCEB3E0EA', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-03-26 00:00:00\",\"text\":\"New task\",\"duration\":1,\"end_date\":\"2023-03-28 00:00:00\",\"parent\":0,\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":3}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682447059, 'added', 'task', NULL, '3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (16, 'D2A8D186-53FC-CB8A-FED1-29A0D7817585', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"New task\",\"duration\":1,\"end_date\":\"2023-04-26 07:00:00\",\"parent\":0,\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":4}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682447066, 'added', 'task', NULL, '095060D3-04A3-BAB7-32BF-222B4BE2B6E5', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (17, '9ECDA0A5-D02E-C40A-6BD6-8CA4292DC846', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"New task\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 07:00:00\",\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":4,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682447071, 'added', 'task', NULL, '90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (18, 'CE2EC359-DAAD-4D56-50EF-354FD5E00E6A', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"New task\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 07:00:00\",\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":5},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682447079, 'added', 'task', NULL, '89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (19, '3FA77599-1B6C-7E99-1B3D-E8290CA1F8F6', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"New task\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-26 07:00:00\",\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":7}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682447088, 'added', 'task', NULL, '2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (20, '1A72D0A5-95C2-0525-B093-AAFD72573273', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"New task\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-26 07:00:00\",\"baseline_start\":\"2999-12-31 23:59:59\",\"baseline_end\":\"2999-12-31 23:59:59\",\"deadline\":\"2999-12-31 23:59:59\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":1,\"order_ui\":8}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Starts Tue 25 Apr (07:00)', 1, 1682447099, 'added', 'task', NULL, 'F3601AC0-C74C-27E0-EDB0-DD387E99E729', '', 1, 0, 'added task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (21, 'A25D8C74-37C4-2FD9-B4C7-F20825D25AC1', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-2\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":8,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"}]}', 'Finish to Start with no lead or lag', 1, 1682447106, 'added', 'link', NULL, '90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F', '89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A', 1, 0, 'added a dependency between <span>\'\'<span> and <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (22, 'FF50F2AA-F957-9652-3747-3AD81605C74E', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-26 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-2\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":8,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"},{\"programme_id\":\"1\",\"source\":\"1682446965594\",\"source_guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"target\":\"1682446965595\",\"target_guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"type\":\"0\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965599,\"color\":\"#999\"}]}', 'Finish was Tue 25 Apr (17:00) and is now Wed 26 Apr (17:00)<br>', 1, 1682447109, 'edited', 'task', NULL, '90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F', '', 1, 0, 'edited task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (23, '3C02E756-A950-A760-18CD-5BE04667ADE1', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-27 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-27 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-2\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":8,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"},{\"programme_id\":\"1\",\"source\":\"1682446965594\",\"source_guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"target\":\"1682446965595\",\"target_guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"type\":\"0\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965599,\"color\":\"#999\"}]}', 'Start to Finish with no lead or lag', 1, 1682447121, 'added', 'link', NULL, '2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E', 'F3601AC0-C74C-27E0-EDB0-DD387E99E729', 1, 0, 'added a dependency between <span>\'\'<span> and <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (24, 'F771200C-07B8-B89F-7C35-DAD409BDF2B1', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-27 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-27 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-25 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-26 07:00:00\",\"text\":\"Task_2-2\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":8,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"},{\"programme_id\":\"1\",\"source\":\"1682446965594\",\"source_guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"target\":\"1682446965595\",\"target_guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"type\":\"0\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965599,\"color\":\"#999\"},{\"programme_id\":\"1\",\"source\":\"1682446965596\",\"source_guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"target\":\"1682446965597\",\"target_guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"type\":\"3\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965601,\"color\":\"#999\"}]}', 'Finish was Tue 25 Apr (17:00) and is now Wed 26 Apr (17:00)<br>', 1, 1682447125, 'edited', 'task', NULL, '2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E', '', 1, 0, 'edited task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (25, '3865941F-D4FD-FE7D-4B52-998367397696', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-27 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-27 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-27 07:00:00\",\"text\":\"Task_2-2\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-27 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":8,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"},{\"programme_id\":\"1\",\"source\":\"1682446965594\",\"source_guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"target\":\"1682446965595\",\"target_guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"type\":\"0\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965599,\"color\":\"#999\"},{\"programme_id\":\"1\",\"source\":\"1682446965596\",\"source_guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"target\":\"1682446965597\",\"target_guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"type\":\"3\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965601,\"color\":\"#999\"}]}', 'Finish was Thu 27 Apr (17:00) and is now Thu 27 Apr (17:00)<br>', 1, 1682447130, 'edited', 'task', NULL, 'F3601AC0-C74C-27E0-EDB0-DD387E99E729', '', 1, 0, 'edited task <span>\'\'<span>', 0, 1);
INSERT INTO `gantt_versions` VALUES (26, '72C86216-6E9D-774D-E7B1-5ADE57968B45', 1, '{\"data\":[{\"id\":1,\"text\":\"Project #2\",\"start_date\":\"1906-10-14 00:00:00\",\"duration\":18,\"progress\":0.4,\"open\":true,\"resource_group_id\":0,\"end_date\":\"1906-10-14 17:00:00\",\"parent\":0,\"order_ui\":0},{\"id\":2,\"text\":\"Task #1\",\"start_date\":\"1907-10-14 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1907-10-14 17:00:00\",\"order_ui\":1},{\"id\":3,\"text\":\"Task #2\",\"start_date\":\"1916-10-13 00:00:00\",\"duration\":8,\"progress\":0.6,\"parent\":1,\"resource_group_id\":0,\"end_date\":\"1916-10-13 17:00:00\",\"order_ui\":2},{\"id\":1682446965592,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_1\",\"duration\":1,\"end_date\":\"2023-04-25 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":3,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965594,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_1-1\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\",\"order_ui\":4},{\"id\":1682446965595,\"start_date\":\"2023-04-27 07:00:00\",\"text\":\"Task_1-2\",\"duration\":1,\"parent\":\"1682446965592\",\"end_date\":\"2023-04-27 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":5,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":null,\"color\":\"#d6daff\",\"parent_guid\":\"3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E\"},{\"id\":1682446965593,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Project_2\",\"duration\":1,\"end_date\":\"2023-04-28 17:00:00\",\"parent\":0,\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":6,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":2400,\"color\":\"#d6daff\",\"is_summary\":\"1\"},{\"id\":1682446965596,\"start_date\":\"2023-04-25 07:00:00\",\"text\":\"Task_2-1\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-26 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":7,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"},{\"id\":1682446965597,\"start_date\":\"2023-04-27 07:00:00\",\"text\":\"Task_2-2\",\"duration\":1,\"parent\":\"1682446965593\",\"end_date\":\"2023-04-28 17:00:00\",\"baseline_start\":\"2023-12-31 00:00:00\",\"baseline_end\":\"2023-12-31 00:00:00\",\"deadline\":\"2023-12-31 00:00:00\",\"guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"progress\":\"0\",\"baseline_progress\":0,\"resource_id\":\"\",\"resource_group_id\":null,\"duration_unit\":\"2\",\"order_ui\":8,\"programme_id\":\"1\",\"timing_overriden\":\"\",\"type\":\"task\",\"price\":\"\",\"budget_at_completion\":\"\",\"budget_cost_completion\":\"\",\"actual_cost_completion\":\"\",\"calendar_id\":\"1\",\"workload_quantity\":\"\",\"workload_quantity_unit\":\"\",\"duration_worked\":1200,\"color\":\"#d6daff\",\"parent_guid\":\"095060D3-04A3-BAB7-32BF-222B4BE2B6E5\"}],\"links\":[{\"id\":1,\"source\":1,\"target\":2,\"type\":\"1\"},{\"id\":2,\"source\":2,\"target\":3,\"type\":\"0\"},{\"programme_id\":\"1\",\"source\":\"1682446965594\",\"source_guid\":\"90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F\",\"target\":\"1682446965595\",\"target_guid\":\"89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A\",\"type\":\"0\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965599,\"color\":\"#999\"},{\"programme_id\":\"1\",\"source\":\"1682446965596\",\"source_guid\":\"2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E\",\"target\":\"1682446965597\",\"target_guid\":\"F3601AC0-C74C-27E0-EDB0-DD387E99E729\",\"type\":\"3\",\"offset_minutes\":\"0\",\"offset_type\":\"0\",\"created\":\"Tue 25 Apr 2023\",\"id\":1682446965601,\"color\":\"#999\"}]}', 'Finish was Thu 27 Apr (17:00) and is now Fri 28 Apr (17:00)<br>', 1, 1682447137, 'edited', 'task', NULL, '89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A', '', 1, 0, 'edited task <span>\'\'<span>', 0, 1);

-- ----------------------------
-- Table structure for gantt_workload_links
-- ----------------------------
DROP TABLE IF EXISTS `gantt_workload_links`;
CREATE TABLE `gantt_workload_links`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `work_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `work_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `quantity` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `is_root` int NULL DEFAULT NULL,
  `parent` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gantt_workload_links
-- ----------------------------
INSERT INTO `gantt_workload_links` VALUES (1, '0388539D-61AD-F987-5A7D-913B946F143B', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (2, '5BAC2005-6726-EA6F-EF31-3A0915BEEF5D', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (3, 'A041EF4B-4310-9C59-8A4A-5E90B158918D', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (4, 'E274729D-72AD-96BB-1FDB-709712EB1332', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (5, '3E91F909-67B3-7171-E1B5-94641517DB17', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (6, '0410715A-2D43-7B72-F85B-F57354A4C3BE', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (7, '3CAC38C8-9ACF-0C22-D0D2-BE160FBF9E0E', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (8, '095060D3-04A3-BAB7-32BF-222B4BE2B6E5', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (9, '90EDCCD1-BAF4-BD88-27A5-6024D37C7B3F', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (10, '89087A54-EEE1-CA3E-F1CD-CFB5AA086F9A', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (11, '2FA71B12-D1DF-7AB8-5A66-FAC7FF431A8E', '2023-04-25', NULL, NULL, 1, 0);
INSERT INTO `gantt_workload_links` VALUES (12, 'F3601AC0-C74C-27E0-EDB0-DD387E99E729', '2023-04-25', NULL, NULL, 1, 0);

-- ----------------------------
-- Table structure for logins
-- ----------------------------
DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `created` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of logins
-- ----------------------------
INSERT INTO `logins` VALUES (1, 1, 1681893845);
INSERT INTO `logins` VALUES (2, 1, 1681906699);
INSERT INTO `logins` VALUES (3, 1, 1681932243);
INSERT INTO `logins` VALUES (4, 1, 1682065152);
INSERT INTO `logins` VALUES (5, 1, 1682073920);
INSERT INTO `logins` VALUES (6, 1, 1682081769);
INSERT INTO `logins` VALUES (7, 1, 1682081935);
INSERT INTO `logins` VALUES (8, 1, 1682090252);
INSERT INTO `logins` VALUES (9, 1, 1682091811);
INSERT INTO `logins` VALUES (10, 1, 1682094868);
INSERT INTO `logins` VALUES (11, 1, 1682095101);
INSERT INTO `logins` VALUES (12, 1, 1682098084);
INSERT INTO `logins` VALUES (13, 1, 1682101694);
INSERT INTO `logins` VALUES (14, 1, 1682103110);
INSERT INTO `logins` VALUES (15, 1, 1682110409);
INSERT INTO `logins` VALUES (16, 1, 1682131750);
INSERT INTO `logins` VALUES (17, 1, 1682163679);
INSERT INTO `logins` VALUES (18, 1, 1682425568);
INSERT INTO `logins` VALUES (19, 1, 1682425685);
INSERT INTO `logins` VALUES (20, 1, 1682432198);
INSERT INTO `logins` VALUES (21, 1, 1682432687);
INSERT INTO `logins` VALUES (22, 1, 1682437008);
INSERT INTO `logins` VALUES (23, 1, 1682442458);
INSERT INTO `logins` VALUES (24, 1, 1682446964);

-- ----------------------------
-- Table structure for user_account_links
-- ----------------------------
DROP TABLE IF EXISTS `user_account_links`;
CREATE TABLE `user_account_links`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `account_id` int NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  `account_first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `account_last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `account_phone_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `account_email_adress` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_account_links
-- ----------------------------
INSERT INTO `user_account_links` VALUES (1, 1, 1, 1, '', '', '', '');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `telephone_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `invite_code` int NULL DEFAULT NULL,
  `hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `created` int NULL DEFAULT NULL,
  `terms_accepted` int NULL DEFAULT NULL,
  `active` int NULL DEFAULT 1,
  `state` int NOT NULL DEFAULT 1,
  `last_programme_id` int NULL DEFAULT NULL,
  `avatar_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT 'https://beta.ibex.software/gantt/img/logo.png',
  `heartbeat_time` int NULL DEFAULT NULL,
  `active_task_guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `active_task_lock_time` int NULL DEFAULT NULL,
  `background_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'https://beta.ibex.software/gantt/img/mountain.jpg',
  `background_opacity` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0.2',
  `opacity_font` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email_address_verified` int NULL DEFAULT 0,
  `account_setup` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, NULL, NULL, 'richard@ibex-consulting.co.uk', NULL, 7821, '$2y$10$lBXbtB2R1blZXMLxvRwxn.xGLRhHn.M7HrlwTJSDMoqXwV9zH7RTu', 1624961082, 1624961083, 1, 2, 1, 'https://beta.ibex.software/gantt/img/logo.png', 1682447215, NULL, NULL, 'https://beta.ibex.software/gantt/img/mountain.jpg', '0.2', '', 1, 0);

SET FOREIGN_KEY_CHECKS = 1;
