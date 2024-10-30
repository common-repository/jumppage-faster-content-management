<?php 
include_once 'Data.php'; 
if (isset($_GET['save_general'])) { 
    $data->save();
} 
?>
<div>
    <h2><?php _e("Jumppage Settings", "anps_jumppage"); ?></h2>
    <div style="margin-bottom:20px;">Choose all menus or select the menus you want to show in your Jumppage menu.</div>
    <form action="options-general.php?page=anps_jumppage&save_general" method="post">
        <div style="margin-bottom:20px;">
            <?php if(in_array("-1", get_option("menu_item", array("-1")))) {
                        $checked_all = " checked";
                    } else {
                        $checked_all = "";
                    }
            ?>
            <input style="margin-right:15px;" type='checkbox' name='menu_item[]' value='-1'<?php echo $checked_all; ?>>All<br /><br />
            <?php 
                $menus = get_terms('nav_menu', array( 'hide_empty' => true));
                foreach($menus as $item) {
                    if(in_array($item->term_id, get_option("menu_item", array("-1")))) {
                        $checked = " checked";
                    } else {
                        $checked = "";
                    }
                    echo "<input style='margin-right:15px;'  type='checkbox' name='menu_item[]' value='".$item->term_id."'$checked>".$item->name."<br /><br />"; 
                }
            ?>
        </div>
        <input style="cursor:pointer; " type="submit" value="<?php _e("Save all changes", "anps_jumppage"); ?>">
    </form>
</div> 