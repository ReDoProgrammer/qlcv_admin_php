<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_user_type" href="javascript:void(0)"><i class="fa fa-plus"></i> Thêm loại nhân viên</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">ID</th>
						<th>Tên loại</th>
						<th>nhóm</th>
						<th>Ngày tạo</th>
						<th>Người tạo</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//$type = array('',"Admin","Quản lý dự án","Nhân viên");
					$qry = $conn->query("SELECT * FROM user_type order by id asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['id'] ?></th>
						<td><b><?php echo ucwords($row['name_ut']) ?></b></td>
						<td><b><?php echo $row['group_ut'] ?></b></td>
						<td><b><?php echo $row['ngay_tao_ut'] ?></b></td>
						<td><b><?php echo $row['nguoi_tao_ut'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_user_type" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xem</a>
		                      <div class="dropdown-divider"></div>
		                      <!--<a class="dropdown-item" href="javascript:void(0)">Sửa</a>-->
		                      <a class="dropdown-item edit_user_type" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-user_type="<?php echo $row['name_ut'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_user_type" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xóa</a>
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
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_user_type.php?id="+$(this).attr('data-id'))
	})
	$('.delete_user_type').click(function(){
	_conf("Are you sure to delete this user_type?","delete_user_type",[$(this).attr('data-id')])
	})
	$('.new_user_type').click(function(){
		//uni_modal("<i class='fa fa-plus'></i> New task for: "+$(this).attr('data-task'),"manage_task.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
		uni_modal("Thêm loại nhân viên mới", "manage_staff_type.php","mid-large")
	})
	$('.edit_user_type').click(function(){
		uni_modal("Chỉnh sửa loại nhân viên: "+$(this).attr('data-user_type'),"manage_staff_type.php?id="+$(this).attr('data-id'),"mid-large")
	})
	})
	function delete_user_type($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user_type',
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