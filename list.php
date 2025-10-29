<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý phòng học</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #999; padding: 8px; text-align: center; }
        th { background: #cce5ff; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Quản lý phòng học</h2>

<?php
// Xử lý thêm mới
if (isset($_POST['add'])) {
    $name = trim($_POST['room_name']);
    $capacity = (int)$_POST['capacity'];
    if ($name && $capacity > 0) {
        $stmt = $pdo->prepare("INSERT INTO classrooms (room_name, capacity) VALUES (?, ?)");
        $stmt->execute([$name, $capacity]);
        echo "<p style='color:green'>Đã thêm phòng học mới!</p>";
    } else {
        echo "<p style='color:red'>Vui lòng nhập đủ thông tin!</p>";
    }
}

// Xử lý xóa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM classrooms WHERE id=?")->execute([$id]);
    echo "<p style='color:red'>Đã xóa phòng học!</p>";
}

// Xử lý cập nhật
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['room_name']);
    $capacity = (int)$_POST['capacity'];
    $pdo->prepare("UPDATE classrooms SET room_name=?, capacity=? WHERE id=?")->execute([$name, $capacity, $id]);
    echo "<p style='color:blue'>Cập nhật thành công!</p>";
}
?>

<!-- Form thêm phòng học -->
<form method="POST">
    <input type="text" name="room_name" placeholder="Tên phòng" required>
    <input type="number" name="capacity" placeholder="Sức chứa" required>
    <button type="submit" name="add">Thêm</button>
</form>

<!-- Hiển thị danh sách -->
<table>
    <tr>
        <th>ID</th>
        <th>Tên phòng</th>
        <th>Sức chứa</th>
        <th>Thao tác</th>
    </tr>
    <?php
    $stmt = $pdo->query("SELECT * FROM classrooms ORDER BY id ASC");
    while ($row = $stmt->fetch()) {
        // Nếu người dùng bấm “Sửa”, hiển thị form sửa ngay tại dòng đó
        if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) {
            echo "
            <tr>
                <form method='POST'>
                    <td>{$row['id']}<input type='hidden' name='id' value='{$row['id']}'></td>
                    <td><input type='text' name='room_name' value='{$row['room_name']}'></td>
                    <td><input type='number' name='capacity' value='{$row['capacity']}'></td>
                    <td>
                        <button type='submit' name='update'>Lưu</button>
                        <a href='index.php'>Hủy</a>
                    </td>
                </form>
            </tr>";
        } else {
            echo "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['room_name']}</td>
                <td>{$row['capacity']}</td>
                <td>
                    <a href='?edit={$row['id']}'>Sửa</a> |
                    <a href='?delete={$row['id']}' onclick='return confirm(\"Xóa phòng này?\")'>Xóa</a>
                </td>
            </tr>";
        }
    }
    ?>
</table>

</body>
</html>
