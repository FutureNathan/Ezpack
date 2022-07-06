<?php

#################################################################################################### --- DESCRIPTION

/**
 * Creates a feedback message.
 * 
 * The message is a HTML list, wherein each individual message is a list item. It is meant to be
 * shown to users, informing them of the result of an action they undertook.
 * 
 * @param   $messageList  array     The list of messages to be displayed.
 * 
 * @param   $messageType  string    The type of message, will be styled differently based on what
 *                                  kind of feedback it is, ie. success, error, attention, etc.
 * 
 * @return                string    The feedback string, as a HTML list.
 */

#################################################################################################### --- FUNCTION DECLARATION

function feedbackMessage (array $messageList, string $messageType) {
  
  $feedback = '
    
    <ul class="' . $messageType . '">';
      
      foreach ($messageList as $message) {
        
        $feedback .= '<li>' . $message . '</li>';
      }
      
      $feedback .= '
    </ul>
  ';
  
  // ----------
  
  return $feedback;
}

?>
