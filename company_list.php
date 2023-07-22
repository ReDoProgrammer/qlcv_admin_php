<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_company" href="javascript:void(0)"><i class="fa fa-plus"></i> Thêm company</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">ID</th>
						<th>Company name</th>
						<th>Mô tả</th>
						<th>Người tạo</th>
						<th>Ngày tạo</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					//$type = array('',"Admin","Quản lý dự án","Nhân viên");
					$qry = $conn->query("SELECT * FROM cong_ty order by id_cong_ty");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['id_cong_ty'] ?></th>
						<td><b><?php echo ucwords($row['ten_cong_ty']) ?></b></td>
						<td><b><?php echo $row['mo_ta'] ?></b></td>
						<td><b><?php echo $row['nguoi_tao'] ?></b></td>
						<td><b><?php echo $row['ngay_tao'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_company" href="javascript:void(0)" data-id="<?php echo $row['id_cong_ty'] ?>">Xem</a>
		                      <div class="dropdown-divider"></div>
		                      <!--<a class="dropdown-item" href="javascript:void(0)">Sửa</a>-->
		                      <a class="dropdown-item edit_company" href="javascript:void(0)" data-id="<?php echo $row['id_cong_ty'] ?>" data-company="<?php echo $row['ten_cong_ty'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_company" href="javascript:void(0)" data-id="<?php echo $row['id_cong_ty'] ?>">Xóa</a>
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
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_company.php?id="+$(this).attr('data-id'))
	})
	$('.delete_company').click(function(){
	_conf("Are you sure to delete this company?","delete_company",[$(this).attr('data-id')])
	})
	$('.new_company').click(function(){
		//uni_modal("<i class='fa fa-plus'></i> New task for: "+$(this).attr('data-task'),"manage_task.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
		uni_modal("Thêm công ty mới", "manage_company.php","mid-large")
	})
	$('.edit_company').click(function(){
		uni_modal("Chỉnh sửa công ty: "+$(this).attr('data-company'),"manage_company.php?id="+$(this).attr('data-id'),"mid-large")
	})
	})
	function delete_company($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_company',
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