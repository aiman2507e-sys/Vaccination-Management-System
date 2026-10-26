-- =============================================================================
-- Vaccination Management System - Complete MySQL Database Script
-- Compatible with MySQL 5.7, 8.0, 9.1 and MariaDB 10.x+
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `vaccination_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vaccination_db`;

-- Drop existing tables in reverse relational dependency order
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `vaccines`;
DROP TABLE IF EXISTS `children`;
DROP TABLE IF EXISTS `hospitals`;
DROP TABLE IF EXISTS `users`;

-- -------------------------------------------------------------
-- 1. Table structure for table `users`
-- -------------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'parent' COMMENT 'admin, hospital, parent',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Metropolis',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 2. Table structure for table `hospitals`
-- -------------------------------------------------------------
CREATE TABLE `hospitals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Metropolis',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Area, district, or landmark',
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operating_hours` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:00 AM - 04:00 PM',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hospitals_user_id_foreign` (`user_id`),
  CONSTRAINT `hospitals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 3. Table structure for table `children`
-- -------------------------------------------------------------
CREATE TABLE `children` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL COMMENT 'Date of Birth',
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `blood_group` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'O+',
  `birth_weight` decimal(4,2) DEFAULT NULL COMMENT 'Weight in kg',
  `birth_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allergies` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `children_parent_id_foreign` (`parent_id`),
  CONSTRAINT `children_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 4. Table structure for table `vaccines`
