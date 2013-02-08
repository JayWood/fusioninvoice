<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * FusionInvoice
 * 
 * A free and open source web based invoicing system
 *
 * @package		FusionInvoice
 * @author		Jesse Terry
 * @copyright	Copyright (c) 2012 - 2013, Jesse Terry
 * @license		http://www.fusioninvoice.com/license.txt
 * @link		http://www.fusioninvoice.
 * 
 */

class Tax_Rates extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('mdl_tax_rates');
	}
	
	public function index()
	{
		$this->layout->set('tax_rates', $this->mdl_tax_rates->paginate()->result());
		$this->layout->buffer('content', 'tax_rates/index');
		$this->layout->render();
	}
	
	public function form($id = NULL)
	{
		if ($this->input->post('btn_cancel'))
		{
			redirect('tax_rates');
		}
		
		if ($this->mdl_tax_rates->run_validation())
		{
			$this->mdl_tax_rates->save($id);
			redirect('tax_rates');
		}
		
		if ($id and !$this->input->post('btn_submit'))
		{
			$this->mdl_tax_rates->prep_form($id);
		}
		
		$this->layout->buffer('content', 'tax_rates/form');
		$this->layout->render();
	}
	
	public function delete($id)
	{
		$this->mdl_tax_rates->delete($id);
		redirect('tax_rates');
	}

}

?>