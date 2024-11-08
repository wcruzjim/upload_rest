<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;

class PHPMailer_Lib
{
    public function __construct(){
      log_message('Debug','PHPMailer class is loaded.');
    }

    public function load(){
      require_once APPPATH.'third_party/phpmailer/phpmailer/src/Exception.php';
      require_once APPPATH.'third_party/phpmailer/phpmailer/src/PHPMailer.php';
      require_once APPPATH.'third_party/phpmailer/phpmailer/src/SMTP.php';

      $mail = new PHPMailer;

      $CI = & get_instance();
      $CI->load->model('GeneralModel');

      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';
      $mail->isSMTP();
      $mail->SMTPDebug = 0;

      $mail->SMTPOptions = array(
          'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          )
      );

      $host = EnvLoader::getEnv('SMTP_HOST');
      $password = EnvLoader::getEnv('SMTP_PASSWORD');
      $port = EnvLoader::getEnv('SMTP_PORT');
      $smtp_auth = filter_var(  EnvLoader::getEnv('SMTP_AUTH_EMAIL') , FILTER_VALIDATE_BOOLEAN);
      $address = EnvLoader::getEnv('SMTP_EMAIL');
      $address_from = EnvLoader::getEnv('SMTP_ADDRESS_FROM');
      $smtp_secure = EnvLoader::getEnv('SMTP_SECURE_EMAIL');
      $smtp_secure = $smtp_secure === 'false' ? false : $smtp_secure;

      $mail->Host = $host;
      $mail->Port = $port;
      $mail->SMTPAuth = $smtp_auth;
      $mail->Username = $address;
      $mail->SMTPSecure = $smtp_secure;
      $mail->setFrom($address_from, 'Jarvis');
      $mail->Password = $password;
      $mail->isHTML(true);


      return $mail;
    }
}
