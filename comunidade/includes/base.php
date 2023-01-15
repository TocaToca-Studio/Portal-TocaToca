<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/footer.php';

protect_page();

$info_usuario=Usuario::logged_user()->get_infos(['*']);

require_once __DIR__ . '/leftbar.php';
require_once __DIR__ . '/rightbar.php';
require_once __DIR__ . '/navbar.php';
