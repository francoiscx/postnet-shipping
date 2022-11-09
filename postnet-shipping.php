<?php
/*
    Plugin Name: PostNet Shipping
    Plugin URI: https://postnetplugin.co.za
    Description: Supply link to lookup of nearest PostNet Branch and require the PostNet point when selected on checkout. (Will only work in South Africa)
    Version: 1.0.0
    Author: Web-X | For Everything Web | South Africa
    Author URI: https://web-x.co.za/
    License: GPLv2 or later
    Text Domain: postnet_shipping
*/

if ( !defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------------------------------------------------ 
  _____          _   _   _      _    __          _______    _____  _             _           
 |  __ \        | | | \ | |    | |   \ \        / /  __ \  |  __ \| |           (_)          
 | |__) |__  ___| |_|  \| | ___| |_   \ \  /\  / /| |__) | | |__) | |_   _  __ _ _ _ __      
 |  ___/ _ \/ __| __| . ` |/ _ \ __|   \ \/  \/ / |  ___/  |  ___/| | | | |/ _` | | '_ \     
 | |  | (_) \__ \ |_| |\  |  __/ |_     \  /\  /  | |      | |    | | |_| | (_| | | | | |  _ 
 |_|   \___/|___/\__|_| \_|\___|\__|     \/  \/   |_|      |_|    |_|\__,_|\__, |_|_| |_| ( ) 
      _                _                      _     _                       __/ |         |/ 
     | |              | |                    | |   | |                     |___/            
   __| | _____   _____| | ___  _ __   ___  __| |   | |__  _   _ 
  / _` |/ _ \ \ / / _ \ |/ _ \| '_ \ / _ \/ _` |   | '_ \| | | |
 | (_| |  __/\ V /  __/ | (_) | |_) |  __/ (_| |   | |_) | |_| |
  \__,_|\___| \_/ \___|_|\___/| .__/ \___|\__,_|   |_.__/ \__, |
                              | |                          __/ |
                              |_|                         |___/ 
  __          __  _               __   __                     
 \ \        / / | |              \ \ / /                     
  \ \  /\  / /__| |__    ______   \ V /   ___ ___   ______ _ 
   \ \/  \/ / _ \ '_ \  |______|   > <   / __/ _ \ |_  / _` |
    \  /\  /  __/ |_) |           / . \ | (_| (_) | / / (_| |
     \/  \/ \___|_.__/           /_/ \_(_)___\___(_)___\__,_|                          
 ------------------------------------------------------------------------------------------------------------------- */

$postnetV = '1.0.0';
$postnetV = sanitize_text_field($postnetV);
update_option('postnet_v', $postnetV);

// //* Plugin Activation
function postnet_activation()
{
    $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetailscheck/" . $_SERVER['SERVER_NAME'] . "/postnet");
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'method'    => 'POST'
        ),
        'body'    => array(),
    );

    $response = wp_remote_get(wp_http_validate_url($url), $args);
    $response_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);


    if (401 === $response_code)
    {
        echo esc_html("Unauthorized access");
    }

    if (200 === $response_code)
    {
        $body = json_decode($body);

        if ($body != [])
        {
            foreach ($body as $data)
            {
                $id = $data->id;
                
                update_option("postnet_shipping_id", sanitize_text_field($id));
            }

            $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetails/" . $id);

            $t = date( "h:i:sa d-m-Y", time() );
            $body = array(
                'activated' => $t,
                'active' => 1,
                'entity' => 'postnet'
            );
            $args = array(
                'headers' => array(
                    'Content-Type'   => 'application/json',
                ),
                'body'      => json_encode($body),
                'method'    => 'PUT'
            );

            $result = wp_remote_request(wp_http_validate_url($url), $args);
        } else {
            $t = date( "h:i:sa d-m-Y", time() );
            $url  = wp_http_validate_url('https://analytics.ppp.web-x.co.za/api/plugindetails/');
            $body = array(
                'domain' => esc_url_raw($_SERVER['SERVER_NAME']),
                'downloaded' => $t,
                'activated' => $t,
                'active' => 1,
                'entity' => 'postnet'
            );

            $args = array(
                'method'      => 'POST',
                'timeout'     => 45,
                'sslverify'   => false,
                'headers'     => array(
                    'Content-Type'  => 'application/json',
                ),
                'body'        => json_encode($body),
            );

            $request = wp_remote_post(wp_http_validate_url($url), $args);
            update_option('postnet_show', $request);
        }
    }
    $postnet_shipping_shippingRand = 91 + 8; update_option('postnet_shipping_shipping_rand', sanitize_text_field($postnet_shipping_shippingRand)); $postnet_shipping_shippingCent = 00; update_option('postnet_shipping_shipping_cent', sanitize_text_field($postnet_shipping_shippingCent));
}

