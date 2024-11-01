<?php
if (! defined('ABSPATH'))
    exit();

function tcl_generate_table($args)
{
    $query_array;
    // $show_roles,$same_role,$filter_mode,$filter_variable
    switch ($args['filter_mode']) {
        case ("Range"):
            $query_array = array(
                'status' => 'completed',
                'date_created' => $args['range_from'] . '...' . $args['range_to'],
                'limit' => '-1'
            );
            break;
        case ("Time"):
            if ($args['time'] == 'All')
                $query_array = array(
                    'status' => 'completed',
                    'limit' => '-1'
                );
            else {
                $date = date("Y-m-d");
                $date = DateTime::createFromFormat('Y-m-d', $date);
                $date->modify('-1 ' . $args['time']);
                $query_array = array(
                    'status' => 'completed',
                    'date_created' => '>' . $date->format('Y-m-d'),
                    'limit' => '-1'
                );
            }
            break;
        default:
            $query_array = array(
                'status' => 'completed',
                // 'date_created'=>'>2000-01-01',
                'limit' => '-1'
            );
    }
    $user_roles = tcl_check_current_user();
    $query = new WC_Order_Query($query_array);
    // $query->set('status','completed','>2020-01-19');
    $orders = $query->get_orders();
    $customers = Array();
    $k = 0;
    $currency = ' (' . get_option('woocommerce_currency') . ')';

    ?>
<table class="wp-list-table widefat fixed striped posts" border="0">
	<thead>
		<tr>
			<i>
				<th data-sort="string"><?php _e("ID", "woocommerce"); ?></th>
				<th data-sort="string"><?php _e("Name", "woocommerce"); ?></th>
				<th data-sort="string"><?php _e("Role", "woocommerce"); ?></th>
				<th data-sort="float"><?php _e("Total spent", "woocommerce"); echo $currency; ?></th>
			</i>
		</tr>
	</thead>
	<tbody>
    <?php

    foreach ($orders as $i) {
        $id = $i->customer_id;
        $user_info = get_user_by('id', $id);
        $ftotal;
        $counter;
        // echo get_option('our_first_field');
        $same_role = false;
        foreach ($user_roles as $role) {
            $same_role = $same_role or in_array($role, $user_info->roles, true);
        }
        if (in_array($user_info->id, array_column($customers, 0), true)) {
            $key = array_search($user_info->id, array_column($customers, 0), true);
            $customers[$key][2] = $customers[$key][2] + $i->total;
        } else {
            if ($args['filter_mode'] == 'Role' && ! in_array($args['role'], $user_info->roles, true));
            else {
                $customers[$k][0] = $id;
                $customers[$k][1] = $user_info->display_name;
                $customers[$k][2] = $i->total;
                $customers[$k][3] = $user_info->roles;
                $customers[$k][4] = $same_role;
                $k ++;
            }
        }
        $ftotal += $i->total;
        $counter ++;
    }
    switch ($arg['sort_mode']) {
        case ('Name'):
            array_multisort(array_column($customers, 1), SORT_ASC, $customers);
            break;
        default:
            array_multisort(array_column($customers, 2), SORT_DESC, $customers);
    }
    $max_entries;
    if (! ((int) $args['entries'] > 0))
        $max_entries = count($customers);
    else
        $max_entries = (int) $args['entries'];
    for ($k = 0; $k < $max_entries && $k < count($customers); $k ++) {
        if ($args['same_role'] == 'Enable' && ! $customers[$k][4]) {} else {
            // if(check_current_user()==$my_customer[$i][3]){
            // $customer = new WP_User($customer_id);
            // if($roles[1]==$customers[$k][3]|| $roles[0]=='Administrator'){ ?>
        <tr>
			<td><?php if(!$args['show_roles'])echo $customers[$k][0];//echo $id; echo $user_info->roles[1] ?></td>
			<td><?php echo $customers[$k][1];//echo $id; echo $user_info->roles[1] ?></td>
			<td><?php if(!$args['show_roles']){foreach($customers[$k][3] as $j){echo '|'.$j.'|';}}//echo $id; echo $user_info->roles[1] ?></td>
			<td><?php echo $customers[$k][2]; //wc_get_customer_total_spent($customers[$k][0]);// ?></td>
		</tr>
    <?php
        }
    }
    ?>
    </tbody>
</table>
<?php
}
add_action('plugins_loaded', 'tcl_check_current_user');

function tcl_check_current_user()
{
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $roles = (array) $current_user->roles;
        // }
        return $roles;
    } else
        return NULL;
}
?>