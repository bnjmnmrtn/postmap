<?php
class remaning_posts{
    public function __construct(){
        if(is_admin()){
            add_action('admin_menu', array($this, 'add_plugin_page'));
        }
    }
    
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_management_page('Posts to Locate', 'Posts to Locate', 'publish_posts', 'posts-to-locate', array($this, 'create_posts_to_locate'));
    }

    public function create_posts_to_locate(){
        ?>
        <div class="wrap posts-to-locate">
            <?php screen_icon(); ?>
            <h2>Posts to Locate</h2>           
            <div>
              <ul>
                <?php
                $query = new WP_Query(array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'location',
                            'value' => '',
                            'compare' => 'NOT EXISTS'
                        )
                    )
                ));

                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<li><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></li>';
                }
                ?>
              </ul>
            </div>
        </div>
    <?php
    }
}
$remainingposts = new remaning_posts();
?>
