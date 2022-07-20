<?php

#################################################################################################### --- NAMESPACES

use \Dompdf\Dompdf;
use \Dompdf\Options;

#################################################################################################### --- VALIDATE REQUEST

# Validate the invoice ID and account ID

if (isEmpty (filter_var ($viewOptions['invoiceId'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]]))) {
  exit;
}

if ($_SESSION['userRole'] !== 'admin') {

  if (isEmpty (filter_var ($viewOptions['invoiceUserId'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]]))) {
    exit;
  }
}

#################################################################################################### --- GET INVOICE DATA

# TODO: make sure that user is either owner of invoice, or admin

# Get invoice's details

if ($_SESSION['userRole'] === 'admin') {
  
  $invoiceQ = pg_query ($dbc['read_only'], sprintf ("
    SELECT
      invoices.*,
      TO_CHAR (invoice_issue_date, 'DD-MM-YYYY') AS \"invoiceIssueDate\",
      TO_CHAR (invoice_issue_date, 'DD Mon YYYY') AS \"invoiceIssueDateMonthFormat\",
      TO_CHAR (invoice_sub_renewal_date, 'DD Mon YYYY') AS \"invoiceSubRenewalDate\"
      
    FROM invoices
    
    LEFT JOIN subscriptions
    ON subscriptions.subscription_user_id = invoices.invoice_user_id
    
    WHERE invoice_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $viewOptions['invoiceId'])
  ));
  
} else {

  $invoiceQ = pg_query ($dbc['read_only'], sprintf ("
    SELECT
      invoices.*,
      TO_CHAR (invoice_issue_date, 'DD-MM-YYYY') AS \"invoiceIssueDate\",
      TO_CHAR (invoice_issue_date, 'DD Mon YYYY') AS \"invoiceIssueDateMonthFormat\",
      TO_CHAR (invoice_sub_renewal_date, 'DD Mon YYYY') AS \"invoiceSubRenewalDate\",
      
      invoices.invoice_customer_details ->> 'user_name' AS user_name,
      invoices.invoice_customer_details ->> 'user_email' AS user_email,
      invoices.invoice_customer_details ->> 'user_phone_number' AS user_phone_number,
      invoices.invoice_customer_details ->  'billing_address' AS billing_address,
      
      invoices.invoice_stripe_details ->> 'stripe_invoice_id' AS stripe_invoice_id,
      invoices.invoice_stripe_details ->> 'stripe_sub_id' AS stripe_sub_id,
      
      subscription_title
      
    FROM invoices
    
    LEFT JOIN subscriptions
    ON subscriptions.subscription_user_id = invoices.invoice_user_id
    
    WHERE invoice_id = '%s'
    AND invoice_user_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $viewOptions['invoiceId']),
    pg_escape_string($dbc['read_only'], $viewOptions['invoiceUserId'])
  ));
}

#################################################################################################### --- 

# If invoice was not found, exit

if (pg_num_rows ($invoiceQ) !== 1) {
  exit;
}

$invoiceR = pg_fetch_assoc($invoiceQ);

$customerBillingAddress = json_decode($invoiceR['billing_address'], true);

#################################################################################################### --- INVOICE CONTENT

# Create the invoice structure

