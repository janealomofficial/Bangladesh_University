<?php
include 'admin_header.php';
require_once __DIR__ . "/../app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['department_name'];
    $head = $_POST['department_head'];
    $faculty = $_POST['total_faculty'];
    $cost = $_POST['course_cost'];
    $credits = $_POST['total_credits'];
    $description = $_POST['description'];

    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/departments/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $stmt = $DB_con->prepare("INSERT INTO departments 
        (department_name, department_head, total_faculty, course_cost, total_credits, description, image_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $head, $faculty, $cost, $credits, $description, $imagePath]);

    header("Location: manage_departments.php");
    exit;
}
?>

<div class="container mt-4">
    <h2>Add Department</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Department Name</label>
            <input type="text" name="department_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Department Head</label>
            <input type="text" name="department_head" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Total Faculty</label>
            <input type="number" name="total_faculty" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Course Cost</label>
            <input type="number" name="course_cost" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Total Credits</label>
            <input type="number" name="total_credits" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Save Department</button>
    </form>
</div>

<?php include 'admin_footer.php'; ?>