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

define('POLY_BLANK_MODULE', 'poly_blank_module');
define('POLY_BLANK_MODULE_VERSION', '1.0.0');
define('POLY_BLANK_MODULE_NAME', 'Poly Blank Module');

class POLYBLANKMODULE
{
    private $CI;
    private $settings;
    private $staff_id;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->staff_id = get_staff_user_id();

        register_activation_hook(POLY_BLANK_MODULE, array($this, 'register_activation_hook'));
        register_deactivation_hook(POLY_BLANK_MODULE, array($this, 'register_deactivation_hook'));

        // Load the list of shared helper libraries.
        $this->CI->load->helper(POLY_BLANK_MODULE . '/poly_blank_module_common');

        // Register menu items and assign menu items permissions.
        hooks()->add_action('admin_init', [$this, 'init_menu_items']);

        /**
         * Register language files, must be registered if the module is using languages
         */
        register_language_files(POLY_BLANK_MODULE, [POLY_BLANK_MODULE]);

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
        return '<div class="poly-hook-area" style="padding: 4px; color: yellow; display: flex; justify-content: center;"><div style="background:red; padding: 4px 8px">' . $content . ' by '.POLY_BLANK_MODULE.'</div></div>';
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

    public function register_activation_hook()
    {
        require_once(__DIR__ . '/install.php');

       // Register your module's custom routes into application/config/routes.php via my_routes.php (the file will be created automatically if it doesn't exist. Therefore, ensure write permissions for the application/config directory). Remove or comment out this code if the module does not register routes.
        poly_blank_module_common_helper::require_in_file(APPPATH . 'config/my_routes.php', "FCPATH.'modules/" . POLY_BLANK_MODULE . "/config/my_routes.php'");
    }
    
    public function register_deactivation_hook()
    {
        // Unregister your module's custom routes from application/config/routes.php via my_routes.php (the file will be created automatically if it doesn't exist. Therefore, ensure write permissions for the application/config directory). Remove or comment out this code if the module does not register routes.
        poly_blank_module_common_helper::unrequire_in_file(APPPATH . 'config/my_routes.php', "FCPATH.'modules/" . POLY_BLANK_MODULE . "/config/my_routes.php'");
    }

    /**
     * Init api module menu items in setup in admin_init hook
     * @return null
     */
    public function init_menu_items()
    {
        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        if (is_admin()) {
            $CI = &get_instance();

            $CI->app_menu->add_sidebar_menu_item(POLY_BLANK_MODULE, [
                'collapse' => true,
                'name'     => _l(POLY_BLANK_MODULE),
                'position' => 2,
                'icon'     => 'fa fa-cogs',
            ]);
            $CI->app_menu->add_sidebar_children_item(POLY_BLANK_MODULE, [
                'slug'     => 'item_test',
                'name'     => _l(POLY_BLANK_MODULE.'_item_test'),
                'href'     => admin_url(POLY_BLANK_MODULE.'/item_test'),
                'position' => 5,
            ]);

            $CI->app_menu->add_sidebar_children_item(POLY_BLANK_MODULE, [
                'slug'     => 'settings',
                'name'     => _l(POLY_BLANK_MODULE.'_settings'),
                'href'     => admin_url(POLY_BLANK_MODULE.'/settings'),
                'position' => 10,
            ]);

            $this->permissions();
        }
    }

    /**
     * Initialize module permissions during setup in the admin_init hook.
     * @return void
     */
    public function permissions()
    {
        $capabilities = [];
        $capabilities['capabilities'] = [
            'view'   => _l('permission_view')
        ];
        register_staff_capabilities(POLY_BLANK_MODULE, $capabilities, _l(POLY_BLANK_MODULE));

        $capabilities = [];
        $capabilities['capabilities'] = [
            'view'   => _l('permission_view'),
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
        ];
        register_staff_capabilities(POLY_BLANK_MODULE.'_item_test', $capabilities, _l(POLY_BLANK_MODULE.'_item_test') . ' (' . _l(POLY_BLANK_MODULE) . ')');

        $capabilities = [];
        $capabilities['capabilities'] = [
            'view'   => _l('permission_view'),
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
        ];
        register_staff_capabilities(POLY_BLANK_MODULE.'_settings', $capabilities, _l(POLY_BLANK_MODULE.'_settings') . ' (' . _l(POLY_BLANK_MODULE) . ')');
    }
}
new POLYBLANKMODULE();
