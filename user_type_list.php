<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_custom" href="javascript:void(0)"><i class="fa fa-plus"></i> Thêm khách hàng</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Công ty</th>
						<th>Tên</th>
						<th>Email</th>
						<th>Style</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					//$type = array('',"Admin","Quản lý dự án","Nhân viên");
					$qry = $conn->query("SELECT *,concat(lastname,' ',firstname) as name FROM custom order by id_cong_ty asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td>Bamboophoto</td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td><b><?php echo $row['style'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_custom" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xem</a>
		                      <div class="dropdown-divider"></div>
		                      <!--<a class="dropdown-item" href="javascript:void(0)">Sửa</a>-->
		                      <a class="dropdown-item edit_custom" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-custom="<?php echo $row['name'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_custom" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Xóa</a>
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
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_custom.php?id="+$(this).attr('data-id'))
	})
	$('.delete_custom').click(function(){
	_conf("Are you sure to delete this custom?","delete_custom",[$(this).attr('data-id')])
	})
	$('.new_custom').click(function(){
		//uni_modal("<i class='fa fa-plus'></i> New task for: "+$(this).attr('data-task'),"manage_task.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
		uni_modal("Thêm khách hàng mới", "manage_custom.php","mid-large")
	})
	$('.edit_custom').click(function(){
		uni_modal("Chỉnh sửa khách hàng: "+$(this).attr('data-custom'),"manage_custom.php?id="+$(this).attr('data-id'),"mid-large")
	})
	})
	function delete_custom($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_custom',
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