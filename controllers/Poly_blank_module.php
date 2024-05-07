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
}
