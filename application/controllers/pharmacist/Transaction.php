<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Transaction extends Pharmacist_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'pharmacist';
	private $current_page = 'pharmacist/transaction/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Transaction_services');
		$this->services = new Transaction_services;
		$this->load->model(array(
			'group_model',
			'prescription_model',
			'item_model',
		));
	}
	public function index()
	{
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/index';
		$pagination['total_records'] = $this->prescription_model->record_count();
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config_no_action($this->current_page);
		$table["rows"] = $this->prescription_model->prescriptions($pagination['start_record'], $pagination['limit_per_page'], 1)->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Print Riwayat",
			"type" => "link",
			"url" => site_url($this->current_page . "print/"),
			"button_color" => "primary",
			"data" => NULL,
		);

		$add_menu = $this->load->view('templates/actions/link', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function prescription($prescription_id)
	{
		$prescription = $this->prescription_model->prescription($prescription_id)->row();
		$table = $this->services->get_table_item_config_no_action();
		$table["rows"] = $this->item_model->items($prescription_id)->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Cetak E-Tiket",
			"type" => "link",
			"url" => site_url($this->current_page . "print/" . $prescription_id),
			"button_color" => "primary",
			"data" => NULL,
		);

		$add_menu = $this->load->view('templates/actions/link', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Resep " . $prescription->code;
		$this->data["header"] = "Daftar Obat";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function print()
	{
		redirect(site_url($this->current_page));
		$this->load->library('pdf');
		$this->pdf->load_view('transaction');
		$this->pdf->render();
		$this->pdf->stream('welcome.pdf');
	}
}
