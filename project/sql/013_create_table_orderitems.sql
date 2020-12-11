CREATE TABLE OrderItems
(
    id          int auto_increment,
    order_id int NOT NULL ,
    product_id     int NOT NULL ,
    quantity int NOT NULL,
    unit_price DOUBLE(25,2) NOT NULL,
    primary key (id)
)


