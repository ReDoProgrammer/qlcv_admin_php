<?php 
include 'db_connect.php';
session_start();
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Ghi chú</label>
			<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
		</div>
		<!--<div class="form-group">-->
		<!--	<label for="">Ghi chú</label>-->
		<!--	<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">-->
		<!--		<?php echo isset($description) ? $description : '' ?>-->
		<!--	</textarea>-->
		<!--</div>-->
		<div class="form-group">
			<label for="">Level</label>
			<select class="form-control form-control-sm select2" name="idlevel">
              	<option></option>
              	<?php 
              	$levels = $conn->query("SELECT * FROM level order by id asc ");
              	while($row= $levels->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($idlevel) && $idlevel == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
              	<?php endwhile; ?>
              </select>
		</div>
		<div class="form-group">
			<label for="">Người QA</label>
			<select class="form-control form-control-sm select2" name="qa" <?php echo $_SESSION['login_type']==6 ? 'disabled' : '' ?>>
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT *  FROM users where type = 5 order by id asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($qa) && $qa == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['viettat']) ?></option>
              	<?php endwhile; ?>
              </select>
		</div>
		<div class="form-group">
			<label for="">Editor</label>
			<select class="form-control form-control-sm select2" name="editor">
              	
              	<?php 
              	$edit='';
              	if($_SESSION['login_type']==6)
              	{
              	    $edit=" and id='".$_SESSION['login_id']."'";
              	}
              	else
              	{
              	   ?><option></option>
              	   <?php
              	   $edit='';
              	}
              	$employees = $conn->query("SELECT * FROM users where type = 6 ".$edit." order by id asc ");
              	while($row= $employees->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($editor) && in_array($row['id'],explode(',',$editor)) ? "selected" : '' ?>><?php echo ucwords($row['viettat']) ?></option>
              	<?php endwhile; ?>
              </select>
		</div>
		<div class="form-group">
			<label for="">Số lượng ảnh</label>
			<input type="number" class="form-control form-control-sm" id="soluong" name="soluong" value="<?php echo isset($soluong) ? $soluong : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Trạng thái</label>
			<select name="status" id="status" class="custom-select custom-select-sm">
			    <option value="">----chọn trạng thái-----</option>
				<?php 
				$quyenstt="";
				if($_SESSION['login_type']==6)
				{
				    $quyenstt="where id in (1,3)";
				}
				if($_SESSION['login_type']==5)
				{
				    $quyenstt="where id in (2,4)";
				}
              	$managers = $conn->query("SELECT *  FROM status_task ".	$quyenstt." order by id asc ");
              	while($row= $managers->fetch_assoc()):
              	    ?>
              	    <option value="<?php echo $row['id'] ?>" <?php echo isset($status) && $status == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['stt_task_name']) ?></option>
              	<?php endwhile; ?>
              	?>
			</select>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){


	$('.summernote').summernote({
        height: 200,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    })
     })
    
    $('#manage-task').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_task',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Dữ liệu lưu thành công',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>