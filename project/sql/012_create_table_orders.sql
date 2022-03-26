CREATE TABLE Orders
(
    id          int auto_increment,
    user_id int NOT NULL ,
    created     TIMESTAMP	default current_timestamp,
    total_price   DOUBLE(25,2)   NOT NULL ,
    address TEXT  NOT NULL,
    payment_method varchar(20) NOT NULL,
    primary key (id)
)
