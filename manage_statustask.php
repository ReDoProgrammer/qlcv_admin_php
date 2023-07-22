<?php 
include 'db_connect.php';
session_start();

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM status_task where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage_custom">
	    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="">Tên status</label>
            <input type="text" name="stt_task_name" class="form-control form-control-sm" required value="<?php echo isset($stt_task_name) ? $stt_task_name : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Màu sắc</label>
			<input type="text" name="color_sttt" class="form-control form-control-sm" required value="<?php echo isset($color_sttt) ? $color_sttt : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Group</label>
			<input type="text" name="group_sttt" class="form-control form-control-sm" required value="<?php echo isset($group_sttt) ? $group_sttt : '' ?>" required>
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
    
    $('#manage_custom').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_sttt',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Lưu dữ liệu thành công.',"success");
					setTimeout(function(){
						location.replace('index.php?page=status_task_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Đã có status này.</div>");
					$('[name="name"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>