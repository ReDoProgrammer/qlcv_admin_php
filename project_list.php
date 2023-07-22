<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php if($_SESSION['login_type'] <= 5): ?>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_project" href="javascript:void(0)"><i class="fa fa-plus"></i> Thêm dự án mới</a>
			</div>
            <?php endif; ?>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="35%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Khách hàng</th>
						<th>Dự án</th>
						<th>Bắt đầu</th>
						<th>kết thúc</th>
						<th>Trạng thái</th>
						<th>Hoạt động</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$stat = array("Chờ","Bắt đầu","Đang làm","Tạm dừng","Quá hạn","Xong");
					$where = "";
					if($_SESSION['login_type'] == 5){
						$where = " where manager_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 6){
						$where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
					}
					
					$qry = $conn->query("SELECT * FROM project_list $where order by end_date desc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);

					 	$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
						if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
						if($prod  > 0  || $cprog > 0)
		                  $row['status'] = 2;
		                else
		                  $row['status'] = 1;
						elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
						$row['status'] = 4;
						endif;
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td>
						    <?php
						    $prkh = $conn->query("SELECT name_ct_mh FROM custom where id = {$row['idkh']}");
						    while($rowkh= $prkh->fetch_assoc()):
						    ?>
							<p><b><?php echo ucwords($rowkh['name_ct_mh']) ?></b></p>
							<?php endwhile; ?>
							<!--<p class="truncate">
							
							</p>-->
						</td>
						<td>
							<p><b><?php echo ucwords($row['name']) ?></b></p>
						</td>
						<td><p><b><?php echo date("G:i",strtotime($row['start_date'])) ?></b></p> <p class="truncate"><?php echo date("d/m/Y",strtotime($row['start_date'])) ?></p></td>
						<td><p><b><?php echo date("G:i",strtotime($row['end_date'])) ?></b></p><p><?php echo date("d/m/Y",strtotime($row['end_date'])) ?></p></td>
						<td class="text-center">
                        <span class='<?php echo $row['color_sttj'];?>'><?php echo ucwords($row['stt_job_name'])?></span>
                        
                        	<?php 
							$sttj = $conn->query("SELECT * from status_job where id=".$row['status']);
							while($rowst=$sttj->fetch_assoc()):
						?>
								<span class='<?php echo $rowst['color_sttj']?>'><?php echo ucwords($rowst['stt_job_name']) ?></span>
						<?php 
							endwhile;
						?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">Xem</a>
		                      <div class="dropdown-divider"></div>
		                      <?php if($_SESSION['login_type'] != 6): ?>
		                      <a class="dropdown-item edit_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-project="<?php echo $row['name'] ?>">Sửa</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xóa</a>
		                  <?php endif; ?>
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
	    $(document).on('click', '.delete_project', function(){
	_conf("Are you sure to delete this project?","delete_project",[$(this).attr('data-id')])
	})
	$('.new_project').click(function(){
		uni_modal("Thêm mới Project", "manage_project.php","mid-large")
	})
	     $(document).on('click', '.edit_project', function(){
		uni_modal("Chỉnh sửa Project: "+$(this).attr('data-custom'),"manage_project.php?id="+$(this).attr('data-id'),"mid-large")
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