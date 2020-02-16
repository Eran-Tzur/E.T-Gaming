<?php

require_once 'app/helpers.php';
sess_start('digg');
session_destroy();
header('location: /signin.php');
exit;