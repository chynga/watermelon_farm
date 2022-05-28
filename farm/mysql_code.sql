-- ALTER TABLE
--     `farm`.`field` DROP FOREIGN KEY IF EXISTS `FK_field_watermelon`;

-- ALTER TABLE
--     `farm`.`cart` DROP FOREIGN KEY IF EXISTS `FK_cart_user`;

-- ALTER TABLE
--     `farm`.`cart` DROP FOREIGN KEY IF EXISTS `FK_cart_watermelon`;

-- ALTER TABLE
--     `farm`.`ordered_items` DROP FOREIGN KEY IF EXISTS `FK_ordered_items_order`;

-- ALTER TABLE
--     `farm`.`ordered_items` DROP FOREIGN KEY IF EXISTS `FK_ordered_items_watermelon`;

-- ALTER TABLE
--     `farm`.`order` DROP FOREIGN KEY IF EXISTS `FK_order_user`;

DROP TABLE IF EXISTS `farm`.`watermelon`;

DROP TABLE IF EXISTS `farm`.`user`;

DROP TABLE IF EXISTS `farm`.`field`;

DROP TABLE IF EXISTS `farm`.`order`;

DROP TABLE IF EXISTS `farm`.`cart`;

DROP TABLE IF EXISTS `farm`.`ordered_items`;

