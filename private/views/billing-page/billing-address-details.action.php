<?php

$userDetailsQ = pg_query($dbc['read_only'], sprintf("
  SELECT
    user_name,
    user_email,
    user_phone_number,
    user_name
  FROM users
  WHERE user_id = '%s'
  ",
  pg_escape_string($dbc['read_only'], $_SESSION['user_id'])
));

$userDetailsR = pg_fetch_assoc($userDetailsQ);

?>
