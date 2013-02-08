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

function phpmail_send($from, $to, $subject, $message, $attachment_path = NULL, $cc = NULL, $bcc = NULL)
{
	require_once(APPPATH . 'modules/mailer/helpers/phpmailer/class.phpmailer.php');

	$CI = & get_instance();
	$CI->load->library('encrypt');

	// Create the basic mailer object
	$mail			 = new PHPMailer();
	$mail->CharSet	 = 'UTF-8';
	$mail->IsHtml();
	$mail->IsSMTP();

	// Set the basic properties
	$mail->Host		 = $CI->mdl_settings->setting('smtp_server_address');
	$mail->Port		 = $CI->mdl_settings->setting('smtp_port');
	$mail->Subject	 = $subject;
	$mail->Body		 = $message;

	// Is SMTP authentication required?
	if ($CI->mdl_settings->setting('smtp_authentication'))
	{
		$mail->SMTPAuth	 = TRUE;
		$mail->Username	 = $CI->mdl_settings->setting('smtp_username');
		$mail->Password	 = $CI->encrypt->decode($CI->mdl_settings->setting('smtp_password'));
	}

	// Is a security method required?
	if ($CI->mdl_settings->setting('smtp_security'))
	{
		$mail->SMTPSecure = $CI->mdl_settings->setting('smtp_security');
	}

	if (is_array($from))
	{
		// This array should be address, name
		$mail->SetFrom($from[0], $from[1]);
	}
	else
	{
		// This is just an address
		$mail->SetFrom($from);
	}

	// Allow multiple recipients delimited by comma or semicolon
	$to = (strpos($to, ',')) ? explode(',', $to) : explode(';', $to);

	// Add the addresses
	foreach ($to as $address)
	{
		$mail->AddAddress($address);
	}

	if ($cc)
	{
		// Allow multiple CC's delimited by comma or semicolon
		$cc = (strpos($cc, ',')) ? explode(',', $cc) : explode(';', $cc);

		// Add the CC's
		foreach ($cc as $address)
		{
			$mail->AddCC($address);
		}
	}

	if ($bcc)
	{
		// Allow multiple BCC's delimited by comma or semicolon
		$bcc = (strpos($bcc, ',')) ? explode(',', $bcc) : explode(';', $bcc);

		// Add the BCC's
		foreach ($bcc as $address)
		{
			$mail->AddBCC($address);
		}
	}

	// Add the attachment if supplied
	if ($attachment_path)
	{
		$mail->AddAttachment($attachment_path);
	}

	// And away it goes...
	if ($mail->Send())
	{
		$CI->session->set_flashdata('alert_success', 'The email has been sent');

		return TRUE;
	}
	else
	{
		// Or not...

		$CI->session->set_flashdata('alert_error', $mail->ErrorInfo);

		return FALSE;
	}
}

?>