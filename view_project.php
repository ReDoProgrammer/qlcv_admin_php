<?php
include 'db_connect.php';
$stat = array("Chờ","Bắt đầu","Đang làm","Tạm dừng","Quá hạn","Xong");
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}

$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)):
$status = 4;
endif;

$manager = $conn->query("SELECT *,concat(lastname,' ',firstname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();

$custom = $conn->query("SELECT * FROM custom where id = $idkh");
$custom = $custom->num_rows > 0 ? $custom->fetch_array() : array();

$sttj = $conn->query("SELECT * FROM status_job where id = $status");
$sttj = $sttj->num_rows > 0 ? $sttj->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
							    <dt><b class="border-bottom border-primary">Tên Khách hàng</b></dt>
								<dd><?php echo ucwords($custom['name_ct_mh']) ?></dd>
								<dt><b class="border-bottom border-primary">Style khách hàng</b></dt>
								<dd><?php echo html_entity_decode($custom['style']) ?></dd>
								<dt><b class="border-bottom border-primary">Tên Job</b></dt>
								<dd><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary">Intruction <button id="translate-btn"><i class="fa fa-copy"></i></button></b></dt>
								<dd id="my-dd"><?php echo html_entity_decode($description) ?></dd>
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Time In</b></dt>
								<dd><?php echo date("G:i d/m/y",strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Time Out</b></dt>
								<dd><?php echo date("G:i d/m/y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Time Out</b></dt>
								<dd><?php echo date("G:i d/m/y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Trạng thái</b></dt>
								<dd>
									  	<span class='<?php echo $sttj['color_sttj']?>'><?php echo ucwords($sttj['stt_job_name']) ?></span>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">QA</b></dt>
								<dd>
									<?php if(isset($manager['id'])) : ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b><?php echo ucwords($manager['name']) ?></b>
									</div>
									<?php else: ?>
										<small><i>Quality Assurance</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Team Editor:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php 
						if(!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(lastname,' ',firstname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
			                        <!-- <span class="users-list-date">Today</span> -->
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Danh sách việc:</b></span>
				<?php if($_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> Việc mới</button>
					</div>
				<?php endif; ?>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
					<table class="table table-condensed m-0 table-hover">
						<colgroup>
							<col width="5%">
							<col width="10%">
							<col width="10%">
							<col width="20%">
							<col width="10%">
							<col width="10%">
						</colgroup>
						<thead>
							<th>#</th>
							<th>Level</th>
							<th>Số lượng</th>
							<th>ghi chú</th>
							<th>Editor</th>
							<th>QA</th>
							<th>Thời gian nhận job</th>
							<th>Trạng thái</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$tasks = $conn->query("SELECT t.id as idtask,l.id as idlevel,l.mau_sac as mau_sac, l.name as name, t.soluong as soluong,t.date_created as date_created, t.editor as editor, t.qa as qa, t.task as task,t.status as status FROM task_list t left join level l on (l.id=t.idlevel) where project_id = {$id} order by task asc");
							while($row=$tasks->fetch_assoc()):
								$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
								unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
								$desc = strtr(html_entity_decode($row['description']),$trans);
								$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
							?>
								<tr>
			                        <td class="text-center"><?php echo $i++ ?></td>
			                        <td class="text-center"><span class="<?php echo $row['mau_sac']?>" ><?php echo ucwords($row['name']) ?></span></td>
			                        <td class="text-center"><b><?php echo ucwords($row['soluong']) ?></b></td>
			                        <td class="text-center"><?php echo ucwords($row['task']) ?></td>
			                        <?php
			                        $editor = $conn->query("SELECT * FROM users where id =". $row['editor']);
                                    $editor = $editor->num_rows > 0 ? $editor->fetch_array() : array();
			                        ?>
			                        <td class="text-center"><b><?php echo ucwords($editor['viettat']) ?></b></td>
			                        <?php
			                        $qa = $conn->query("SELECT * FROM users where id =". $row['qa']);
                                    $qa= $qa->num_rows > 0 ? $qa->fetch_array() : array();?>
                                    <td class="text-center"><b><?php echo ucwords($qa['viettat']) ?></b></td>
			                        <td class="text-center"><p><b><?php echo date("G:i",strtotime($row['date_created'])) ?></b></p></td>
			                        <?php
			                        $status = $conn->query("SELECT * FROM status_task where id =". $row['status']);
                                    $status= $status->num_rows > 0 ? $status->fetch_array() : array();?>
			                        <td>
                                    <span class="<?php echo $status['color_sttt'] ?>"><?php echo ucwords($status['stt_task_name']) ?></span>
			                        </td>
			                        <td class="text-center">
										<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
					                      Action
					                    </button>
					                    <div class="dropdown-menu" style="">
					                      <a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['idtask'] ?>"  data-task="<?php echo $row['task'] ?>">View</a>
					                      <div class="dropdown-divider"></div>
					                      <?php if($_SESSION['login_type'] != 3): ?>
					                      <a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['idtask'] ?>"  data-task="<?php echo $row['task'] ?>">Edit</a>
					                      <div class="dropdown-divider"></div>
					                      <a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['idtask'] ?>">Delete</a>
					                  <?php endif; ?>
					                    </div>
									</td>
		                    	</tr>
							<?php 
							endwhile;
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>Hoạt động/tiến độ thành viên</b>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> Thêm tiến độ</button>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$progress = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.task FROM user_productivity p inner join users u on u.id = p.user_id inner join task_list t on t.id = p.task_id where p.project_id = $id order by unix_timestamp(p.date_created) desc ");
					while($row = $progress->fetch_assoc()):
					?>
						<div class="post">

		                      <div class="user-block">
		                      	<?php if($_SESSION['login_id'] == $row['user_id']): ?>
		                      	<span class="btn-group dropleft float-right">
								  <span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								    <i class="fa fa-ellipsis-v"></i>
								  </span>
								  <div class="dropdown-menu">
								  	<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>">Edit</a>
			                      	<div class="dropdown-divider"></div>
				                     <a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
								  </div>
								</span>
								<?php endif; ?>
		                        <img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
		                        <span class="username">
		                          <a href="#"><?php echo ucwords($row['uname']) ?>[ <?php echo ucwords($row['task']) ?> ]</a>
		                        </span>
		                        <span class="description">
		                        	<span class="fa fa-calendar-day"></span>
		                        	<span><b><?php echo date('M d, Y',strtotime($row['date'])) ?></b></span>
		                        	<span class="fa fa-user-clock"></span>
                      				<span>Start: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['start_time'])) ?></b></span>
		                        	<span> | </span>
                      				<span>End: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['end_time'])) ?></b></span>
	                        	</span>

	                        	

		                      </div>
		                      <!-- /.user-block -->
		                      <div>
		                       <?php echo html_entity_decode($row['comment']) ?>
		                      </div>

		                      <p>
		                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a> -->
		                      </p>
	                    </div>
	                    <div class="post clearfix"></div>
                    <?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
	    border-radius: 50%;
	    height: 67px;
	    width: 67px;
	    object-fit: cover;
	}
	.users-list>li {
		width: 33.33% !important
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
	$('#new_task').click(function(){
		uni_modal("Thêm việc mới cho <?php echo ucwords($name) ?>","manage_task.php?pid=<?php echo $id ?>","mid-large")
	})
	$('.edit_task').click(function(){
		uni_modal("Chỉnh sửa việc: "+$(this).attr('data-task'),"manage_task.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.view_task').click(function(){
		uni_modal("Chi tiết việc","view_task.php?id="+$(this).attr('data-id'),"mid-large")
	})
	$('#new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Tiến độ mới","manage_progress.php?pid=<?php echo $id ?>",'large')
	})
	$('.manage_progress').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Sửa tiến độ","manage_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	$('.delete_progress').click(function(){
	_conf("Bạn có muốn xóa tiến độ này không?","delete_progress",[$(this).attr('data-id')])
	})
	function delete_progress($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Dữ liệu xóa thành công!",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
  $(document).ready(function() {
  // Bắt sự kiện click vào button
  $("#translate-btn").click(function() {
    // Lấy nội dung từ thẻ dd có id là my-dd
    var ddText = $("#my-dd").text();

    // Tạo một đối tượng input ẩn và đặt giá trị nội dung vào đó
    var hiddenInput = document.createElement("input");
    hiddenInput.setAttribute("type", "text");
    hiddenInput.setAttribute("value", ddText);
    hiddenInput.setAttribute("style", "position:absolute;left:-1000px");

    // Thêm đối tượng input vào trang web
    document.body.appendChild(hiddenInput);

    // Chọn nội dung của đối tượng input và copy vào clipboard
    hiddenInput.select();
    document.execCommand("copy");

    // Xóa đối tượng input khỏi trang web
    document.body.removeChild(hiddenInput);

    // Thông báo cho người dùng biết nội dung đã được sao chép vào clipboard
    window.open("https://translate.google.com/?sl=auto&tl=vi&text="+ddText);
  });
});


</script>