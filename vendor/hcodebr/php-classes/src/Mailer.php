<?php

namespace Hcode;

use Rain\Tpl;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use \Hcode\Util\Variaveis;
use \Hcode\Util\Lg;

require_once("email/vendor/autoload.php");

date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');

class Mailer {

    const USERNAME = 'carloalvesjean@gmail.com';
    const PASSWORD = 'Canjica2020';
    const NAME_FROM = 'Ecommerce Store';

    private $mail;
    private $smtpLogs = [];

    public function __construct($toAddress, $toName, $subject, $tplName, $data= array()) {
        $path_app=Variaveis::_getPathApp();
        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$path_app."/views/email/",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"].$path_app."/views-cache/",
            "debug"         => false
           );
        
        Tpl::configure( $config );   

        $tpl = new Tpl;

        foreach ($data as $key => $value) {
            $tpl->assign($key , $value);
        }

        $html = $tpl->draw($tplName, true);

        //Create a new PHPMailer instance
        $this->mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $this->mail->isSMTP();

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        //$this->mail->SMTPDebug =0; //SMTP::DEBUG_SERVER;
        $this->mail->SMTPDebug = 2;

        //Set the hostname of the mail server
        $this->mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $this->mail->SMTPSecure =PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $this->mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $this->mail->Username =Mailer::USERNAME;

        //Password to use for SMTP authentication
        $this->mail->Password = Mailer::PASSWORD;

        //Set who the message is to be sent from
        $this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

        //Set an alternative reply-to address
        //$mail->addReplyTo('contato@vapti.com.br', 'First Last');

        //Set who the message is to be sent to
        $this->mail->addAddress($toAddress,  $toName);

        //Set the subject line
        $this->mail->Subject = $subject;

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $this->mail->msgHTML($html);

        //Replace the plain text body with one created manually
        $this->mail->AltBody = 'Email vindo do site.';

        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
    }

    public function send() {
        $lg = new Lg();
        $lg->log("Mailer.send, ");

        $return = $this->mail->send();

        if (!isset($this->mail->ErrorInfo)) {
            $lg->log("Email, ErrorInfo: ".$this->mail->ErrorInfo);
        }  else {
            $lg->log("Email enviado com sucesso! , return: ".$return);
        }


        // After the send
        //print_r($this->mail->ErrorInfo);
        //printLogs($this->smtpLogs);


        return $return;


        /*
        //send the message, check for errors
        if (!$this->mail->send()) {
            echo 'Mailer Error: '. $this->mail->ErrorInfo;
        } else {
            echo "<script>
            alert('Mensagem enviada com sucesso, em breve entraremos em contato, obrigado.');
            window.location.href = 'index.html';
            </script>";
            //echo 'Email enviado com sucesso, entraremos em contato em breve, obrigado.';
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
        }        
        */
    }

}



?>