<?php
include 'db_connect.php';
session_start();
if (isset($_GET['id'])) {
  $qry = $conn->query("SELECT * FROM project_list where id = " . $_GET['id'])->fetch_array();
  foreach ($qry as $k => $v) {
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
                while ($row = $managers->fetch_assoc()) :
                ?>
                  <option value="<?php echo $row['id_cong_ty'] ?>"><?php echo ucwords($row['ten_cong_ty']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <?php if ($_SESSION['login_type'] == 1) : ?>
            <div class="col-md-12">
              <div class="form-group">
                <label for="" class="control-label">Khách hàng</label>
                <select class="form-control form-control-sm select2" name="idkh" id="customer_select">
                  <option></option>
                  <?php
                  $managers = $conn->query("SELECT * FROM custom order by id asc ");
                  while ($row = $managers->fetch_assoc()) :
                  ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($idkh) && $idkh == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name_ct']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
          <?php else : ?>
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
                while ($row = $status_job->fetch_assoc()) :
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
              <input type="text" class="form-control form-control-sm datepicker" autocomplete="off" name="start_date" value="<?php echo isset($start_date) ? date("Y/m/d H:i", strtotime($start_date)) : date('Y/m/d H:i') ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Ngày kết thúc</label>
              <input type="text" class="form-control form-control-sm datepicker" autocomplete="off" name="end_date" value="<?php echo isset($end_date) ? date("Y/m/d H:i", strtotime($end_date)) : date('Y/m/d H:i', strtotime('+8 hours')) ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <?php if ($_SESSION['login_type'] == 1) : ?>
            <div class="col-md-6">
              <div class="form-group">
                <label for="" class="control-label">QA</label>
                <select class="form-control form-control-sm select2" name="manager_id">
                  <option></option>
                  <?php
                  $managers = $conn->query("SELECT * FROM users where type = 5 order by id asc ");
                  while ($row = $managers->fetch_assoc()) :
                  ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['viettat']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
          <?php else : ?>
            <input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
          <?php endif; ?>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Editor</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
                <option></option>
                <?php
                $employees = $conn->query("SELECT * FROM users where type = 6 order by id asc ");
                while ($row = $employees->fetch_assoc()) :
                ?>
                  <option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'], explode(',', $user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['viettat']) ?></option>
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
  </div>
</div>


<script src="assets/plugins/ckeditor/ckeditor.js"></script>
<script src="assets/customize//js_project.js"></script>