$invoiceHtml = '
  <html>
    <head>
      <style>
    
        h2 {
          font-size: 32px;
          margin: 0px 0px 16px 0px;
        }
        
        h3 {
          font-size: 24px;
          margin: 0px 0px 16px 0px;
        }
        
        ul {
          list-style-type: none;
          padding: 0px;
          margin: 0px;
        }
        
        p {
          margin-bottom: 0px;
          margin-top: 8px;
        }
        
        .rightAlign {
          text-align: right;
        }
        
        #invoiceContent {
          position: relative;
        }
        
        #invoiceHead {
          margin-bottom: 50px;
        }
        
        #invoiceHead img {
          height: 2.5em;
          margin-bottom: 1em;
        }
        
        #invoiceHead > h2 {
          position: absolute;
          top: 0px;
          right: 0px;
        }
        
        #invoiceHead > .invoiceStatus {
          position: absolute;
          top: 42px;
          right: 0px;
          
          color: #228b22;
          font-weight: 700;
        }
        
        #invoiceHeading {
          position: absolute;
          top: 0px;
          right: 0px;
        }
        
        #invoiceHeading > .invoiceStatus {
          color: #228b22;
          font-weight: 700;
        }
        
        #invoiceBody {
          position: relative;
          padding-bottom: 32px;
          margin-bottom: 32px;
          
          border-bottom: 1px solid #ced4da;
        }
        
        #invoiceDetails {
          position: absolute;
          top: 0px;
          left: 500px;
        }
        
        #invoiceDetails span {
          font-weight: 700;
        }
        
        #invoiceTable {
          width: 100%;
          border-collapse: collapse;
        }
        
        thead {
          background-color: #f1f4f7;
        }
        
        th {
          text-align: left;
        }
        
        th,
        td {
          padding: 16px;
          border-top: 1px solid #ced4da;
        }
        
        #invoiceTotal {
          padding: 16px;
          margin: 0px;
          border-top: 1px solid #ced4da;
        }
        
        #invoiceTotal span {
          font-weight: bold;
          padding-right: 1em;
        }
        
      </style>
    </head>
    
    <body>
      <div id="invoiceContent">
        <div id="invoiceHead">
        
          <div>
            <!--<img src="' . getPubUrl('application-common', 'images/crewin-logo.png') . '">-->
            
            <h2>
              Ezpack
            </h2>
          </div>  
          
          <h2>Invoice</h2>
          <p class="invoiceStatus">PAID</p>
        </div>
        
        <div id="invoiceBody">  
          
          <ul id="invoiceRecipient">
            <h3>Recipient</h3>
            
            <li>' . escape ($invoiceR['user_name']) . '</li>
            <li>' . escape ($invoiceR['user_email']) . '</li>
            <li>' . escape ($invoiceR['user_phone_number']) . '</li>
            <li>' . escape ($customerBillingAddress['address_street_and_number']) . '</li>
            <li>' . escape ($customerBillingAddress['address_city']) . ', ' . escape ($customerBillingAddress['address_zip_code']) . '</li>
            <li>' . escape ($customerBillingAddress['address_country']) . '</li>
          </ul>
          
          <div id="invoiceDetails">
            <p>
              <span>INVOICE #  </span>
              ' . escape ($invoiceR['invoice_id']) . '
            </p>
            
            <p>
              <span>ISSUE DATE: </span>
              ' . escape ($invoiceR['invoiceIssueDate']) . '
            </p>
          </div>
          
        </div>
        
        <table id="invoiceTable">
          <thead>
            <tr>
              <th>DESCRIPTION</th>
              <th class="rightAlign">AMOUNT</th>
            </tr>
          </thead>
          
          <tbody>
            <tr>
              <td>' . escape ($invoiceR['subscription_title']) . ' (' . $invoiceR['invoiceIssueDateMonthFormat'] . ' - ' . $invoiceR['invoiceSubRenewalDate'] . ')</td>
              <td class="rightAlign">' . escape ($invoiceR['invoice_total_amount']) . ' USD</td>
            </tr>
          </tbody>
        </table>
        
        <p id="invoiceTotal" class="rightAlign">
          <span>TOTAL</span>
          ' . escape ($invoiceR['invoice_total_amount']) . ' USD
        </p>
      </div>
    </body>
  </html>
';

#################################################################################################### --- 

# Render the PDF with dompdf

require_once PATH_PRIVATE_THIRD_PARTY . 'dompdf/autoload.inc.php';

$options = new Options();
$options->set('defaultFont', 'helvetica');

# Display image
$options->set('isRemoteEnabled', true);
 
$dompdf = new DOMPDF($options);

$dompdf->loadHtml($invoiceHtml, 'UTF-8');
$dompdf->render();

#################################################################################################### ---

# If streamOutput option is TRUE, we use stream() (to show in browser) 

if ($viewOptions['streamOutput'] === true) {
  $dompdf->stream("Ezpack-Invoice-" . $invoiceR['invoice_id'] . "-" . $invoiceR['invoiceIssueDate']);
  #print_r($invoiceHtml);
}

#################################################################################################### ---

$insertViewResult = [
  'invoiceDetails' => $invoiceR,
  'pdfOutput'      => $dompdf->output()
];

#################################################################################################### ---

?>
