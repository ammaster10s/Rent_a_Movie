use Movierentalsystem;


INSERT INTO User_address (House_Address, City, Country, Zipcode, User_ID)
VALUES
    ('123 Main St', 'Los Angeles', 'CA', '90001', 1),
    ('456 Elm St', 'New York', 'NY', '10001', 1),
    ('789 Oak St', 'Chicago', 'IL', '60007', 1);
INSERT INTO Payment (CreditCard_Number, CVC, Expiration_Date, User_ID, Payment_Date, Address_ID)
VALUES 
    ('1234567812345678', '123', '2025-01-01', 1, '2023-10-01',7),
    ('2345678923456789', '234', '2025-01-01', 1, '2023-10-02',8),
    ('3456789034567890', '345', '2025-01-01', 1, '2023-10-03',9);


INSERT INTO Orders (Order_ID, Payment_ID, Status)
VALUES
    (1, 7, 'Paid'),
    (2, 8, 'Paid'),
    (3, 9, 'Paid');


INSERT INTO Order_Contain (Order_ID, Movie_ID)
VALUES
    (1, 1), -- Movie 1 in Order 1
    (1, 2), -- Movie 2 in Order 1
    (2, 3), -- Movie 3 in Order 2
    (3, 1), -- Movie 1 in Order 3
    (3, 3); -- Movie 3 in Order 3


INSERT INTO Place_Order (Order_ID, User_ID)
VALUES
    (1, 1), -- Order 1 belongs to User 101
    (2, 1), -- Order 2 belongs to User 102
    (3, 1); -- Order 3 belongs to User 103


SELECT 
    o.Order_ID,
    m.Movie_Name,
    m.Price
FROM Orders o
INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
WHERE po.User_ID = 1
ORDER BY m.Movie_Name ASC;