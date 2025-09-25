<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Add Room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $stmt = $DB_con->prepare("INSERT INTO rooms (room_name, capacity, location) VALUES (:room, :cap, :loc)");
    $stmt->execute([
        ':room' => $_POST['room_name'],
        ':cap'  => $_POST['capacity'],
        ':loc'  => $_POST['location']
    ]);
    $success = "Room added successfully!";
}

// ✅ Edit Room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_room'])) {
    $stmt = $DB_con->prepare("UPDATE rooms SET room_name=:room, capacity=:cap, location=:loc WHERE id=:id");
    $stmt->execute([
        ':room' => $_POST['room_name'],
        ':cap'  => $_POST['capacity'],
        ':loc'  => $_POST['location'],
        ':id'   => $_POST['id']
    ]);
    $success = "Room updated successfully!";
}

// ✅ Delete Room
if (isset($_GET['delete'])) {
    $stmt = $DB_con->prepare("DELETE FROM rooms WHERE id=:id");
    $stmt->execute([':id' => $_GET['delete']]);
    $success = "Room deleted!";
}

// Fetch
$rooms = $DB_con->query("SELECT * FROM rooms ORDER BY room_name")->fetchAll(PDO::FETCH_ASSOC);
include 'admin_header.php';
?>

<div class="container mt-4">
    <h2>Manage Rooms</h2>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <!-- Add Form -->
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="add_room" value="1">
        <div class="col-md-4"><input type="text" name="room_name" placeholder="Room Name" class="form-control" required></div>
        <div class="col-md-3"><input type="number" name="capacity" placeholder="Capacity" class="form-control" required></div>
        <div class="col-md-4"><input type="text" name="location" placeholder="Location" class="form-control"></div>
        <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
    </form>

    <!-- Table -->
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Capacity</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['room_name']) ?></td>
                    <td><?= $r['capacity'] ?></td>
                    <td><?= htmlspecialchars($r['location']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoom<?= $r['id'] ?>">Edit</button>
                        <a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this room?');" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editRoom<?= $r['id'] ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5>Edit Room</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="edit_room" value="1">
                                    <div class="mb-2"><label>Room Name</label><input class="form-control" name="room_name" value="<?= $r['room_name'] ?>"></div>
                                    <div class="mb-2"><label>Capacity</label><input class="form-control" type="number" name="capacity" value="<?= $r['capacity'] ?>"></div>
                                    <div class="mb-2"><label>Location</label><input class="form-control" name="location" value="<?= $r['location'] ?>"></div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-success">Save</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>