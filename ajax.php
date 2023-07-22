<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}

if($action == 'save_sttj'){
	$save = $crud->save_sttj();
	if($save)
		echo $save;
}

if($action == 'update_sttj'){
	$save = $crud->update_sttj();
	if($save)
		echo $save;
}
if($action == 'delete_sttj'){
	$save = $crud->delete_sttj();
	if($save)
		echo $save;
}


if($action == 'update_sttt'){
	$save = $crud->update_sttt();
	if($save)
		echo $save;
}
if($action == 'delete_sttt'){
	$save = $crud->delete_sttt();
	if($save)
		echo $save;
}
if($action == 'save_sttt'){
	$save = $crud->save_sttt();
	if($save)
		echo $save;
}

if($action == 'save_custom'){
	$save = $crud->save_custom();
	if($save)
		echo $save;
}

if($action == 'update_custom'){
	$save = $crud->update_custom();
	if($save)
		echo $save;
}
if($action == 'delete_custom'){
	$save = $crud->delete_custom();
	if($save)
		echo $save;
}
if($action == 'save_level'){
	$save = $crud->save_level();
	if($save)
		echo $save;
}
if($action == 'update_level'){
	$save = $crud->update_level();
	if($save)
		echo $save;
}
if($action == 'delete_level'){
	$save = $crud->delete_level();
	if($save)
		echo $save;
}
if($action == 'save_staff'){
	$save = $crud->save_staff();
	if($save)
		echo $save;
}
if($action == 'update_staff'){
	$save = $crud->update_staff();
	if($save)
		echo $save;
}
if($action == 'delete_staff'){
	$save = $crud->delete_staff();
	if($save)
		echo $save;
}
if($action == 'save_company'){
	$save = $crud->save_company();
	if($save)
		echo $save;
}
if($action == 'update_company'){
	$save = $crud->update_company();
	if($save)
		echo $save;
}
if($action == 'delete_company'){
	$save = $crud->delete_company();
	if($save)
		echo $save;
}
if($action == 'save_project'){
	$save = $crud->save_project();
	if($save)
		echo $save;
}
if($action == 'delete_project'){
	$save = $crud->delete_project();
	if($save)
		echo $save;
}
if($action == 'save_task'){
	$save = $crud->save_task();
	if($save)
		echo $save;
}
if($action == 'save_gettask'){
	$save = $crud->save_gettask();
	if($save)
		echo $save;
}
if($action == 'delete_task'){
	$save = $crud->delete_task();
	if($save)
		echo $save;
}
if($action == 'save_progress'){
	$save = $crud->save_progress();
	if($save)
		echo $save;
}
if($action == 'delete_progress'){
	$save = $crud->delete_progress();
	if($save)
		echo $save;
}
if($action == 'get_report'){
	$get = $crud->get_report();
	if($get)
		echo $get;
}
ob_end_flush();
?>