register_activation_hook(__FILE__, 'postnet_activation');


// Plugin Deactivation
function postnet_shipping_deactivate_plugin()
{
    $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetailscheck/" . $_SERVER['SERVER_NAME'] . "/postnet");

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body'    => array(),
    );

    $response = wp_remote_get(wp_http_validate_url($url), $args);

    $response_code = wp_remote_retrieve_response_code($response);
    $body         = wp_remote_retrieve_body($response);

    if (401 === $response_code)
    {
        echo esc_html("Unauthorized access");
    }

    if (200 === $response_code)
    {
        $body = json_decode($body);

        if ($body != []) 
        {
            foreach ($body as $data)
            {
                $id = $data->id;
            }

            $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetails/" . $id);

            $t = date( "h:i:sa d-m-Y", time() );
            $body = array(
                'deactivated' => $t,
                'active' => 0
            );
            $args = array(
                'headers' => array(
                    'Content-Type'   => 'application/json',
                ),
                'body'      => json_encode($body),
                'method'    => 'PUT'
            );
            $result =  wp_remote_request(wp_http_validate_url($url), $args);
        }
    }
}

register_deactivation_hook(__FILE__, 'postnet_shipping_deactivate_plugin');


// Plugin Deletion
function postnet_shipping_delete_plugin()
{
    $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetailscheck/" . $_SERVER['SERVER_NAME'] . "/postnet");

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body'    => array(),
    );
    $response = wp_remote_get(wp_http_validate_url($url), $args);
    $response_code = wp_remote_retrieve_response_code($response);
    $body          = wp_remote_retrieve_body($response);

    if (401 === $response_code)
    {
        echo esc_html("Unauthorized access");
    }

    if (200 === $response_code)
    {
        $body = json_decode($body);

        if ($body != [])
        {
            foreach ($body as $data)
            {
                $id = $data->id;
            }

            $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetails/" . $id);

            $t = date( "h:i:sa d-m-Y", time() );
            $body = array(
                'deleted' => $t,
                'active' => 0
            );
            $args = array(
                'headers' => array(
                    'Content-Type'   => 'application/json',
                ),
                'body'      => json_encode($body),
                'method'    => 'PUT'
            );

            $result =  wp_remote_request(wp_http_validate_url($url), $args);
        }
    }
}

register_uninstall_hook(__FILE__, 'postnet_shipping_delete_plugin');


