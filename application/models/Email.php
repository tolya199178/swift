<?
/**
* Send emails with Zend Mailer
*/
class Email{

	protected $_fromEmail = "myiartz@myiartz.com";
	protected $_fromName = "MyIartz";


	/**
	* Method to send mail
	* @param array $options ($message, $to, $subject)
	*/
	public function sendMail($options){
		//$config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'myiartz@myiartz.com', 'password' => 'iruyeqij');
		//$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
		
		//Message Gears
		$config = array('ssl' => 'tls', 'port' => 2525, 'auth' => 'login', 'username' => '66917973', 'password' => '85cd337542324ca6addd1e821349dbf2');
		$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.messagegears.net', $config);
	
		$html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><html><head></head><body style=\"background: #e2e2e2;\"><div style=\"width:600px !important; font-family:Arial; margin:auto; border:1px solid #e2e2e2; padding:15px; background: #fff; \" width=\"600\" ><table width='600' style='background: #fff; width:600px;'><tr><td valign='top'>".$options['message']."</td></tr></table><br><br><br></div><br><br><br></body></html>";
		$html = wordwrap($html,50);
		$html = preg_replace('/\x00+/', '', $html);
		$mail = new Zend_Mail('utf-8');
		//$mail->setBodyText(strip_tags($options['message']));
		$mail->setBodyHTML($html);
		$mail->setFrom($this->_fromEmail, $this->_fromName);
		$mail->addTo($options['to']);
		if(isset($options['Bcc'])){
			$mail->addBcc($options['Bcc']);
		}
		if(isset($options['cc'])){
			$mail->addCc($options['cc']);
		}
		$mail->setSubject($options['subject']);
		$mail->send($smtpConnection);
		
	}

}