-- -------------------------------------------------------------
CREATE TABLE `vaccines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `target_disease` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recommended_age_days` int(11) NOT NULL DEFAULT 0,
  `recommended_age_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosage` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.5 ml',
  `route` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Intramuscular (IM)',
  `status` enum('available','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precautions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 5. Table structure for table `appointments`
-- -------------------------------------------------------------
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_reference` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `parent_id` bigint(20) unsigned NOT NULL,
  `child_id` bigint(20) unsigned NOT NULL,
  `vaccine_id` bigint(20) unsigned NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '09:00 AM - 10:00 AM',
  `status` enum('pending','approved','rejected','vaccinated','not_vaccinated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_action_at` timestamp NULL DEFAULT NULL,
  `administered_date` date DEFAULT NULL,
  `batch_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `administered_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hospital_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `appointments_parent_id_foreign` (`parent_id`),
  KEY `appointments_child_id_foreign` (`child_id`),
  KEY `appointments_vaccine_id_foreign` (`vaccine_id`),
  KEY `appointments_hospital_id_foreign` (`hospital_id`),
  CONSTRAINT `appointments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_vaccine_id_foreign` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Insert Seed Data: Users
-- Default password: password123 (bcrypt hash)
-- -------------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `address`, `city`, `password`) VALUES
(1, 'System Administrator', 'admin@vaccine.test', 'admin', '+1 (555) 019-2831', 'Ministry of Health & Family Welfare, Center 1', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(2, 'City Children Hospital Admin', 'cityhospital@vaccine.test', 'hospital', '+1 (555) 234-5678', '742 Evergreen Terrace, Medical District', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(3, 'St. Mary General Hospital Admin', 'stmary@vaccine.test', 'hospital', '+1 (555) 345-6789', '108 Healthcare Blvd, Downtown', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(4, 'Sunrise Pediatric Clinic Admin', 'sunrise@vaccine.test', 'hospital', '+1 (555) 456-7890', '42 Park Avenue, North Hills', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(5, 'Hope Community Health Center Admin', 'hope@vaccine.test', 'hospital', '+1 (555) 567-8901', '19 East River Road', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(6, 'Sarah Johnson', 'parent1@vaccine.test', 'parent', '+1 (555) 901-1122', '12 Maple Leaf Street, Apt 4B', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(7, 'David Miller', 'parent2@vaccine.test', 'parent', '+1 (555) 902-3344', '88 Oakridge Drive', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye'),
(8, 'Amina Khan', 'parent3@vaccine.test', 'parent', '+1 (555) 903-5566', '305 Sunset Boulevard', 'Metropolis', '$2y$12$N3aP9XmF65uV7RknqfWkueeJm9K3Xp6k13hZ3OknF/pP1Wb4gCqye');

-- -------------------------------------------------------------
-- Insert Seed Data: Hospitals
-- -------------------------------------------------------------
INSERT INTO `hospitals` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `city`, `location`, `contact_person`, `operating_hours`, `status`) VALUES
(1, 2, 'City Children & Maternity Hospital', 'cityhospital@vaccine.test', '+1 (555) 234-5678', '742 Evergreen Terrace, Medical District', 'Metropolis', 'Central Zone, Block C', 'Dr. Eleanor Vance (Pediatric Chief)', 'Mon-Sat 08:00 AM - 05:00 PM', 'active'),
(2, 3, 'St. Mary General Healthcare Center', 'stmary@vaccine.test', '+1 (555) 345-6789', '108 Healthcare Blvd, Downtown', 'Metropolis', 'South Zone, Sector 4', 'Dr. Robert Chen', 'Mon-Sun 24/7 Vaccination Wing', 'active'),
(3, 4, 'Sunrise Pediatric & Immunization Clinic', 'sunrise@vaccine.test', '+1 (555) 456-7890', '42 Park Avenue, North Hills', 'Metropolis', 'North District, Plaza 2', 'Nurse Sarah Jenkins', 'Mon-Fri 08:30 AM - 04:30 PM', 'active'),
(4, 5, 'Hope Community Health Center', 'hope@vaccine.test', '+1 (555) 567-8901', '19 East River Road', 'Metropolis', 'East Riverside', 'Dr. Marcus Brody', 'Mon-Sat 09:00 AM - 03:00 PM', 'active');

-- -------------------------------------------------------------
-- Insert Seed Data: Vaccines
-- -------------------------------------------------------------
INSERT INTO `vaccines` (`id`, `name`, `short_code`, `target_disease`, `recommended_age_days`, `recommended_age_label`, `dosage`, `route`, `status`, `description`) VALUES
(1, 'BCG (Bacillus Calmette-Guérin)', 'BCG', 'Tuberculosis (TB)', 0, 'At Birth', '0.05 ml', 'Intradermal', 'available', 'Provides essential childhood protection against severe forms of tuberculosis, including tuberculous meningitis.'),
(2, 'Hepatitis B (Birth Dose)', 'HEP-B-0', 'Hepatitis B Virus', 0, 'At Birth (within 24 hrs)', '0.5 ml', 'Intramuscular', 'available', 'Prevents perinatal transmission of Hepatitis B from mother to infant.'),
(3, 'OPV 0 (Oral Polio - Birth Dose)', 'OPV-0', 'Poliomyelitis (Paralysis)', 0, 'At Birth', '2 drops', 'Oral', 'available', 'Live attenuated oral polio drops to induce early mucosal gut immunity.'),
(4, 'Pentavalent 1 (DTP + HepB + Hib)', 'PENTA-1', 'Diphtheria, Tetanus, Pertussis, Hep B, Hib', 42, '6 Weeks', '0.5 ml', 'Intramuscular', 'available', '5-in-1 combo vaccine protecting against five dangerous childhood killers.'),
(5, 'OPV 1 (Oral Polio Dose 1)', 'OPV-1', 'Poliomyelitis', 42, '6 Weeks', '2 drops', 'Oral', 'available', 'Second milestone dose of oral polio drops.'),
(6, 'Rotavirus 1', 'ROTA-1', 'Rotavirus Diarrhea & Dehydration', 42, '6 Weeks', '5 drops (oral)', 'Oral', 'available', 'Protects infants from severe life-threatening diarrhea caused by Rotavirus.'),
(7, 'PCV 1 (Pneumococcal Conjugate)', 'PCV-1', 'Pneumococcal Pneumonia & Meningitis', 42, '6 Weeks', '0.5 ml', 'Intramuscular', 'available', 'Guards against Streptococcus pneumoniae bacterial infections.'),
(8, 'Pentavalent 2 (DTP + HepB + Hib)', 'PENTA-2', 'Diphtheria, Tetanus, Pertussis, Hep B, Hib', 70, '10 Weeks', '0.5 ml', 'Intramuscular', 'available', 'Second dose of 5-in-1 combo vaccine.'),
(9, 'Pentavalent 3 (DTP + HepB + Hib)', 'PENTA-3', 'Diphtheria, Tetanus, Pertussis, Hep B, Hib', 98, '14 Weeks', '0.5 ml', 'Intramuscular', 'available', 'Third primary dose completing the infant Pentavalent foundation.'),
(10, 'Measles & Rubella 1 (MR-1)', 'MR-1', 'Measles & Rubella', 270, '9 Months', '0.5 ml', 'Subcutaneous', 'available', 'Essential milestone to prevent high mortality measles outbreaks.'),
(11, 'MMR Booster (Measles, Mumps, Rubella)', 'MMR-2', 'Measles, Mumps, Rubella', 450, '15 - 18 Months', '0.5 ml', 'Subcutaneous', 'available', 'Booster dose ensuring lifetime antibody protection.'),
(12, 'Typhoid Conjugate Vaccine (TCV)', 'TCV-1', 'Typhoid Fever', 730, '2 Years', '0.5 ml', 'Intramuscular', 'unavailable', 'Protects young children from waterborne typhoid enteric fever.');

-- -------------------------------------------------------------
-- Insert Seed Data: Children
-- -------------------------------------------------------------
INSERT INTO `children` (`id`, `parent_id`, `name`, `dob`, `gender`, `blood_group`, `birth_weight`, `birth_place`, `allergies`, `medical_history`) VALUES
(1, 6, 'Leo Johnson', DATE_SUB(CURDATE(), INTERVAL 90 DAY), 'Male', 'A+', 3.35, 'City Maternity Clinic', 'None known', 'Full-term normal delivery.'),
(2, 6, 'Mia Johnson', DATE_SUB(CURDATE(), INTERVAL 480 DAY), 'Female', 'O+', 3.10, 'City Hospital', 'Mild lactose sensitivity', 'Up to date with primary immunization series.'),
(3, 7, 'Oliver Miller', DATE_SUB(CURDATE(), INTERVAL 49 DAY), 'Male', 'B+', 3.50, 'St. Mary General Healthcare', 'None', 'Healthy infant, breastfed.'),
(4, 8, 'Zara Khan', DATE_SUB(CURDATE(), INTERVAL 300 DAY), 'Female', 'AB+', 2.95, 'Sunrise Pediatric Clinic', 'None reported', 'Born at 38 weeks. Strong vital signs.');

-- -------------------------------------------------------------
-- Insert Seed Data: Appointments
-- -------------------------------------------------------------
INSERT INTO `appointments` (`id`, `booking_reference`, `parent_id`, `child_id`, `vaccine_id`, `hospital_id`, `appointment_date`, `appointment_time`, `status`, `admin_notes`, `administered_date`, `batch_number`, `administered_by`, `hospital_remarks`) VALUES
(1, 'VAC-2026-1001', 6, 1, 1, 1, DATE_SUB(CURDATE(), INTERVAL 90 DAY), '09:00 AM - 10:00 AM', 'vaccinated', 'Approved automatically for birth immunization.', DATE_SUB(CURDATE(), INTERVAL 90 DAY), 'BCG-SERUM-8821B', 'Nurse Claire Adams', 'Administered intradermally in left upper arm. No adverse immediate reaction.'),
(2, 'VAC-2026-1002', 6, 1, 4, 1, DATE_SUB(CURDATE(), INTERVAL 42 DAY), '10:30 AM - 11:30 AM', 'vaccinated', 'Approved schedule.', DATE_SUB(CURDATE(), INTERVAL 42 DAY), 'PEN-GSK-40291X', 'Dr. Eleanor Vance', 'Child tolerated dose well. Parent advised on infant paracetamol for low fever.'),
(3, 'VAC-2026-1003', 6, 1, 8, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '09:30 AM - 10:30 AM', 'approved', 'Appointment approved. Hospital confirmed slot availability.', NULL, NULL, NULL, NULL),
(4, 'VAC-2026-1004', 7, 3, 4, 2, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '11:00 AM - 12:00 PM', 'pending', NULL, NULL, NULL, NULL, NULL),
(5, 'VAC-2026-1005', 6, 2, 10, 3, DATE_SUB(CURDATE(), INTERVAL 210 DAY), '02:00 PM - 03:00 PM', 'vaccinated', 'Approved routine 9-month immunization.', DATE_SUB(CURDATE(), INTERVAL 210 DAY), 'MR-SII-77109', 'Nurse Sarah Jenkins', 'Measles-Rubella vaccine administered right upper arm.'),
(6, 'VAC-2026-1006', 8, 4, 10, 3, DATE_ADD(CURDATE(), INTERVAL 7 DAY), '10:00 AM - 11:00 AM', 'pending', NULL, NULL, NULL, NULL, NULL),
(7, 'VAC-2026-1007', 6, 2, 11, 1, CURDATE(), '10:00 AM - 11:00 AM', 'approved', 'Approved for 16-month booster visit.', NULL, NULL, NULL, NULL);
