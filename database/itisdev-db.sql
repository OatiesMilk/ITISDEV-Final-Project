-- Create the database if it doesn’t exist
CREATE DATABASE IF NOT EXISTS itisdev;

-- Select the database to use
USE itisdev;

-- Table structure for table `accounts`
CREATE TABLE `accounts` (
  `account_id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `mobile_num` varchar(11) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `accounts` table
INSERT INTO `accounts` (`account_id`, `username`, `password`, `firstname`, `lastname`, `email`, `mobile_num`) VALUES
(1, 'admin', '1234', 'John', 'Doe', 'JohnDoe@gmail.com', '09999912345'),
(2, 'test', 'test', 'Tyler', 'Durden', 'TDurden@gmail.com', '09999912345');
