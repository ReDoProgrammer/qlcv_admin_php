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
  <button type="button" class="btn btn-primary" id="searchBtn">Search</button>
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
					<col width="30%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Tên job</th>
						<th>Ghi chú</th>
						<th>Time in</th>
						<th>Time out</th>
						<th>Level</th>
						<th>Số lượng</th>
						<th>Editor</th>
						<th>QA</th>
						<th>TT Task</th>
						<th>TT Job</th>
						<th>Action</th>
					</tr>
				</thead >
				<tbody id="s_tasklist">
					<?php
					$i = 1;
					$where = "where ";
					if($_SESSION['login_type'] == 5){
						$where = "where p.manager_id = '{$_SESSION['login_id']}' and ";
					}elseif($_SESSION['login_type'] == 6){
						$where = "where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' and ";
					}
					
					$stat = array("Chờ","Bắt đầu","Đang làm","Tạm dừng","Quá hạn","Xong");
					$sq="SELECT t.*, p.name AS pname, p.start_date, p.status AS pstatus, t.status AS tstatus, p.end_date, p.id AS pid, p.idkh as idkh FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $where (t.status != '7' OR DATE(p.end_date) BETWEEN '$date1 00:00' AND '$date1 23:59') ORDER BY t.status ASC, p.end_date DESC ";
				// 	print_r($sq);
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
		              //  if($row['pstatus'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
		              //  if($prod  > 0  || $cprog > 0)
		              //    $row['pstatus'] = 2;
		              //  else
		              //    $row['pstatus'] = 1;
		              //  elseif($row['pstatus'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
		              //  $row['pstatus'] = 4;
		              //  endif;
		                
		                $custom = $conn->query("SELECT * FROM custom where id = {$row['idkh']}");
                        $custom = $custom->num_rows > 0 ? $custom->fetch_array() : array();
                        
                        $level = $conn->query("SELECT *  FROM level where id = {$row['idlevel']}");
                        $level = $level->num_rows > 0 ? $level->fetch_array() : array();
                        
                        $editor = $conn->query("SELECT *  FROM users where id = {$row['editor']}");
                        $editor = $editor->num_rows > 0 ? $editor->fetch_array() : array();
                        
                        $qa = $conn->query("SELECT *  FROM users where id = {$row['qa']}");
                        $qa = $qa->num_rows > 0 ? $qa->fetch_array() : array();


					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td>
							<p><b><?php echo ucwords($row['pname']) ?></b></p>
							<p class="truncate"><dd>«<?php echo ucwords($custom['name_ct_mh']) ?>»</dd></p>
						</td>
						<td>
							<p><b><?php echo ucwords($row['task']) ?></b></p>
							<p class="truncate"><?php echo strip_tags($desc) ?></p>
						</td>
						<td><p><b><?php echo date("G:i",strtotime($row['start_date'])) ?></b></p> <p class="truncate"><?php echo date("d/m/Y",strtotime($row['start_date'])) ?></p></td>
						<td><p><b><?php echo date("G:i",strtotime($row['end_date'])) ?></b></p><p><?php echo date("d/m/Y",strtotime($row['end_date'])) ?></p></td>
						<td class="text-center"><b class="<?php echo $level['mau_sac']?>"><?php echo ucwords($level['name']) ?></b></td>
						<td class="text-center"><b><?php echo ucwords($row['soluong']) ?></b></td>
                        <td class="text-center"><b><?php echo ucwords($editor['viettat']) ?></b></td>
                        <td class="text-center"><b><?php echo ucwords($qa['viettat']) ?></b></td>
                        <?php
                        $status_t = $conn->query("SELECT *  FROM status_task where id = {$row['tstatus']}");
                        $status_t = $status_t->num_rows > 0 ? $status_t->fetch_array() : array();
                        ?>
						<td class="text-center">
						<span class="<?php echo $status_t['color_sttt'] ?>"><?php echo $status_t['stt_task_name'] ?></span>
						</td>
						<?php
                        $status_j = $conn->query("SELECT *  FROM status_job where id = {$row['pstatus']}");
                        $status_j = $status_j->num_rows > 0 ? $status_j->fetch_array() : array();
                        ?>
						<td class="text-center">
						<span class="<?php echo $status_j['color_sttj'] ?>"><?php echo $status_j['stt_job_name'] ?></span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
			                    <div class="dropdown-menu" style="">
			                      <a class="dropdown-item new_productivity" data-pid = '<?php echo $row['pid'] ?>' data-tid = '<?php echo $row['id'] ?>'  data-task = '<?php echo ucwords($row['task']) ?>'  href="javascript:void(0)">Thêm ghi chú</a>
			                      
                                    <a class="dropdown-item new_task" data-pid="<?php echo $row['pid'] ?>" data-tid="<?php echo $row['id'] ?>" data-task="<?php echo ucwords($row['task']) ?>" data-name="<?php echo ucwords($row['pname']) ?>" href="javascript:void(1)">Thêm task</a>
                                    <a class="dropdown-item get_task" data-pid="<?php echo $row['pid'] ?>" data-tid="<?php echo $row['id'] ?>" data-task="<?php echo ucwords($row['status']) ?>" href="javascript:void(0)">Update status</a>
                                
			                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	    $(document).on('click', '.new_productivity', function(){
		uni_modal("<i class='fa fa-plus'></i> New Progress for: "+$(this).attr('data-task'),"manage_progress.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
	})
	    $(document).on('click', '.new_task', function(){
		//uni_modal("<i class='fa fa-plus'></i> New task for: "+$(this).attr('data-task'),"manage_task.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
		uni_modal("Thêm task mới: " +$(this).attr('data-name'), "manage_task.php?pid="+$(this).attr('data-pid'),"mid-large")
	})
	    $(document).on('click', '.get_task', function(){
		//uni_modal("<i class='fa fa-plus'></i> New task for: "+$(this).attr('data-task'),"manage_task.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
		uni_modal("Get status task: ", "manage_gettask.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid')+"&stu="+$(this).attr('data-task'),"mid-large")
	})
	})
	function delete_project($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_project',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>
<script>
$(document).ready(function(){
    $("#searchBtn").click(function(){
        // Lấy giá trị từng input
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        
        // Kiểm tra nếu input không rỗng thì thực hiện Ajax
        if(fromDate != "" && toDate != ""){
            $.ajax({
                url: "search_tasklist.php", // Đường dẫn đến file xử lý Ajax
                type: "POST", // Phương thức gửi dữ liệu
                data: {fromDate: fromDate, toDate: toDate}, // Dữ liệu gửi lên
                dataType: "html", // Kiểu dữ liệu trả về
                success: function(result){ // Kết quả trả về từ server
                    $('#s_tasklist').html(result); // Hiển thị kết quả

                    // Khởi tạo lại plugin Bootstrap Table với dữ liệu mới
                    $('#list').bootstrapTable('destroy').bootstrapTable({
                        data: JSON.parse(result)
                    });
                }
            });
        }
    });
});

	</script>
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