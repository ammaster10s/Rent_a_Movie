-- Creating the database

DROP DATABASE IF EXISTS MovieRentalSystem;
CREATE DATABASE IF NOT EXISTS MovieRentalSystem;
USE MovieRentalSystem;

-- Creating the User table
CREATE TABLE Users (
    User_ID INT PRIMARY KEY AUTO_INCREMENT,     -- PK for User
    Username VARCHAR(50) UNIQUE NOT NULL,   
    F_Name VARCHAR(50) NOT NULL,
    L_Name VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Email_Address VARCHAR(100) UNIQUE NOT NULL
   
);

-- Creating the Movie table

CREATE TABLE Movie (
    Movie_ID INT PRIMARY KEY AUTO_INCREMENT,    -- PK for Movie
    Price DECIMAL(10, 2) NOT NULL,
    Main_Actor VARCHAR(100),
    Released_date DATE,
    Description TEXT,
    Length INT, -- Length in minutes
    Movie_Name VARCHAR(255) NOT NULL,
    Poster_Path VARCHAR(40) -- For saving the path of the poster ( MYSQL cannot store Poster (BLOB not going to be good ))
    , Category VARCHAR(20) 
);

DROP TABLE IF EXISTS User_Address;
CREATE TABLE User_Address(
    ADDRESS_ID INT PRIMARY KEY AUTO_INCREMENT,
    User_ID INT,
    City VARCHAR(50),
    House_Address VARCHAR(255),
    Zipcode VARCHAR(10),
    Country VARCHAR(50),
    Phone_number INT(10),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

-- Creating the Payment table first to resolve FK dependencies
CREATE TABLE Payment (
    Payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    CreditCard_Number VARCHAR(20) NOT NULL,
    CVC CHAR(3) NOT NULL,
    Expiration_Date DATE NOT NULL,
    User_ID INT NOT NULL,
    Address_ID INT,
    Card_Holder_FName VARCHAR(50),
    Card_Holder_LName VARCHAR(50),
    Payment_Date DATE NOT NULL,  
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Address_ID) REFERENCES User_Address(ADDRESS_ID)
);


-- Creating the Order tablea

-- Creating the Place_Order table (junction table for Order and User)
CREATE TABLE Place_Order (
    Order_ID INT NOT NULL,
    User_ID INT NOT NULL,
    PRIMARY KEY (Order_ID, User_ID),
    -- Creating the Orders table
    CREATE TABLE Orders (
        Order_ID INT PRIMARY KEY AUTO_INCREMENT,
        Order_Date DATE NOT NULL,
        Total_Amount DECIMAL(10, 2) NOT NULL
    );

    -- Trigger to update payment_time when a payment is made
    DELIMITER //
    CREATE TRIGGER update_payment_time
    AFTER INSERT ON Payment
    FOR EACH ROW
    BEGIN
        UPDATE Payment
        SET Payment_Date = NOW()
        WHERE Payment_ID = NEW.Payment_ID;
    END;
    //
    DELIMITER ;
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

CREATE TABLE Borrow_History (
    Borrow_ID INT PRIMARY KEY AUTO_INCREMENT,
    Payment_ID INT,
    FOREIGN KEY (Payment_ID) REFERENCES Payment(Payment_ID)
);


-- Creating the Order_Contain table (junction table for Order and Movie)
CREATE TABLE Order_Contain (
    Order_ID INT,
    Movie_ID INT,
    PRIMARY KEY (Order_ID, Movie_ID),
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID),
    FOREIGN KEY (Movie_ID) REFERENCES Movie(Movie_ID)
);



-- Creating the Wishlist table
CREATE TABLE Wishlist (
    WishlistID INT PRIMARY KEY AUTO_INCREMENT,
    User_ID INT NOT NULL,
    Movie_ID INT NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Movie_ID) REFERENCES Movie(Movie_ID)
);




-- Linking User with Borrow_History for access rights (many-to-many relationship)
CREATE TABLE User_Access_BorrowHistory (
    Borrow_ID INT NOT NULL,
    User_ID INT NOT NULL,
    PRIMARY KEY (Borrow_ID, User_ID),
    FOREIGN KEY (Borrow_ID) REFERENCES Borrow_History(Borrow_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);