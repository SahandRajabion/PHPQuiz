<?php

require_once('Controller/MasterController.php');

session_start();

$masterController = new MasterController();
$masterController->doControll();

