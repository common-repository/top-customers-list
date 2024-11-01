<?php
if (! defined('ABSPATH'))
    exit();
include 'topwoofields.php';

class tcl_Settings
{

    public function __construct()
    {
        // Hook into the admin menu
        // admin_menu - menu setup, admin_init - menu content, array (method_name)
        add_action('admin_menu', array(
            $this,
            'create_plugin_settings_page'
        ));
        add_action('admin_init', array(
            $this,
            'setup_sections'
        ));
        add_action('admin_init', array(
            $this,
            'setup_fields'
        ));
    }

    /*
     * Admin GUI settings
     * steps: init-> create_plugin_settings_page (defines the titles)
     * ->setup_fields (contains individual field code)
     * ->field callback (filters fields and generates html based on whats defined)
     * ->setup sections (creates the section of the page for the fields, required)
     * -> individual page method
     */
    public function create_plugin_settings_page()
    {
        // Add the menu item and page
        $page_title = 'Top Customers List Settings';
        $menu_title = 'Top Customers Lists';
        $capability = 'manage_options';
        $slug = 'tcl-settings';
        $callback = array(
            $this,
            'plugin_settings_page_content'
        );
        $icon = 'dashicons-admin-plugins';
        $position = 100;

        // add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
        add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback);
    }

    public function setup_fields()
    {
        $fields = tcl_getfields();
        // filter here
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], array(
                $this,
                'field_callback'
            ), $field['slug'], $field['section'], $field);
            register_setting($field['slug'], $field['uid']);
        }
    }

    public function field_callback($arguments)
    {
        $value = get_option($arguments['uid']); // Get the current value, if there is one
        if (! $value) { // If no value exists
            $value = $arguments['default']; // Set to our default
        }
        // Check which type of field we want
        switch ($arguments['type']) {
            case 'text': // If it is a text field
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
                break;
            case 'textarea': // If it is a textarea
                printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], checked(1, $value));
                break;
            case 'select': // If it is a select dropdown
                if (! empty($arguments['options']) && is_array($arguments['options'])) {
                    $options_markup = '';
                    foreach ($arguments['options'] as $key => $label) {
                        $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value, $key, false), $label);
                    }
                    printf('<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup);
                }
                break;
            case 'checkbox': // <input type="checkbox" text = "test"name="myoption[option_one]" value="1"<?php checked( isset( $options['option_one'] ) );
                              // <input type="checkbox" name="myoption[option_two]" value="1"<?php checked( isset( $options['option_two'] ) );

                printf('<input id="%1$s" type="%2$s" name="id="%1$s" %3$s />', $arguments['uid'], $arguments['type'], $value);
                // printf('<input name="%1$s" id="%1$s" type="checkbox" value="1" ' . checked( 1, $value, false ) . ' />');
                break;
            case "key-function":
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], check_balance(get_option('key'), get_option('mode')));
                break;
        }

        // If there is help text
        if ($helper = $arguments['helper']) {
            printf('<span class="helper"> %s</span>', $helper); // Show it
        }

        // If there is supplemental text
        if ($supplimental = $arguments['supplemental']) {
            printf('<p class="description">%s</p>', $supplimental); // Show it
        }
    }

    public function section_callback($arguments)
    {}

    public function setup_sections()
    {
        add_settings_section('our_first_section', 'Admin Dashboard Widget Settings', array(
            $this,
            'section_callback'
        ), 'tcl_fields');
        add_settings_section('our_second_section', 'Site Widget Settings', array(
            $this,
            'section_callback'
        ), 'tcl_fields2');
    }

    public function plugin_settings_page_content()
    {
        ?>
<div class="wrap">
	<h2>Top Resellers Settings</h2>
	<form method="post" action="options.php">
            <?php
        settings_fields('tcl_fields');
        do_settings_sections('tcl_fields');
        submit_button();
        ?>
        </form>
	<form method="post" action="options.php">
            <?php
        settings_fields('tcl_fields2');
        do_settings_sections('tcl_fields2');
        submit_button();
        ?>
        </form>
</div>
<?php
    }
}
new tcl_Settings();
