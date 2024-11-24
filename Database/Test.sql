use Movierentalsystem;


INSERT INTO User_address (House_Address, City, Country, Zipcode, User_ID)
VALUES
    ('123 Main St', 'Los Angeles', 'CA', '90001', 1),
    ('456 Elm St', 'New York', 'NY', '10001', 1),
    ('789 Oak St', 'Chicago', 'IL', '60007', 1);
INSERT INTO Payment (CreditCard_Number, CVC, Expiration_Date, User_ID, Payment_Date, Address_ID)
VALUES 
    ('1234567812345678', '123', '2025-01-01', 1, '2023-10-01',1),
    ('2345678923456789', '234', '2025-01-01', 1, '2023-10-02',2),
    ('3456789034567890', '345', '2025-01-01', 1, '2023-10-03',3);


INSERT INTO Orders (User_ID ,Order_ID, Payment_ID, Status)
VALUES
    (1, 1, 10, 'Completed'),
    (1, 2, 11, 'Completed');
    -- (1, 3, 12, 'Completed');


INSERT INTO Order_Contain (Order_ID, Movie_ID)
VALUES
    (1, 1), -- Movie 1 in Order 1
    (1, 2), -- Movie 2 in Order 1
    (2, 3), -- Movie 3 in Order 2
    (3, 1); -- Movie 1 in Order 3
    -- (3, 3); -- Movie 3 in Order 3


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



SELECT * FROM Order_Contain;


SELECT 
    o.Order_ID,
    m.Movie_Name,
    m.Price,
    p.Payment_Date AS Issue_Date,
    DATE_ADD(p.Payment_Date, INTERVAL 7 DAY) AS Due_Date,
    DATEDIFF(DATE_ADD(p.Payment_Date, INTERVAL 7 DAY), p.Payment_Date) AS Period
FROM Orders o
INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
INNER JOIN Payment p ON o.Payment_ID = p.Payment_ID
WHERE po.User_ID = 1
ORDER BY m.Movie_Name ASC;

SELECT 
    o.Order_ID,
    m.Movie_Name,
    m.Price,
    p.Payment_Date AS Issue_Date,
    DATE_ADD(p.Payment_Date, INTERVAL 7 DAY) AS Due_Date,
    DATEDIFF(DATE_ADD(p.Payment_Date, INTERVAL 7 DAY), p.Payment_Date) AS Period
FROM Orders o
INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
INNER JOIN Payment p ON o.Payment_ID = p.Payment_ID
WHERE po.User_ID = 1
ORDER BY o.Order_ID, m.Movie_Name ASC;


SELECT 
            o.Order_ID,
            m.Movie_Name,
            m.Price,
            p.Payment_Date AS Issue_Date,
            DATE_ADD(p.Payment_Date, INTERVAL 7 DAY) AS Due_Date,
            DATEDIFF(DATE_ADD(p.Payment_Date, INTERVAL 7 DAY), p.Payment_Date) AS Period
          FROM Orders o
          INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
          INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
          INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
          INNER JOIN Payment p ON o.Payment_ID = p.Payment_ID
          WHERE po.User_ID = 1
          ORDER BY m.Movie_Name ASC;