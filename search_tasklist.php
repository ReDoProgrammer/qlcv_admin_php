<?php
// Thực hiện kết nối đến CSDL
include('db_connect.php');
session_start();
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
					$i = 1;
					$where = "where ";
					if($_SESSION['login_type'] == 5){
						$where = "where p.manager_id = '{$_SESSION['login_id']}' and ";
					}elseif($_SESSION['login_type'] == 6){
						$where = "where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' and ";
					}
					
					$stat = array("Chờ","Bắt đầu","Đang làm","Tạm dừng","Quá hạn","Xong");
					$sq="SELECT t.*, p.name AS pname, p.start_date, p.status AS pstatus, t.status AS tstatus, p.end_date, p.id AS pid FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $where (t.status != '7' OR DATE(p.end_date) BETWEEN '$fromDate 00:00' AND '$toDate 23:59') ORDER BY t.status ASC, p.end_date DESC ";
					$qry = $conn->query($sq);
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
						$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['pid']}")->num_rows;
		                $custom = $conn->query("SELECT *,concat(lastname,' ',firstname) as name FROM custom where id = {$row['pid']}");
                        $custom = $custom->num_rows > 0 ? $custom->fetch_array() : array();
                        
                        $level = $conn->query("SELECT *  FROM level where id = {$row['idlevel']}");
                        $level = $level->num_rows > 0 ? $level->fetch_array() : array();
                        
                        $editor = $conn->query("SELECT *  FROM users where id = {$row['editor']}");
                        $editor = $editor->num_rows > 0 ? $editor->fetch_array() : array();
                        
                        $qa = $conn->query("SELECT *  FROM users where id = {$row['qa']}");
                        $qa = $qa->num_rows > 0 ? $qa->fetch_array() : array();
					print_r($sq);
						echo "<tr><td class='text-center'>".$i++."</td>";
						echo "<td>";
						echo "<p><b>". ucwords($row['pname']) ."</b></p>";
						echo "<p class='truncate'>".ucwords($custom['name_ct_mh'])."</p>";
						echo "</td>";
						
						echo "<td><p><b>".ucwords($row['task'])."</b></p>";
						echo "<p class='truncate'>".strip_tags($desc)."</p>";
						echo "</td>";
						echo "<td><p><b>". date("G:i",strtotime($row['start_date']))."</b></p> <p class='truncate'>".date("d/m/Y",strtotime($row['start_date'])) ."</p></td>";
						echo "<td><p><b>". date("G:i",strtotime($row['end_date']))."</b></p> <p class='truncate'>".date("d/m/Y",strtotime($row['end_date'])) ."</p></td>";
						echo "<td class='text-center'><b class='".$level['mau_sac']."'>".ucwords($level['name'])."</b></td>";
						echo "<td class='text-center'><b>".ucwords($row['soluong'])."</b></td>";
                        echo "<td class='text-center'><b>".ucwords($editor['viettat'])."</b></td>";
                        echo "<td class='text-center'><b>". ucwords($qa['viettat'])."</b></td>";
                        $status_t = $conn->query("SELECT *  FROM status_task where id = {$row['tstatus']}");
                        $status_t = $status_t->num_rows > 0 ? $status_t->fetch_array() : array();
						echo "<td class='text-center'>";
						echo "<span class='". $status_t['color_sttt']."'>". $status_t['stt_task_name']."</span>";
						echo "</td>";
                        $status_j = $conn->query("SELECT *  FROM status_job where id = {$row['pstatus']}");
                        $status_j = $status_j->num_rows > 0 ? $status_j->fetch_array() : array();
                        
						echo "<td class='text-center'>";
						echo "<span class='".$status_j['color_sttj'] ."'>".$status_j['stt_job_name']."</span>";
						echo "</td>";
						echo "<td class='text-center'>";
							echo "<button type='button' class='btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle' data-toggle='dropdown' aria-expanded='true'>";
		                      echo "Action";
		                    echo "</button>";
			                    echo "<div class='dropdown-menu' style=''>";
			                      echo "<a class='dropdown-item new_productivity' data-pid = '".$row['pid']."' data-tid = '".$row['id']."'  data-task = '".ucwords($row['task'])."'  href='javascript:void(0)'>Thêm ghi chú</a>";
			                        
			                      echo "<a class='dropdown-item new_task' data-pid = '".$row['pid'] ."' data-tid = '".$row['id']."'  data-task = '".ucwords($row['task'])."' data-name = '".ucwords($row['pname'])."'  href='javascript:void(1)'>Thêm task</a>";
			                      echo "<a class='dropdown-item get_task' data-pid = '".$row['pid']."' data-tid = '".$row['id'] ."'  data-task = '".ucwords($row['status'])."'  href='javascript:void(0)'>Update status</a>";
			                      
			                      echo "</div>";
			                      
						echo "</td>";
					echo "</tr>";
				    endwhile;
mysqli_close($conn);
?>
