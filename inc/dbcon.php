<?php
	$hostname="127.0.0.1";
	$dbuserid="rotel";
	$dbpasswd="soon06051007?!";
	$dbname="python";
	$user_table="python";
/*
	$connect=@mysql_connect($hostname,$dbuserid,$dbpasswd)
	or die("잠시 서버 작업중입니다. 잠시후 다시 이용해주세요. 죄송합니다.(__)");
	@mysql_select_db($dbname,$connect) or die("1:".mysql_error());
	mysql_query("set names utf8");
	mysql_query("set session character_set_connection=utf8");
	mysql_query("set session character_set_results=utf8");
	mysql_query("set session character_set_client=utf8");
*/

$mysqli = new mysqli($hostname, $dbuserid, $dbpasswd, $dbname);
$mysqli->query("SET sql_mode = 'HIGH_NOT_PRECEDENCE';");
$mysqli->query("set names utf8");
$mysqli->query("set session character_set_connection=utf8");
$mysqli->query("set session character_set_results=utf8");
$mysqli->query("set session character_set_client=utf8");

// 연결 오류 발생 시 스크립트 종료
if ($mysqli->connect_errno) {
    die('Connect Error: '.$mysqli->connect_error);
}
	include "function.php";

?>

