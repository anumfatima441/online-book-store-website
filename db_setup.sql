-- Create database
CREATE DATABASE IF NOT EXISTS online_book_store;

-- Use the database
USE online_book_store;

-- Remove old tables if they exist so the import can recreate correct schema
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('user', 'admin') DEFAULT 'user'
);

-- Create books table
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    rating DECIMAL(2,1) DEFAULT 0.0
);

-- Create cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1
);

-- Create purchases table
CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    purchased_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create favorites/wishlist table
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorite (user_id, book_id)
);

-- Insert sample admin user
INSERT INTO users (name, email, password, user_type) VALUES ('Admin', 'admin@example.com', MD5('admin123'), 'admin');

-- Insert sample books
INSERT INTO books (title, author, price, image, description, rating) VALUES 
('The Great Gatsby', 'F. Scott Fitzgerald', 500.00, 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg', 'A classic novel about the American Dream.', 4.5),
('To Kill a Mockingbird', 'Harper Lee', 600.00, 'https://covers.openlibrary.org/b/isbn/9780061120084-L.jpg', 'A powerful story about racism and justice.', 4.8),
('Introduction to Algorithms', 'Cormen, Leiserson, Rivest, Stein', 1200.00, 'https://covers.openlibrary.org/b/isbn/9780262033848-L.jpg', 'Comprehensive guide to algorithms.', 4.7),
('Clean Code', 'Robert C. Martin', 800.00, 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg', 'A handbook of agile software craftsmanship.', 4.6),
('The Pragmatic Programmer', 'Andrew Hunt, David Thomas', 900.00, 'https://covers.openlibrary.org/b/isbn/9780201616224-L.jpg', 'Your journey to mastery.', 4.9),
('Computer Networks', 'Andrew S. Tanenbaum', 1100.00, 'https://covers.openlibrary.org/b/isbn/9780132126953-L.jpg', 'A top-down approach.', 4.4),
('Operating System Concepts', 'Abraham Silberschatz', 1000.00, 'https://covers.openlibrary.org/b/isbn/9781118063330-L.jpg', 'Essential concepts of operating systems.', 4.3),
('Database System Concepts', 'Abraham Silberschatz', 950.00, 'https://covers.openlibrary.org/b/isbn/9780073523323-L.jpg', 'Fundamentals of database systems.', 4.5),
('Artificial Intelligence: A Modern Approach', 'Stuart Russell, Peter Norvig', 1300.00, 'https://covers.openlibrary.org/b/isbn/9780136042594-L.jpg', 'Comprehensive introduction to AI.', 4.8),
('Python Crash Course', 'Eric Matthes', 700.00, 'https://covers.openlibrary.org/b/isbn/9781593276034-L.jpg', 'A hands-on, project-based introduction to programming.', 4.7),
('Head First Design Patterns', 'Eric Freeman, Elisabeth Robson', 850.00, 'https://covers.openlibrary.org/b/isbn/9780596007126-L.jpg', 'A brain-friendly guide to design patterns.', 4.6),
('Code Complete', 'Steve McConnell', 950.00, 'https://covers.openlibrary.org/b/isbn/9780735619678-L.jpg', 'A practical handbook of software construction.', 4.7),
('A Brief History of Time', 'Stephen Hawking', 850.00, 'https://covers.openlibrary.org/b/isbn/9780553380163-L.jpg', 'A landmark volume in science writing about the universe and time.', 4.6),
('The Selfish Gene', 'Richard Dawkins', 780.00, 'https://covers.openlibrary.org/b/isbn/9780198788607-L.jpg', 'An influential book on evolution and genetics.', 4.5),
('Aag ka Darya', 'Qurratulain Hyder', 650.00, 'https://via.placeholder.com/300x450?text=Aag+Ka+Darya', 'A sweeping Urdu novel tracing the history of South Asia from ancient times to partition.', 4.6),
('Raja Gidh', 'Bano Qudsia', 620.00, 'https://via.placeholder.com/300x450?text=Raja+Gidh', 'A philosophical Urdu novel exploring morality, madness, and the cost of obsession.', 4.7),
('Umrao Jaan Ada', 'Mirza Hadi Ruswa', 700.00, 'https://via.placeholder.com/300x450?text=Umrao+Jaan+Ada', 'The life and poetry of a celebrated nineteenth-century Lucknow courtesan.', 4.8),
('Aangan', 'Khadija Mastoor', 680.00, 'https://via.placeholder.com/300x450?text=Aangan', 'A moving Urdu novel set during the partition of India, centered around family and resistance.', 4.6),
('Peer-e-Kamil', 'Umera Ahmed', 720.00, 'https://via.placeholder.com/300x450?text=Peer-e-Kamil', 'A modern Urdu spiritual novel about faith, love, and self-discovery.', 4.7),
('The Sealed Nectar', 'Safi-ur-Rahman Mubarakpuri', 550.00, 'https://via.placeholder.com/300x450?text=The+Sealed+Nectar', 'A detailed biography of Prophet Muhammad (PBUH), based on authentic sources.', 4.9),
('Riyad-us-Saliheen', 'Imam Nawawi', 500.00, 'https://via.placeholder.com/300x450?text=Riyad-us-Saliheen', 'A classic Islamic collection of hadith covering ethics, manners, and worship.', 4.8),
('Tafsir Ibn Kathir', 'Ibn Kathir', 600.00, 'https://via.placeholder.com/300x450?text=Tafsir+Ibn+Kathir', 'A respected Qur''anic exegesis explaining the key verses and prophetic teachings.', 4.9),
('Purification of the Heart', 'Hamza Yusuf', 480.00, 'https://via.placeholder.com/300x450?text=Purification+of+the+Heart', 'A guide to inner spiritual growth based on classical Islamic teachings.', 4.6),
('In the Footsteps of the Prophet', 'Tariq Ramadan', 530.00, 'https://via.placeholder.com/300x450?text=In+the+Footsteps+of+the+Prophet', 'A contemporary account of the life and character of Prophet Muhammad (PBUH).', 4.7),

-- History Books
('Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 950.00, 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg', 'From a renowned historian comes a groundbreaking narrative of humanity''s creation and evolution—a #1 international bestseller—that explores the ways in which biology and history have defined us and enhanced our understanding of what it means to be "human." One hundred thousand years ago, at least six different species of humans inhabited Earth. Yet today there is only one—homo sapiens. What happened to the others? And what may happen to us? Most books about the history of humanity pursue either a historical or a biological approach, but Dr. Yuval Noah Harari breaks the mold with this highly original book that begins about 70,000 years ago with the appearance of modern cognition. From examining the role evolving humans have played in the global ecosystem to charting the rise of empires, Sapiens integrates history and science to reconsider accepted narratives, connect past developments with contemporary concerns, and examine specific events within the context of larger ideas. Dr. Harari also compels us to look ahead, because over the last few decades humans have begun to bend laws of natural selection that have governed life for the past four billion years. We are acquiring the ability to design not only the world around us, but also ourselves. Where is this leading us, and what do we want to become?', 4.8),

('Guns, Germs, and Steel: The Fates of Human Societies', 'Jared Diamond', 1100.00, 'https://covers.openlibrary.org/b/isbn/9780393317558-L.jpg', 'Winner of the Pulitzer Prize and a national bestseller: the global account of the rise of civilization that is also a stunning refutation of ideas of human development based on race. In this "artful, informative, and delightful" (William H. McNeill, New York Review of Books) book, Jared Diamond convincingly argues that geographical and environmental factors shaped the modern world. Societies that had a head start in food production advanced beyond the hunter-gatherer stage, and those with the right environment then spread to take over or outcompete other societies. Diamond reveals how climate and topography played fundamental roles in human history, explaining why Eurasians conquered or displaced Native Americans, Australians, and Africans, but not the reverse. "It is not just the story of guns and germs," writes Diamond. "It is also the story of wheat and rice, horses and oxen, and the wealth they produced." With a new chapter that brings the book up to date, Diamond''s sweeping narrative explains why some societies succeeded and others didn''t—and why the world is the way it is today.', 4.7),

('The Rise and Fall of the Third Reich', 'William L. Shirer', 1400.00, 'https://covers.openlibrary.org/b/isbn/9781451651683-L.jpg', 'The definitive account of Nazi Germany and World War II, from the rise of Hitler to the fall of the Third Reich. This is the most comprehensive and authoritative account of the Third Reich ever written. It is a masterful narrative of Adolf Hitler''s Germany, of the men and women who made it possible, and of the war they led to its doom. William L. Shirer, who lived through the war as a foreign correspondent, brings to life the extraordinary personalities and events that shaped the Nazi era. From Hitler''s early days as a failed artist to his final hours in the bunker beneath Berlin, Shirer captures the madness and the horror of the Nazi regime. He shows how Hitler''s charisma and propaganda machine seduced a nation, how the Nazi war machine conquered Europe, and how the Allied forces ultimately defeated the Third Reich. This is history at its most gripping and profound.', 4.6),

('The Wright Brothers', 'David McCullough', 850.00, 'https://covers.openlibrary.org/b/isbn/9781476728742-L.jpg', 'The dramatic story-behind-the-story about the courageous brothers who taught the world how to fly—Wilbur and Orville Wright. On December 17, 1903, at Kitty Hawk, North Carolina, brothers Wilbur and Orville Wright achieved what no one had ever done before: they flew, and in doing so, changed the world forever. In this thrilling book, master historian David McCullough draws on the immense riches of the Wright Papers, including private diaries, notebooks, scrapbooks, and more than a thousand letters from private family correspondence to tell the human side of the Wright Brothers'' story. He speaks of the brothers'' deep fraternal bond and great love for each other, their genius, their courage, their persistence against enormous odds, and their fearlessness in the face of the unknown. The result is at once an intimate portrait of a family and a driving tale of unprecedented achievement. David McCullough''s The Wright Brothers is one of his most moving and memorable books, a powerful reminder of what the human spirit can accomplish when passion and ingenuity come together.', 4.5),

-- Science Books
('Cosmos', 'Carl Sagan', 900.00, 'https://covers.openlibrary.org/b/isbn/9780345331359-L.jpg', 'Cosmos is a popular science book by astronomer and science communicator Carl Sagan. Its 13 illustrated chapters, corresponding to the 13 episodes of the Cosmos TV series, explore the wonders of the universe from the Big Bang to the evolution of life on Earth. Sagan discusses scientific subjects such as stellar evolution, the origin of life, the search for extraterrestrial intelligence, and the possibility of human extinction. He also explores the relationship between science and religion, and emphasizes the importance of science education. The book is known for its poetic language and Sagan''s ability to make complex scientific concepts accessible to the general public. It has been praised for inspiring generations of scientists and science enthusiasts, and remains a classic in popular science literature.', 4.9),

('The Gene: An Intimate History', 'Siddhartha Mukherjee', 1200.00, 'https://covers.openlibrary.org/b/isbn/9781476733500-L.jpg', 'The story of the gene begins in an obscure Augustinian abbey in Moravia in 1856 where a monk named Gregor Mendel, in his quiet pursuit of a lifetime, discovers the fundamental laws of inheritance. These laws would be the precepts of our destiny: we are who we are because of what we inherit. At each moment, a tide of stories flows from the long, unbroken chain of our ancestors, and Siddhartha Mukherjee tells the epic tale of how the gene came to be discovered, how it came to be understood, and how it came to be manipulated. From the early days of agriculture to the latest advances in gene therapy, from the brilliant mind of Charles Darwin to the shrewd observations of Francis Crick and James Watson, Mukherjee takes us through the revolutions in science that have fundamentally changed our understanding of ourselves and our world. We learn about the heroes and the tragedies, the triumphs and the disasters, and we come to understand the gene as the most powerful and most feared idea in science.', 4.7),

('The Body: A Guide for Occupants', 'Bill Bryson', 950.00, 'https://covers.openlibrary.org/b/isbn/9780385539302-L.jpg', 'In the bestselling, prize-winning A Short History of Nearly Everything, Bill Bryson achieved the seemingly impossible by making the science of our world both understandable and entertaining to millions of people around the globe. Now, in this new book, he turns his attention to the body. How does it work? How do we acquire it? Why do we die? What happens when we age? How do we stay healthy? How do we get ill? Bryson takes us on a head-to-toe (mostly head) tour of the marvel that is the human body. As addictive as it is comprehensive, this is Bryson at his very best, a must-read owner''s manual for everybody. With his characteristic wit and engaging style, he explores the science of the human body, from the remarkable capabilities of our cells to the wonders of our senses, and reveals how our bodies work, why they work that way, and what happens when they don''t. The result is a book that is both informative and entertaining, a celebration of the human body that will leave you in awe of the miracle that is you.', 4.6),

('The Immortal Life of Henrietta Lacks', 'Rebecca Skloot', 800.00, 'https://covers.openlibrary.org/b/isbn/9781400052189-L.jpg', 'Her name was Henrietta Lacks, but scientists know her as HeLa. She was a poor black tobacco farmer whose cells—taken without her knowledge in 1951—became one of the most important tools in medicine, vital for developing the polio vaccine, cloning, gene mapping, and more. Henrietta''s cells have been bought and sold by the billions, yet she remains virtually unknown, and her family can''t afford health insurance. This phenomenal New York Times bestseller tells a riveting story of the collision between ethics, race, and medicine; of scientific discovery and faith healing; and of a daughter consumed with questions her mother would have asked. "Mrs. Lacks''s story has not only been vital to modern medicine, but has also helped to spark a worldwide movement to ensure that human cells are obtained and used ethically," says Skloot. The Immortal Life of Henrietta Lacks is a powerful and important book that will leave you thinking about science, ethics, and the value of human life.', 4.8),

('The Emperor of All Maladies: A Biography of Cancer', 'Siddhartha Mukherjee', 1100.00, 'https://covers.openlibrary.org/b/isbn/9781439170915-L.jpg', 'Winner of the Pulitzer Prize and a #1 New York Times bestseller, The Emperor of All Maladies is a magnificent, profoundly humane "biography" of cancer—from its first documented appearances thousands of years ago through the epic battles in the twentieth century to cure, control, and conquer it to a radical new understanding of its essence. Physician, researcher, and award-winning science writer, Siddhartha Mukherjee examines cancer with a cellular biologist''s precision, a historian''s perspective, and a biographer''s passion. The result is an astonishingly lucid and eloquent chronicle of a disease humans have lived with—and perished from—for more than five thousand years. The story of cancer is a story of human ingenuity, resilience, and perseverance, but also of hubris, paternalism, and misperception. Mukherjee recounts centuries of discoveries, setbacks, victories, and deaths, told through the eyes of his predecessors and peers, training their wits against an infinitely resourceful adversary that, just three decades ago, was thought to be easily vanquished in an all-out "war against cancer." The book reads like a literary thriller, full of awe-inspiring drama and surprise twists, peopled with a cast of characters as varied and compelling as any in literature. It is a story of science and scientists, of struggle and survival, of hubris and redemption, and of the courage and curiosity of the human spirit.', 4.7),

('Thinking, Fast and Slow', 'Daniel Kahneman', 1000.00, 'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg', 'In the international bestseller, Thinking, Fast and Slow, Daniel Kahneman, the renowned psychologist and winner of the Nobel Prize in Economics, takes us on a groundbreaking tour of the mind and explains the two systems that drive the way we think. System 1 is fast, intuitive, and emotional; System 2 is slower, more deliberative, and more logical. The impact of overconfidence on corporate strategies, the difficulties of predicting what will make us happy in the future, the profound effect of cognitive biases on everything from playing the stock market to planning our next vacation—each of these can be understood only by knowing how the two systems shape our judgments and decisions. Engaging the reader in a lively conversation about how we think, Kahneman reveals where we can and cannot trust our intuitions and how we can tap into the benefits of slow thinking. He offers practical and enlightening insights into how choices are made in both our business and our personal lives—and how we can use different techniques to guard against the mental glitches that often get us into trouble. Winner of the National Academies Communication Award, this book will change the way you think about thinking.', 4.6),

('The Sixth Extinction: An Unnatural History', 'Elizabeth Kolbert', 850.00, 'https://covers.openlibrary.org/b/isbn/9780805092998-L.jpg', 'Over the last half a billion years, there have been five mass extinctions, when the diversity of life on earth suddenly and dramatically contracted. Scientists around the world are currently monitoring the sixth extinction, predicted to be the most devastating extinction event since the asteroid impact that wiped out the dinosaurs. This time around, the cataclysm is us. In The Sixth Extinction, two-time winner of the National Magazine Award and New York Times bestselling author Elizabeth Kolbert draws on the work of scores of researchers in half a dozen disciplines, accompanying many of them into the field: geologists who study deep ocean cores, botanists who follow the distribution of exotic plants, marine biologists who dive off the Great Barrier Reef. She introduces us to a major figure in contemporary biology, E. O. Wilson, and to places far flung: the coral reefs of Hawaii, the mountains of Peru, the deserts of Australia. Her searching reports show us that it is too late to stop climate change or deforestation; that we can no longer live without plastic; that we must reckon with our role in the world''s calamitous future. But she also shows us that by understanding the roots of the current environmental crisis, we can make choices that can help build a more hopeful future. The Sixth Extinction is a sobering account of the damage we''ve done and a clarion call to address the environmental crisis before it''s too late.', 4.5);

-- Add missing columns to users table for admin profile
ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER password;
ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT AFTER phone;
ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER user_type;
ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user' AFTER user_type;