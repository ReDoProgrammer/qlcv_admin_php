<?php 
include 'db_connect.php';
session_start();

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM custom where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage_custom">
	    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="">Công ty</label>
			<select class="form-control form-control-sm select2" name="id_cong_ty">
              	<option></option>
              	<?php 
              	$cong_tys = $conn->query("SELECT * FROM cong_ty order by id_cong_ty asc ");
              	while($row= $cong_tys->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id_cong_ty'] ?>" <?php echo isset($id_cong_ty) && $id_cong_ty == $row['id_cong_ty'] ? "selected" : '' ?>><?php echo ucwords($row['ten_cong_ty']) ?></option>
              	<?php endwhile; ?>
              </select>
		</div>
		<div class="form-group">
			<label for="">Tên khách hàng</label>
			<input type="text" name="name_ct" class="form-control form-control-sm" required value="<?php echo isset($name_ct) ? $name_ct : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Tên mã hóa</label>
			<input type="text" name="name_ct_mh" class="form-control form-control-sm" required value="<?php echo isset($name_ct_mh) ? $name_ct_mh : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Email</label>
			<input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>" required>
			<small id="#msg"></small>
		</div>
		<div class="form-group">
			<label for="">Link khách hàng</label>
			<input type="text" name="link_kh" class="form-control form-control-sm" required value="<?php echo isset($link_kh) ? $link_kh : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Mật khẩu</label>
			<input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required":'' ?>>
							<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password":'' ?></i></small>
		</div>
		<div class="form-group">
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
		                      <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
		                      <label class="custom-file-label" for="customFile">Choose file</label>
		                    </div>
						</div>
		<div class="form-group">
					<label for="" class="control-label">Style KH</label>
					<textarea name="style" id="" cols="30" rows="20" class="summernote form-control">
						<?php echo isset($style) ? $style : '' ?>
					</textarea>
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
			url:'ajax.php?action=save_custom',
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
						location.replace('index.php?page=custom_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>