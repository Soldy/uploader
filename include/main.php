<?php

$SUPERGLOBAL=[];
$SUPERGLOBAL['config']=[];



require_once 'config.php';
require_once 'sql.php';
require_once 'post.php';
require_once 'json.php';
require_once 'admin.php'; # administration connect function
require_once 'file.server.php'; # file server connection function
require_once 'file.upload.php'; # file upload connection function
require_once 'cron.php'; # cron server function