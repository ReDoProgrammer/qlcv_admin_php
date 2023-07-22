<?php 
include'db_connect.php'; 
session_start();
$date1=date("Y-m-d");
?>
<div class="col-lg-6">
		<div class="d-flex">
  <div class="input-group mr-2">
    <div class="input-group-prepend">
      <span class="input-group-text">Từ ngày</span>
    </div>
    <input type="date" id="from_date" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
  </div>
  <div class="input-group mr-2">
    <div class="input-group-prepend">
      <span class="input-group-text">Đến ngày</span>
    </div>
    <input type="date" id="to_date" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
  </div>
  <select class="form-control form-control-sm select2" id="user_select" required <?php echo $_SESSION['login_type'] == 6?'disabled':'' ?>>
              	<option value="">---Tất cả ---</option>
              	<?php 
              	$where="where ";
              	if($_SESSION['login_type'] == 6){
              	    $where='where id='.$_SESSION['login_id'].' and ';
              	}
              	$managers = $conn->query("SELECT * FROM users $where type=6 order by id asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo $_SESSION['login_type'] == 6?'selected':'' ?>><?php echo ucwords($row['viettat']) ?></option>
              	<?php endwhile; ?>
              </select>
  <button type="button" class="btn btn-primary" id="searchBtn"> Search</button>
</div>

</div>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<!--<div class="card-tools">-->
			<!--	<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i>Thêm dự án mới</a>-->
			<!--</div>-->
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" data-toggle="table" id="list">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Tên Editor</th>
						<th>PE-Stand</th>
						<th>PE-Basic</th>
						<th>PE-Drone</th>
						<th>RE-Basic</th>
						<th>RE-Stand</th>
						<th>Re-ADV</th>
						<th>Re-exream</th>
						<th>$-PE-Basic</th>
						<th>$-PE-Stand</th>
						<th>$-PE-Drone</th>
						<th>$-RE-Basic</th>
						<th>$-RE-Stand</th>
						<th>$-RE-ADV</th>
						<th>$-RE-Extream</th>
						<th>$-Total</th>
					</tr>
				</thead >
				<tbody id="s_tasklist">
					<?php
					$i = 1;
					$where="";
              	     if($_SESSION['login_type'] == 6){
              	    $where='u.id='.$_SESSION['login_id'].' and ';
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
                                WHERE $where p.end_date BETWEEN '$date1 00:00' AND '$date1 23:59'
                                GROUP BY t.editor";
				// 	print_r($sql);
					$qry = $conn->query($sql);
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td class="text-center"><?php echo $row['viettat'] ?></td>
						<td class="text-center"><?php echo $row['pestand'] ?></td>
						<td class="text-center"><?php echo $row['pebasic'] ?></td>
						<td class="text-center"><?php echo $row['pedrone'] ?></td>
						<td class="text-center"><?php echo $row['rebasic'] ?></td>
						<td class="text-center"><?php echo $row['restand'] ?></td>
						<td class="text-center"><?php echo $row['readv'] ?></td>
						<td class="text-center"><?php echo number_format($row['reextream']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_pestand']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_pebasic']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_pedrone']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_rebasic']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_restand']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_readv']) ?></td>
						<td class="text-center"><?php echo number_format($row['price_reextream']) ?></td>
						<td class="text-center"><b><?php echo number_format($row['total_price']) ?></b></td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
    $("#searchBtn").click(function(){
        // Lấy giá trị từng input
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var user_select=$("#user_select").val();
        // Kiểm tra nếu input không rỗng thì thực hiện Ajax
        if(fromDate != "" && toDate != ""){
            $.ajax({
                url: "search_statistical.php", // Đường dẫn đến file xử lý Ajax
                type: "POST", // Phương thức gửi dữ liệu
                data: {fromDate: fromDate, toDate: toDate,user_select:user_select}, // Dữ liệu gửi lên
                dataType: "html", // Kiểu dữ liệu trả về
                success: function(result){ // Kết quả trả về từ server
                    $('#s_tasklist').html(result); // Hiển thị kết quả
                    
                }
            });
        }
    });
});
</script>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
	<script>
	    var reloadTimer = 0;
function startReloadTimer() {
  reloadTimer = setInterval(reloadCheck, 1000);
}

function reloadCheck() {
  reloadTimer++;
  if (reloadTimer >= 60) {
    clearInterval(reloadTimer);
    location.reload();
  }
}

document.addEventListener("mousemove", function(event) {
  clearInterval(reloadTimer);
  reloadTimer = 0;
});

document.addEventListener("keydown", function(event) {
  clearInterval(reloadTimer);
  reloadTimer = 0;
});

startReloadTimer();
	</script>
