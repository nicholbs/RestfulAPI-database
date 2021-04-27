CREATE DATABASE ski_manufacturer;

USE ski_manufacturer;

CREATE TABLE ski_types ( 
    type_id int AUTO_INCREMENT, 
    model varchar(255) NOT NULL, 
    type varchar(255) NOT NULL, 
    temperature ENUM('cold','warm') NOT NULL, 
    grip varchar(255) NOT NULL, 
    size ENUM('142','147','152','157','162','167','172','177','182','187','192','197','202','207') NOT NULL, 
    weight_class ENUM('20-30','30-40','40-50','50-60','60-70','70-80','80-90','90+') NOT NULL, 
    description varchar(255), 
    historical boolean DEFAULT 0, 
    photo_url varchar(255) DEFAULT 'photo-url',
    msrp int NOT NULL, 
    PRIMARY KEY(type_id) 
);

CREATE TABLE skis (
    serial_nr int AUTO_INCREMENT,
    ski_type int NOT NULL,
    manufactured_date date DEFAULT CURRENT_TIMESTAMP,
    order_assigned int,
    PRIMARY KEY (serial_nr)
);

CREATE TABLE employees (
    employee_id int AUTO_INCREMENT,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    department ENUM ('customer-rep', 'production-planner', 'storekeeper') NOT NULL,
    token varchar(255),
    PRIMARY KEY (employee_id)
);

CREATE TABLE customers (
    customer_id int AUTO_INCREMENT,
    name varchar(255),
    start_date date NULL DEFAULT CURRENT_TIMESTAMP,
    end_date date,
    buying_price float DEFAULT 1,
    token varchar(255),
    PRIMARY KEY (customer_id)
);

CREATE TABLE franchises (
    customer_id int NOT NULL,
    shipping_address varchar(255) NOT NULL,
    PRIMARY KEY (customer_id)
);

CREATE TABLE stores (
    customer_id int NOT NULL,
    shipping_address varchar(255) NOT NULL,
    franchise_id int,
    PRIMARY KEY (customer_id)
);

CREATE TABLE team_skiers (
    customer_id int NOT NULL,
    birthdate date NOT NULL,
    club varchar(255) NOT NULL,
    skis_per_year int NOT NULL,
    PRIMARY KEY (customer_id)
);

CREATE TABLE transporters (
    name varchar(255),
    PRIMARY KEY (name)
);

CREATE TABLE orders (
    order_nr int AUTO_INCREMENT,
    price float NOT NULL,
    state ENUM ('new', 'open', 'skis-available', 'shipped') DEFAULT 'new',
    customer_id int NOT NULL,
    date_placed timestamp DEFAULT CURRENT_TIMESTAMP,
    order_aggregate int,
    PRIMARY KEY (order_nr)
);

CREATE TABLE order_history (
    order_nr int AUTO_INCREMENT,
    state ENUM ('open', 'skis-available', 'shipped') NOT NULL,
    customer_rep int NOT NULL,
    changed_date datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (order_nr, state)
); 

CREATE TABLE order_aggregates (
    aggregate_id int AUTO_INCREMENT,
    customer_id int NOT NULL,
    PRIMARY KEY (aggregate_id)
);

CREATE TABLE shipments (
    shipment_nr int AUTO_INCREMENT,
    customer_id int NOT NULL,
    shipping_address varchar(255) NOT NULL,
    scheduled_pickup datetime NOT NULL,
    state ENUM ('ready', 'picked-up') DEFAULT 'ready',
    order_nr int NOT NULL,
    transporter varchar(255) NOT NULL,
    driver_id int NOT NULL,
    PRIMARY KEY (shipment_nr)
);

CREATE TABLE production_plans (
    ski_type int NOT NULL,
    day date NOT NULL,
    quantity int NOT NULL,
    PRIMARY KEY (ski_type, day)
);

CREATE TABLE sub_orders (
    order_nr int NOT NULL,
    type_id int NOT NULL,
    ski_quantity int DEFAULT 1,
    PRIMARY KEY (order_nr, type_id)
);

