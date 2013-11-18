<?php
class location_map{
    public function __construct(){
        if(is_admin()){
            add_action('admin_menu', array($this, 'add_plugin_page'));
        }
    }
    
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_management_page('Location Picker', 'Location Picker', 'publish_posts', 'location-picker', array($this, 'create_location_picker'));
    }

    public function create_location_picker(){
        ?>
        <div class="wrap location-picker">
            <?php screen_icon(); ?>
            <h2>Choose Location</h2>           
            <div>
                <span class="location">Location: <input class="coordinate_viewer" type="text" readonly /></span>
                <button class="button button-primary my-position" onclick="goToMyLocation()" >My Location</button>
            </div>
            <br>
            <div id="location-picker-map"></div>
        </div>
    <?php
    }
}
$locationmap = new location_map();
?>
