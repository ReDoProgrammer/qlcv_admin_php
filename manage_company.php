<?php 
include 'db_connect.php';
session_start();

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM cong_ty where id_cong_ty = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage_custom">
	    <input type="hidden" name="id_cong_ty" value="<?php echo isset($id_cong_ty) ? $id_cong_ty : '' ?>">
		<div class="form-group">
			<label for="">Tên công ty</label>
			<input type="text" name="ten_cong_ty" class="form-control form-control-sm" required value="<?php echo isset($ten_cong_ty) ? $ten_cong_ty : '' ?>" required>
		</div>
		<div class="form-group">
					<label for="" class="control-label">Mô tả</label>
					<textarea name="mo_ta_ct" id="" cols="30" rows="20" class="summernote form-control">
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
			url:'ajax.php?action=save_company',
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
						location.replace('index.php?page=company_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Trùng tên công ty.</div>");
					$('[ten_cong_ty="ten_cong_ty"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>