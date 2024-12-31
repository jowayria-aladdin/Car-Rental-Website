USE car_rental;

-- Insert Offices
INSERT INTO office (office_id, location, admin_id) VALUES
(1, 'Alexandria', 1),
(2, 'Aswan', 2),
(3, 'Asyut', 3),
(4, 'Beheira', 4),
(5, 'Beni Suef', 5),
(6, 'Cairo', 6);

-- Insert Admins
INSERT INTO admin (admin_id, email, password, office_id) VALUES
(1, 'admin@domain.com', '1234', 1),
(2, 'reem@domain.com', '1234', 2),
(3, 'nada@domain.com', '1234', 3),
(4, 'jury@domain.com', '1234', 4),
(5, 'yomna@domain.com', '1234', 5),
(6, 'slwa@domain.com', '1234', 6);

-- Insert Customers
INSERT INTO customer (customer_id, firstname, lastname, email, address, dob, password, phone_number, second_phone_number)
VALUES
(1, 'jury', 'alaa', 'jury@domain.com', 'smouha', '2003-09-15', '1234', '0123456789', '0987654321'),
(2, 'reem', 'mohamed', 'reem@domain.com', 'smouha', '2009-09-15', '1234', '0123456788', NULL),
(3, 'nada', 'hanafi', 'nada@domain.com', 'smouha', '2002-09-15', '1234', '0123456787', '0987654320'),
(4, 'yomna', 'gharib', 'yomna@domain.com', 'smouha', '2000-09-15', '1234', '0123456786', NULL);

-- Insert Cars
INSERT INTO car (car_id, office_id, brand, model, year, color, turbo, ccs, status, price, plate_id) VALUES
(1, 1, 'Toyota', 'Corolla', 2020, 'White', FALSE, 1000, 'active', 50.00, 'PLT-001'),
(2, 1, 'Toyota', 'Camry', 2021, 'Silver', TRUE, 1000, 'active', 60.00, 'PLT-002'),
(3, 2, 'Honda', 'Civic', 2019, 'Black', FALSE, 1000, 'active', 45.00, 'PLT-003'),
(4, 2, 'Honda', 'Accord', 2020, 'Blue', TRUE, 1000, 'active', 55.00, 'PLT-004'),
(5, 3, 'Ford', 'Focus', 2018, 'Red', FALSE, 1000, 'active', 40.00, 'PLT-005'),
(6, 3, 'Ford', 'Fusion', 2020, 'Grey', TRUE, 1200, 'rented', 70.00, 'PLT-006'),
(7, 4, 'Chevrolet', 'Malibu', 2022, 'White', TRUE, 1200, 'active', 75.00, 'PLT-007'),
(8, 4, 'Chevrolet', 'Impala', 2021, 'Black', TRUE, 1200, 'active', 65.00, 'PLT-008'),
(9, 5, 'BMW', '3 Series', 2019, 'Blue', TRUE, 1200, 'out_of_service', 100.00, 'PLT-009'),
(10, 5, 'BMW', '5 Series', 2020, 'White', TRUE, 1200, 'active', 120.00, 'PLT-010'),
(11, 6, 'Audi', 'A4', 2020, 'Silver', TRUE, 1400, 'rented', 110.00, 'PLT-011'),
(12, 6, 'Audi', 'Q5', 2021, 'Grey', TRUE, 1400, 'active', 130.00, 'PLT-012'),
(13, 1, 'Hyundai', 'Elantra', 2018, 'Black', FALSE, 1400, 'active', 40.00, 'PLT-013'),
(14, 1, 'Hyundai', 'Tucson', 2020, 'Red', TRUE, 1400, 'active', 60.00, 'PLT-014'),
(15, 2, 'Kia', 'Sportage', 2019, 'Blue', TRUE, 1400, 'active', 55.00, 'PLT-015'),
(16, 2, 'Kia', 'Soul', 2021, 'White', FALSE, 1400, 'active', 50.00, 'PLT-016'),
(17, 3, 'Nissan', 'Altima', 2017, 'Grey', FALSE, 1600, 'active', 45.00, 'PLT-017'),
(18, 3, 'Nissan', 'Maxima', 2019, 'Black', TRUE, 1600, 'active', 55.00, 'PLT-018'),
(19, 4, 'Mazda', 'Mazda3', 2020, 'Red', FALSE, 1600, 'rented', 50.00, 'PLT-019'),
(20, 4, 'Mazda', 'CX-5', 2021, 'White', TRUE, 1600, 'active', 65.00, 'PLT-020'),
(21, 5, 'Volkswagen', 'Jetta', 2018, 'Blue', FALSE, 1600, 'active', 48.00, 'PLT-021'),
(22, 5, 'Volkswagen', 'Passat', 2019, 'Silver', TRUE, 1600, 'active', 58.00, 'PLT-022'),
(23, 6, 'Subaru', 'Impreza', 2020, 'Black', FALSE, 3500, 'active', 47.00, 'PLT-023'),
(24, 6, 'Subaru', 'Forester', 2021, 'Green', TRUE, 3500, 'active', 65.00, 'PLT-024'),
(25, 1, 'Tesla', 'Model 3', 2022, 'White', TRUE, 3500, 'rented', 120.00, 'PLT-025'),
(26, 1, 'Tesla', 'Model Y', 2021, 'Grey', TRUE, 3500, 'active', 140.00, 'PLT-026'),
(27, 2, 'Mercedes-Benz', 'C-Class', 2018, 'Black', TRUE, 3500, 'out_of_service', 130.00, 'PLT-027'),
(28, 2, 'Mercedes-Benz', 'E-Class', 2020, 'White', TRUE, 3500, 'active', 150.00, 'PLT-028'),
(29, 3, 'Volvo', 'S60', 2019, 'Silver', TRUE, 3500, 'active', 70.00, 'PLT-029'),
(30, 3, 'Volvo', 'XC90', 2020, 'Black', TRUE, 3500, 'rented', 110.00, 'PLT-030'),
(31, 4, 'Jeep', 'Wrangler', 2021, 'Green', TRUE, 3500, 'active', 90.00, 'PLT-031'),
(32, 4, 'Jeep', 'Cherokee', 2020, 'Red', FALSE, 3500, 'active', 80.00, 'PLT-032');

-- Insert Reservations
INSERT INTO reservation (reservation_id, car_id, office_id, customer_id, reservation_date, pickup_date, return_date, status)
VALUES
(1, 1, 1, 1, '2022-10-19', '2022-10-20', '2023-02-01', 'done'),
(2, 3, 2, 2, '2022-12-04', '2022-12-05', '2022-12-10', 'cancelled');



SET @row_num = 0;

UPDATE car
JOIN (
    SELECT car_id, (@row_num := @row_num + 1) AS row_num
    FROM car
) AS numbered_cars ON car.car_id = numbered_cars.car_id
SET car.ccs = CASE
    WHEN numbered_cars.row_num <= 10 THEN 1000
    WHEN numbered_cars.row_num <= 20 THEN 1200
    WHEN numbered_cars.row_num <= 30 THEN 1400
    WHEN numbered_cars.row_num <= 40 THEN 1600
    ELSE 3500
END;
