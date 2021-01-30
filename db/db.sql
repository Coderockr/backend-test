-- -----------------------------------------------------
-- Database event_manager
-- -----------------------------------------------------
DROP DATABASE IF EXISTS `event_manager`;
CREATE DATABASE IF NOT EXISTS `event_manager` DEFAULT CHARACTER SET utf8;
USE `event_manager`;

-- -----------------------------------------------------
-- Table `event_manager`.`address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_manager`.`address`
(
    `id`             INT         NOT NULL AUTO_INCREMENT,
    `address_line_1` VARCHAR(60) NOT NULL,
    `address_line_2` VARCHAR(60) NULL     DEFAULT NULL,
    `state`          VARCHAR(20) NULL     DEFAULT NULL,
    `city`           VARCHAR(20) NULL     DEFAULT NULL,
    `postal_code`    VARCHAR(20) NULL     DEFAULT NULL,
    `created_at`     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `event_manager`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_manager`.`user`
(
    `id`                       INT          NOT NULL AUTO_INCREMENT,
    `name`                     VARCHAR(100) NOT NULL,
    `email`                    VARCHAR(50)  NOT NULL,
    `password`                 VARCHAR(20)  NOT NULL,
    `password_failed_attempts` INT          NULL     DEFAULT NULL,
    `account_locked_at`        TIMESTAMP    NULL     DEFAULT NULL,
    `bio`                      TEXT         NULL     DEFAULT NULL,
    `user_address_id`          INT          NULL     DEFAULT NULL,
    `active`                   TINYINT      NOT NULL DEFAULT 1,
    `last_login`               TIMESTAMP    NULL     DEFAULT NULL,
    `created_at`               TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`               TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `user_address_id`
        FOREIGN KEY (`user_address_id`)
            REFERENCES `event_manager`.`address` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `event_manager`.`friendship`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_manager`.`friendship`
(
    `id`                    INT       NOT NULL AUTO_INCREMENT,
    `friendship_inviter_id` INT       NOT NULL,
    `friendship_invitee_id` INT       NOT NULL,
    `requested_at`          TIMESTAMP NOT NULL,
    `accepted_at`           TIMESTAMP NULL DEFAULT NULL,
    `rejected_at`           TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `friendship_inviter_id`
        FOREIGN KEY (`friendship_inviter_id`)
            REFERENCES `event_manager`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `friendship_invitee_id`
        FOREIGN KEY (`friendship_invitee_id`)
            REFERENCES `event_manager`.`user` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `event_manager`.`event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_manager`.`event`
(
    `id`               INT         NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(20) NOT NULL,
    `description`      TEXT        NULL     DEFAULT NULL,
    `date`             DATE        NOT NULL,
    `time`             TIME        NOT NULL,
    `event_address_id` INT         NULL     DEFAULT NULL,
    `creator_id`       INT         NOT NULL,
    `canceled`         TINYINT     NOT NULL DEFAULT 0,
    `created_at`       TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `creator_id`
        FOREIGN KEY (`creator_id`)
            REFERENCES `event_manager`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `event_address_id`
        FOREIGN KEY (`event_address_id`)
            REFERENCES `event_manager`.`address` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `event_manager`.`event_attendance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_manager`.`event_attendance`
(
    `id`                  INT NOT NULL AUTO_INCREMENT,
    `attendance_event_id` INT NOT NULL,
    `attendance_user_id`  INT NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `attendance_event_id`
        FOREIGN KEY (`attendance_event_id`)
            REFERENCES `event_manager`.`event` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `attendance_user_id`
        FOREIGN KEY (`attendance_user_id`)
            REFERENCES `event_manager`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `event_manager`.`event_invitation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_manager`.`event_invitation`
(
    `id`                    INT       NOT NULL,
    `invitation_inviter_id` INT       NULL     DEFAULT NULL,
    `invitation_invitee_id` INT       NULL     DEFAULT NULL,
    `invitation_event_id`   INT       NULL     DEFAULT NULL,
    `invitation_invited_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `accepted_at`           TIMESTAMP NULL     DEFAULT NULL,
    `rejected_at`           TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `invitation_invitee_id`
        FOREIGN KEY (`invitation_invitee_id`)
            REFERENCES `event_manager`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `invitation_inviter_id`
        FOREIGN KEY (`invitation_inviter_id`)
            REFERENCES `event_manager`.`user` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
    CONSTRAINT `invitation_event_id`
        FOREIGN KEY (`invitation_event_id`)
            REFERENCES `event_manager`.`event` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;