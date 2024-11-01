<?php
// add_settings_field( 'our_first_field', 'Field Name', array( $this, 'field_callback' ), 'smashing_fields', 'our_first_section' );
/*
 * field list - time, role. Sort by time, amount, name, how many entries
 * this file contains fields for the plugin GUI for admin settings
 */
if (! defined('ABSPATH'))
    exit();

function tcl_getfields()
{
    $fields = array( // Easy Parcel Settings
        array(
            'uid' => 'tcl-entries', // ID
            'label' => 'How many Entries to show:', // Display name
            'section' => 'our_first_section', // Section
            'slug' => 'tcl_fields', // page
            'type' => 'text', // type of field
            'options' => false, // multiple options
            'placeholder' => '-1', // insert text here
            'helper' => 'Limit number of entries to show (-1 for all)', // text after field
            'supplemental' => '', // text under field
            'default' => '-1' // default if not saved
        ),
        array(
            'uid' => 'tcl-filter',
            'label' => 'filter by',
            'section' => 'our_first_section',
            'slug' => 'tcl_fields',
            'type' => 'select',
            'options' => array(
                'Time' => 'Time',
                'Range' => 'Range',
                'Role' => 'Role',
                'None' => 'None'
            ),
            'placeholder' => 'None',
            'helper' => 'Filter which entries to show',
            'supplemental' => '',
            'default' => 'None'
        ),
        array(
            'uid' => 'tcl-filter-time',
            'label' => 'time',
            'section' => 'our_first_section',
            'slug' => 'tcl_fields',
            'type' => 'select',
            'options' => array(
                'Day' => 'Day',
                'Week' => 'Week',
                'Month' => 'Month',
                'Year' => 'Year',
                'All' => 'All'
            ),
            'placeholder' => 'All',
            'helper' => 'consider orders only from the lastest time',
            'supplemental' => '',
            'default' => 'All'
        ),
        array(
            'uid' => 'tcl-filter-range-from', // ID
            'label' => 'From:', // Display name
            'section' => 'our_first_section', // Section
            'slug' => 'tcl_fields', // page
            'type' => 'text', // type of field
            'options' => false, // multiple options
            'placeholder' => '', // insert text here
            'helper' => 'consider orders from', // text after field
            'supplemental' => 'format yyyy-mm-dd', // text under field
            'default' => 'yyyy-mm-dd' // default if not saved
        ),
        array(
            'uid' => 'tcl-filter-range-to', // ID
            'label' => 'To:', // Display name
            'section' => 'our_first_section', // Section
            'slug' => 'tcl_fields', // page
            'type' => 'text', // type of field
            'options' => false, // multiple options
            'placeholder' => '', // insert text here
            'helper' => 'consider orders to', // text after field
            'supplemental' => 'format yyyy-mm-dd', // text under field
            'default' => 'yyyy-mm-dd' // default if not saved
        ),
        array(
            'uid' => 'tcl-roles', // ID
            'label' => 'Filter by which role', // Display name
            'section' => 'our_first_section', // Section
            'slug' => 'tcl_fields', // page
            'type' => 'select', // type of field
            'options' => tcl_get_user_roles(), // multiple options
            'placeholder' => '', // insert text here
            'helper' => 'Show only users with set role', // text after field
            'supplemental' => '', // text under field
            'default' => '' // default if not saved
        ),
        array(
            'uid' => 'tcl-sort',
            'label' => 'sort by',
            'section' => 'our_first_section',
            'slug' => 'tcl_fields',
            'type' => 'select',
            'options' => array(
                'Amount' => 'Amount',
                'Name' => 'Name'
            ),
            'placeholder' => 'Amount',
            'helper' => 'Sort by in descending',
            'supplemental' => 'currently doesnt work, only sorts by amount',
            'default' => 'Amount'
        ),
        array(
            'uid' => 'tcl-widget',
            'label' => 'Enable site Widget',
            'section' => 'our_second_section',
            'slug' => 'tcl_fields2',
            'type' => 'select',
            'options' => array(
                'Enable' => 'Enable',
                'Disable' => 'Disable'
            ),
            'placeholder' => 'Disable',
            'helper' => 'Enable/Disable the page widget on site',
            'supplemental' => '',
            'default' => 'Disable'
        ),
        array(
            'uid' => 'tcl-wentries', // ID
            'label' => 'How many Entries to show:', // Display name
            'section' => 'our_second_section', // Section
            'slug' => 'tcl_fields2', // page
            'type' => 'text', // type of field
            'options' => false, // multiple options
            'placeholder' => '-1', // insert text here
            'helper' => 'Limit number of entries to show (-1 for all)', // text after field
            'supplemental' => '', // text under field
            'default' => '-1' // default if not saved
        ),
        array(
            'uid' => 'tcl-wroles', // ID
            'label' => 'Same roles only:', // Display name
            'section' => 'our_second_section', // Section
            'slug' => 'tcl_fields2', // page
            'type' => 'select', // type of field
            'options' => array(
                'Enable' => 'Enable',
                'Disable' => 'Disable'
            ), // multiple options
            'placeholder' => 'Disable', // insert text here
            'helper' => 'Only show entries that have the same role as viewer', // text after field
            'supplemental' => 'If you want to restrict this to logged in users only, place widget behind a restricted page', // text under field
            'default' => 'Disable' // default if not saved
        ),
        array(
            'uid' => 'tcl-wfilter',
            'label' => 'filter by',
            'section' => 'our_second_section',
            'slug' => 'tcl_fields2',
            'type' => 'select',
            'options' => array(
                'Time' => 'Time',
                'None' => 'None'
            ),
            'placeholder' => 'None',
            'helper' => 'Only consider certain orders?',
            'supplemental' => '',
            'default' => 'None'
        ),
        array(
            'uid' => 'tcl-wfilter-time',
            'label' => 'time',
            'section' => 'our_second_section',
            'slug' => 'tcl_fields2',
            'type' => 'select',
            'options' => array(
                'Day' => 'Day',
                'Week' => 'Week',
                'Month' => 'Month',
                'Year' => 'Year',
                'All' => 'All'
            ),
            'placeholder' => 'None',
            'helper' => 'Consider only orders by latest',
            'supplemental' => '',
            'default' => 'All'
        ),
        array(
            'uid' => 'tcl-wsort',
            'label' => 'sort by',
            'section' => 'our_second_section',
            'slug' => 'tcl_fields2',
            'type' => 'select',
            'options' => array(
                'Amount' => 'Amount',
                'Name' => 'Name'
            ),
            'placeholder' => 'Amount',
            'helper' => 'Show in descending order by',
            'supplemental' => 'Oddly does not work at the moment, so only sorts by amount',
            'default' => 'Amount'
        )
    );
    return $fields;
}

function tcl_get_user_roles()
{
    global $wp_roles;
    $result = array();
    $roles = array_keys($wp_roles->roles);
    $result[''] = '';
    foreach ($roles as $role) {
        $result[$role] = $role;
    }
    return $result;
}
?>