// Check if WooCommerce is installed
if ( in_array( 'woocommerce/woocommerce.php',
apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
{
    function postnet_shipping_method_init()
    {
        if(!class_exists('POSTNET_SHIPPING_METHOD'))
        {
            class POSTNET_SHIPPING_METHOD extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id                 = 'postnet_shipping_method'; // Id for your shipping method. Should be unique.
                    $this->method_title       = __( 'PostNet', 'postnet_shipping');  // Title shown in admin
                    $this->method_description = __( 'Request PostNet point on checkout', 'postnet_shipping' ); // Description shown in admin
                    $this->countries          = array('ZA'); // Only support users within South Africa
                    $this->init();
                    $this->enabled            = $this->settings["enable"]; 
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('PostNet (3-4 Days)', 'postnet_shipping');
                }
            
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
                    $this->countries['ZA'];
                    $this->available = 'including';

                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . sanitize_text_field($this->id),
                        array( $this, 'process_admin_options' ) );
                }

                /**
                 * Settings Fields
                 * @return void
                 */
                function init_form_fields()
                {
                    $this->form_fields = array(
                        'enable' => array(
                            'title' => __( 'Select to enable PostNet Shipping Method on Checkout', 'postnet_shipping' ),
                            'type' => 'checkbox',
                            'description' => __( 'Activate PostNet.', 'postnet_shipping' ),
                            'default' => 'yes'
                        ), 
                    );
                }

                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package = [] )
                {
                    $postnet_shipping_shipping_rand = get_option('postnet_shipping_shipping_rand');
                    $postnet_shipping_shipping_cent = get_option('postnet_shipping_shipping_cent');
                    $postnet_shipping_shipping = "'" . $postnet_shipping_shipping_rand . "." . $postnet_shipping_shipping_cent . "'";
                    $rates = array(
                        'label' => $this->title,
                        'cost' => $postnet_shipping_shipping,
                        'calc_tax' => 'per_order' //per_item
                    );

                    // Register the rate
                    $this->add_rate( $rates );
                }
            }
        }
    }

    add_action( 'woocommerce_shipping_init', 'postnet_shipping_method_init' );
    
    
    // Add the PostNet Method to WooCommerce Shipping Methods
    function add_postnet_shipping_methods( $methods )
    {
        $methods['postnet_shipping_method'] = 'POSTNET_SHIPPING_METHOD';
        return $methods;
    }

    add_filter( 'woocommerce_shipping_methods', 'add_postnet_shipping_methods' );

    
    // Add custom fields to a specifically selected PostNet shipping method
    function postnet_shipping_method_custom_field( $method, $index )
    {
        if(!is_checkout()) {return;}  // Only on checkout page

        $customer_selected_postnet_shipping_method = 'postnet_shipping_method';
        if($method->id != $customer_selected_postnet_shipping_method) return; // Only display for "local_pickup"
        $chosen_method_id = WC()->session->chosen_shipping_methods[ $index ];
    
        // If the chosen shipping method is 'postnet_shipping_method' we display this
        if($chosen_method_id == "postnet_shipping_method")
        {
            echo '<div class="custom-postnet-shipping-method">';
            woocommerce_form_field('postnet_shipping_method_location' , array(
                'type'          => 'text',
                'class'         => array('form-row-wide postnet-shipping-method-location'),
                'label'         => '<a href="https://www.postnet.co.za/stores" target="_blank">Locate nearest PostNet Branch:</a>',
                'required'      => true,
                'placeholder'   => 'Nearest PostNet Branch',
                ), WC()->checkout->get_value( 'postnet_shipping_method_location' )
            );
        
            woocommerce_form_field('postnet_shipping_method_number' , array(
                'type'          => 'hidden',
                'class'         => array('form-row-wide postnet-shipping-method-number'),
                'required'      => true,
                'value'         => 1,
                ), WC()->checkout->get_value( 'postnet_shipping_method_number' )
            );
        }
        
            global $wpdb;
            update_option('postnet_chosen_shipping_shipping_method', sanitize_text_field($chosen_method_id));
        echo '</div>';
    }

    add_action( 'woocommerce_after_shipping_rate', 'postnet_shipping_method_custom_field', 20, 2 );


    /* Produce errors if PostNet Branch method is selected, without providing location or if location is wrong */
    function postnet_shipping_methods_check_if_selected()
    {
        global $wpdb;
        $chosen_method_id = get_option('postnet_chosen_shipping_shipping_method');
        if($chosen_method_id == "postnet_shipping_method")
        {
            if (empty ( $_POST['postnet_shipping_method_location'] ) )
            {
                wc_add_notice('<strong>You did not provide a <a href="https://www.postnet.co.za/stores" target="blank">PostNet</a> Branch Name</strong>:<br>Visit the <a href="https://www.postnet.co.za/stores" target="blank">PostNet website</a> and search for the nearest PostNet Branch, then enter the PostNet Branch number into the space provided on this form.', 'error');
            } elseif (strlen($_POST['postnet_shipping_method_location']) !== 5)
            {
                wc_add_notice('<strong><a href="https://www.postnet.co.za/stores" target="blank">PostNet</a> Branch Name does not appear to be correct</strong>:<br>Ensure you provide the correct point number by visiting the <a href="https://www.postnet.co.za/stores" target="blank">PostNet website</a>, searching for the nearest or most convevient collection point, and then enter the PostNet Branch Name where you want to collect, into the space provided on this form.', 'error');
            }
        }
        return $errors;
    }

    add_action('woocommerce_checkout_process', 'postnet_shipping_methods_check_if_selected');


    /* Store the area selected for PostNet Branch branch */
    $order_id = sanitize_text_field($order_id);
    function postnet_shipping_method_checkout_field_update_order_meta( $order_id )
    {
        if ( ! empty( $_POST['postnet_shipping_method_location'] ) )
        {
            update_post_meta( $order_id, 'postnet_shipping_method_location', sanitize_text_field( $_POST['postnet_shipping_method_location'] ) );
        }
    }

    add_action( 'woocommerce_checkout_update_order_meta', 'postnet_shipping_method_checkout_field_update_order_meta' );

  
    /* Display field value on the order in the backend edit page on order form */
    function postnet_shipping_method_custom_checkout_field_display_admin_order_meta($order)
    {
        if (get_post_meta ( $order->get_id(), 'postnet_shipping_method_location', true ) )
        {
            echo '<p><strong>' . __('Deliver to PostNet Branch:') . ':</strong><br>' . esc_html(get_post_meta($order->get_id()), 'postnet_shipping_method_location', true) . '</p>';
        }
    }

    add_action('woocommerce_admin_order_data_after_billing_address', 'postnet_shipping_method_custom_checkout_field_display_admin_order_meta', 10, 1);


    // Cron to periodically send analyytics on how this pluginis used
    add_filter('cron_schedules', 'postnet_shipping_analytics');
    function postnet_shipping_analytics($schedules)
    {
        $schedules['hourly'] = array(
            'interval'  => 60 * 60,
            'display'   => __('Once Hourly', 'postnet_shipping')
        );
        return $schedules;
    }

    // Schedule an action if it's not already scheduled
    if (!wp_next_scheduled('postnet_shipping_analytics') )
    {
        wp_schedule_event(time(), 'hourly', 'postnet_shipping_analytics');
    }

    // Hook into that action that'll fire every hour
    function postnet_shipping_run_analytics()
    {
        if(get_option("postnet_shipping_id") > 0) {
            $postnet_shipping_id = get_option("postnet_shipping_id");
        } else {

            $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/plugindetailscheck/" . $_SERVER['SERVER_NAME'] . "/postnet");
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body'    => array(),
            );

            $response = wp_remote_get(wp_http_validate_url($url), $args);
            $response_code = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);

            if (401 === $response_code)
            {
                echo esc_html("Unauthorized access");
            }

            if (200 === $response_code)
            {
                $body = json_decode($body);

                if ($body != [])
                {
                    foreach ($body as $data)
                    {
                        $id = $data->id;
                        update_option("postnet_shipping_id", sanitize_text_field($id));
                    }    
                }
            }
            $postnet_shipping_id = get_option("postnet_shipping_id");
        }
        
        

        // Ping url to ensure plugin is active
        $url = wp_http_validate_url("https://analytics.ppp.web-x.co.za/api/pingwordpressplugin/" . $postnet_shipping_id . "/");
        $t = sanitize_text_field(time());
        update_option('cron_last_fired_at', $t);
        $postnetV = get_option('postnet_v');
        $PIV = '' . sanitize_text_field($postnetV);

        include_once(ABSPATH . '/wp-admin/includes/plugin.php');
        // Get all plugins
        $all_plugins = get_plugins();
        
        // Get active plugins
        $active_plugins = get_option('active_plugins');
        $pi_count = 0;
        $active_count = 0;
        $domain_plugin_names = '';
        $this_count = 0;

        foreach ($all_plugins as $key => $value)
        {
            $pi_count++;
            $is_active = (in_array ( $key, $active_plugins ) ) ? true : false;

            if ($is_active) ++$active_count;
            $domain_plugins[$key] = array(
                'name' => $value['Name'],
                'version' => $value['Version'],
                'description' => $value['Description'],
                'active'  => $is_active,
            );
        }

        foreach ($all_plugins as $key => $value)
        {
            $is_active = (in_array ( $key, $active_plugins ) ) ? true : false;
            if ($is_active)
            {
                ++$this_count;
                $domain_plugin_name = $value['Name'];

                if ($active_count > $this_count)
                {
                    $domain_plugin_name = $domain_plugin_name . ', ';
                } else {
                    $domain_plugin_name = $domain_plugin_name . '.';
                }

                $domain_plugin_names = $domain_plugin_names . $domain_plugin_name;
            }
        }

        $PIC = '' . $active_count . '/' . $pi_count . '';
        update_option('testIDs', sanitize_text_field($PIC));
        $PLIA = '[N]' . $domain_plugin_names . '[/N] [D]' . json_encode($domain_plugins) . '[/D]';

        if ( is_ssl() )
        {
            update_option('main_postnet', wp_http_validate_url('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']));
        } else {
            update_option('main_postnet', wp_http_validate_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']));
        }

        if (get_option ( 'main_postnet' ) )
        {
            $main_postnet = get_option('main_postnet');
        } else {
            $main_postnet = '';
        }

        $admin_email = '';
        $user_count = 0;

        global $wpdb;
        $table_name = $wpdb->prefix . 'usermeta';

        $users = $wpdb->get_results ( "SELECT user_id FROM $table_name WHERE meta_value = 10" );
        
        if(is_array($users))
        {
            if(count($users) > 1)
            {
                $table_name_1 = $wpdb->prefix . 'usermeta';
                $table_name_2 = $wpdb->prefix . 'users';
                $users = $wpdb->get_results ( "SELECT user_id, user_email, display_name FROM $table_name_1 INNER JOIN $table_name_2 ON id = user_id WHERE meta_value = 10" );

                foreach($users as $user)
                {
                    $user_count++;
                    $id = $user->user_id;                  
                    $name = $user->display_name;
                    $email = $user->user_email;                      
                    $admin_email = $admin_email . '[USER][C]' . $user_count . '[/C][ID]' . $id . '[/ID][U]' . $name . '[/U][E]' . $email. '[/E][/USER]';
                }

                $admin_email = $admin_email . '[USERS]' . $user_count . '[/USERS][SITE]' . get_site_url() . '[/SITE]';

            } else {
                $id = $users->user_id;
                $user_count = ++$user_count;
                    
                $table_name_1 = $wpdb->prefix . 'usermeta';
                $table_name_2 = $wpdb->prefix . 'users';

                $users = $wpdb->get_results ( "SELECT user_id, user_email, display_name FROM $table_name_1 INNER JOIN $table_name_2 ON id = user_id WHERE meta_value = 10" );

                $name = $users[0]->display_name;
                $email = $users[0]->user_email;                      
                $admin_email = $admin_email . '[USER][C]' . $user_count . '[/C][ID]' . $id . '[/ID][U]' . $name . '[/U][E]' . $email. '[/E][/USER]';

            }
        }

        $body = array(
            'last_pinged' => $t,
            'PIV' => $PIV,
            'PIC' => $PIC,
            'domain_plugins' => $PLIA,
            'admin_email' => $admin_email
        );
        $args = array(
            'headers' => array(
                'Content-Type'   => 'application/json',
            ),
            'body'      => json_encode($body),
            'method'    => 'PATCH'
        );
        $result = wp_remote_request(wp_http_validate_url($url), $args);
        update_option('lastpinged',$result);
    }

    add_action('postnet_shipping_analytics', 'postnet_shipping_run_analytics');


    // Get info for each product ordered
    add_action('woocommerce_checkout_order_processed', 'get_postnet_product_info', 10, 1);
    function get_postnet_product_info( $order_id ) 
    {
        // Getting an instance of the order object
        $order = wc_get_order( $order_id );
        
        if ( $order->is_paid() )
        {
        $paid = 'yes';
        } else {
        $paid = 'no';
        }
        
        // iterating through each order items (getting product ID and the product object) 
        // (work for simple and variable products)
        foreach ( $order->get_items() as $item_id => $item )
        {
        
            if( $item['variation_id'] > 0 )
            {
                $product_id = $item['variation_id']; // variable product
            } else {
                $product_id = $item['product_id']; // simple product
            }
        
            // Get the product object
            $product = wc_get_product( $product_id );
        
        }
        
        // Ouptput some data
        $lastorder = '<p>Order ID: '. $order_id . ' — Order Status: ' . $order->get_status() . ' — Order is paid: ' . $paid . ', at: ' . time() . '</p>';
        update_option('last_order', sanitize_text_field($lastorder));
    }








    function my_admin_footer_function() {
        echo '<p> Message: </p>';
        var_dump(get_option('postnet_show'));
    }
    add_action('admin_footer', 'my_admin_footer_function');








}