<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Uadmin_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'uadmin';
	private $current_page = 'uadmin/';
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'ibuhamil_model',
			'balita_model',
		));
	}
	public function index()
	{
		$add_menu = array(
			"name" => "Tambah Group",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => array(
				"name" => array(
					'type' => 'text',
					'label' => "Nama Group",
					'value' => "",
				),
				"description" => array(
					'type' => 'textarea',
					'label' => "Deskripsi",
					'value' => "-",
				),
				'data' => NULL
			),
		);

		$add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		$this->data['jml_ibuhamil'] = count($this->ibuhamil_model->ibuhamils()->result());
		$this->data['jml_balita'] = count($this->balita_model->balitas()->result());
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("admin/dashboard/content");
	}
}
