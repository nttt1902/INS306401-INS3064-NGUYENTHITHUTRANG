<?php
/**
 * MIDTERM EXAM - PHP WEB APPLICATION WITH DATABASE
 * STUDENT MANAGEMENT SYSTEM - EXAM 2
 * Candidate: Trang - VNUIS
 */

// 1. DATABASE CONNECTION (PDO)
$host = 'localhost';
$db   = 'student_management';
$user = 'root';
$pass = ''; 
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
    die("Connection failed: " . $e->getMessage());
}

$message = "";

// 2. CRUD OPERATIONS (students table)
// --- CREATE & UPDATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_student'])) {
    $id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $student_code = trim($_POST['student_code']);
    $full_name = trim($_POST['full_name']);
    $dob = $_POST['date_of_birth'];
    $email = trim($_POST['email']);
    $gender = $_POST['gender'];

    // Basic Validation
    if (empty($student_code) || empty($full_name)) {
        $message = "<div class='alert alert-warning animate__animated animate__fadeIn'>Mã SV và Họ tên không được để trống!</div>";
    } else {
        try {
            if (empty($id)) {
                // CREATE - PREPARED STATEMENT
                $stmt = $pdo->prepare("INSERT INTO students (class_id, student_code, full_name, date_of_birth, email, gender) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$class_id, $student_code, $full_name, $dob, $email, $gender]);
                $message = "<div class='alert alert-success'>Thêm sinh viên mới thành công!</div>";
            } else {
                // UPDATE - PREPARED STATEMENT
                $stmt = $pdo->prepare("UPDATE students SET class_id=?, student_code=?, full_name=?, date_of_birth=?, email=?, gender=? WHERE id=?");
                $stmt->execute([$class_id, $student_code, $full_name, $dob, $email, $gender, $id]);
                $message = "<div class='alert alert-success'>Cập nhật thông tin thành công!</div>";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "<div class='alert alert-danger'>Lỗi: Mã SV hoặc Email đã tồn tại trong hệ thống!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Lỗi hệ thống: " . $e->getMessage() . "</div>";
            }
        }
    }
}

// --- DELETE ---
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: student_management.php?msg=deleted");
    exit;
}

// 3. READ, SEARCH & FILTER (JOIN query)
$search = $_GET['search'] ?? '';
$filter_class = $_GET['filter_class'] ?? '';

$query = "SELECT s.*, c.class_name 
          FROM students s 
          JOIN classes c ON s.class_id = c.id 
          WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (s.student_code LIKE ? OR s.full_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($filter_class)) {
    $query .= " AND s.class_id = ?";
    $params[] = $filter_class;
}

$query .= " ORDER BY s.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();

// Get classes for dropdowns
$classes = $pdo->query("SELECT id, class_name FROM classes")->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | IS School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --sidebar-color: #1e293b;
            --accent-color: #0f172a;
            --bg-color: #f8fafc;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-color);
            color: #334155;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        .main-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: white;
        }
        .table thead {
            background-color: #f1f5f9;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            border: none;
        }
        .btn-primary { background-color: var(--accent-color); border: none; padding: 0.5rem 1.25rem; border-radius: 8px; }
        .btn-primary:hover { background-color: #0a0f1a; }
        .badge-class { background-color: #dbeafe; color: #1e40af; font-weight: 600; }
        .search-box { border-radius: 8px; border: 1px solid #e2e8f0; }
        .modal-content { border: none; border-radius: 16px; }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.5rem; }
        .form-label { font-weight: 500; font-size: 0.9rem; color: #475569; }
    </style>
</head>
<body>

<nav class="navbar mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 fw-bold text-primary">TRƯỜNG QUỐC TẾ - ĐẠI HỌC QUỐC GIA HÀ NỘI</span>
        <div class="d-flex align-items-center">
            <span class="text-muted me-3 small">Chào mừng, <strong>Trang</strong></span>
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">T</div>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="d-md-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Quản lý sinh viên</h2>
            <p class="text-muted small mb-0">Hệ thống theo dõi và báo cáo học tập định kỳ.</p>
        </div>
        <button class="btn btn-primary mt-3 mt-md-0 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#studentModal" onclick="openModal()">
            <span class="me-2">+</span> Thêm sinh viên mới
        </button>
    </div>

    <?php echo $message; ?>
    <?php if(isset($_GET['msg'])) echo "<div class='alert alert-success'>Dữ liệu đã được cập nhật thành công!</div>"; ?>

    <div class="main-card p-4 mb-4">
        <form class="row g-3" method="GET">
            <div class="col-md-5">
                <label class="form-label">Tìm kiếm nhanh</label>
                <input type="text" name="search" class="form-control search-box" placeholder="Nhập tên hoặc mã sinh viên..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Lọc theo lớp học</label>
                <select name="filter_class" class="form-select search-box">
                    <option value="">-- Tất cả các lớp --</option>
                    <?php foreach($classes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filter_class == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-secondary w-100 py-2">Áp dụng bộ lọc</button>
            </div>
        </form>
    </div>

    <div class="main-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Mã sinh viên</th>
                        <th>Họ và Tên</th>
                        <th>Lớp học</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Email liên hệ</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($students)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Không tìm thấy dữ liệu sinh viên phù hợp.</td></tr>
                    <?php else: ?>
                        <?php foreach($students as $s): ?>
                        <tr>
                            <td class="ps-4"><span class="fw-bold text-dark"><?= $s['student_code'] ?></span></td>
                            <td><?= $s['full_name'] ?></td>
                            <td><span class="badge badge-class"><?= $s['class_name'] ?></span></td>
                            <td class="text-muted small"><?= date('d/m/Y', strtotime($s['date_of_birth'])) ?></td>
                            <td><?= $s['gender'] ?></td>
                            <td class="small"><?= $s['email'] ?></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light text-primary fw-medium me-1" onclick='editStudent(<?= json_encode($s) ?>)'>Sửa</button>
                                <a href="?delete=<?= $s['id'] ?>" class="btn btn-sm btn-light text-danger fw-medium" onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="fw-bold mb-0" id="modalTitle">Thêm sinh viên mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="student_id" id="student_id">
                <div class="mb-3">
                    <label class="form-label">Phân lớp học *</label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?> - <?= $c['subject_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã số sinh viên *</label>
                        <input type="text" name="student_code" id="student_code" class="form-control" required placeholder="VD: 2100xxxx">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Địa chỉ Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="name@school.edu.vn">
                </div>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" name="save_student" class="btn btn-primary px-4">Lưu dữ liệu</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openModal() {
    document.getElementById('modalTitle').innerText = "Thêm sinh viên mới";
    document.getElementById('student_id').value = "";
    document.getElementById('student_code').value = "";
    document.getElementById('full_name').value = "";
    document.getElementById('date_of_birth').value = "";
    document.getElementById('email').value = "";
    document.getElementById('gender').value = "Male";
}

function editStudent(data) {
    document.getElementById('modalTitle').innerText = "Cập nhật thông tin sinh viên";
    document.getElementById('student_id').value = data.id;
    document.getElementById('class_id').value = data.class_id;
    document.getElementById('student_code').value = data.student_code;
    document.getElementById('full_name').value = data.full_name;
    document.getElementById('date_of_birth').value = data.date_of_birth;
    document.getElementById('email').value = data.email;
    document.getElementById('gender').value = data.gender;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('studentModal')).show();
}
</script>

</body>
</html>