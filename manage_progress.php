<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM user_productivity where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
$pid = $_GET['pid'];
$tid = $_GET['tid'];

// truy vấn để lấy các tin có cùng pid và tid
$sql = "SELECT * FROM user_productivity WHERE project_id = $pid AND task_id = $tid ORDER BY id DESC";
$result = $conn->query($sql);

// hiển thị các tin

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-lg-12 comment-container">
                    <div class="user-info">
                        <?php
                        $sql1 = "SELECT * FROM users WHERE id=".$row['user_id'];
                        $result1 = $conn->query($sql1);
                        while ($row1 = $result1->fetch_assoc()) {
                        ?>
                        <span class="text-success"><?php echo $row1['viettat'] ?></span><br>
                        <?php }
                        ?>
                        <span style="color: gray;"><?php echo date('d/m/Y H:i:s', strtotime($row['date_created'])) ?></span>
                    </div>
                    <div class="comment-info">
                        <p><?php echo $row['comment'] ?></p>
                    </div>
                </div>
            <?php
            }
        }
        ?>
      <form action="" id="manage-progress">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
        <input type="hidden" name="task_id" value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : '' ?>">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Mô tả bình luận/tiến độ</label>
                <textarea name="comment" id="" cols="30" rows="10" class="summernote form-control" required="">
                  <?php echo isset($comment) ? $comment : '' ?>
                </textarea>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<style>
    .comment-container {
        display: flex;
        margin-bottom: 10px;
    }
    .user-info {
        width: 15%;
        border: 1px solid #ccc;
        background-color: #d7f7f7;
        padding: 10px;
    }
    .comment-info {
        width: 85%;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
    }
    .comment-container .user-info span {
        color: blue;
        font-weight: bold;
    }
    .comment-container .comment-info p {
        margin: 0;
    }
</style>
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
     $('.select2').select2({
	    placeholder:"Chọn...",
	    width: "100%"
	  });
     })
    $('#manage-progress').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_progress',
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
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>