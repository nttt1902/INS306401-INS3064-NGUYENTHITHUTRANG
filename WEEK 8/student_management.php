<?php
// 1. KẾT NỐI DATABASE (PDO)
$host = 'localhost';
$db   = 'student_management';
$user = 'root';
$pass = ''; // Mặc định XAMPP là rỗng
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Lỗi kết nối database: " . $e->getMessage());
}

$message = "";  // Biến lưu thông báo kết quả thao tác

// 2. XỬ LÝ CÁC THAO TÁC CRUD
// Kiểm tra nếu có request POST (form được submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // THÊM HOẶC CẬP NHẬT SINH VIÊN
    // Nếu action là 'save', thực hiện tạo hoặc sửa sinh viên
    if (isset($_POST['action']) && ($_POST['action'] == 'save')) {
        // Lấy dữ liệu từ form (sử dụng ?? để gán giá trị mặc định)
        $id = $_POST['id'] ?? '';  // ID sinh viên (rỗng nếu thêm mới)
        $class_id = $_POST['class_id'];  // Mã lớp học
        $student_code = $_POST['student_code'];  // Mã sinh viên
        $full_name = $_POST['full_name'];  // Họ và tên
        $dob = $_POST['date_of_birth'];  // Ngày sinh
        $email = $_POST['email'];  // Email
        $gender = $_POST['gender'];  // Giới tính

        // Thực hiện thao tác với xử lý exception
        try {
            if (empty($id)) {
                // CREATE - Nếu không có ID, thêm sinh viên mới
                // Sử dụng prepared statement để phòng chống SQL Injection
                $sql = "INSERT INTO students (class_id, student_code, full_name, date_of_birth, email, gender) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);  // Chuẩn bị câu SQL
                $stmt->execute([$class_id, $student_code, $full_name, $dob, $email, $gender]);  // Thực hiện chèn dữ liệu
                $message = "Thêm sinh viên thành công!";
            } else {
                // UPDATE - Nếu có ID, cập nhật thông tin sinh viên
                $sql = "UPDATE students SET class_id=?, student_code=?, full_name=?, date_of_birth=?, email=?, gender=? WHERE id=?";
                $stmt = $pdo->prepare($sql);  // Chuẩn bị câu SQL
                $stmt->execute([$class_id, $student_code, $full_name, $dob, $email, $gender, $id]);  // Thực hiện cập nhật
                $message = "Cập nhật thành công!";
            }
        } catch (Exception $e) {
            // Nếu có lỗi, lưu thông báo lỗi
            $message = "Lỗi: " . $e->getMessage();
        }
    }

    // XÓA SINH VIÊN
    // Nếu action là 'delete', xóa sinh viên khỏi database
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");  // Chuẩn bị câu SQL xóa
        $stmt->execute([$_POST['id']]);  // Thực hiện xóa dựa trên ID
        $message = "Đã xóa sinh viên!";
    }
}

// 3. LẤY DỮ LIỆU CHO DASHBOARD (READ)
// Lấy giá trị tìm kiếm và filter từ URL (?search=... &filter_class=...)
$search = $_GET['search'] ?? '';  // Từ khóa tìm kiếm (rỗng nếu không có)
$filter_class = $_GET['filter_class'] ?? '';  // Lọc theo lớp (rỗng nếu không lọc)

// Câu SQL JOIN để lấy dữ liệu sinh viên kèm tên lớp
$query = "SELECT s.*, c.class_name 
          FROM students s 
          JOIN classes c ON s.class_id = c.id 
          WHERE (s.full_name LIKE ? OR s.student_code LIKE ?)";  // Tìm kiếm theo tên hoặc mã SV

$params = ["%$search%", "%$search%"];  // Mảng tham số cho prepared statement

// Nếu có filter theo lớp, thêm điều kiện WHERE
if (!empty($filter_class)) {
    $query .= " AND s.class_id = ?";  // Lọc thêm theo lớp
    $params[] = $filter_class;  // Thêm tham số filter
}

$query .= " ORDER BY s.id DESC";  // Sắp xếp theo ID giảm dần (mới nhất lên trước)

// Thực hiện truy vấn
$stmt = $pdo->prepare($query);  // Chuẩn bị câu SQL
$stmt->execute($params);  // Thực hiện với các tham số
$students = $stmt->fetchAll();  // Lấy tất cả kết quả thành mảng

