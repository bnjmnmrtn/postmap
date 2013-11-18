<?php
function location_meta_boxes() {
    global $post;
    $value = get_post_meta($post->ID, 'location', true);
    ?>
    <table class="form-table">
      <tr>
        <th style="width:10%;">
          <label for="location">Lat/Lon</label>
        </th>
        <td>
          <div>
            <input type="text" class="widefat" name="location" id="location" value="<?php echo $value; ?>"> 
            <input type="hidden" name="location_noncename" id="location_noncename" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
            <a target="_blank" href="<?php echo get_admin_url(null, 'tools.php?page=location-picker')?>">Pick from map</a>
          </div>
        </td>
      </tr>
    </table>
    <?php
}

function post_map_save_meta($post_id) {
    global $post;
    if (!array_key_exists('post_type', $_POST)) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if (!wp_verify_nonce($_POST['location_noncename'], plugin_basename(__FILE__))) {
        return $post_id;
    }

    $data = stripslashes($_POST['location']);
    if (get_post_meta($post_id, 'location') == '') {
        add_post_meta($post_id, 'location', $data);
    }
    elseif ($data != get_post_meta($post_id, 'location', true)) {
        update_post_meta($post_id, 'location', $data);
    }
    elseif ($data == '') {
        delete_post_meta($post_id, 'location', get_post_meta($post_id, 'location', true));
    }
}

function create_post_map_meta_box() {
    add_meta_box('post-map-meta-box', __('Location Settings'), 'location_meta_boxes', 'post', 'side', 'high');
}
add_action('admin_menu', 'create_post_map_meta_box');
add_action('save_post', 'post_map_save_meta');

?>
