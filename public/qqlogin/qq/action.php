<?php
include "../comm/comm.php";
if (empty($_SESSION['login']) || $_SESSION['login'] == 0) {
	exit;
}
if ($_GET['act'] == "get_zhan") {
	$index = intval($_POST['page']);
	$pagesize = intval($_POST['limit']);
	$where = 1;
	$pages = intval($numrows / $pagesize);
	if ($numrows % $pagesize) {
		$pages++;
	}
	if (isset($index)) {
		$page = intval($index);
	} else {
		$page = 1;
	}
	$offset = $pagesize * ($page - 1);

	$sql = "select * from qqlogin_zhan where {$where} order by zhan_id desc limit $offset,$pagesize";
	$rs = $DB->query($sql);
	$data = array();
	$i = 0;
	while ($row = $rs->fetch()) {

		array_push($data, $row);

		$i++;
	}
	$count_sql = "select count(zhan_id) from qqlogin_zhan where {$where}";
	$count = $DB->query($count_sql)->fetchColumn();
	$return = array("code" => 0, "message" => "获取成功", "count" => $count, "data" => $data);
	exit(json_encode($return));
} elseif ($_GET['act'] == "zhan_delete_byid") {
	$id = $_POST['zhan_id'];
	$sql = "DELETE FROM qqlogin_zhan WHERE zhan_id = " . $id;
	if ($DB->query($sql)) {
		$return = array("code" => 1, "msg" => "删除成功");
		exit(json_encode($return));
	} else {
		$return = array("code" => 0, "msg" => "删除失败");
		exit(json_encode($return));
	}
} elseif ($_GET['act'] == "zhan_update_byid") {
	$id = $_POST['zhan_id'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	$sql = "update qqlogin_zhan set `$field` = '$value' where zhan_id = " . $id;
	if ($DB->query($sql)) {
		$return = array("code" => 1, "msg" => "更新信息成功");
		exit(json_encode($return));
	} else {
		$return = array("code" => 0, "msg" => "更新信息失败");
		exit(json_encode($return));
	}
}elseif($_GET['act'] == "get_loginlog"){
	$index = intval($_POST['page']);
	$pagesize = intval($_POST['limit']);
	$where = 1;
	
	if (isset($index)) {
		$page = intval($index);
	} else {
		$page = 1;
	}
	$offset = $pagesize * ($page - 1);

	$sql = "select * from qqlogin_log where {$where} order by log_id desc limit $offset,$pagesize";
	$rs = $DB->query($sql);
	$data = array();
	$i = 0;
	while ($row = $rs->fetch()) {

		array_push($data, $row);

		$i++;
	}
	$count_sql = "select count(log_id) from qqlogin_log where {$where}";
	$count = $DB->query($count_sql)->fetchColumn();
	$return = array("code" => 0, "message" => "获取成功", "count" => $count, "data" => $data);
	exit(json_encode($return));
}elseif($_GET['act'] == "all_delete"){
	$sql = "delete from qqlogin_log";
	if ($DB->query($sql)) {
		$return = array("code" => 1, "msg" => "成功删除全部");
		exit(json_encode($return));
	} else {
		$return = array("code" => 0, "msg" => "删除失败");
		exit(json_encode($return));
	}
}

?>