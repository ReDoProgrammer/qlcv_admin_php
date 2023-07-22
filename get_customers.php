<?php
// Thực hiện kết nối đến CSDL
include('db_connect.php');

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy giá trị của select công ty được chọn
$company_id = $_GET['company_id'];

// Tạo truy vấn để lấy danh sách khách hàng của công ty được chọn
$sql = "SELECT id, name_ct,name_ct_mh FROM custom WHERE id_cong_ty = " . $company_id;

// Thực hiện truy vấn
$result = mysqli_query($conn, $sql);

// Tạo một mảng chứa thông tin của các khách hàng
$customers = array();
while($row = mysqli_fetch_assoc($result)) {
    $customers[] = array(
        'id' => $row['id'],
        'name' => $row['name_ct'],
        'name_mh' => $row['name_ct_mh']
    );
}

// Đóng kết nối đến CSDL
mysqli_close($conn);

// Chuyển đổi danh sách khách hàng thành chuỗi JSON
echo json_encode($customers);
?>
