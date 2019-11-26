-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2019 at 04:12 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bankmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `account_no` int(11) NOT NULL,
  `balance` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`account_no`, `balance`) VALUES
(1, 0),
(2, 14000),
(3, 21000),
(4, 100000000),
(5, 80000),
(6, 600.5),
(7, 4000),
(8, 500),
(9, 9500);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `account_no` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `phone_no` varchar(12) DEFAULT NULL,
  `branch` varchar(10) DEFAULT NULL,
  `account_type` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `create_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`account_no`, `name`, `address`, `phone_no`, `branch`, `account_type`, `role`, `password`, `create_date`) VALUES
(1, 'Amdadul Hoque', 'sdfghjk', '01318322201', 'panthapath', 'SV', 'ADMIN', '*FDF3B274C2218F4A8FB14714B740D1C6FCE113F4', '2019-11-04'),
(2, 'national', 'bangladesh', '123456', 'dhaka', 'SV', '', '', '2019-11-04'),
(3, 'Amdadul Hoque', 'gjkhkjasd', '01740929512', 'panthapath', 'CR', 'USER', '*FDF3B274C2218F4A8FB14714B740D1C6FCE113F4', '2019-11-04'),
(4, 'Monirujjaman', 'Chapai nawabganj', '01774212988', 'Mohakhali', 'SV', 'ADMIN', '*00A51F3F48415C7D4E8908980D443C29C69B60C9', '2019-11-05'),
(5, 'Shourob', 'Sirajganj', '34567890987', 'dhanmondi', 'CR', '', '', '2019-11-05'),
(6, 'sajal ganguly', 'dhaka', '456789997', 'panthapath', 'CR', '', '', '2019-11-05'),
(7, 'Hasan', 'Sirajganj', '234567890', 'Sirajganj', 'SV', '', '', '2019-11-21'),
(8, 'Kawsar Mahmud', 'Sirajganj', '01728275066', 'Sirajganj', 'CR', '', '', '2019-11-21'),
(9, 'Sheak Ahmed', 'Cumilla', '01830960105', 'Kathalbaga', 'SV', '', '', '2019-11-22');

--
-- Triggers `client`
--
DELIMITER $$
CREATE TRIGGER `initial_balance` AFTER INSERT ON `client` FOR EACH ROW BEGIN 
   
   INSERT into balance(account_no,balance) VALUES (NEW.account_no,'0');
   
   END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `trans_id` int(11) NOT NULL,
  `account_no` int(11) DEFAULT NULL,
  `slip_no` varchar(20) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`trans_id`, `account_no`, `slip_no`, `amount`, `date`) VALUES
(1001, 4, 'SL1001', 400, '2019-11-06'),
(1002, 4, 'SL1002', 400, '2019-11-06'),
(1003, 4, 'SL1003', 100.59, '2019-11-06'),
(1004, 2, 'SL1004', 4500, '2019-11-06'),
(1005, 2, 'SL1005', 1000, '2019-11-13'),
(1006, 6, 'SL1006', 500, '2019-11-13'),
(1010, 6, 'SL123', 1000, '2019-11-20'),
(1013, 7, 'SL1234', 5500, '2019-11-21'),
(1016, 4, 'SL123456', 100000000, '2019-11-22'),
(1017, 2, 'SL1234567', 10000, '2019-11-22'),
(1018, 6, 'asdfghjkl', 100.5, '2019-11-22'),
(1019, 9, 'SL12345678', 10000, '2019-11-22'),
(1022, 3, 'SL010101', 20000, '2019-11-25');

--
-- Triggers `deposit`
--
DELIMITER $$
CREATE TRIGGER `balance_diposit` AFTER INSERT ON `deposit` FOR EACH ROW BEGIN 
   DECLARE B double;
   DECLARE FB double;
   
   SELECT balance INTO @B FROM balance WHERE account_no = NEW.account_no;
   
   SET @FB = @B + NEW.amount;
   
   UPDATE balance SET balance = @FB WHERE account_no = NEW.account_no;
   
   END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `balance_update` BEFORE UPDATE ON `deposit` FOR EACH ROW BEGIN
DECLARE U double;
DECLARE PB double;
DECLARE NB double;

IF (NEW.account_no = OLD.account_no) THEN

SELECT balance INTO @PB FROM balance WHERE account_no = OLD.account_no;
    SET @U = NEW.amount - OLD.amount;
    SET @NB = @PB + @U;
UPDATE balance SET balance = @NB WHERE account_no = OLD.account_no;

ELSE

SELECT balance INTO @PB FROM balance WHERE account_no = OLD.account_no;
    SET @NB = @PB - OLD.amount;
UPDATE balance SET balance = @NB WHERE account_no = OLD.account_no;

SELECT balance INTO @PB FROM balance WHERE account_no = NEW.account_no;
    SET @NB = @PB + NEW.amount;
UPDATE balance SET balance = @NB WHERE account_no = NEW.account_no;


END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trans_diposit` AFTER INSERT ON `deposit` FOR EACH ROW BEGIN 
   DECLARE type varchar(15);
   SET @type = 'deposit';
   
   INSERT INTO transection (trans_id,account_no,trans_type,amount,trans_date) VALUES (NEW.trans_id, NEW.account_no, @type, NEW.amount, NEW.date);
   
   END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trans_update` BEFORE UPDATE ON `deposit` FOR EACH ROW BEGIN
DECLARE U double;
DECLARE PA double;
DECLARE NA double;

IF (NEW.account_no = OLD.account_no) THEN
    SET @NA = NEW.amount;
UPDATE transection SET amount = @NA WHERE trans_id = OLD.trans_id;

ELSE
    SET @NA = NEW.amount;
    SET @U = NEW.account_no;
    UPDATE transection SET amount = @NA, account_no = @U WHERE trans_id = OLD.trans_id;


END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transection`
--

