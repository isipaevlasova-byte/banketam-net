CREATE DATABASE IF NOT EXISTS banketam_net CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE banketam_net;

-- Пользователи
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fio VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Помещения
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- Способы оплаты
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Заявки
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    payment_id INT NOT NULL,
    banquet_date DATE NOT NULL,
    status ENUM('Новая','Банкет назначен','Банкет завершен') DEFAULT 'Новая',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (payment_id) REFERENCES payment_methods(id)
);

-- Отзывы
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Начальные данные
INSERT INTO rooms (name, description, image) VALUES
('Зал', 'Просторный зал с панорамным видом на город', 'room1.jpg'),
('Ресторан', 'Классический ресторан премиум-класса', 'room2.jpg'),
('Летняя веранда', 'Открытая веранда среди зелени', 'room3.jpg'),
('Закрытая веранда', 'Стеклянная веранда круглый год', 'room4.jpg');

INSERT INTO payment_methods (name) VALUES
('Наличные'), ('Банковская карта'), ('Онлайн-перевод');
