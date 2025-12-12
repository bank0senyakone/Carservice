-- Workshop Management Portal Seed Data
-- Sample data for testing

USE wms_db;

-- ============================================
-- Admin User (password: password)
-- Hash is for 'password' - use fix_password.php to change
-- ============================================
INSERT INTO users (username, password, name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Staff', 'Staff');

-- ============================================
-- Sample Customers
-- ============================================
INSERT INTO customers (name, phone, email, address) VALUES
('John Smith', '555-0101', 'john.smith@email.com', '123 Main St, City'),
('Mike Wilson', '555-0102', 'mike.wilson@email.com', '456 Oak Ave, Town'),
('Emily Davis', '555-0103', 'emily.davis@email.com', '789 Pine Rd, Village'),
('David Brown', '555-0104', 'david.brown@email.com', '321 Elm St, Borough'),
('Sarah Johnson', '555-0105', 'sarah.johnson@email.com', '654 Maple Dr, District');

-- ============================================
-- Sample Jobs
-- ============================================
INSERT INTO jobs (customer_id, license_plate, car_model, car_year, vin, description, status, priority, estimated_completion) VALUES
(1, 'ABC 1234', 'Toyota Camry', 2022, 'JT2BF22K120285319', 'Full Service', 'Queue', 'Medium', DATE_ADD(NOW(), INTERVAL 3 HOUR)),
(2, 'DEF 9012', 'BMW X5', 2023, 'WBA5A5C58FD123456', 'Engine Diagnostic', 'In Progress', 'High', DATE_ADD(NOW(), INTERVAL 4 HOUR)),
(3, 'GHI 3456', 'Mercedes C300', 2020, 'WDDWF4KB8FR123456', 'Transmission Repair', 'Waiting for Parts', 'Medium', DATE_ADD(NOW(), INTERVAL 6 HOUR)),
(4, 'JKL 7890', 'Audi A4', 2022, 'WAUZZZ8E1EA123456', 'AC Repair', 'Quality Check', 'Low', DATE_ADD(NOW(), INTERVAL 1 HOUR)),
(5, 'MNO 2345', 'Honda Accord', 2021, '1HGCV1F34LA123456', 'Brake Replacement', 'Completed', 'Medium', NOW());

-- ============================================
-- Sample Inventory
-- ============================================
INSERT INTO inventory (part_name, sku, category, description, stock_level, unit_price, reorder_point) VALUES
('Brake Pads (Front)', 'BP-001', 'Brakes', 'Front brake pads - universal fit', 4, 89.99, 10),
('Brake Pads (Rear)', 'BP-002', 'Brakes', 'Rear brake pads - universal fit', 12, 79.99, 10),
('Engine Oil 5W-30', 'OIL-001', 'Fluids', '5W-30 Synthetic Motor Oil - 5L', 8, 42.99, 20),
('Engine Oil 10W-40', 'OIL-002', 'Fluids', '10W-40 Synthetic Motor Oil - 5L', 25, 38.99, 20),
('Air Filter (Universal)', 'AF-001', 'Filters', 'Universal air filter', 3, 24.99, 15),
('Oil Filter', 'OF-001', 'Filters', 'Standard oil filter', 18, 12.99, 15),
('Spark Plugs (Set of 4)', 'SP-001', 'Ignition', 'Iridium spark plugs set', 6, 45.99, 10),
('Battery 12V', 'BAT-001', 'Electrical', '12V car battery - 60Ah', 5, 129.99, 5);

-- ============================================
-- Sample Quotes
-- ============================================
INSERT INTO quotes (job_id, total_amount, tax_rate, status, notes) VALUES
(2, 377.98, 8.00, 'Pending', 'Engine diagnostic and brake service');

-- ============================================
-- Sample Quote Items
-- ============================================
INSERT INTO quote_items (quote_id, inventory_id, description, item_type, unit_price, quantity, hours) VALUES
(1, NULL, 'Engine Diagnostic', 'Labor', 85.00, 1, 1),
(1, NULL, 'Brake Pad Replacement', 'Labor', 75.00, 2, 2),
(1, 1, 'Brake Pads (Front)', 'Part', 89.99, 1, NULL),
(1, 6, 'Oil Filter', 'Part', 12.99, 2, NULL);
