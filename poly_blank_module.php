<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Poly Blank Module
Description: Sample module template with basic hooks, filters, classes, and methods for creating a module for PerfexCRM.
Version: 1.0.0
Requires at least: 3.0.0
Author: PolyXGO
Author URI: https://codecanyon.net/user/polyxgo
*/

define('POLY_BLANK_MODULE_NAME', 'poly_blank_module');
define('POLY_BLANK_MODULE_FOLDER', module_dir_path(POLY_BLANK_MODULE_NAME));
define('POLY_BLANK_MODULE_UPLOAD_FOLDER', module_dir_path(POLY_BLANK_MODULE_NAME, 'uploads'));

class POLYTOURGUIDE
{
    private $CI;
    private $settings;
    private $staff_id;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->staff_id = get_staff_user_id();

        register_activation_hook(POLY_BLANK_MODULE_NAME, array($this, 'module_activation_hook'));

        $this->CI->load->helper(POLY_BLANK_MODULE_NAME . '/poly_blank_module_common');

        hooks()->add_action('admin_init', [$this, 'module_init_menu_items']);
        hooks()->add_action('admin_init', [$this, 'permissions']);

        //Admin
        hooks()->add_action('app_admin_head', [$this, 'assets_head'], 1);
        hooks()->add_action('app_admin_footer', [$this, 'assets_footer']);

        //Customer
        hooks()->add_action('app_customers_head', [$this, 'assets_head'], 1);
        hooks()->add_action('app_customers_footer', [$this, 'assets_footer']);

        /**
         * Register language files, must be registered if the module is using languages
         */
        register_language_files(POLY_BLANK_MODULE_NAME, [POLY_BLANK_MODULE_NAME]);

        /**
         * Admin | Customers | Both => scripts, styles.
         */
        hooks()->add_action('app_admin_head', [$this, 'scripts_styles_admin_staff_head']); // Admin header
        hooks()->add_action('app_admin_footer', [$this, 'scripts_styles_admin_staff_footer']); // Admin footer

        hooks()->add_action('app_customers_head', [$this, 'scripts_styles_clients_customers_head']); // Clients header
        hooks()->add_action('app_customers_footer', [$this, 'scripts_styles_clients_customers_footer']); // Clients footer

        //Page login
        hooks()->add_action('app_admin_authentication_head', [$this, 'admin_authentication_head']); // Admin, Staff header. Footer use `before_admin_login_form_close` & `clients_login_form_end`

        hooks()->add_action('after_admin_login_form_start', [$this, 'admin_login_form_head']); // Admin login form header
        hooks()->add_action('before_admin_login_form_close', [$this, 'admin_login_form_footer']); // Admin login form footer


        hooks()->add_action('clients_login_form_start', [$this, 'clients_customers_login_form_header']); // Clients & Customers login form header
        hooks()->add_action('clients_login_form_end', [$this, 'clients_customers_login_form_footer']); // Clients & Customers login form footer


    }
    public function show_block_area($content)
    {
        return '<div class="poly-hook-area" style="padding: 4px; color: yellow; display: flex; justify-content: center;"><div style="background:red; padding: 4px 8px">' . $content . '</div></div>';
    }
    public function admin_authentication_head()
    {
        echo $this->show_block_area('Login_Admin_Staff_HEAD');
    }

    public function admin_login_form_head()
    {
        echo $this->show_block_area('Login_Admin_Staff_Form_HEAD');
    }
    public function admin_login_form_footer()
    {
        echo $this->show_block_area('Login_Admin_Staff_FOOTER');
    }

    public function clients_customers_login_form_header()
    {
        echo $this->show_block_area('Login_Clients_Form_HEAD');
    }
    public function clients_customers_login_form_footer()
    {
        echo $this->show_block_area('Login_Clients_Form_FOOTER');
    }

    //Login admin & staff
    public function scripts_styles_admin_staff_head()
    {
        echo $this->show_block_area('Logged_Amin_HEAD');
    }
    public function scripts_styles_admin_staff_footer()
    {
        echo $this->show_block_area('Logged_Admin_FOOTER');
    }

    //Login clients/ customers
    public function scripts_styles_clients_customers_head()
    {
        echo $this->show_block_area('LoginLogged_Clients_Customer_HEAD');
    }
    public function scripts_styles_clients_customers_footer()
    {
        echo $this->show_block_area('LoginLogged_Clients_Customer_FOOTER');
    }

    public function module_activation_hook()
    {
        require_once(__DIR__ . '/install.php');
    }
}
