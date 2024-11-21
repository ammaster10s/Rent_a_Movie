use MovieRentalSystem;

DELETE * FROM Movie;

-- Inserting Movies ( Short Description )
INSERT INTO Movie (Movie_Name, Price, Main_Actor, Released_date, Description, Length)
VALUES
('Gladiator 2', 15.99, 'Russell Crowe', '2024-11-21', 'Sequel to the epic historical drama', 180),
('How to Train Your Dragon', 12.99, 'Jay Baruchel', '2010-03-26', 'A young Viking befriends a dragon', 98),
('Minecraft Movie', 10.99, 'Jason Momoa', '2025-04-04', 'A movie based on the Minecraft game', 120),
('Venom 3', 14.99, 'Tom Hardy', '2024-10-01', 'Continuation of the Venom saga', 140),
('Sonic 3', 13.99, 'Ben Schwartz', '2024-12-20', 'Sonic teams up for a new adventure', 110),
('Red One', 11.99, 'Dwayne Johnson', '2024-11-15', 'A Christmas-themed action movie', 125),

('Joker 1', 14.99, 'Joaquin Phoenix', '2019-10-04', 'A psychological exploration of Joker', 122),
('Hungergame 1', 13.99, 'Jennifer Lawrence', '2012-03-23', 'A dystopian battle for survival', 142),
('Civilwar', 15.99, 'Chris Evans', '2016-05-06', 'Captain America faces Iron Man', 147),
('Birdbox', 12.99, 'Sandra Bullock', '2018-12-14', 'Surviving in a world of unseen horrors', 124),
('Escape Room: Tournament of Champions', 11.99, 'Taylor Russell', '2021-07-16', 'An intense sequel to Escape Room', 88),
('Jurassic Park', 14.99, 'Sam Neill', '1993-06-11', 'Dinosaurs come alive in this classic', 127),

('Pacific Rims', 13.99, 'Charlie Hunnam', '2013-07-12', 'Humans pilot giant robots to battle monsters', 131),
('Interstellar', 15.99, 'Matthew McConaughey', '2014-11-07', 'A journey through space and time', 169),
('Edge of Tomorrow', 14.99, 'Tom Cruise', '2014-06-06', 'A time-loop sci-fi action movie', 113),
('Tenet', 16.99, 'John David Washington', '2020-08-26', 'A mind-bending action thriller', 150),
('Dune', 16.99, 'Timothée Chalamet', '2021-10-22', 'Epic sci-fi tale on desert planet Arrakis', 155),
('Ready Player One', 14.99, 'Tye Sheridan', '2018-03-29', 'A VR journey through the OASIS', 140);


-- Inserting Movies ( Long Description )
INSERT INTO Movie (Movie_Name, Price, Main_Actor, Released_date, Description, Length , Poster_Path , Category)
VALUES
('Gladiator 2', 15.99, 'Russell Crowe', '2024-11-21', 
 'The gripping sequel to the original classic "Gladiator," diving into themes of legacy and vengeance as new warriors rise in the Colosseum.', 180, "img\\Trending-1.jpg" ,"Trending"),
('How to Train Your Dragon', 12.99, 'Jay Baruchel', '2010-03-26', 
 'A heartwarming tale of a young Viking named Hiccup, who defies his tribe’s expectations by befriending a dragon named Toothless.', 98,"img\\Trending-2.jpg" ,"Trending"),
('Minecraft Movie', 10.99, 'Jason Momoa', '2025-04-04', 
 'An action-packed adventure where players are transported into the Minecraft universe, fighting mobs and building to survive.', 120, "img\\Trending-3.jpg", "Trending"),
('Venom 3', 14.99, 'Tom Hardy', '2024-10-01', 
 'Eddie Brock and Venom face a powerful new enemy while struggling to coexist, in this darkly comedic and action-filled sequel.', 140, "img\\Trending-4.jpg", "Trending"),
