<?php

/**
 * Plugin Name: Top customers list
 * Description: List your top customers on your website, requires woocommerce. To prevent any errors or warnings, please fill in the settings first. Any warnings could also be a result of invalid entries in your woocommerce order history. Widget is included to place on your website
 * Plugin URI: https://bumbu.agency/
 * Version: 1.03
 * Author: System Error Message
 * Author URI: https://system-error-message.com/
 * Text Domain: bumbu-top-customers
 */
if (! defined('ABSPATH'))
    exit();
include ('getstuff.php');
include ('settings-api.php');

function tcl_load_widget()
{
    register_widget('me_tcl_widget');
}
add_action('widgets_init', 'tcl_load_widget');

// Creating the widget
class me_tcl_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // Base ID of your widget
            'tcl_widget', 

            // Widget name will appear in UI
            __('Top customers widget', 'wpb_widget_domain'), 

            // Widget description
            array(
                'description' => __('See your top customers in dashboard', 'wpb_widget_domain')
            ));
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (! empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        echo $args['after_widget'];
        if (get_option('tcl-widget') == 'Enable') {
            $arguments = array(
                'entries' => get_option('tcl-wentries'),
                'filter_mode' => get_option('tcl-wfilter'),
                'time' => get_option('tcl-wfilter-time'),
                'same_role' => get_option('tcl-wroles'),
                'sort_mode' => get_option('tcl-wsort'),
                'show_roles' => true
            );
            @tcl_generate_table($arguments);
        }
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'tcl_widget_domain');
        }
        // Widget admin form
        ?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // Class wpb_widget ends here
add_action('wp_dashboard_setup', 'tcl_custom_dashboard_widget_add');

function tcl_custom_dashboard_widget_add()
{
    wp_add_dashboard_widget('custom_tcl_widget', 'Top Customers', 'tcl_custom_dashboard_widget');
}

function tcl_custom_dashboard_widget()
{
    /**
     * Check if WooCommerce is activated
     */
    if (! function_exists('is_woocommerce_activated')) {
        if (class_exists('woocommerce')) {} else {
            echo '<h3>Warning! Woocommerce plugin is missing</h3>';
        }
    }
    $arguments = array(
        'entries' => get_option('tcl-entries'),
        'filter_mode' => get_option('tcl-filter'),
        'time' => get_option('tcl-filter-time'),
        'range_from' => get_option('tcl-filter-range-from'),
        'range_to' => get_option('tcl-filter-range-to'),
        'role' => get_option('tcl-roles'),
        'sort_mode' => get_option('tcl-sort')
    );
    @tcl_generate_table($arguments);
}
?>