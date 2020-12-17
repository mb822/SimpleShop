CREATE TABLE Viewers
(
    id          int auto_increment,
    ip TEXT,
    created     TIMESTAMP	default current_timestamp,
    user_id     int,
    primary key (id)
)

