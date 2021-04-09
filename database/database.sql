SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
# todo add 'revenue' and subtraction logic + exception if no revenues on orders
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL UNIQUE,
  `password` varchar(128),
  `since` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users : password for testing is "AnyPass1000"
-- ----------------------------
INSERT INTO `users` (`name`, `email`, `password`) VALUES ('Test User', 'test@user.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `email`, `password`, `since`) VALUES ('Coca Cola', 'coca-cola@mail.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e', '2014-06-28');
INSERT INTO `users` (`name`, `email`, `password`, `since`) VALUES ('Teamleader', 'teamleader@mail.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e', '2015-01-15');
INSERT INTO `users` (`name`, `email`, `password`, `since`) VALUES ('Jeroen De Wit', 'jeroen@mail.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e', '2016-02-11');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`
(
    `id`      INT(11) NOT NULL AUTO_INCREMENT,
    `total`   FLOAT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `fk_order_user_idx` (`user_id` ASC),
    CONSTRAINT `fk_order_user`
        FOREIGN KEY (`user_id`)
            REFERENCES `users` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

INSERT INTO `orders`
(`id`, `total`, `user_id`)
VALUES (1000, 69, 1);

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`
(
    `id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

INSERT INTO `category`
(`id`, `name`)
VALUES (1, 'Tools'),
       (2, 'Switches');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`
(
    `id`          VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NULL,
    `category`    INT(11)      NOT NULL,
    `price`       FLOAT(11)    NOT NULL,
    PRIMARY KEY (`id`, `category`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `fk_products_category1_idx` (`category` ASC),
    CONSTRAINT `fk_products_category1`
        FOREIGN KEY (`category`)
            REFERENCES `category` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB
    AUTO_INCREMENT = 0
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `products`
    (`id`, `description`, `category`, `price`)
VALUES ('A101', 'Screwdriver', 1, 9.75),
       ('A102', 'Electric screwdriver', 1, 49.50),
       ('B101', 'Basic on-off switch', 2, 4.99),
       ('B102', 'Press button', 2, 4.99),
       ('B103', 'Switch with motion detector', 2, 12.95);

-- ----------------------------
-- Table structure for product_has_order
-- ----------------------------
DROP TABLE IF EXISTS `product_has_order`;
CREATE TABLE `product_has_order`
(
    `id`         INT(11) NOT NULL AUTO_INCREMENT,
    `product_id` VARCHAR(255) NOT NULL,
    `order_id`   INT(11) NOT NULL,
    `price`      FLOAT(11) NULL,
    `quantity`   INT(11) NULL,
    PRIMARY KEY (`product_id`, `order_id`, `id`),
    INDEX `fk_product_has_order_order1_idx` (`order_id` ASC),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    CONSTRAINT `fk_product_has_order_product1`
        FOREIGN KEY (`product_id`)
            REFERENCES `products` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
    CONSTRAINT `fk_product_has_order_order1`
        FOREIGN KEY (`order_id`)
            REFERENCES `orders` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB
    AUTO_INCREMENT = 1
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `product_has_order`
(`product_id`, `order_id`, `price`, `quantity`)
VALUES ('A101', 1000, 9.75, 2),
       ('A102', 1000, 49.5, 1);