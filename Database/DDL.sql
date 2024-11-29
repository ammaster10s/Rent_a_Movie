-- Creating the database
DROP DATABASE IF EXISTS MovieRentalSystem;
CREATE DATABASE IF NOT EXISTS MovieRentalSystem;
USE MovieRentalSystem;

-- Creating the Users table
CREATE TABLE Users (
    User_ID INT PRIMARY KEY AUTO_INCREMENT,     -- Primary Key for Users
    Username VARCHAR(50) UNIQUE NOT NULL,       -- Unique Username
    F_Name VARCHAR(50) NOT NULL,                -- First Name
    L_Name VARCHAR(50) NOT NULL,                -- Last Name
    Password VARCHAR(255) NOT NULL,             -- Password (hashed)
    Email_Address VARCHAR(100) UNIQUE NOT NULL  -- Unique Email Address
);

-- Creating the Movie table
CREATE TABLE Movie (
    Movie_ID INT PRIMARY KEY AUTO_INCREMENT,    -- Primary Key for Movies
    Price DECIMAL(10, 2) NOT NULL,              -- Price of the Movie
    Main_Actor VARCHAR(100),                    -- Main Actor
    Released_date DATE,                         -- Release Date
    Description TEXT,                           -- Movie Description
    Length INT,                                 -- Length in minutes
    Movie_Name VARCHAR(255) NOT NULL,          -- Movie Name
    Poster_Path VARCHAR(100),                   -- Path for the Poster
    Category VARCHAR(20)                       -- Movie Category
);

-- Creating the User_Address table
CREATE TABLE User_Address (
    Address_ID INT PRIMARY KEY AUTO_INCREMENT,  -- Primary Key for Addresses
    User_ID INT,                                -- Foreign Key to Users
    City VARCHAR(50),                           -- City
    House_Address VARCHAR(255),                -- Detailed Address
    Zipcode VARCHAR(10),                        -- Zip Code
    Country VARCHAR(50),                        -- Country
    Phone_number VARCHAR(15),                  -- Phone Number
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID) -- Link to Users
);

-- Creating the Payment table

CREATE TABLE Payment (
    Payment_ID INT PRIMARY KEY AUTO_INCREMENT,   -- Primary Key for Payments
    CreditCard_Number VARCHAR(255) NOT NULL,      -- Credit Card Number
    CVC CHAR(3) NOT NULL,                        -- Card CVC
    Expiration_Date DATE NOT NULL,               -- Expiration Date of Card
    User_ID INT NOT NULL,                        -- Foreign Key to Users
    City VARCHAR(50),                            -- Temporary City
    House_Address VARCHAR(255),                 -- Temporary Address
    Zipcode VARCHAR(10),                         -- Temporary Zipcode
    Country VARCHAR(50),                         -- Temporary Country
    Phone_number VARCHAR(15),                   -- Temporary Phone Number
    Payment_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Card_holder_Name VARCHAR(100),              -- Card Holder Name
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

-- Creating the Orders table
CREATE TABLE Orders (
    Order_ID INT PRIMARY KEY AUTO_INCREMENT,    -- Primary Key for Orders
    Payment_ID INT DEFAULT NULL,                -- Foreign Key to Payments
    User_ID INT NOT NULL,         
    Status ENUM('Pending','Completed') NOT NULL DEFAULT 'Pending', -- Order Status
    FOREIGN KEY (Payment_ID) REFERENCES Payment(Payment_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

-- Creating the Borrow_History table
CREATE TABLE Borrow_History (
    Borrow_ID INT PRIMARY KEY AUTO_INCREMENT,   -- Primary Key for Borrow History
    Payment_ID INT,
    User_ID INT ,                            -- Foreign Key to Payments
    FOREIGN KEY (Payment_ID) REFERENCES Payment(Payment_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

-- Creating the Order_Contain table (junction table for Orders and Movies)
CREATE TABLE Order_Contain (
    Order_ID INT,                               -- Foreign Key to Orders
    Movie_ID INT,                               -- Foreign Key to Movies
    PRIMARY KEY (Order_ID, Movie_ID),           -- Composite Primary Key
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID),
    FOREIGN KEY (Movie_ID) REFERENCES Movie(Movie_ID)
);

-- Creating the Place_Order table (junction table for Orders and Users)
CREATE TABLE Place_Order (
    Order_ID INT,                               -- Foreign Key to Orders
    User_ID INT,                                -- Foreign Key to Users
    PRIMARY KEY (Order_ID, User_ID),            -- Composite Primary Key
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

-- Creating the Wishlist table
CREATE TABLE Wishlist (
    Wishlist_ID INT PRIMARY KEY AUTO_INCREMENT, -- Primary Key for Wishlist
    User_ID INT NOT NULL,                       -- Foreign Key to Users
    Movie_ID INT NOT NULL,                      -- Foreign Key to Movies
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Movie_ID) REFERENCES Movie(Movie_ID)
);

-- Creating the User_Access_BorrowHistory table
CREATE TABLE User_Access_BorrowHistory (
    Borrow_ID INT NOT NULL,                     -- Foreign Key to Borrow_History
    User_ID INT NOT NULL,                       -- Foreign Key to Users
    PRIMARY KEY (Borrow_ID, User_ID),           -- Composite Primary Key
    FOREIGN KEY (Borrow_ID) REFERENCES Borrow_History(Borrow_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);


-- 'Pending', 'Completed', 'Cancelled'
-- ALTER TABLE Users ADD COLUMN Role ENUM('user', 'admin') DEFAULT 'user';
