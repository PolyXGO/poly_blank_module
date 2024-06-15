<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Poly_blank_module extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Settings
     * @return view
     */
    public function settings()
    {
        $data['title'] = _l(POLY_BLANK_MODULE . '_settings');
        $this->load->view('settings', $data);
    }

    /**
     * Item test
     * @return view
     */
    public function item_test()
    {
        $data['title'] = _l(POLY_BLANK_MODULE . '_item_test');
        $this->load->view('item_test', $data);
    }

    
     /**
     * The function assists you in creating a tab panel feature similar to the Settings section of the system.
     * @return view
     */
     public function settings_tab(){
        $data['title'] = _l(POLY_BLANK_MODULE . '_settings');

        $tab = $this->input->get('group');

        $data['tabs'] = [
            "tab1" => poly_blank_module_common_helper::createTab("tab1", "Tab 1", POLY_BLANK_MODULE."/tabs/tab1", 10, "fa fa-th"),
            "tab2" => poly_blank_module_common_helper::createTab("tab2", "Tab 2", POLY_BLANK_MODULE."/tabs/tab2", 15, "fa fa-chart-bar"),
            "tab3" => poly_blank_module_common_helper::createTab("tab3", "Tab 3", POLY_BLANK_MODULE."/tabs/tab3", 20, "fa fa-envelope"),
            "tab4" => poly_blank_module_common_helper::createTab("tab4", "Tab 4", POLY_BLANK_MODULE."/tabs/tab4", 25, "fa fa-sliders-h")
        ];


        if (!$tab || (in_array($tab, $data['tabs']) && !is_admin())) {
            $tab = 'tab1'; // Default first tab;
        }

        if (!in_array($tab, $data['tabs'])) {
            $data['tab'] = $this->app_tabs->filter_tab($data['tabs'], $tab);
        }

        $this->load->view(POLY_BLANK_MODULE_SETTINGS_TAB, $data);
    }
}
