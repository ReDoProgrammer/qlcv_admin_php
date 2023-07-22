<?php
// Thực hiện kết nối đến CSDL
include('db_connect.php');
session_start();
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$user_select = $_POST['user_select'];
// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
					$i = 1;
					$where="";
              	     if($user_select == ''){
              	         $where="";
              	     }
              	     else {
              	    $where='u.id='.$user_select.' and ';
              	     }
					
					$sql = "SELECT t.editor,u.viettat,
                               SUM(CASE WHEN idlevel = 1 THEN soluong ELSE 0 END) AS pestand,
                               SUM(CASE WHEN idlevel = 2 THEN soluong ELSE 0 END) AS pebasic,
                               SUM(CASE WHEN idlevel = 3 THEN soluong ELSE 0 END) AS pedrone,
                               SUM(CASE WHEN idlevel = 4 THEN soluong ELSE 0 END) AS restand,
                               SUM(CASE WHEN idlevel = 5 THEN soluong ELSE 0 END) AS rebasic,
                               SUM(CASE WHEN idlevel = 6 THEN soluong ELSE 0 END) AS readv,
                               SUM(CASE WHEN idlevel = 7 THEN soluong ELSE 0 END) AS reextream,
                               SUM(CASE WHEN idlevel = 1 THEN (soluong * l.dongia) ELSE 0 END) AS price_pestand,
                               SUM(CASE WHEN idlevel = 2 THEN (soluong * l.dongia) ELSE 0 END) AS price_pebasic,
                               SUM(CASE WHEN idlevel = 3 THEN (soluong * l.dongia) ELSE 0 END) AS price_pedrone,
                               SUM(CASE WHEN idlevel = 4 THEN (soluong * l.dongia) ELSE 0 END) AS price_restand,
                               SUM(CASE WHEN idlevel = 5 THEN (soluong * l.dongia) ELSE 0 END) AS price_rebasic,
                               SUM(CASE WHEN idlevel = 6 THEN (soluong * l.dongia) ELSE 0 END) AS price_readv,
                               SUM(CASE WHEN idlevel = 7 THEN (soluong * l.dongia) ELSE 0 END) AS price_reextream,
                               SUM(soluong * l.dongia) AS total_price
                                FROM task_list t
                                JOIN project_list p ON t.project_id = p.id
                                JOIN users u on u.id=t.editor
                                JOIN level l ON l.id=t.idlevel
                                WHERE $where p.end_date BETWEEN '$fromDate 00:00' AND '$toDate 23:59'
                                GROUP BY t.editor";
				// 	print_r($sql);
					$qry = $conn->query($sql);
					while($row= $qry->fetch_assoc()):
					echo "<tr>";
					echo "<td class='text-center'>".$i++ ."</td>";
					echo "	<td class='text-center'>".$row['viettat'] ."</td>";
					echo "	<td class='text-center'>".$row['pestand'] ."</td>";
					echo "	<td class='text-center'>".$row['pebasic'] ."</td>";
					echo "	<td class='text-center'>".$row['pedrone'] ."</td>";
					echo "	<td class='text-center'>".$row['rebasic'] ."</td>";
					echo "	<td class='text-center'>".$row['restand'] ."</td>";
					echo "	<td class='text-center'>".$row['readv'] ."</td>";
					echo "	<td class='text-center'>".number_format($row['reextream']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_pestand']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_pebasic']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_pedrone']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_rebasic']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_restand']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_readv']) ."</td>";
					echo "	<td class='text-center'>".number_format($row['price_reextream']) ."</td>";
					echo "	<td class='text-center'><b>".number_format($row['total_price']) ."</b></td>";
					echo "</tr>	";
				endwhile;
mysqli_close($conn);
?>