-- CREATE TABLES
CREATE TABLE `farm`.`watermelon` (
    `id` int NOT NULL AUTO_INCREMENT,
    `status` enum('ripe', 'unripe', 'plucked') NOT NULL default 'unripe',
    `weight` float NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `farm`.`user` (
    `id` int NOT NULL AUTO_INCREMENT,
    `email` varchar(50) UNIQUE NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `farm`.`field` (
    `row` int NOT NULL,
    `col` int NOT NULL,
    `watermelon_id` int UNIQUE,
    PRIMARY KEY (`row`, `col`)
);

CREATE TABLE `farm`.`order` (
    `id` int NOT NULL AUTO_INCREMENT,
    `status` enum('delivered', 'not_dispatched') NOT NULL default 'not_dispatched',
    `time` datetime NOT NULL,
    `city` varchar(50) NOT NULL,
    `street` varchar(50) NOT NULL,
    `home_number` varchar(20) NOT NULL,
    `phone` varchar(50) NOT NULL,
    `in_slices` enum('yes', 'no') NOT NULL default 'no',
    `user_id` int NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `farm`.`ordered_items` (
    `watermelon_id` int NOT NULL,
    `order_id` int NOT NULL
);

CREATE TABLE `farm`.`cart` (
    `watermelon_id` int NOT NULL,
    `user_id` int NOT NULL
);

-- CREATE FOREIGN KEY CONSTRAINTS 
ALTER TABLE
    `farm`.`field`
ADD
    CONSTRAINT `FK_field_watermelon` FOREIGN KEY (`watermelon_id`) REFERENCES `watermelon` (`id`) ON DELETE Cascade ON UPDATE Cascade;

ALTER TABLE
    `farm`.`cart`
ADD
    CONSTRAINT `FK_cart_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE Cascade ON UPDATE Cascade;

ALTER TABLE
    `farm`.`cart`
ADD
    CONSTRAINT `FK_cart_watermelon` FOREIGN KEY (`watermelon_id`) REFERENCES `watermelon` (`id`) ON DELETE Cascade ON UPDATE Cascade;

ALTER TABLE
    `farm`.`ordered_items`
ADD
    CONSTRAINT `FK_ordered_items_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE Cascade ON UPDATE Cascade;

ALTER TABLE
    `farm`.`ordered_items`
ADD
    CONSTRAINT `FK_ordered_items_watermelon` FOREIGN KEY (`watermelon_id`) REFERENCES `watermelon` (`id`) ON DELETE Cascade ON UPDATE Cascade;

ALTER TABLE
    `farm`.`order`
ADD
    CONSTRAINT `FK_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE Cascade ON UPDATE Cascade;

-- TRIGGER
USE `farm`;

delimiter / / CREATE TRIGGER tr_amount_check BEFORE
INSERT
    ON `farm`.`cart` FOR EACH ROW BEGIN IF (
        SELECT
            COUNT(*)
        FROM
            `farm`.`cart`
        WHERE
            `cart`.`user_id` = NEW.`user_id`
    ) > 2 THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Amount of items must not exceed 3';

--    SET NEW.`user_id` = NULL;
END IF;

END;

/ / delimiter ;

delimiter / / CREATE TRIGGER tr_duplicate_check BEFORE
INSERT
    ON `farm`.`cart` FOR EACH ROW FOLLOWS tr_amount_check BEGIN IF (
        SELECT
            COUNT(*)
        FROM
            `farm`.`cart`
        WHERE
            `cart`.`user_id` = NEW.`user_id`
            AND `cart`.`watermelon_id` = NEW.`watermelon_id`
    ) > 0 THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Duplicate values are not allowed';

--    SET NEW.`user_id` = NULL;
END IF;

END;

/ / delimiter ;

delimiter / / CREATE TRIGGER tr_date_check BEFORE
INSERT
    ON `farm`.`order` FOR EACH ROW BEGIN IF (
        SELECT
            (DATEDIFF(DATE(NEW.`time`), CURRENT_DATE))
    ) > 9 THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Date should be within 9 days';

--    SET NEW.`time` = NULL;
END IF;

END;

/ / delimiter ;

delimiter / / CREATE TRIGGER tr_cart_check BEFORE
INSERT
    ON `farm`.`order` FOR EACH ROW FOLLOWS tr_date_check BEGIN IF (
        SELECT
            COUNT(*)
        FROM
            cart
        WHERE
            NEW.user_id = cart.user_id
    ) <= 0 THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Order can not contain empty items';

--    SET NEW.`user_id` = NULL;
END IF;

END;

/ / delimiter ;

delimiter / / CREATE TRIGGER tr_move_from_cart_to_ordered
AFTER
INSERT
    ON `farm`.`order` FOR EACH ROW -- FOLLOWS tr_cart_check
    BEGIN DECLARE CURSOR_USER_ID INT;

DECLARE CURSOR_WATERMELON_ID INT;

DECLARE done INT DEFAULT FALSE;

DECLARE cursor_cart CURSOR FOR
SELECT
    user_id,
    watermelon_id
FROM
    cart
WHERE
    user_id = NEW.`user_id`;

DECLARE CONTINUE HANDLER FOR NOT FOUND
SET
    done = TRUE;

OPEN cursor_cart;

loop_through_rows: LOOP FETCH cursor_cart INTO CURSOR_USER_ID,
CURSOR_WATERMELON_ID;

IF done THEN LEAVE loop_through_rows;

END IF;

INSERT INTO
    ordered_items(order_id, watermelon_id)
VALUES
(NEW.`id`, CURSOR_WATERMELON_ID);

END LOOP;

CLOSE cursor_cart;

DELETE FROM
    cart
WHERE
    `user_id` = NEW.`user_id`;

END;

/ / delimiter ;

-- POPULATE TABLE
DELETE FROM `farm`.`watermelon`;

DELETE FROM `farm`.`field`;

INSERT INTO
    `farm`.`watermelon` (`id`, `status`, `weight`)
VALUES
    (1, 'unripe', 6.2),
    (2, 'ripe', 10.0),
    (3, 'unripe', 5.9),
    (4, 'unripe', 7.4),
    (5, 'ripe', 11.2),
    (6, 'unripe', 4.2),
    (7, 'ripe', 11.0),
    (8, 'unripe', 6.9),
    (9, 'unripe', 6.4),
    (10, 'ripe', 12.2),
    (11, 'unripe', 3.2),
    (12, 'ripe', 14.0),
    (13, 'unripe', 5.5),
    (14, 'unripe', 7.1),
    (15, 'ripe', 11.25);

INSERT INTO
    `farm`.`field` (`row`, `col`, `watermelon_id`)
VALUES
    (1, 1, 1),
    (1, 2, 2),
    (1, 3, 3),
    (1, 4, 4),
    (1, 5, 5),
    (2, 1, 6),
    (2, 2, 7),
    (2, 3, 8),
    (2, 4, 9),
    (2, 5, 10),
    (3, 1, 11),
    (3, 2, 12),
    (3, 3, 13),
    (3, 4, 14),
    (3, 5, 15);

INSERT INTO
    `farm`.`user` (`id`, `email`)
VALUES
    (1, 'test1@gmail.com'),
    (2, 'test2@gmail.com'),
    (3, 'test3@gmail.com'),
    (4, 'test4@gmail.com'),
    (5, 'test5@gmail.com');