<?php
require_once __DIR__.'/../core/config.inc.php';
LoginTool::logout();
Utils::redirect(site_url('home'));