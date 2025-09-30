-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 02:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university_ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `program` varchar(100) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `payment_reference` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `invoice_no` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 5000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `full_name`, `student_id`, `email`, `phone`, `address`, `program`, `batch`, `department`, `payment_status`, `payment_reference`, `created_at`, `invoice_no`, `amount`) VALUES
(1, 'Suhani', 'BU2025-002', 'suhani@gmail.com', '017281088765', 'Bosila, Muhammadpur, Dhaka', 'B.Sc. CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'ADMINPAYCC0AD331', '2025-09-29 19:54:49', NULL, 5000.00),
(2, 'Jane Alom', 'BU2025-001', 'janepwad@gmail.com', '01728108888', 'Bosila, Muhammadpur, Dhaka', 'B.Sc. CSE', '67', 'Computer Science & Engineering', 'paid', 'PAY219ABC8A3AD1', '2025-09-17 10:16:25', NULL, 5000.00),
(5, 'Arin', 'BU2025-003', 'arin@gmail.com', '0172810898', 'Washpur, Muhammadpur, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', NULL, '2025-09-29 20:32:53', NULL, 5000.00),
(1001, 'Arin', 'BU2025-006', 'arin@gmail.com', '0172810898', 'Washpur, Muhammadpur, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', NULL, '2025-09-29 20:39:23', NULL, 5000.00),
(1002, 'Arin', 'BU2025-1002', 'arin@gmail.com', '0172810898', 'Washpur, Muhammadpur, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', NULL, '2025-09-29 20:39:26', NULL, 5000.00),
(1003, 'Arin2', 'BU2025-1003', 'arin2@gmail.com', '0172810899', 'Washpur, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', NULL, '2025-09-29 20:39:49', NULL, 5000.00),
(1004, 'Student2', 'BU2025-1004', 'student2@gmail.com', '0172810893', 'Framgate, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering', 'paid', NULL, '2025-09-29 20:43:12', NULL, 5000.00),
(1005, 'Student2', 'BU2025-752', 'student2@gmail.com', '0172810893', 'Framgate, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering ', 'paid', 'PAY68DAF0954CF90', '2025-09-29 20:48:21', NULL, 5000.00),
(1006, 'Student2', 'BU2025-534', 'student2@gmail.com', '0172810893', 'Framgate, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering ', 'paid', 'PAY68DAF09875661', '2025-09-29 20:48:24', NULL, 5000.00),
(1007, 'abc', 'BU2025-745', 'abc@gmail.com', '0172345672', 'Paltan, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'PAY68DAF181DA661', '2025-09-29 20:52:17', NULL, 5000.00),
(1008, 'abc', 'BU2025-439', 'abc@gmail.com', '0172345672', 'Paltan, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'PAY68DAF697E5492', '2025-09-29 21:13:59', NULL, 5000.00),
(1009, 'abc', 'BU2025-385', 'abc@gmail.com', '0172345672', 'Paltan, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'PAY68DAF69B397D5', '2025-09-29 21:14:03', NULL, 5000.00),
(1010, 'abc', 'BU2025-652', 'abc@gmail.com', '0172345672', 'Paltan, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'PAY68DAF69DCD751', '2025-09-29 21:14:05', NULL, 5000.00),
(1011, 'abc', 'BU2025-1011', 'abc@gmail.com', '0172345672', 'Paltan, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', NULL, '2025-09-29 21:15:53', NULL, 5000.00),
(1012, 'Jibran', 'BU2025-1012', 'jibran@gmail.com', '017281022222', 'Puran Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'ADMINPAYCDC4E849', '2025-09-29 23:41:14', NULL, 5000.00),
(1013, 'Jibran', 'BU2025-1013', 'jibran@gmail.com', '017281022222', 'Puran Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'ADMINPAY7F095A77', '2025-09-29 23:52:04', NULL, 5000.00),
(1014, 'suhani2', 'BU2025-1014', 'suhani2@gmail.com', '01728108834', 'Muhammadpur, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', 'ADMINPAYDCB93B7D', '2025-09-29 23:54:38', NULL, 5000.00),
(1015, 'suhani3', 'BU2025-1015', 'suhani3@gmail.com', '017245672', 'Bosila', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering', 'paid', 'ADMINPAY6CBE3011', '2025-09-29 23:58:15', NULL, 5000.00),
(1016, 'mubin', 'BU2025-1016', 'mubin@gmail.com', '0172810893', 'Framgate, Dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering 2', 'paid', NULL, '2025-09-30 00:29:34', NULL, 5000.00),
(1017, 'saif', 'BU2025-1017', 'saif@gmail.com', '0172345673', 'mirpur, dhaka', 'B.Sc. in CSE', 'Spring 2025', 'Computer Science & Engineering', 'paid', NULL, '2025-09-30 09:19:43', NULL, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `admission_invoices`
--

CREATE TABLE `admission_invoices` (
  `id` int(11) NOT NULL,
  `admission_id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_invoices`
--

INSERT INTO `admission_invoices` (`id`, `admission_id`, `invoice_no`, `amount`, `status`, `issued_at`) VALUES
(1, 0, 'INV20250930-1011', 5000.00, 'paid', '2025-09-29 19:55:34'),
(2, 1, 'INV20250917-0001', 10000.00, 'paid', '2025-09-17 10:16:38'),
(3, 1016, 'INV20250930-1016', 5000.00, 'paid', '2025-09-30 00:29:34'),
(4, 1017, 'INV20250930-1017', 5000.00, 'paid', '2025-09-30 09:19:43');

-- --------------------------------------------------------

--
-- Table structure for table `admission_invoices_backup`
--

CREATE TABLE `admission_invoices_backup` (
  `id` int(11) NOT NULL,
  `admission_id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_invoices_backup`
--

INSERT INTO `admission_invoices_backup` (`id`, `admission_id`, `invoice_no`, `amount`, `status`, `issued_at`) VALUES
(0, 0, 'INV20250930-1011', 5000.00, 'paid', '2025-09-29 19:55:34'),
(1, 1, 'INV20250917-0001', 10000.00, 'paid', '2025-09-17 10:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `alumni_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `current_position` varchar(150) DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `department` varchar(120) DEFAULT NULL,
  `grad_year` year(4) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`alumni_id`, `user_id`, `full_name`, `batch`, `current_position`, `achievements`, `department`, `grad_year`, `bio`, `image_path`, `created_at`) VALUES
(1, NULL, 'Jane Alom', '67', 'Software Engineer', 'I\'m Jane Alom, a web developer living in Habiganj, Sylhet, Bangladesh⚡', NULL, NULL, NULL, 'uploads/alumni/1757584595_jane.jpeg', '2025-09-11 09:56:35'),
(2, NULL, 'Jane Alom', '67', 'Software Engineer', 'I\'m Jane Alom, a web developer living in Habiganj, Sylhet, Bangladesh⚡', NULL, NULL, NULL, 'uploads/alumni/1757584607_jane.jpeg', '2025-09-11 09:56:47'),
(3, NULL, 'Jane', '67', 'Software Engineer', 'Nothing', NULL, NULL, NULL, 'uploads/alumni/1757584834_1.jpg', '2025-09-11 10:00:34'),
(4, NULL, 'Jane', '67', 'Software Engineer', 'Nothing', NULL, NULL, NULL, 'uploads/alumni/1757585042_1.jpg', '2025-09-11 10:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `convocation_registrations`
--

CREATE TABLE `convocation_registrations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `session` varchar(50) DEFAULT NULL,
  `program` varchar(255) NOT NULL,
  `batch` varchar(100) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `convocation_registrations`
--

INSERT INTO `convocation_registrations` (`id`, `full_name`, `student_id`, `email`, `phone`, `department`, `session`, `program`, `batch`, `status`, `created_at`) VALUES
(1, 'Jane', '123456', 'janepwad@gmail.com', '01728108888', NULL, NULL, 'cse', '67', 'Rejected', '2025-09-16 10:38:48'),
(2, 'Jane Alom', '123456', 'janepwad1@gmail.com', '01728108888', NULL, NULL, 'cse', '67', 'Pending', '2025-09-16 10:52:18'),
(3, 'Jane', '123456', 'janepwad@gmail.com', '01728108888', 'CSE', 'Spring 2024', 'cse', '67', 'Pending', '2025-09-16 11:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(150) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_code`, `department`, `faculty_id`, `semester`, `created_at`) VALUES
(1, 'Database Systems', 'CS301', 'Computer Science', 1, 'Spring 2025', '2025-09-04 11:51:18'),
(2, 'Operating Systems', 'CS302', 'Computer Science', 1, 'Spring 2025', '2025-09-04 11:51:18'),
(3, 'Programming C', 'PC303', 'CSE', 1, '3rd', '2025-09-04 12:07:54'),
(7, 'Programming In Java', 'JAVA304', 'CSE', NULL, NULL, '2025-09-10 10:57:06');

-- --------------------------------------------------------

--
-- Table structure for table `course_offerings`
--

CREATE TABLE `course_offerings` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `section` varchar(10) NOT NULL,
  `year` year(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_offerings`
--

INSERT INTO `course_offerings` (`id`, `course_id`, `faculty_id`, `semester_id`, `section`, `year`, `created_at`) VALUES
(0, 7, 1, 1, 'A', '2025', '2025-09-29 23:18:38'),
(1, 1, 1, 1, 'a', '2025', '2025-09-28 09:55:30'),
(2, 2, 1, 1, 'B', '2025', '2025-09-28 09:55:44'),
(3, 3, 1, 1, 'C', '2025', '2025-09-28 09:55:57'),
(4, 8, 3, 1, 'A', '2025', '2025-09-29 10:54:20');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_head` varchar(255) DEFAULT NULL,
  `total_faculty` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_credits` int(11) DEFAULT 0,
  `course_cost` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_head`, `total_faculty`, `description`, `image_path`, `created_at`, `total_credits`, `course_cost`) VALUES
(5, 'Computer Science & Engineering 2', 'Jane Alom 2', 25, 'descrpition', '../uploads/departments/card2.jpg', '2025-09-15 10:45:50', 130, 230000),
(6, 'Computer Science & Engineering ', 'Jane Alom', 120, 'habijabi', '../uploads/departments/BU-Campus.jpg', '2025-09-15 10:48:07', 3235500, 30000),
(7, 'Computer Science & Engineering ', 'Jane Alom', 23, 'sasggfd', '../uploads/departments/istockphoto-1156903571-612x612.jpg', '2025-09-15 10:52:59', 140, 1300000);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `offering_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `enrolled_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `student_id`, `course_id`, `offering_id`, `faculty_id`, `semester`, `enrolled_on`) VALUES
(7, 1, 1, NULL, 1, 'Spring 2025', '2025-09-30 09:50:45'),
(8, 1, 2, NULL, 1, 'Spring 2025', '2025-09-30 09:50:57'),
(9, 1, 3, NULL, 1, 'Spring 2025', '2025-09-30 09:51:08'),
(10, 12, 3, NULL, 1, 'Spring 2025', '2025-09-30 09:53:16'),
(11, 7, 1, NULL, 1, 'Spring 2025', '2025-09-30 09:56:01'),
(14, 5, 3, 3, 1, 'Summer-25', '2025-09-30 10:15:38');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `user_id`, `full_name`, `designation`, `department`, `contact`, `address`, `hire_date`) VALUES
(1, 2, 'Dr. Alice Smith', 'Professor', 'Computer Science', '9876543210', 'Dhaka, Bangladesh', '2015-02-10');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `mark_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_events`
--

CREATE TABLE `news_events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  `is_live` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_events`
--

INSERT INTO `news_events` (`event_id`, `title`, `description`, `event_date`, `image_path`, `is_live`, `created_at`) VALUES
(1, 'MOU signed between Bangladesh University (BU) and UCB PLC', 'A Memorandum of Understanding (MoU) was signed between BU and the UCBL PLC, one of the leading banks of the first generation. Today, the 15th of November 2023, in a showy ceremony, the MoU was signed by Md. Firoz Alam, Sr. Executive Vice Chairman & Head of Transaction (Banking Division) on behalf of UCB PLC and his counterpart, BU’s Director, (Finance and Accounts) SM Firoz Ahmed.\r\n\r\nVice-Chancellor of BU (Acting), Prof. Dr. Md. Jahangir Alam; Treasurer (Acting), Sazedul Alam; Registrar, Brig. Gen. Md. Mahbubul Haque, (Retd.) and Additional MD, Nabil M Rahman of UCB; First Vice president, Farhana Akter; Vice President, (Transaction Banking), Riad Hashim and Relationship Manager (Transaction Banking), Muhammad Abdun Nur were present among others during signing of the MoU.\r\n\r\nUnder this MoU, the students of BU can enter their own Ids. to make payment of their tuition fees by printing out their payment slip of the respective semester, in any branches of UCB and the money they deposit then and then will be shown in the respective student’s Id. Besides, under this agreement the students can make any payment of their tuition fees online in future.', '2025-09-11 11:47:39', 'uploads/news_events/1757591259_MOU.jpg', 1, '2025-09-11 11:47:39'),
(2, 'BU Won the TV Debate Competition', 'Yesterday (30.7.2023, Saturday), a television debate competition was staged at ATN Bangla in the Bangladesh Film Development Corporation with a view to growing awareness to prevent plastic pollution. Bangladesh University (BU) and Cumilla University participated in the debate competition organized by ‘Debate for Democracy.’ The motion of the debate was ‘Individual Awareness Alone Can Check the Plastic Pollution.’\r\n\r\nBangladesh University, as treasury branch took part in the debate in favor of the motion and won the competition defending Cumilla University.\r\n\r\n\r\nIn the BU team Md. Ashikuzzaman, the president of   the BU debating club, as team leader played the role of prime minister, Tasmin-e-Jannat Siddiqui played the role of minister and Bappi Kaiser Himu, Muntasir Rabbi and Mahtab Hossain played the role of members of the parliament.  Sheikh Alauddin, Head, Department of English was the moderator of the BU debate team.\r\n\r\nAt the end of the debate competition the chief guest, famous environmental scientist and researcher and Executive Director of Bangladesh Center for Advanced Studies, Dr. Atiq Rahman and the Chairman of Debate for Democracy Hasan Ahmed Chowdhury Kiran congratulated the BU debate team and handed over the champion trophy.  A large number of students and teachers from both the universities enjoyed the TV debate competition at the ATN Bangla studio.', '2025-09-11 11:52:17', 'uploads/news_events/1757591537_debate.jpg', 1, '2025-09-11 11:52:17'),
(3, 'BU became the hat-trick champion in the Clemon Indoor Uni Cricket Tournament', 'Bangladesh University brought home the champion trophy in the 7th season of the Clemon Indoor Uni Cricket Tournament 2023. Today, Thursday, July 27, 2023, the BU cricket team won the title for the consecutive 3rd time defeating Stamford University (SU) by 5 wickets in the final held in the Mirpur Shaheed Sohrawardhi Indoor Stadium.\r\n\r\nLosing the toss, SU went to bat. However, the beginning was not so good. They lost their first five wickets to get 57 runs on the scoreboard. Though they did not feel comfortable even after that. None of the batsmen could cross the double-digit quota. The team\'s highest individual 37 runs came from the bye runs. Subsequently, they could not score more than 63 runs after losing all the wickets in the scheduled 8 overs. For Bangladesh University, Sohel Rana took 3 wickets for 10 runs, Abdul Gaffar 2 wickets for 7 runs, and Shafiq 1 wicket for 18 runs.\r\n\r\nBangladesh University\'s two openers Nazmul Hossain Shanto and Zahirul Islam came to bat to win the title. They had a good start to the innings, but captain Shanto returned with a catch for 16 runs. At one stage, BU lost two striking batsmen to score 26 runs on the scoreboard.\r\n\r\nThen, Sohel Rana took the lead of the team. Thanks to his impeccable batting, BU easily reached the port of victory. Sohel Rana scored an unbeaten 42 off 15 balls with the help of five sixes and three boundaries. Bangladesh University won the title for the third time after beating SU in Sohel\'s all-round charismatic performance. Stamford\'s Alamgir took 2 wickets for 10 runs.\r\n\r\nAt the end of the final, the members of the organizing committee handed over the trophy to the captain of the winning team. BU received Tk. 5 lakh as prize money for being the champion. And the runner-up Stamford University received 2 lakh. Bangladesh national cricket team\'s three former stars Atahar Ali Khan, Akram Khan, and Khaled Masood Pilot\'s \'Three Creeks\' as usual organized this tournament.\r\n\r\nNazmul Hossain Shanto, captain of Bangladesh University, became the \'tournament best\' among 32 university teams. Sohel Rana, who led Bangladesh University to a great victory, was selected as Man of the Final. In the final, he got 42 runs and 3 wickets for 10 runs.\r\n\r\nTrustee Board Chairman, of Bangladesh University, Quazi Jamil Azhar, and Acting Vice-Chancellor of BU Professor Dr. Md. Jahangir Alam congratulated the players, coaches and officials on the team\'s victory.', '2025-09-11 11:54:21', 'uploads/news_events/1757591661_clemon.jpg', 1, '2025-09-11 11:54:21'),
(4, 'Formal inauguration of all academic programs in the BU permanent campus held', 'One of the leading private universities started its academic programs fully on its permanent campus. Today (March 29, 2023) the Vice-Chancellor (Acting) Prof. Dr. Md. Jahangir Alam formally inaugurated the shifting of all the academic programs in the BU permanent campus at Adabar, Dhaka by cutting the red ribbon. Registrar Brig. Gen. Md. Mahbubul Haque (Retd.), Deans of all faculties, the Director, Heads of various departments, faculty members, high officials, and students of BU were present in the program.\r\n\r\n\r\nIn the year 2015, partial academic activities started on the beautiful permanent campus built by the renowned architect and engineers of the country on two acres of land. Since then shifting of its activities was on, and now the shifting is over. Right now, all academic programs and activities are running from this permanent campus. A digital library, modern laboratory, large computer lab, digital classroom, spacious auditorium, cafeteria, and big playground along with all modern facilities are available on the permanent campus.\r\n\r\nIn this context, the President of the Board of Trustees Quazi Jamil Azher said that a permanent campus and necessary infrastructure are very important for any educational institution. We have been able to transfer the entire academic program and activities of the University to the permanent campus through tireless efforts. I believe that the BU permanent campus will be one of the rich campuses in the South Asian region. Due to the complete shifting of all the academic programs in the permanent campus, around five thousand students studying here are now able to avail of all kinds of the latest facilities of modern education.', '2025-09-11 12:01:33', 'uploads/news_events/1757592093_jane_bu.jpg', 1, '2025-09-11 12:01:33'),
(6, 'Orientation Program held at Bangladesh University', 'An orientation program was held at the Permanent Campus of Bangladesh University at Adabor where the students of the spring semester were cordially received with flowers.  The esteemed guest noted that “The students of Bangladesh University are going abroad, employed in numerous organizations based on their excellence and they are spreading the name and fame of Bangladesh University.”\r\n\r\nThe member of Bangladesh Awami League Presidium, Former State Minister Adv. Jahangir Kabir Nanak was present as the Chief Guest at the freshers’ reception program. UGC member Prof. Dr. Md. Sajjad Hossain was present as the Special guest. The director of the Renessa group Ayesha Akter Dalia was also present as the Honorable guest. The program was chaired by the acting the Pro-VC of Bangladesh University, Professor Dr. Jahangir Alam.\r\n\r\nThe Chief Guest Adv. Jahangir Kabir Nanak stated while welcoming the freshers that, Bangladesh University is progressing forward with an ambient academic environment under the supervision of experienced faculty members. The graduates from this university are employed in numerous organizations based on their excellence and they are spreading the name and fame of Bangladesh University.\r\n\r\nHe further added that “I am very pleased that the university handled the Covid-19 situation with their utmost and excellent capability”. He also recommended and encouraged the students to read books. At the same time, he addressed that he would provide any form of support to upgrade the current academic environment.\r\n\r\nThe Special guest Prof. Dr. Md. Sajjad Hossain in his speech stated, “ this program will be memorable for all of you. Now, you have to join the struggle of establishing yourselves in the eye of family and society.”\r\n\r\nThe program started with the National Anthem and then the Honorable Registrar, Brig. Gen. Mahabubul Haque (retired) kept his welcome speech. Afterwards, on behalf of all the Heads of the Departments Prof. Dr. Md. Tajul Islam, Dean, Faculty of Arts, Social Science & Law spoke on the occasion. Later on, Dr. Quazi Taif Sadat, Director, BU delivered the vote of thanks.\r\n\r\nAt the end of the Fresher\'s Orientation Program, a small prize-giving ceremony of Quazi Azher Ali Memorial Sports Tournament took place where the Chief Guest Adv. Jahangir Kabir Nanak gave the crests to the winners and notable players.\r\n\r\nIn the evening the cultural part of the program started, where BU students performed alongside the popular Band Warfaze. A large number of students, faculty and staff members and the guardians of the students attended the program.', '2025-09-11 12:12:22', 'uploads/news_events/1757592742_orientation.jpg', 1, '2025-09-11 12:12:22'),
(10, 'Bangladesh University: A Hub of Innovation and Excellence', 'Established in 2001, Bangladesh University (BU) is a prominent private institution located in Mohammadpur, Dhaka. With a commitment to providing quality education at affordable costs, BU caters especially to students from underprivileged backgrounds. \r\n\r\n\r\nThe university offers a range of undergraduate and postgraduate programs, focusing on disciplines like Business Administration, Computer Science, and Architecture. BU\'s state-of-the-art facilities, experienced faculty, and diverse student body create an environment conducive to academic and personal growth. The university\'s mission is to produce ethical leaders equipped with modern knowledge and technology to represent Bangladesh globally.', '2025-09-11 12:25:50', 'uploads/news_events/1757593550_IMG-20250911-WA0032.jpg', 1, '2025-09-11 12:25:50');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `paid_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `student_id`, `semester_id`, `amount`, `status`, `paid_on`) VALUES
(1, 4, 1, 250000.00, 'paid', '2025-09-29 10:02:11'),
(2, 4, 2, 25000.00, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `marks` decimal(5,2) NOT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `capacity`, `location`) VALUES
(1, 'Room A', 60, 'Building 1, First Floor'),
(2, 'Room B', 40, 'Building 1, Second Floor');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `offering_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `timeslot_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `offering_id`, `room_id`, `timeslot_id`, `created_at`) VALUES
(1, 1, 1, 1, '2025-09-28 10:04:54'),
(2, 2, 2, 2, '2025-09-28 10:05:48'),
(3, 3, 1, 3, '2025-09-28 10:06:05'),
(4, 4, 2, 4, '2025-09-29 10:54:52'),
(0, 0, 1, 4, '2025-09-30 10:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `semester_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `name`, `start_date`, `end_date`, `fee`, `created_at`, `semester_name`) VALUES
(1, 'Summer-25', '2025-04-04', '2025-04-04', 25000.00, '2025-09-10 10:42:55', NULL),
(2, 'Spring-25', '2025-09-30', '2025-12-31', 20000.00, '2025-09-29 23:23:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `full_name`, `dob`, `gender`, `department`, `contact`, `address`, `admission_date`, `semester_id`, `email`, `phone`) VALUES
(1, 3, 'John Doe', '2002-04-15', 'male', 'Computer Science', '1234567890', 'Dhaka, Bangladesh', '2021-09-01', 2, 'student@example.com', NULL),
(4, 13, 'ayash', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 14, 'suhani', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 15, 'arin', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 16, 'student2', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 17, 'kenod', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 18, 'abc', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 19, 'jibran', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 20, 'userstudent', NULL, NULL, 'Not Assigned', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 21, 'mubin', NULL, NULL, 'Not Assigned', '01736353994', NULL, NULL, 2, 'mubin@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `assigned_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`id`, `student_id`, `course_id`, `assigned_on`) VALUES
(1, 12, 1, '2025-09-30 00:36:33'),
(2, 12, 2, '2025-09-30 00:36:33'),
(3, 12, 3, '2025-09-30 00:36:33'),
(4, 12, 7, '2025-09-30 00:36:33'),
(9, 1, 1, '2025-09-30 09:52:34'),
(10, 1, 2, '2025-09-30 09:52:34'),
(11, 1, 3, '2025-09-30 09:52:34'),
(12, 1, 7, '2025-09-30 09:52:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userquery`
--

CREATE TABLE `tbl_userquery` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `emailid` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) DEFAULT 0 COMMENT '0=Pending, 1=Read',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_userquery`
--

INSERT INTO `tbl_userquery` (`id`, `name`, `emailid`, `mobile`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Malachi Calderon', 'luxekoqewi@mailinator.com', 'Culpa in itaque', 'Voluptatem hic quaer', 'Proident qui quod s', 0, '2025-08-27 07:02:16'),
(2, 'Seth Webb', 'nyju@mailinator.com', 'Corporis libero', 'Facere illum numqua', 'Expedita sit numquam', 0, '2025-08-27 07:02:21'),
(4, 'Jescie Mcbride', 'dodynal@mailinator.com', 'Non deleniti a ', 'Velit do facere pos', 'Commodi cillum error', 1, '2025-08-27 07:02:28'),
(5, 'TTL DDD', 'janepwad@gmail.com', 'Non deleniti a ', 'Est reprehenderit ut', 'er', 0, '2025-09-09 11:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`id`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, 'Monday', '08:30:00', '09:30:00'),
(2, 'Monday', '09:30:00', '10:30:00'),
(3, 'Monday', '10:30:00', '11:30:00'),
(4, 'Monday', '11:30:00', '12:30:00'),
(5, 'Monday', '13:30:00', '14:30:00'),
(6, 'Monday', '14:30:00', '15:30:00'),
(7, 'Monday', '15:30:00', '16:30:00'),
(8, 'Monday', '16:30:00', '17:30:00'),
(9, 'Tuesday', '08:30:00', '09:30:00'),
(10, 'Tuesday', '09:30:00', '10:30:00'),
(11, 'Tuesday', '10:30:00', '11:30:00'),
(12, 'Tuesday', '11:30:00', '12:30:00'),
(13, 'Tuesday', '13:30:00', '14:30:00'),
(14, 'Tuesday', '14:30:00', '15:30:00'),
(15, 'Tuesday', '15:30:00', '16:30:00'),
(16, 'Tuesday', '16:30:00', '17:30:00'),
(17, 'Wednesday', '08:30:00', '09:30:00'),
(18, 'Wednesday', '09:30:00', '10:30:00'),
(19, 'Wednesday', '10:30:00', '11:30:00'),
(20, 'Wednesday', '11:30:00', '12:30:00'),
(21, 'Wednesday', '13:30:00', '14:30:00'),
(22, 'Wednesday', '14:30:00', '15:30:00'),
(23, 'Wednesday', '15:30:00', '16:30:00'),
(24, 'Wednesday', '16:30:00', '17:30:00'),
(25, 'Thursday', '08:30:00', '09:30:00'),
(26, 'Thursday', '09:30:00', '10:30:00'),
(27, 'Thursday', '10:30:00', '11:30:00'),
(28, 'Thursday', '11:30:00', '12:30:00'),
(29, 'Thursday', '13:30:00', '14:30:00'),
(30, 'Thursday', '14:30:00', '15:30:00'),
(31, 'Thursday', '15:30:00', '16:30:00'),
(32, 'Thursday', '16:30:00', '17:30:00'),
(33, 'Friday', '08:30:00', '09:30:00'),
(34, 'Friday', '09:30:00', '10:30:00'),
(35, 'Friday', '10:30:00', '11:30:00'),
(36, 'Friday', '11:30:00', '12:30:00'),
(37, 'Friday', '13:30:00', '14:30:00'),
(38, 'Friday', '14:30:00', '15:30:00'),
(39, 'Friday', '15:30:00', '16:30:00'),
(40, 'Friday', '16:30:00', '17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','faculty','student') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'admin1', 'admin@example.com', '$2y$10$q9UmG3GEsaQDX9tEIhsZx.AlmaBPMTL87T43bBYZ3Oveqi1ab04Rq', 'admin', 'active', '2025-09-04 11:34:50'),
(2, 'faculty1', 'faculty@example.com', '$2y$10$1y3.wZQkxitW0A3AuvUJPOzV6KFlE0w25yuANGIAaYxJ3c7Rh/oQ2', 'faculty', 'active', '2025-09-04 11:34:50'),
(3, 'student1', 'student@example.com', '$2y$10$o0PU/RmAQ7WRmcTflEPVGuDDAYOAWK0FB3eU21Gvt2Tm/zEwQtdMS', 'student', 'active', '2025-09-04 11:34:51'),
(4, 'janealom', 'janepwad@gmail.com', '$2y$10$GZu1fh.RxLAiAN61EDsq..qjOenDLNU72AtVYh89je/P2tSfZ3hdG', 'student', 'active', '2025-09-04 11:42:20'),
(7, 'jane', 'janealom084@gmail.com', '$2y$10$a8kHnQQtit6RxabZZGT/buh2xM.Zq5PdhBrX8Cfhmao51VU1vrpeG', 'student', 'active', '2025-09-07 09:42:48'),
(10, 'janealom1', 'janepwad1@gmail.com', '$2y$10$.lVfmjN2raPr3lM6d6p83.fyOs9Cgs.webTfLrBvZMSKrQDOIXwhS', 'student', 'active', '2025-09-09 09:54:19'),
(12, 'janealomstd', 'janealomstd@gmail.com', '$2y$10$5Ydp6qX7Y8LEJzJmqF/jwu1ZoqO7UPfgVv8.1q.AkLY/LguU/cqve', 'student', 'active', '2025-09-10 10:59:05'),
(13, 'ayash', 'ayash@gmail.com', '$2y$10$45oPWE6EW3VpM5HLg7H4GeddDSgrNAqrGkDi541iAIpxZL4KXOfR6', 'student', 'active', '2025-09-29 19:37:23'),
(14, 'suhani', 'suhani@gmail.com', '$2y$10$Jr8WqcDvlbyiNTyjGuuWLujO7HBGd0k7TO1GZACsIgL4/tVQPBcKO', 'student', 'active', '2025-09-29 19:53:46'),
(15, 'arin', 'arin@gmail.com', '$2y$10$z5nDESmbj0G3Mm8/uvvyHuYI0ivaWp2lBA9mdaoj0RnF6W4yDXuGK', 'student', 'active', '2025-09-29 20:17:34'),
(16, 'student2', 'student2@gmail.com', '$2y$10$WXpsXwJnZ5Di4pHBEhbV7uGkk9AqXGEz53h0f19XCkpzD071RxNaq', 'student', 'active', '2025-09-29 20:42:14'),
(17, 'kenod', 'zefohusif@mailinator.com', '$2y$10$TIK17LWfUQ0OUgUdexwYz.SYjcua2yzOduKASGq5kPVahtJFDYSEC', 'student', 'active', '2025-09-29 20:49:25'),
(18, 'abc', 'abc@gmail.com', '$2y$10$8ZrZL.B3dXH0kmOo2Ca0Auu7qZRtiDpMbvcbPZww/dd5OaDctkvrC', 'student', 'active', '2025-09-29 20:50:14'),
(19, 'jibran', 'jibran@gmail.com', '$2y$10$WOHfspnY6Vil21zgquTBge9EYJoN.o5GfARYuV0UxC1ogvGy7Meru', 'student', 'active', '2025-09-29 23:40:28'),
(20, 'userstudent', 'userstudent@gmail.com', '$2y$10$XHW7pkjXadN3b4XMOC3dU.7MEEuV2Kec2D5gRMMUJLI4rPvfl2Ivq', 'student', 'active', '2025-09-30 00:14:29'),
(21, 'mubin', 'mubin@gmail.com', '$2y$10$dQ9VYduBV21q483sDKpTGeTfeEaJsHTIO.rLHe14A7F58hy/F1yLq', 'student', 'active', '2025-09-30 00:29:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `admission_invoices`
--
ALTER TABLE `admission_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_invoice_no` (`invoice_no`),
  ADD KEY `idx_invoice_admission` (`admission_id`);

--
-- Indexes for table `admission_invoices_backup`
--
ALTER TABLE `admission_invoices_backup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admission_id` (`admission_id`);

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`alumni_id`),
  ADD KEY `fk_alumni_user` (`user_id`);

--
-- Indexes for table `convocation_registrations`
--
ALTER TABLE `convocation_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `course_offerings`
--
ALTER TABLE `course_offerings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_id` (`course_id`,`faculty_id`,`semester_id`,`section`,`year`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_result` (`student_id`,`course_id`,`semester_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_students_semester` (`semester_id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_course_unique` (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `tbl_userquery`
--
ALTER TABLE `tbl_userquery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;

--
-- AUTO_INCREMENT for table `admission_invoices`
--
ALTER TABLE `admission_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_userquery`
--
ALTER TABLE `tbl_userquery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_semester` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
