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
 * @license		http://www.fusioninvoice.com/support/page/license-agreement
 * @link		http://www.fusioninvoice.com
 * 
 */

class Email_Templates extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('mdl_email_templates');
	}
	
	public function index($page = 0)
	{
        $this->mdl_email_templates->paginate(site_url('email_templates/index'), $page);
        $email_templates = $this->mdl_email_templates->result();
        
		$this->layout->set('email_templates', $email_templates);
		$this->layout->buffer('content', 'email_templates/index');
		$this->layout->render();
	}
	
	public function form($id = NULL)
	{
		if ($this->input->post('btn_cancel'))
		{
			redirect('email_templates');
		}
		
		if ($this->mdl_email_templates->run_validation())
		{
			$this->mdl_email_templates->save($id);
			redirect('email_templates');
		}
		
		if ($id and !$this->input->post('btn_submit'))
		{
			if (!$this->mdl_email_templates->prep_form($id))
            {
                show_404();
            }
		}
		
		$this->layout->buffer('content', 'email_templates/form');
		$this->layout->render();
	}
	
	public function delete($id)
	{
		$this->mdl_email_templates->delete($id);
		redirect('email_templates');
	}

}

?>