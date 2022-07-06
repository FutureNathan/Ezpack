<?php

#################################################################################################### --- DESCRIPTION

/**
 * Sends email with PHPMailer
 *
 * https://github.com/PHPMailer/PHPMailer/blob/master/README.md
 * 
 * @param   array         $emailParams     All the details we need to send the email (email addres, subject, body etc...).
 * 
 * @return  true|false                     TRUE if the email was sent, FALSE otherwise
 */

#################################################################################################### --- INCLUDE DEPENDENCIES

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/PHPMailer.php';
require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/SMTP.php';
require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/Exception.php';

 
#################################################################################################### --- FUNCTION DECLARATION 

function send_email (array $emailParams) {
  
  $mail = new PHPMailer (true);
  
  $mail->CharSet = "UTF-8";

#################################################################################################### --- SERVER SETTINGS
  
  # The commented server settings maybe are not needed????
  
//   $mail->SMTPDebug  = 2;
  $mail->isSMTP();
  $mail->SMTPAuth   = true;
  $mail->Host       = SMTP[WEBSITE_EMAIL]['host'];
  $mail->Username   = SMTP[WEBSITE_EMAIL]['username'];
  $mail->Password   = SMTP[WEBSITE_EMAIL]['password'];
  $mail->SMTPSecure = 'tls';
  $mail->Port       = SMTP[WEBSITE_EMAIL]['port'];
  
#################################################################################################### --- SENDER
  
  $mail->setFrom ($emailParams['senderEmailAddress'], $emailParams['senderName']);
  
#################################################################################################### --- CONTENT
  
  if ($emailParams['isHTML'] === true) {
    
    $mail->isHTML(true);
  }
  
  $mail->Subject = $emailParams['emailSubject'];
  $mail->Body    = $emailParams['emailBody'];
  
#################################################################################################### --- ADD ATTACHMENTS
  
  if (! isEmpty($emailParams['attachmentFiles'])) {
    
    foreach($emailParams['attachmentFiles'] as $fileName => $attachmentFile) {
      $mail->addStringAttachment ($attachmentFile, $fileName);
    }
  }
  
#################################################################################################### --- RECIPIENTS
  
  if (! isEmpty($emailParams['recipientEmailAddresses'])) {
    
    foreach($emailParams['recipientEmailAddresses'] as $recipientEmailAddress) {
      $mail->addAddress($recipientEmailAddress);
    }
  }
  
  // ----------
  
  if (! isEmpty($emailParams['ccEmailAddresses'])) {
    
    foreach($emailParams['ccEmailAddresses'] as $ccEmailAddress) {
      $mail->addCC($ccEmailAddress);
    }
  }
  
  // ----------
  
  if (! isEmpty($emailParams['bccEmailAddresses'])) {
    
    foreach($emailParams['bccEmailAddresses'] as $bccEmailAddress) {
      $mail->addBCC($bccEmailAddress);
    }
  }
  
#################################################################################################### --- SEND EMAIL
  
  if ($mail->send()) {
    
    return true;
    
  } else {
    log_error(["message" => "Message could not be sent. PHPMailer error:" . $mail->ErrorInfo]);
  }
  
  return false;
}

/*
Sample function call

$subject = 'blablabal';
$body    = 'blablabla';

send_email ([
  
  'senderEmailAddress'    => 'info@crewin.com'
  'senderName'            => 'Crewin',
  
  'recipientEmailAddress' => 'alesia@ketri.al',
  
  
  // if the attachment is a binary string
  'attachmentFiles'       => ['filename' => file, ],
  
  // if the attachmet is from a path on the filesystem
  'attachmentFiles'       => ['filename' => file_get_contents(string $file-path) ],
  
  
  'ccEmailAddresses'      => ['asd1d@asd1.asd1', 'asd2d@asd2.asd2'],
  'bccEmailAddresses'     => ['asd1d@asd1.asd1', 'asd2d@asd2.asd2'],
  
  'emailSubject'          => $subject,
  'emailBody'             => $body,
  
  'isHTML'                => true
]);
*/

?>
