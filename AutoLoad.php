<?php


// Method Auto Load

function classAutoLoad($classname){
    $directories = ["classes", "contents", "forms", "processes", "global", "menus"];

    foreach($directories AS $dir){
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $classname . ".php";

        if(file_exists($filename) AND is_readable($filename)){
            require_once($filename);
        }
    }
}
spl_autoload_register('classAutoLoad');

// Creating an instance of a class
$ObjLayout = new layout();
$ObjContent = new contents();