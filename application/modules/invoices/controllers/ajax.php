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

class Ajax extends Admin_Controller {

    public $ajax_controller = TRUE;

    public function save()
    {
        $this->load->model('invoices/mdl_items');
        $this->load->model('invoices/mdl_invoices');

        $invoice_id = $this->input->post('invoice_id');
        
        $this->mdl_invoices->set_id($invoice_id);

        if ($this->mdl_invoices->run_validation('validation_rules_save_invoice'))
        {
            $items = json_decode($this->input->post('items'));

            foreach ($items as $item)
            {
                if ($item->item_name)
                {
                    $item->item_quantity = standardize_amount($item->item_quantity);
                    $item->item_price    = standardize_amount($item->item_price);

                    $item_id = ($item->item_id) ?: NULL;
                    
                    unset($item->item_id);
                    
                    $this->mdl_items->save($invoice_id, $item_id, $item);
                }
            }

            $db_array = array(
                'invoice_number'       => $this->input->post('invoice_number'),
                'invoice_terms'        => $this->input->post('invoice_terms'),
                'invoice_date_created' => date_to_mysql($this->input->post('invoice_date_created')),
                'invoice_date_due'     => date_to_mysql($this->input->post('invoice_date_due'))
            );

            $this->mdl_invoices->save($invoice_id, $db_array);

            $response = array(
                'success' => 1
            );
        }
        else
        {
            $this->load->helper('json_error');
            $response = array(
                'success'           => 0,
                'validation_errors' => json_errors()
            );
        }

        if ($this->input->post('custom'))
        {
            $db_array = array();

            foreach ($this->input->post('custom') as $custom)
            {
                // I hate myself for this...
                $db_array[str_replace(']', '', str_replace('custom[', '', $custom['name']))] = $custom['value'];
            }

            $this->load->model('custom_fields/mdl_invoice_custom');
            $this->mdl_invoice_custom->save_custom($invoice_id, $db_array);
        }

        echo json_encode($response);
    }

    public function save_invoice_tax_rate()
    {
        $this->load->model('invoices/mdl_invoice_tax_rates');

        if ($this->mdl_invoice_tax_rates->run_validation())
        {
            $this->mdl_invoice_tax_rates->save($this->input->post('invoice_id'));

            $response = array(
                'success' => 1
            );
        }
        else
        {
            $response = array(
                'success'           => 0,
                'validation_errors' => $this->mdl_invoice_tax_rates->validation_errors
            );
        }

        echo json_encode($response);
    }

    public function create()
    {
        $this->load->model('invoices/mdl_invoices');

        if ($this->mdl_invoices->run_validation())
        {
            $invoice_id = $this->mdl_invoices->create();

            $response = array(
                'success'    => 1,
                'invoice_id' => $invoice_id
            );
        }
        else
        {
            $this->load->helper('json_error');
            $response = array(
                'success'           => 0,
                'validation_errors' => json_errors()
            );
        }

        echo json_encode($response);
    }
    
    public function create_recurring()
    {
        $this->load->model('invoices/mdl_invoices_recurring');

        if ($this->mdl_invoices_recurring->run_validation())
        {
            $this->mdl_invoices_recurring->save();
            
            $response = array(
                'success'    => 1,
            );
        }
        else
        {
            $this->load->helper('json_error');
            $response = array(
                'success'           => 0,
                'validation_errors' => json_errors()
            );
        }
        
        echo json_encode($response);
    }

    public function get_item()
    {
        $this->load->model('invoices/mdl_items');

        $item = $this->mdl_items->get_by_id($this->input->post('item_id'));

        echo json_encode($item);
    }

    public function modal_create_invoice()
    {
        $this->load->module('layout');

        $this->load->model('invoice_groups/mdl_invoice_groups');
        $this->load->model('tax_rates/mdl_tax_rates');

        $data = array(
            'invoice_groups' => $this->mdl_invoice_groups->get()->result(),
            'tax_rates'      => $this->mdl_tax_rates->get()->result(),
            'client_name'    => $this->input->post('client_name')
        );

        $this->layout->load_view('invoices/modal_create_invoice', $data);
    }

    public function modal_create_recurring()
    {
        $this->load->module('layout');

        $this->load->model('mdl_invoices_recurring');

        $data = array(
            'invoice_id'            => $this->input->post('invoice_id'),
            'recur_frequencies' => $this->mdl_invoices_recurring->recur_frequencies
        );

        $this->layout->load_view('invoices/modal_create_recurring', $data);
    }
    
    public function get_recur_start_date()
    {
        $invoice_date = $this->input->post('invoice_date');
        $recur_frequency = $this->input->post('recur_frequency');
        
        echo increment_user_date($invoice_date, $recur_frequency);
    }

    public function modal_copy_invoice()
    {
        $this->load->module('layout');

        $this->load->model('invoices/mdl_invoices');
        $this->load->model('invoice_groups/mdl_invoice_groups');
        $this->load->model('tax_rates/mdl_tax_rates');

        $data = array(
            'invoice_groups' => $this->mdl_invoice_groups->get()->result(),
            'tax_rates'      => $this->mdl_tax_rates->get()->result(),
            'invoice_id'     => $this->input->post('invoice_id'),
            'invoice'        => $this->mdl_invoices->where('fi_invoices.invoice_id', $this->input->post('invoice_id'))->get()->row()
        );

        $this->layout->load_view('invoices/modal_copy_invoice', $data);
    }

    public function copy_invoice()
    {
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('invoices/mdl_items');
        $this->load->model('invoices/mdl_invoice_tax_rates');

        if ($this->mdl_invoices->run_validation())
        {
            $target_id = $this->mdl_invoices->save();
            $source_id = $this->input->post('invoice_id');

            $this->mdl_invoices->copy_invoice($source_id, $target_id);

            $response = array(
                'success'    => 1,
                'invoice_id' => $target_id
            );
        }
        else
        {
            $response = array(
                'success'           => 0,
                'validation_errors' => json_errors()
            );
        }

        echo json_encode($response);
    }

}

?>