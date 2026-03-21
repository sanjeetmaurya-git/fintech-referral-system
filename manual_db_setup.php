<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// 1. work_categories
$mysqli->query("CREATE TABLE IF NOT EXISTS `work_categories` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `icon` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 2. work_subcategories
$mysqli->query("CREATE TABLE IF NOT EXISTS `work_subcategories` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `subcat_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `work_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 3. workers
$mysqli->query("CREATE TABLE IF NOT EXISTS `workers` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `alternate_mobile` VARCHAR(20) NULL,
    `highest_qualification` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `district` VARCHAR(100) NULL,
    `state` VARCHAR(100) NULL,
    `pincode` VARCHAR(10) NULL,
    `category_id` INT(11) UNSIGNED NULL,
    `subcategory_id` INT(11) UNSIGNED NULL,
    `skills` TEXT NULL,
    `experience` INT(5) DEFAULT 0,
    `aadhar_number` VARCHAR(20) NULL,
    `pan_number` VARCHAR(20) NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `is_online` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `worker_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `worker_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `work_categories`(`id`) ON DELETE SET NULL,
    CONSTRAINT `worker_subcat_fk` FOREIGN KEY (`subcategory_id`) REFERENCES `work_subcategories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 4. worker_documents
$mysqli->query("CREATE TABLE IF NOT EXISTS `worker_documents` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `worker_id` INT(11) UNSIGNED NOT NULL,
    `document_type` VARCHAR(50) NOT NULL,
    `file_path` TEXT NOT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `doc_worker_fk` FOREIGN KEY (`worker_id`) REFERENCES `workers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 5. jobs
$mysqli->query("CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `worker_id` INT(11) UNSIGNED NOT NULL,
    `category_id` INT(11) UNSIGNED NULL,
    `description` TEXT NULL,
    `budget` DECIMAL(10,2) NULL,
    `location` VARCHAR(255) NULL,
    `status` ENUM('requested', 'accepted', 'rejected', 'completed', 'cancelled') DEFAULT 'requested',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `job_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `job_worker_fk` FOREIGN KEY (`worker_id`) REFERENCES `workers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo 'Created';
$mysqli->close();
