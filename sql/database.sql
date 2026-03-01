-- Create database
CREATE DATABASE IF NOT EXISTS architecture_firm;
USE architecture_firm;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    category_id INT,
    location VARCHAR(255),
    year INT,
    area VARCHAR(100),
    client VARCHAR(200),
    description TEXT,
    featured_img VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Project images table
CREATE TABLE project_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    img_url VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- Team members table
CREATE TABLE team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    bio TEXT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact submissions table
CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT INTO categories (name, slug) VALUES
('Residential', 'residential'),
('Commercial', 'commercial'),
('Interior', 'interior'),
('Landscape', 'landscape');

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@architecture.com', '$2y$10$YourHashedPasswordHere', 'admin');

-- Insert sample team members
INSERT INTO team (name, role, bio, sort_order) VALUES
('John Smith', 'Principal Architect', 'With over 20 years of experience in luxury residential architecture...', 1),
('Sarah Chen', 'Senior Designer', 'Sarah brings innovative design solutions with a focus on sustainable luxury...', 2),
('Michael Roberts', 'Project Manager', 'Michael ensures every project meets the highest standards of excellence...', 3),
('Elena Rodriguez', 'Interior Architect', 'Specializing in creating harmonious interior spaces that blend with architecture...', 4);

-- Insert sample projects
INSERT INTO projects (title, slug, category_id, location, year, area, client, description, featured_img) VALUES
('Crystal Waters Villa', 'crystal-waters-villa', 1, 'Malibu, California', 2023, '8500 sq ft', 'Private Client', 'A stunning oceanfront residence that seamlessly integrates with its natural surroundings...', 'crystal-waters.jpg'),
('Horizon Tower', 'horizon-tower', 2, 'New York City', 2022, '250000 sq ft', 'Metro Development', 'A landmark commercial tower redefining the city skyline with sustainable design...', 'horizon-tower.jpg'),
('Minimalist Loft', 'minimalist-loft', 3, 'Tokyo, Japan', 2023, '2200 sq ft', 'Design Collective', 'An exploration of space, light, and materiality in urban living...', 'minimalist-loft.jpg'),
('Coastal Garden', 'coastal-garden', 4, 'Santorini, Greece', 2022, '15000 sq ft', 'Luxury Resorts', 'Landscape architecture that celebrates the Mediterranean coastline...', 'coastal-garden.jpg');

-- Insert sample project images
INSERT INTO project_images (project_id, img_url, caption, sort_order) VALUES
(1, 'crystal-waters-1.jpg', 'Ocean view terrace', 1),
(1, 'crystal-waters-2.jpg', 'Living room interior', 2),
(1, 'crystal-waters-3.jpg', 'Infinity pool', 3),
(2, 'horizon-tower-1.jpg', 'Building exterior', 1),
(2, 'horizon-tower-2.jpg', 'Lobby design', 2),
(3, 'minimalist-loft-1.jpg', 'Open living space', 1),
(3, 'minimalist-loft-2.jpg', 'Kitchen detail', 2),
(4, 'coastal-garden-1.jpg', 'Garden pathway', 1);