CREATE TABLE `transection` (
  `trans_id` int(11) NOT NULL,
  `account_no` int(11) DEFAULT NULL,
  `trans_type` varchar(15) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `trans_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transection`
--

INSERT INTO `transection` (`trans_id`, `account_no`, `trans_type`, `amount`, `trans_date`) VALUES
(1004, 2, 'deposit', 5000, '2019-11-06'),
(1005, 2, 'deposit', 1000, '2019-11-13'),
(1006, 6, 'deposit', 500, '2019-11-13'),
(1008, 4, 'withdraw', 50.589, '2019-11-14'),
(1009, 1, 'transfer', 700, '2019-11-14'),
(1010, 6, 'deposit', 1000, '2019-11-20'),
(1011, 2, 'withdraw', 1000, '2019-11-20'),
(1012, 6, 'transfer', 1000, '2019-11-21'),
(1013, 7, 'deposit', 5500, '2019-11-21'),
(1014, 7, 'withdraw', 1000, '2019-11-21'),
(1015, 7, 'transfer', 500, '2019-11-21'),
(1016, 4, 'deposit', 100000000, '2019-11-22'),
(1017, 2, 'deposit', 10000, '2019-11-22'),
(1018, 6, 'deposit', 100.5, '2019-11-22'),
(1019, 9, 'deposit', 10000, '2019-11-22'),
(1020, 9, 'withdraw', 500, '2019-11-22'),
(1021, 3, 'transfer', 700, '2019-11-25'),
(1022, 3, 'deposit', 20000, '2019-11-25'),
(1023, 4, 'withdraw', 900, '2019-11-26');

-- --------------------------------------------------------

--
-- Table structure for table `transfer`
--

CREATE TABLE `transfer` (
  `trans_id` int(11) NOT NULL,
  `account_from` int(11) DEFAULT NULL,
  `account_to` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transfer`
--

INSERT INTO `transfer` (`trans_id`, `account_from`, `account_to`, `amount`, `date`) VALUES
(1009, 1, 3, 700, '2019-11-14'),
(1012, 6, 3, 1000, '2019-11-21'),
(1015, 7, 8, 500, '2019-11-21'),
(1021, 3, 1, 700, '2019-11-25');

--
-- Triggers `transfer`
--
DELIMITER $$
CREATE TRIGGER `balance_transfer_from` AFTER INSERT ON `transfer` FOR EACH ROW BEGIN 
   DECLARE B double;
   DECLARE FB double;
   
   SELECT balance INTO @B FROM balance WHERE account_no = NEW.account_from;
   
   SET @FB = @B - NEW.amount;
   
   UPDATE balance SET balance = @FB WHERE account_no = NEW.account_from;
   
   END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `balance_transfer_to` AFTER INSERT ON `transfer` FOR EACH ROW BEGIN 
   DECLARE B double;
   DECLARE FB double;
   
   SELECT balance INTO @B FROM balance WHERE account_no = NEW.account_to;
   
   SET @FB = @B + NEW.amount;
   
   UPDATE balance SET balance = @FB WHERE account_no = NEW.account_to;
   
   END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `balance_update_fransfer` BEFORE UPDATE ON `transfer` FOR EACH ROW BEGIN

DECLARE UF double;
DECLARE PBF double;
DECLARE NBF double;

DECLARE UT double;
DECLARE PBT double;
DECLARE NBT double;

DECLARE UFN double;
DECLARE PBFN double;
DECLARE NBFN double;

DECLARE UTN double;
DECLARE PBTN double;
DECLARE NBTN double;

UPDATE transection SET account_no = NEW.account_from, amount = NEW.amount WHERE trans_id = OLD.trans_id;


IF OLD.account_from = NEW.account_from AND OLD.account_to = NEW.account_to THEN

SELECT balance INTO @PBF FROM balance WHERE account_no = OLD.account_from;
    SET @UF = OLD.amount - NEW.amount;
    SET @NBF = @PBF + @UF;
UPDATE balance SET balance = @NBF WHERE account_no = OLD.account_from;

SELECT balance INTO @PBT FROM balance WHERE account_no = OLD.account_to;
    SET @UT = NEW.amount - OLD.amount;
    SET @NBT = @PBT + @UT;
UPDATE balance SET balance = @NBT WHERE account_no = OLD.account_to;


ELSEIF OLD.account_from != NEW.account_from AND OLD.account_to != NEW.account_to THEN

SELECT balance INTO @PBF FROM balance WHERE account_no = OLD.account_from;
    SET @UF = OLD.amount;
    SET @NBF = @PBF + @UF;
UPDATE balance SET balance = @NBF WHERE account_no = OLD.account_from;

SELECT balance INTO @PBFN FROM balance WHERE account_no = NEW.account_from;
    SET @UFN = NEW.amount;
    SET @NBFN = @PBFN - @UFN;
UPDATE balance SET balance = @NBFN WHERE account_no = NEW.account_from;

SELECT balance INTO @PBT FROM balance WHERE account_no = OLD.account_to;
    SET @UT = OLD.amount;
    SET @NBT = @PBT - @UT;
UPDATE balance SET balance = @NBT WHERE account_no = OLD.account_to;

SELECT balance INTO @PBTN FROM balance WHERE account_no = NEW.account_to;
    SET @UTN = NEW.amount;
    SET @NBTN = @PBTN + @UTN;
UPDATE balance SET balance = @NBTN WHERE account_no = NEW.account_to;


ELSEIF OLD.account_from = NEW.account_from THEN


SELECT balance INTO @PBF FROM balance WHERE account_no = OLD.account_from;
    SET @UF = OLD.amount - NEW.amount;
    SET @NBF = @PBF + @UF;
UPDATE balance SET balance = @NBF WHERE account_no = OLD.account_from;

SELECT balance INTO @PBT FROM balance WHERE account_no = OLD.account_to;
    SET @UT = OLD.amount;
    SET @NBT = @PBT - @UT;
UPDATE balance SET balance = @NBT WHERE account_no = OLD.account_to;

SELECT balance INTO @PBTN FROM balance WHERE account_no = NEW.account_to;
    SET @UTN = NEW.amount;
    SET @NBTN = @PBTN + @UTN;
UPDATE balance SET balance = @NBTN WHERE account_no = NEW.account_to;


ELSEIF OLD.account_to = NEW.account_to THEN


SELECT balance INTO @PBF FROM balance WHERE account_no = OLD.account_from;
    SET @UF = OLD.amount;
    SET @NBF = @PBF + @UF;
UPDATE balance SET balance = @NBF WHERE account_no = OLD.account_from;

SELECT balance INTO @PBT FROM balance WHERE account_no = OLD.account_to;
    SET @UT = NEW.amount - OLD.amount;
    SET @NBT = @PBT + @UT;
UPDATE balance SET balance = @NBT WHERE account_no = OLD.account_to;


SELECT balance INTO @PBFN FROM balance WHERE account_no = NEW.account_from;
    SET @UFN = NEW.amount;
    SET @NBFN = @PBFN - @UFN;
UPDATE balance SET balance = @NBFN WHERE account_no = NEW.account_from;



END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trans_transfer` AFTER INSERT ON `transfer` FOR EACH ROW BEGIN 
   DECLARE type varchar(15);
   SET @type = 'transfer';
   
   INSERT INTO transection (trans_id,account_no,trans_type,amount,trans_date) VALUES (NEW.trans_id, NEW.account_from, @type, NEW.amount, NEW.date);
   
   END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw`
--

CREATE TABLE `withdraw` (
  `trans_id` int(11) NOT NULL,
  `account_no` int(11) DEFAULT NULL,
  `withdraw_type` varchar(15) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `cheque_no` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `withdraw`
--

INSERT INTO `withdraw` (`trans_id`, `account_no`, `withdraw_type`, `amount`, `cheque_no`, `date`) VALUES
(1007, 2, 'ATM', 500, NULL, '2019-11-14'),
(1008, 4, 'AGENT', 50.589, '12345678', '2019-11-14'),
(1011, 2, 'BANK', 1000, '123134345', '2019-11-20'),
(1014, 7, 'BANK', 1000, '3456789', '2019-11-21'),
(1020, 9, 'ATM', 500, '23456789', '2019-11-22'),
(1023, 4, 'ATM', 900, '23456789', '2019-11-26');

--
-- Triggers `withdraw`
--
DELIMITER $$
CREATE TRIGGER `balance_update_wd` BEFORE UPDATE ON `withdraw` FOR EACH ROW BEGIN
DECLARE U double;
DECLARE PB double;
DECLARE NB double;

IF (NEW.account_no = OLD.account_no) THEN
SELECT balance INTO @PB FROM balance WHERE account_no = OLD.account_no;
    SET @U = OLD.amount - NEW.amount;
    SET @NB = @PB + @U;
UPDATE balance SET balance = @NB WHERE account_no = OLD.account_no;

ELSE

SELECT balance INTO @PB FROM balance WHERE account_no = OLD.account_no;
    SET @NB = @PB + OLD.amount;
UPDATE balance SET balance = @NB WHERE account_no = OLD.account_no;

SELECT balance INTO @PB FROM balance WHERE account_no = NEW.account_no;
    SET @NB = @PB - NEW.amount;
UPDATE balance SET balance = @NB WHERE account_no = NEW.account_no;


END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `balance_withdraw` AFTER INSERT ON `withdraw` FOR EACH ROW BEGIN 
   DECLARE B double;
   DECLARE FB double;
   
   SELECT balance INTO @B FROM balance WHERE account_no = NEW.account_no;
   
   SET @FB = @B - NEW.amount;
   
   UPDATE balance SET balance = @FB WHERE account_no = NEW.account_no;
   
   END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trans_update_wd` BEFORE UPDATE ON `withdraw` FOR EACH ROW BEGIN
DECLARE U double;
DECLARE PA double;
DECLARE NA double;

IF (NEW.account_no = OLD.account_no) THEN
    SET @NA = NEW.amount;
UPDATE transection SET amount = @NA WHERE trans_id = OLD.trans_id;

ELSE
SET @NA = NEW.amount;
SET @U = NEW.account_no;
UPDATE transection SET amount = @NA, account_no = @U WHERE trans_id = OLD.trans_id;

END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trans_withdraw` AFTER INSERT ON `withdraw` FOR EACH ROW BEGIN 
   DECLARE type varchar(15);
   SET @type = 'withdraw';
   
   INSERT INTO transection (trans_id,account_no,trans_type,amount,trans_date) VALUES (NEW.trans_id, NEW.account_no, @type, NEW.amount, NEW.date);
   
   END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`account_no`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`account_no`),
  ADD UNIQUE KEY `phone_no` (`phone_no`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indexes for table `transection`
--
ALTER TABLE `transection`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indexes for table `withdraw`
--
ALTER TABLE `withdraw`
  ADD PRIMARY KEY (`trans_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `account_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance`
--
ALTER TABLE `balance`
  ADD CONSTRAINT `balance_ibfk_1` FOREIGN KEY (`account_no`) REFERENCES `client` (`account_no`);

--
-- Constraints for table `transection`
--
ALTER TABLE `transection`
  ADD CONSTRAINT `transection_ibfk_1` FOREIGN KEY (`account_no`) REFERENCES `client` (`account_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
