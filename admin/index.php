<?php

$title = "Панель администратора";
$template = "default";

if($_GET['page']) $page = $_GET['page'];
else $page = 'index';

if($page == 'index'){
    require_once ('modules/menu.php');
    $content = menu();
}

if($page == 'add_content'){
    require_once ('../admin/modules/add_content_form.php');
    $content = $add_content_form->readTemplate("../admin/templates/$template/add_content_form.html");
}

require_once "templates/$template/index.html";