ALTER TABLE skis 
ADD CONSTRAINT skis_skitypes_fk 
FOREIGN KEY (ski_type) 
REFERENCES ski_types(type_id) 
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE skis 
ADD CONSTRAINT skis_orders_fk 
FOREIGN KEY (order_assigned) 
REFERENCES orders(order_nr) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE franchises 
ADD CONSTRAINT franchises_customer_fk 
FOREIGN KEY(customer_id) 
REFERENCES customers(customer_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE stores 
ADD CONSTRAINT stores_customer_fk 
FOREIGN KEY(customer_id) 
REFERENCES customers(customer_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE stores 
ADD CONSTRAINT stores_franchises_fk 
FOREIGN KEY(franchise_id) 
REFERENCES franchises(customer_id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE team_skiers 
ADD CONSTRAINT team_skiers_customers_fk 
FOREIGN KEY(customer_id) 
REFERENCES customers(customer_id) 
ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE orders 
ADD CONSTRAINT orders_customers_fk 
FOREIGN KEY(customer_id) 
REFERENCES customers(customer_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE orders 
ADD CONSTRAINT orders_aggregates_fk 
FOREIGN KEY(order_aggregate) 
REFERENCES order_aggregates(aggregate_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE order_history 
ADD CONSTRAINT order_history_orders_fk 
FOREIGN KEY(order_nr) 
REFERENCES orders(order_nr) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE order_history 
ADD CONSTRAINT order_history_employees_fk 
FOREIGN KEY(customer_rep) 
REFERENCES employees(employee_id) 
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE order_aggregates 
ADD CONSTRAINT order_aggregates_customer_fk 
FOREIGN KEY(customer_id) 
REFERENCES customers(customer_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE shipments 
ADD CONSTRAINT shipments_orders_fk 
FOREIGN KEY(order_nr) 
REFERENCES orders(order_nr) 
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE shipments 
ADD CONSTRAINT shipments_transporters_fk 
FOREIGN KEY(transporter) 
REFERENCES transporters(name) 
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE production_plans 
ADD CONSTRAINT production_plans_ski_types_fk 
FOREIGN KEY(ski_type) 
REFERENCES ski_types(type_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE sub_orders 
ADD CONSTRAINT sub_orders_order_fk
FOREIGN KEY (order_nr) 
REFERENCES orders(order_nr) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE sub_orders 
ADD CONSTRAINT sub_orders_ski_types_fk
FOREIGN KEY (type_id) 
REFERENCES ski_types(type_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `ski_types` (`model`, `type`, `temperature`, `grip`, `size`, `weight_class`, `description`, `historical`, `msrp`) VALUES 
('Active Pro', 'Skate', 'cold', 'IntelliWax', '182', '50-60', 'Good skis.', '0', '3200'),
('Redline', 'Classic', 'warm', 'Grippers', '167', '40-50', 'Slightly small skis.', '0', '1800'),
('Active Plain', 'Skate', 'cold', 'Handles', '197', '80-90', 'For big boys.', '1', '1650');

INSERT INTO `employees`(`first_name`, `last_name`, `department`, `token`)
VALUES ('Sylvester', 'Sølvtunge', 'customer-rep','839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261'), ('Njalle', 'Nøysom', 'production-planner', '022224c9a11805494a77796d671bec4c5bae495af78e906694018dbbc39bf2cd'), ('Didrik', 'Disk', 'storekeeper', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');

INSERT INTO `customers`(`name`, `start_date`, `buying_price`, `token`) 
VALUES ('Lars Monsen', '2021-03-15', 0.00, '2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85'), ('Snowy Plains Inc.', '2005-07-11', 0.65, '99f72d7e511685bae6517db832f1ee328538d8414470974ad53b94612fa7aa1e'), 
('Snowy Plains Asker', '2012-01-12', 0.65, '61973af54a323dd2d702219b86b494b0da247839eb1937ccff1e06e59e0934c3'), ('Snegutta', '2018-09-19', 0.80, '03b936e1b6f4bf1399253dbd4b2ddae49170572f107d8c13304dca880e689545');

INSERT INTO `franchises`(`customer_id`, `shipping_address`) 
VALUES (2, 'Bakgata 32');

INSERT INTO `stores`(`customer_id`, `shipping_address`, `franchise_id`) 
VALUES (3, 'Askervegen 2', 2), (4, 'Gaten 41', NULL);

INSERT INTO `team_skiers`(`customer_id`, `birthdate`, `club`, `skis_per_year`) 
VALUES (1, '1963-04-21', 'Uteklubben', 5);

INSERT INTO `transporters`(`name`)
VALUES ('Flyttegutta A/S'), ('Reposisjoneringspatruljen');

INSERT INTO `order_aggregates`(`customer_id`)
VALUES (2);

INSERT INTO `orders`(`price`, `state`, `customer_id`, `order_aggregate`, `date_placed`) VALUES
(208000, 'new', 2, 1, '2021-03-22'), (58500, 'new', 2, 1, '2021-03-22'), (32175, 'open', 3, NULL, '2021-03-19'), 
(7200, 'skis-available', 4, NULL, '2021-03-15'), (9600, 'skis-available', 1, NULL, '2021-03-17');

INSERT INTO `order_history`(`order_nr`,`state`, `customer_rep`, `changed_date`) 
VALUES (3, 'open', 1, '2021-03-12'), (4, 'open', 1, '2021-03-19'), (4, 'skis-available', 1, '2021-03-20'), (5, 'open', 1, '2021-03-22'),
(5, 'skis-available', 1, '2021-03-22');

INSERT INTO `shipments`(`customer_id`, `shipping_address`, `scheduled_pickup`, `state`, `order_nr`, `transporter`, `driver_id`)
VALUES (4, 'Gaten 41', '2021-03-27', 'picked-up', 4, 'Reposisjoneringspatruljen', 123167), (1, 'Monsensgate 1', '2021-04-05', 'ready', 5, 'Flyttegutta A/S', 120943);

INSERT INTO `skis`(`ski_type`, `manufactured_date`, `order_assigned`) VALUES
(1, '2021-02-21', 5), (1, '2021-02-21', 5), (1, '2021-02-21', 5), (1, '2021-02-21', NULL), (1, '2021-02-21', NULL), (1, '2021-02-21', NULL),
(1, '2021-02-21', NULL), (1, '2021-02-21', NULL), (1, '2021-02-21', NULL), (2, '2021-01-29', 4), (2, '2021-01-29', 4), (2, '2021-01-29', 4),
(2, '2021-01-29', 4), (2, '2021-01-29', 4), (2, '2021-01-29', NULL), (2, '2021-01-29', NULL), (2, '2021-01-29', NULL), (2, '2021-01-29', NULL),
(3, '2021-03-02', NULL), (3, '2021-03-02', NULL), (3, '2021-03-02', NULL), (3, '2021-03-02', NULL), (3, '2021-03-02', NULL), (3, '2021-03-02', NULL),
(3, '2021-03-02', NULL), (3, '2021-03-02', NULL), (3, '2021-03-02', NULL);

INSERT INTO `sub_orders`(`order_nr`, `type_id`,`ski_quantity` )
VALUES (1,1,100), (2,2,50), (3,3,30),(4,2,5),(5,1,3);