// Lấy danh sách lớp cho dropdown filter
$classes = $pdo->query("SELECT id, class_name FROM classes")->fetchAll();  // Lấy tất cả lớp học
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

        :root {
            --primary: #4f46e5;
            /* Màu xanh indigo hiện đại */
            --primary-hover: #4338ca;
            --danger: #ef4444;
            /* Đỏ mềm hơn */
            --danger-hover: #dc2626;
            --warning: #f59e0b;
            /* Vàng cam mềm */
            --warning-hover: #d97706;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-body: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1050px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.025);
        }

        h2 {
            color: #111827;
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            letter-spacing: -0.025em;
        }

        /* Toolbar & Forms */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box form {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        input,
        select {
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }

        input:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* Buttons */
        .btn {
            cursor: pointer;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-add {
            background: var(--primary);
        }

        .btn-add:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }

        .btn-delete {
            background: var(--danger);
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
        }

        .btn-delete:hover {
            background: var(--danger-hover);
        }

        .btn-edit {
            background: var(--warning);
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
        }

        .btn-edit:hover {
            background: var(--warning-hover);
        }

        .btn-filter {
            background: white;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .btn-filter:hover {
            background: #f3f4f6;
        }

        /* Table Style */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
        }

        th,
        td {
            padding: 16px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background: #f9fafb;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        th:first-child {
            border-top-left-radius: 8px;
        }

        th:last-child {
            border-top-right-radius: 8px;
        }

        td {
            font-size: 14px;
            color: var(--text-main);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f9fafb;
        }

        /* Tags cho Lớp học (Optional tinh chỉnh) */
        .class-tag {
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 12px;
        }

        /* Modal Style */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 450px;
            margin: 5vh auto;
            padding: 30px;
            border-radius: 16px;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            animation: modalFadeIn 0.3s ease-out forwards;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content h3 {
            margin-top: 0;
            color: #111827;
        }

        .modal-content label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
            font-size: 14px;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .modal-content input,
        .modal-content select {
            width: 100%;
            box-sizing: border-box;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 30px;
            height: 30px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            color: var(--text-muted);
            transition: 0.2s;
        }

        .close:hover {
            background: #e5e7eb;
            color: #111827;
        }

        .alert {
            padding: 12px 16px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>🎓 STUDENT MANAGEMENT SYSTEM</h2>

        <?php if ($message): ?>
            <!-- Hiển thị thông báo kết quả (Thêm/Sửa/Xóa thành công hoặc lỗi) -->
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>

        <div class="toolbar">
            <div class="search-box">
                <!-- Form tìm kiếm và lọc sinh viên -->
                <form method="GET" style="display: flex; gap: 5px;">
                    <!-- Input tìm kiếm theo tên hoặc mã SV -->
                    <input type="text" name="search" placeholder="Mã SV hoặc Tên..." value="<?= htmlspecialchars($search) ?>">

                    <!-- Dropdown lọc theo lớp học -->
                    <select name="filter_class">
                        <option value="">-- Tất cả lớp --</option>
                        <?php foreach ($classes as $c): ?>
                            <!-- Lặp qua danh sách lớp và hiển thị option, highlight lớp được chọn -->
                            <option value="<?= $c['id'] ?>" <?= $filter_class == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Button gửi form tìm kiếm -->
                    <button type="submit" class="btn" style="background: #64748b;">Tìm kiếm</button>
                </form>
            </div>
            <!-- Button mở modal thêm sinh viên mới -->
            <button class="btn btn-add" onclick="openModal()">+ Thêm Sinh Viên</button>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ và Tên</th>
                        <th>Lớp</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Email</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <!-- Lặp qua từng sinh viên và hiển thị hàng trong bảng -->
                        <tr>
                            <!-- Hiển thị mã sinh viên (in đậm) -->
                            <td><strong><?= $s['student_code'] ?></strong></td>
                            <!-- Hiển thị họ và tên sinh viên -->
                            <td><?= $s['full_name'] ?></td>
                            <!-- Hiển thị tên lớp học -->
                            <td><?= $s['class_name'] ?></td>
                            <!-- Hiển thị ngày sinh -->
                            <td><?= $s['date_of_birth'] ?></td>
                            <!-- Hiển thị giới tính -->
                            <td><?= $s['gender'] ?></td>
                            <!-- Hiển thị email -->
                            <td><?= $s['email'] ?></td>
                            <!-- Cột hành động: Sửa và Xóa -->
                            <td>
                                <!-- Button Sửa: Chuyển dữ liệu sinh viên sang form modal -->
                                <button class="btn btn-edit" onclick='editStudent(<?= json_encode($s) ?>)'>Sửa</button>
                                <!-- Form Xóa: Gửi POST request để xóa sinh viên -->
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <input type="hidden" name="id" value="<?= $s['id'] ?>"> <!-- ID sinh viên cần xóa -->
                                    <input type="hidden" name="action" value="delete"> <!-- Xác định action là delete -->
                                    <!-- Button Xóa với xác nhận -->
                                    <button type="submit" class="btn btn-delete">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal dialog cho thêm/sửa sinh viên -->
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <!-- Nút đóng modal -->
            <span class="close" onclick="closeModal()">&times;</span>
            <!-- Tiêu đề modal (thay đổi giữa "Thêm" và "Sửa") -->
            <h3 id="modalTitle">Thêm Sinh Viên Mới</h3>

            <!-- Form POST để gửi dữ liệu -->
            <form method="POST">
                <!-- Hidden fields xác định action là 'save' và lưu ID -->
                <input type="hidden" name="action" value="save"> <!-- Xác định hành động là lưu -->
                <input type="hidden" name="id" id="form_id"> <!-- ID sinh viên (rỗng khi thêm mới) -->

                <!-- Dropdown chọn lớp học -->
                <label>Lớp học:</label>
                <select name="class_id" id="form_class_id" required>
                    <?php foreach ($classes as $c): ?>
                        <!-- Lặp danh sách lớp -->
                        <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Input mã sinh viên (bắt buộc) -->
                <label>Mã Sinh Viên:</label>
                <input type="text" name="student_code" id="form_code" required>

                <!-- Input họ và tên (bắt buộc) -->
                <label>Họ và Tên:</label>
                <input type="text" name="full_name" id="form_name" required>

                <!-- Input ngày sinh -->
                <label>Ngày sinh:</label>
                <input type="date" name="date_of_birth" id="form_dob">

                <!-- Input email -->
                <label>Email:</label>
                <input type="email" name="email" id="form_email">

                <!-- Dropdown giới tính -->
                <label>Giới tính:</label>
                <select name="gender" id="form_gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>

                <!-- Button gửi form -->
                <button type="submit" class="btn btn-add" style="width: 100%; margin-top: 20px;">Lưu Dữ Liệu</button>
            </form>
        </div>
    </div>

    <script>
        // Lấy element modal để hiển thị/ẩn
        const modal = document.getElementById('studentModal');

        // Function mở modal để thêm sinh viên mới
        function openModal() {
            // Thay đổi tiêu đề thành "Thêm Sinh Viên Mới"
            document.getElementById('modalTitle').innerText = "Thêm Sinh Viên Mới";

            // Xóa dữ liệu cũ trong form (reset)
            document.getElementById('form_id').value = ""; // Xóa ID (rỗng = thêm mới)
            document.getElementById('form_code').value = ""; // Xóa mã SV
            document.getElementById('form_name').value = ""; // Xóa tên
            document.getElementById('form_email').value = ""; // Xóa email

            // Hiển thị modal
            modal.style.display = "block";
        }

        // Function đóng modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Function mở modal để sửa sinh viên (nhận dữ liệu từ bảng)
        function editStudent(data) {
            // Thay đổi tiêu đề thành "Chỉnh sửa sinh viên"
            document.getElementById('modalTitle').innerText = "Chỉnh sửa sinh viên";

            // Điền dữ liệu sinh viên vào form
            document.getElementById('form_id').value = data.id; // ID sinh viên
            document.getElementById('form_class_id').value = data.class_id; // Lớp học
            document.getElementById('form_code').value = data.student_code; // Mã SV
            document.getElementById('form_name').value = data.full_name; // Họ và tên
            document.getElementById('form_dob').value = data.date_of_birth; // Ngày sinh
            document.getElementById('form_email').value = data.email; // Email
            document.getElementById('form_gender').value = data.gender; // Giới tính

            // Hiển thị modal
            modal.style.display = "block";
        }

        // Đóng modal khi click vào vùng ngoài modal
        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }
    </script>

</body>

</html>