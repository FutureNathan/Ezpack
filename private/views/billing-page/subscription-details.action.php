<?php

#################################################################################################### --- SUBSCRIPTION INFO

$checkSubscriptionQ = pg_query($dbc['read_only'], sprintf("
  SELECT
    subscription_id,
    subscription_title,
    TO_CHAR(subscription_start_date, 'DD Mon YYYY') AS \"subscriptionStartDate\",
    TO_CHAR(subscription_renewal_date, 'DD Mon YYYY') AS \"subscriptionRenewalDate\"
  FROM subscriptions
  WHERE subscription_user_id = '%s'
  AND subscription_renewal_date > NOW()
  ",
  pg_escape_string($dbc['read_only'], $_SESSION['user_id'])
));

#################################################################################################### --- INVOICES

$getInvoicesQ = pg_query($dbc['read_only'], sprintf("
  SELECT
    invoice_id,
    invoice_total_amount,
    TO_CHAR(invoice_issue_date, 'DD-MM-YYYY') AS \"invoiceIssueDate\"
  FROM invoices
  WHERE invoice_user_id = '%s'
  ",
  pg_escape_string($dbc['read_only'], $_SESSION['user_id'])
));

?>
