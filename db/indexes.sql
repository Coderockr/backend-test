USE `event_manager`;

-- -----------------------------------------------------
-- Table `event_manager`.`user`
-- -----------------------------------------------------
CREATE UNIQUE INDEX IF NOT EXISTS `email_UNIQUE` ON `event_manager`.`user` (`email` ASC);
CREATE INDEX IF NOT EXISTS `address_id_idx` ON `event_manager`.`user` (`user_address_id` ASC);

-- -----------------------------------------------------
-- Table `event_manager`.`friendship`
-- -----------------------------------------------------
CREATE INDEX IF NOT EXISTS `inviter_email_idx` ON `event_manager`.`friendship` (`friendship_inviter_id` ASC);
CREATE INDEX IF NOT EXISTS `invitee_id_idx` ON `event_manager`.`friendship` (`friendship_invitee_id` ASC);
CREATE INDEX IF NOT EXISTS `requested_at_idx` ON `event_manager`.`friendship` (`requested_at` ASC);

-- -----------------------------------------------------
-- Table `event_manager`.`event`
-- -----------------------------------------------------
CREATE INDEX IF NOT EXISTS `address_id_idx` ON `event_manager`.`event` (`event_address_id` ASC);
CREATE INDEX IF NOT EXISTS `date_idx` ON `event_manager`.`event` (`date` ASC);
CREATE INDEX IF NOT EXISTS `time_idx` ON `event_manager`.`event` (`time` ASC);
CREATE INDEX IF NOT EXISTS `canceled_idx` ON `event_manager`.`event` (`canceled` ASC);
CREATE INDEX IF NOT EXISTS `name_idx` ON `event_manager`.`event` (`name` ASC);

-- -----------------------------------------------------
-- Table `event_manager`.`event_attendance`
-- -----------------------------------------------------
CREATE INDEX IF NOT EXISTS `event_id_idx` ON `event_manager`.`event_attendance` (`attendance_event_id` ASC);
CREATE INDEX IF NOT EXISTS `user_id_idx` ON `event_manager`.`event_attendance` (`attendance_user_id` ASC);

-- -----------------------------------------------------
-- Table `event_manager`.`event_invitation`
-- -----------------------------------------------------
CREATE INDEX IF NOT EXISTS `invitee_id_idx` ON `event_manager`.`event_invitation` (`invitation_invitee_id` ASC);
CREATE INDEX IF NOT EXISTS `inviter_id_idx` ON `event_manager`.`event_invitation` (`invitation_inviter_id` ASC);
CREATE INDEX IF NOT EXISTS `invited_at_idx` ON `event_manager`.`event_invitation` (`invitation_invited_at` ASC);
CREATE INDEX IF NOT EXISTS `event_id_idx` ON `event_manager`.`event_invitation` (`invitation_event_id` ASC);