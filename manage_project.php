<?php 
include 'db_connect.php';
session_start();
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
    foreach($qry as $k => $v){
	$$k = $v;
}
}
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-project">

        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
           <div class="col-md-12">
            <div class="form-group">
              <label for="" class="control-label">Công ty</label>
              <select class="form-control form-control-sm select2" id="company_select">
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT * FROM cong_ty order by id_cong_ty asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id_cong_ty'] ?>"><?php echo ucwords($row['ten_cong_ty']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
        	<?php if($_SESSION['login_type'] == 1 ): ?>
           <div class="col-md-12">
            <div class="form-group">
              <label for="" class="control-label">Khách hàng</label>
              <select class="form-control form-control-sm select2" name="idkh" id="customer_select">
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT * FROM custom order by id asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($idkh) && $idkh == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name_ct']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
      <?php else: ?>
      	<input type="hidden" name="idkh" value="<?php echo $_SESSION['login_id'] ?>">
      <?php endif; ?>
        </div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="" class="control-label">Tên</label>
					<input type="text" class="form-control form-control-sm" name="name" value="<?php echo isset($name) ? $name : '' ?>">
				</div>
			</div>
          	<div class="col-md-6">
				<div class="form-group">
					<label for="">Trạng thái</label>
					<select name="status" id="status" class="custom-select custom-select-sm">
									<?php 
                                  	$status_job = $conn->query("SELECT * FROM status_job order by id asc");
                                  	while($row= $status_job->fetch_assoc()):
                                  	?>
                                  	<option value="<?php echo $row['id'] ?>" <?php echo isset($type) && $type == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['stt_job_name']) ?></option>
                                  	<?php endwhile; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Ngày bắt đầu</label>
              <input type="text" class="form-control form-control-sm datepicker" autocomplete="off" name="start_date" value="<?php echo isset($start_date) ? date("Y/m/d H:i",strtotime($start_date)) : date('Y/m/d H:i') ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Ngày kết thúc</label>
              <input type="text" class="form-control form-control-sm datepicker" autocomplete="off" name="end_date" value="<?php echo isset($end_date) ? date("Y/m/d H:i",strtotime($end_date)) : date('Y/m/d H:i', strtotime('+8 hours')) ?>">
            </div>
          </div>
		</div>
        <div class="row">
        	<?php if($_SESSION['login_type'] == 1 ): ?>
           <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">QA</label>
              <select class="form-control form-control-sm select2" name="manager_id">
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT * FROM users where type = 5 order by id asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['viettat']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
      <?php else: ?>
      	<input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
      <?php endif; ?>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Editor</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
              	<option></option>
              	<?php 
              	$employees = $conn->query("SELECT * FROM users where type = 6 order by id asc ");
              	while($row= $employees->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'],explode(',',$user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['viettat']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="" class="control-label">Mô tả</label>
					<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
						<?php echo isset($description) ? $description : '' ?>
					</textarea>
				</div>
			</div>
		</div>
        </form>
    	</div>
    	<!--<div class="card-footer border-top border-info">-->
    	<!--	<div class="d-flex w-100 justify-content-center align-items-center">-->
    	<!--		<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project">Lưu</button>-->
    	<!--		<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancel</button>-->
    	<!--	</div>-->
    	<!--</div>-->
	</div>
</div>
<script>
$(document).ready(function() {
  $('.select2').select2();
});
</script>
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
    
	$('#manage-project').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_project',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Lưu dữ liệu thành công',"success");
					setTimeout(function(){
						location.href = 'index.php?page=project_list'
					},2000)
				}
			}
		})
	})
</script>
<script>
// Activate datepicker on all inputs with class "datepicker"
$(document).ready(function() {
  $('.datepicker').datetimepicker({
    dateFormat: 'yy/mm/dd', // format of date
    timeFormat: 'HH:mm', // format of time
    showButtonPanel: true, // show button panel
    showTimezone: false, // hide timezone picker
    controlType: 'select', // control type for time
    oneLine: true // display in one line
  });
});

</script>
<script>
  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');
  // Lắng nghe sự kiện change trên start_date
  startDateInput.addEventListener('change', function() {
    // Tính toán giá trị mới cho end_date bằng cách thêm 8 giờ vào giá trị của start_date
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(startDate.getTime() + 8 * 60 * 60 * 1000);
    const endDateISOString = endDate.toISOString().slice(0, 16);

    // Cập nhật giá trị của end_date
    endDateInput.value = endDateISOString;
  });
</script>
<script>
$(document).ready(function() {
  // Khi chọn công ty, thực hiện AJAX request để lấy danh sách khách hàng
$('#company_select').change(function() {
    var company_id = $(this).val();
    $.ajax({
        url: 'get_customers.php',
        type: 'GET',
        data: { company_id: company_id },
        dataType: 'json',
        success: function(data) {
            // Xóa danh sách khách hàng cũ
            $('#customer_select').empty();
            // Thêm danh sách khách hàng mới
            $.each(data, function(index, customer) {
                $('#customer_select').append('<option value="' + customer.id + '">' + customer.name + '</option>');
            });
        },
        error: function() {
            // Hiển thị thông báo nếu có lỗi xảy ra
            alert('An error occurred while fetching customers.');
        }
    });
});

});
</script>