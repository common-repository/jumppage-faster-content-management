<?php
class Data {
    public function save() { 
        if(!isset($_POST['menu_item'])) {
            $_POST['menu_item'] = array("-1");
        }
        foreach($_POST as $name=>$value) {  
            update_option($name, $value);
        }
        echo "<script>window.location = 'options-general.php?page=anps_jumppage';</script>";
    }
}
$data = new Data();