('Sonic 3', 13.99, 'Ben Schwartz', '2024-12-20', 
 'The speedy hedgehog teams up with Tails and Knuckles to thwart a new evil plot, featuring dazzling visuals and thrilling races.', 110 ,"img\\Trending-5.jpg", "Trending"),
('Red One', 11.99, 'Dwayne Johnson', '2024-11-15', 
 'A festive action-comedy where a heroic duo embarks on a high-stakes mission to save Christmas from an unexpected villain.', 125, "img\\Trending-6.jpg", "Trending"),
('Joker 1', 14.99, 'Joaquin Phoenix', '2019-10-04', 
 'A haunting origin story that explores the psychological descent of Arthur Fleck into the infamous villain Joker.', 122, "img\\Thriller-1.jpg","Thriller"),
('Hungergame 1', 13.99, 'Jennifer Lawrence', '2012-03-23', 
 'In a dystopian world, Katniss Everdeen becomes a symbol of rebellion while fighting for survival in the brutal Hunger Games.', 142, "img\\Thriller-2.jpg", "Thriller"),
('Civilwar', 15.99, 'Chris Evans', '2016-05-06', 
 'Captain America and Iron Man clash over ideological differences, fracturing the Avengers and leading to an epic showdown.', 147, "img\\Thriller-3.jpg", "Thriller"),
('Birdbox', 12.99, 'Sandra Bullock', '2018-12-14', 
 'A post-apocalyptic thriller where survivors must navigate a world overrun by mysterious creatures that kill anyone who looks at them.', 124, "img\\Thriller-4.jpg", "Thriller"),
('Escape Room: Tournament of Champions', 11.99, 'Taylor Russell', '2021-07-16', 
 'A group of survivors must work together to solve deadly puzzles in this high-stakes psychological thriller.', 88 ,"img\\Thriller-5.jpg", "Thriller"),
('Jurassic Park', 14.99, 'Sam Neill', '1993-06-11', 
 'A groundbreaking adventure where dinosaurs are brought back to life, leading to chaos in a theme park gone wrong.', 127, "img\\Thriller-6.jpg", "Thriller"),
('Pacific Rims', 13.99, 'Charlie Hunnam', '2013-07-12', 
 'Humans pilot massive robots called Jaegers to battle colossal sea monsters threatening the survival of humanity.', 131 ,"img\\SciFi_Fantasy-1.jpg", "SciFi_Fantasy"),
('Interstellar', 15.99, 'Matthew McConaughey', '2014-11-07', 
 'A visually stunning journey through space as a team of astronauts searches for a new home for humanity among the stars.', 169, "img\\SciFi_Fantasy-2.jpg", "SciFi_Fantasy"),
('Edge of Tomorrow', 14.99, 'Tom Cruise', '2014-06-06', 
 'A soldier gains the ability to reset time and uses it to battle an alien invasion in this thrilling sci-fi action movie.', 113 ,"img\\SciFi_Fantasy-3.jpg", "SciFi_Fantasy"),
('Tenet', 16.99, 'John David Washington', '2020-08-26', 
 'A mind-bending spy thriller involving time inversion, where the protagonist must prevent global annihilation.', 150 ,"img\\SciFi_Fantasy-4.jpg", "SciFi_Fantasy"),
('Dune', 16.99, 'Timothée Chalamet', '2021-10-22', 
 'An epic tale of politics, betrayal, and destiny as Paul Atreides rises to power on the desert planet Arrakis.', 155 , "img\\SciFi_Fantasy-5.jpg", "SciFi_Fantasy"),
('Ready Player One', 14.99, 'Tye Sheridan', '2018-03-29', 
 'In a futuristic world, players dive into the OASIS, a virtual reality universe, to compete for ultimate control and freedom.', 140 ,"img\\SciFi_Fantasy-6.jpg", "SciFi_Fantasy");



SELECT * FROM Movie;

SELECT Movie_ID, Movie_Name, Poster_Path FROM Movie WHERE Category = 'Trending';