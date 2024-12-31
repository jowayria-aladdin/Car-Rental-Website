-- Car Rental Database
CREATE DATABASE car_rental;
USE car_rental;

-- Office Table
CREATE TABLE office (
    office_id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL,
    admin_id INT
);

-- Admin Table
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    office_id INT
);

-- Car Table 
CREATE TABLE car (
    car_id INT AUTO_INCREMENT,
    office_id INT,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    color VARCHAR(30) NOT NULL,
    turbo BOOLEAN NOT NULL DEFAULT FALSE,
    ccs BOOLEAN NOT NULL DEFAULT FALSE,
    status ENUM('active', 'out_of_service', 'rented') DEFAULT 'active',
    price DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (car_id, office_id)
);

-- Customer Table
CREATE TABLE customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    address VARCHAR(255),
    dob DATE NOT NULL
);

-- Reservation Table
CREATE TABLE reservation (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    office_id INT NOT NULL,
    customer_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    pickup_date DATE NOT NULL,
    return_date DATE NOT NULL,
    status ENUM('cancelled', 'ongoing', 'done') DEFAULT 'ongoing'
);

-- Payment Table
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL
);

-- Add Columns to Customer Table
ALTER TABLE customer
ADD COLUMN password VARCHAR(255) NOT NULL,
ADD COLUMN phone_number VARCHAR(15) NOT NULL,
ADD COLUMN second_phone_number VARCHAR(15);

-- Add Columns to Car Table
ALTER TABLE car 
MODIFY COLUMN ccs INT;

ALTER TABLE car
ADD COLUMN plate_id VARCHAR(20) UNIQUE NOT NULL;
------------------------------------------------------------------------------------------------
-- Add Foreign Key Constraints
ALTER TABLE admin
ADD CONSTRAINT fk_admin_office
FOREIGN KEY (office_id) REFERENCES office(office_id);

ALTER TABLE office
ADD CONSTRAINT fk_office_admin
FOREIGN KEY (admin_id) REFERENCES admin(admin_id);

ALTER TABLE car
ADD CONSTRAINT fk_car_office
FOREIGN KEY (office_id) REFERENCES office(office_id);

ALTER TABLE reservation
ADD CONSTRAINT fk_reservation_car
FOREIGN KEY (car_id, office_id) REFERENCES car(car_id, office_id),
ADD CONSTRAINT fk_reservation_customer
FOREIGN KEY (customer_id) REFERENCES customer(customer_id);

ALTER TABLE payment
ADD CONSTRAINT fk_payment_reservation
FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id);
