<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

#################################################################################################### --- INCLUDE DEPENDENCIES

require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/PHPMailer.php';
require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/SMTP.php';
require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/Exception.php';

#################################################################################################### --- SEND EMAIL NOTIFICATION

function sendNotification ($notificationTitle, $params) {
  
  global $dbc;
  
  $sentStatus = [];
  
#################################################################################################### --- GET NOTIFICATION DATA
  
  $notificationDataQ = pg_query($dbc['read_only'], sprintf("
    SELECT *
    FROM notification_templates
    WHERE notif_identifier = '%s'
    ",
    pg_escape_string($dbc['read_only'], $notificationTitle)
  ));
  
  $notificationDataR = pg_fetch_assoc($notificationDataQ);
 
#################################################################################################### --- REPLACE PLACEHOLDERS
  
  if ($params['email'] === true) {
    
    if (isEmpty($from)) {
      
      $fromEmail = WEBSITE_DEFAULT_EMAIL;
      $fromName  = WEBSITE_DEFAULT_EMAIL_FROM;
      
    } else {
      
      $fromEmail = $from['email'];
      $fromName  = $from['name'];
    }
    
    // ----------
    
    # Replace the link placholders
    
    foreach ($params['linkPlaceholders'] as $pattern => $url) {
      
      # gje nëse teksti brenda anchor është në vetëvete placeholder
      preg_match ("~$pattern~", $notificationDataR['notif_template_email_body'], $matches);
      
      if ( ! isEmpty ($params['linkPlaceholders'][$matches[1]])) {
        
        # nqs është placeholder, e zëvëndëson me vlerën përkatëse
        $anchorText = $params['linkPlaceholders'][$matches[1]];
        
      } else {
        
        # nqs nuk është placeholder, e zëvëndëson me capturing group
        $anchorText = '$1';
      }
      
      $links[] = '<a href="' . $url . '">' . $anchorText . '</a>';
      
    }
    
    $template = preg_replace (array_keys($params['linkPlaceholders']), $links, $notificationDataR['notif_template_email_body']);
    
    // ----------
    
    # Replace the plain text placholders
    
    if ($params['textPlaceholders']) {
      
      $notificationSubject = str_replace (array_keys($params['textPlaceholders']), $params['textPlaceholders'], $notificationDataR['notif_template_email_subject']);
      
      if ($template) {
      
        $notificationBody    = nl2br(str_replace (array_keys($params['textPlaceholders']), $params['textPlaceholders'], $template));
        
      } else {
        
        $notificationBody    = nl2br(str_replace (array_keys($params['textPlaceholders']), $params['textPlaceholders'], $notificationDataR['notif_template_email_body']));
      }
      
    } else {
      
      $notificationSubject = $notificationDataR['notif_template_email_subject'];
      
      if ($template) {
        
        $notificationBody    = nl2br($template);
        
      } else {
        
        $notificationBody    = nl2br($notificationDataR['notif_template_email_body']);
      }
    }
    
    // ----------
    
    $mail = new PHPMailer (true);
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);
    
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = SMTP['info@libroteka.al']['host'];
    $mail->Port = SMTP['info@libroteka.al']['port'];
    $mail->Username = SMTP['info@libroteka.al']['username'];
    $mail->Password = SMTP['info@libroteka.al']['password'];
    
    $mail->setFrom ($fromEmail, $fromName);
    
    $mail->Subject = $notificationSubject;
    $mail->Body = $notificationBody;
    $mail->addAddress ($params['to']);
    
    // ----------
    
    if ($mail->send()) {
      
      return true;
      
    } else {
      
      # Email was not sent, error log
      
      error_log('################################################################################' . "\n");
      
      error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ EMAIL NOTIFICATIONS' . "\n");
      
      error_log('----------' . "\n");
      
      error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ Attempting to send "' . $notificationTitle . '" email notification to ' . $params['to'] . ' with PHPMailer' . "\n");
      
      error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ Email could not be sent to ' . $params['to'] . "\n");
      
      error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ PHPMailer object: ' . $mail . "\n");
      
      error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ /home/git-repos/libroteka.al.git/private/functions/sendNotification.function.php' . "\n");
    }
    
  }
  
#################################################################################################### --- REPLACE PLACEHOLDERS
  
  if ($params['website'] === true) {
    
    $notificationText = str_replace (array_keys($params['textPlaceholders']), $params['textPlaceholders'], $notificationDataR['notif_template_web']);
    
    // ----------
    
    // send user website notification
    
    if ($notificationDataR['notif_user'] === 't') {
      
      if (!isEmpty($params['imageTableName']) && !isEmpty($params['notifImageId'])) {
        
        $userNotifImage = [
          
          $params['imageTableName'] => $params['notifImageId']
        ];
      }
      
      // ----------
      
      $insertUserNotificationQ = pg_query($dbc['read_write'], sprintf("
        INSERT INTO web_notifications_users (
          wnu_text,
          wnu_link,
          wnu_date,
          wnu_image,
          wnu_user_id
        )
        
        VALUES ('%s', '%s', NOW(), '%s', '%s')
        ",
        pg_escape_string($dbc['read_write'], $notificationText),
        pg_escape_string($dbc['read_write'], $params['link']),
        pg_escape_string($dbc['read_write'], json_encode($userNotifImage)),
        pg_escape_string($dbc['read_write'], $params['user_id'])
      ));
      
      if (pg_affected_rows($insertUserNotificationQ) === 1) {
        
        $sentStatus['websiteNotification'] = true;
      }
    }
    
    // ----------
    
    // send admin website notification
    
    if ($notificationDataR['notif_admin'] === 't') {
      
      if (!isEmpty($params['imageTableName']) && !isEmpty($params['notifImageId'])) {
        
        $adminNotifImage = [
          
          $params['imageTableName'] => $params['notifImageId']
        ];
      }
      
      // ----------
      
      $insertAdminNotificationQ = pg_query($dbc['read_write'], sprintf("
        INSERT INTO web_notifications_admin (
          wna_text,
          wna_link,
          wna_date,
          wna_image
        )
        
        VALUES ('%s', '%s', NOW(), '%s')
        ",
        pg_escape_string($dbc['read_write'], $notificationText),
        pg_escape_string($dbc['read_write'], $params['link']),
        pg_escape_string($dbc['read_write'], json_encode($adminNotifImage))
      ));
      
      if (pg_affected_rows($insertAdminNotificationQ) === 1) {
        
        $sentStatus['websiteNotification'] = true;
      }
    }
  }
  
#################################################################################################### --- 
  
  return $sentStatus;
  
}

?>
