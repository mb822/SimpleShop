CREATE TABLE IF NOT EXISTS `Ratings` (
    `id` INT NOT NULL AUTO_INCREMENT
    ,`product_id` INT  NOT NULL
    ,`user_id` INT NOT NULL
    ,`rating` INT NOT NULL
    ,`comment` TEXT  NOT NULL
    ,`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,PRIMARY KEY (`id`)
)

  
