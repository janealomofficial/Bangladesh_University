<?php
session_start();
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // full name
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            // Check if username OR email already exists
            $stmt = $DB_con->prepare("SELECT user_id FROM users WHERE username = :username OR email = :email LIMIT 1");
            $stmt->execute([
                ':username' => $username,
                ':email'    => $email
            ]);

            if ($stmt->rowCount() > 0) {
                $error = "Username or Email already exists!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert into users table
                $stmt = $DB_con->prepare("
                    INSERT INTO users (username, email, password, role, status, created_at) 
                    VALUES (:username, :email, :password, 'student', 'active', NOW())
                ");
                $stmt->execute([
                    ':username' => $username,
                    ':email'    => $email,
                    ':password' => $hashed_password
                ]);

                // ✅ Get last inserted user_id
                $user_id = $DB_con->lastInsertId();

                // ✅ Auto-link student profile
                $stmt2 = $DB_con->prepare("
                    INSERT INTO students (user_id, full_name, department) 
                    VALUES (:uid, :full_name, :dept)
                ");
                $stmt2->execute([
                    ':uid'       => $user_id,
                    ':full_name' => $username,    // store as student full name
                    ':dept'      => 'Not Assigned'
                ]);

                // ✅ Redirect after success
                header("Location: login.php?registered=1");
                exit;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username or Email already exists!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #1a237e;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #1a237e;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0d125a;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Student Registration</h2>

        <?php if (isset($error)): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Full Name</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Student Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Create Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Register as Student</button>
        </form>
    </div>
</body>

</html>