<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../inc/connection.php';
$conn = dbconnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_no = $_POST['emp_no'] ?? '';
    $dept_no = $_POST['dept_no'] ?? '';
    $from_date = $_POST['from_date'] ?? '';

    if (empty($emp_no) || empty($dept_no) || empty($from_date)) {
        header("Location: ../pages/fiche_employee.php?emp_no=$emp_no&error=missing");
        exit;
    }

    $emp_no = intval($emp_no);
    $dept_no = mysqli_real_escape_string($conn, $dept_no);
    $from_date = mysqli_real_escape_string($conn, $from_date);

    $check = mysqli_query($conn, "SELECT * FROM dept_emp WHERE emp_no = $emp_no AND dept_no = '$dept_no'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: ../pages/fiche_employee.php?emp_no=$emp_no&error=duplicate");
        exit;
    }

    $date_fin = date('Y-m-d', strtotime($from_date . ' -1 day'));
    mysqli_query($conn, "UPDATE dept_emp 
                         SET to_date = '$date_fin' 
                         WHERE emp_no = $emp_no AND to_date = '9999-01-01'");

    mysqli_query($conn, "INSERT INTO dept_emp (emp_no, dept_no, from_date, to_date)
                         VALUES ($emp_no, '$dept_no', '$from_date', '9999-01-01')");

    header("Location: ../pages/fiche_employee.php?emp_no=$emp_no&success=1");
    exit;

} else {
    echo "Méthode non autorisée.";
}
?>
