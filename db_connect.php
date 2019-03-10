<?php
$conn = mysqli_connect("localhost", "root", "", "it_village");

if (!$conn) {
	die("Connection_failed: ".mysqli_connect_error());
}