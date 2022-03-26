ALTER TABLE Users
    ADD COLUMN visibility ENUM('private','public')  DEFAULT 'private';
