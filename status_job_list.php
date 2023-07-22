<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_statusjob" href="javascript:void(0)"><i class="fa fa-plus"></i> Thêm trạng thái JOB</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">ID</th>
						<th>Tên loại JOB</th>
						<th>Màu sắc</th>
						<th>Nhóm</th>
						<th>Người tạo</th>
						<th>Ngày tạo</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//$type = array('',"Admin","Quản lý dự án","Nhân viên");
					$qry = $conn->query("SELECT * FROM status_job order by id asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['id'] ?></th>
						<td><b><?php echo ucwords($row['stt_job_name']) ?></b></td>
						<td><b  class='<?php echo $row['color_sttj']?>'><?php echo $row['color_sttj'] ?></b></td>
						<td><b><?php echo $row['group_sttj'] ?></b></td>
						<td><b><?php echo $row['ngay_tao_sttj'] ?></b></td>
						<td><b><?php echo $row['nguoi_tao_sttj'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_statusjob" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xem</a>
		                      <div class="dropdown-divider"></div>
		                      <!--<a class="dropdown-item" href="javascript:void(0)">Sửa</a>-->
		                      <a class="dropdown-item edit_statusjob" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-statusjob="<?php echo $row['stt_job_name'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_statusjob" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xóa</a>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.view_user').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_statusjob.php?id="+$(this).attr('data-id'))
	})
	$('.delete_statusjob').click(function(){
	_conf("Are you sure to delete this statusjob?","delete_statusjob",[$(this).attr('data-id')])
	})
	$('.new_statusjob').click(function(){
		//uni_modal("<i class='fa fa-plus'></i> New task for: "+$(this).attr('data-task'),"manage_task.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
		uni_modal("Thêm trạng thái job mới", "manage_statusjob.php","mid-large")
	})
	$('.edit_statusjob').click(function(){
		uni_modal("Chỉnh sửa trạng thái job: "+$(this).attr('data-statusjob'),"manage_statusjob.php?id="+$(this).attr('data-id'),"mid-large")
	})
	})
	function delete_statusjob($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_statusjob',
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