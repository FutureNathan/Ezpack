<?php

#################################################################################################### --- LOAD WEBSITE SETTINGS

foreach (glob ('private/settings/*.settings.php') as $settingsFilePath) {
  
  if ((is_file ($settingsFilePath) === true) && (is_readable ($settingsFilePath) === true)) {
    
    require_once $settingsFilePath;
  }
}

#################################################################################################### --- START OUTPUT BUFFER

ob_start();

#################################################################################################### --- START SESSION

session_start();

#################################################################################################### --- LOAD PHP FUNCTIONS

foreach (glob (PATH_PRIVATE_FUNCTIONS . '*.function.php') as $functionFilePath) {
  
  if ((is_file ($functionFilePath) === true) && (is_readable ($functionFilePath) === true)) {
    
    require_once $functionFilePath;
    
  }
}

#################################################################################################### --- SET / UPDATE LOCALE

assignLocale();

#################################################################################################### --- GET VIEWS STRUCTURE

define ('VIEWS', getViewsStructure());

#################################################################################################### --- SET USER ROLE

setUserRole();

#################################################################################################### --- CONNECT TO DB

$dbc = [
  'read_only'   => dbConnect ('read_only'),
  'read_write'  => dbConnect ('read_write')
];

#################################################################################################### --- 

pg_query ($dbc['read_write'], 'BEGIN');

// ----------

# Get transaction ID

$txIdQ = pg_query ($dbc['read_write'], "
  SELECT txid_current()
");

$txIdR = pg_fetch_assoc ($txIdQ);

####################################################################################################

$products = [
  [
    'name'    => 'J 8',
    'length'  => '8',
    'width'   => '5',
    'height'  => '3',
    'price_box_only'   => '249'
  ],
  
  [
    'name'    => 'Cellphone',
    'length'  => '10',
    'width'   => '6',
    'height'  => '3',
    'price_box_only'   => '699'
  ],
  
  [
    'name'    => '6 Cube',
    'length'  => '6',
    'width'   => '6',
    'height'  => '6',
    'price_box_only'   => '325'
  ],
  
  [
    'name'    => 'J 11',
    'length'  => '10',
    'width'   => '7',
    'height'  => '4',
    'price_box_only'   => '299'
  ],
  
  [
    'name'    => '8 cube',
    'length'  => '8',
    'width'   => '8',
    'height'  => '8',
    'price_box_only'   => '399'
  ],
  
  [
    'name'    => 'VJ 14',
    'length'  => '12',
    'width'   => '9',
    'height'  => '6',
    'price_box_only'   => '499'
  ],
  
  [
    'name'    => 'J 15',
    'length'  => '13',
    'width'   => '11',
    'height'  => '5',
    'price_box_only'   => '425'
  ],
  
  [
    'name'    => 'Shirt A',
    'length'  => '16',
    'width'   => '10',
    'height'  => '5',
    'price_box_only'   => '499'
  ],
  
  [
    'name'    => 'Comcast/Modem',
    'length'  => '12',
    'width'   => '12',
    'height'  => '6',
    'price_box_only'   => '669'
  ],
  
  [
    'name'    => '14 x 8 x 8',
    'length'  => '14',
    'width'   => '8',
    'height'  => '8',
    'price_box_only'   => '699'
  ],
  
  [
    'name'    => 'Umbrella',
    'length'  => '4',
    'width'   => '4',
    'height'  => '58',
    'price_box_only'   => '1499'
  ],
  
  [
    'name'    => '10 Cube',
    'length'  => '10',
    'width'   => '10',
    'height'  => '10',
    'price_box_only'   => '475'
  ],
  
  [
    'name'    => 'Shirt B',
    'length'  => '18',
    'width'   => '14',
    'height'  => '4',
    'price_box_only'   => '599'
  ],
  
  [
    'name'    => 'J 16',
    'length'  => '15',
    'width'   => '11',
    'height'  => '7',
    'price_box_only'   => '499'
  ],
  
  [
    'name'    => 'Sm. Wreath',
    'length'  => '18',
    'width'   => '18',
    'height'  => '4',
    'price_box_only'   => '799'
  ],
  
  [
    'name'    => 'J 17',
    'length'  => '18',
    'width'   => '13',
    'height'  => '6',
    'price_box_only'   => '599'
  ],
  
  [
    'name'    => '17x11x18',
    'length'  => '17',
    'width'   => '11',
    'height'  => '18',
    'price_box_only'   => '699'
  ],
  
  [
    'name'    => 'Laptop',
    'length'  => '21',
    'width'   => '16',
    'height'  => '5',
    'price_box_only'   => '3199'
  ],
  
  [
    'name'    => '12 Cube',
    'length'  => '12',
    'width'   => '12',
    'height'  => '12',
    'price_box_only'   => '599'
  ],
  
  [
    'name'    => '6x6 Lamp',
    'length'  => '6',
    'width'   => '6',
    'height'  => '48',
    'price_box_only'   => '1499'
  ],
  
  [
    'name'    => 'Shirt C',
    'length'  => '24',
    'width'   => '15',
    'height'  => '5',
    'price_box_only'   => '699'
  ],
  
  [
    'name'    => 'Dyson',
    'length'  => '15',
    'width'   => '12',
    'height'  => '10',
    'price_box_only'   => '699'
  ],
  
  [
    'name'    => 'J 20',
    'length'  => '18',
    'width'   => '13',
    'height'  => '9',
    'price_box_only'   => '649'
  ],
  
  [
    'name'    => 'Ski (half so 2x)',
    'length'  => '42',
    'width'   => '9',
    'height'  => '6',
    'price_box_only'   => '1199'
  ],
  
  [
    'name'    => 'Ski (half so 2x)',
    'length'  => '42',
    'width'   => '9',
    'height'  => '6',
    'price_box_only'   => '1199'
  ],
  
  [
    'name'    => 'Md. Wreath',
    'length'  => '20',
    'width'   => '20',
    'height'  => '6',
    'price_box_only'   => '899'
  ],
  
  [
    'name'    => 'PR 8',
    'length'  => '18',
    'width'   => '12',
    'height'  => '12',
    'price_box_only'   => '599'
  ],
  
  [
    'name'    => '24x18x6',
    'length'  => '24',
    'width'   => '18',
    'height'  => '6',
    'price_box_only'   => '1199'
  ],
  
  [
    'name'    => 'J 22',
    'length'  => '20',
    'width'   => '15',
    'height'  => '9',
    'price_box_only'   => '725'
  ],
  
  [
    'name'    => '14 Cube',
    'length'  => '14',
    'width'   => '14',
    'height'  => '14',
    'price_box_only'   => '649'
  ],
  
  [
    'name'    => 'Lg. Wreath',
    'length'  => '24',
    'width'   => '24',
    'height'  => '6',
    'price_box_only'   => '1199'
  ],
  
  [
    'name'    => '16 Cube',
    'length'  => '16',
    'width'   => '16',
    'height'  => '16',
    'price_box_only'   => '799'
  ],
  
  [
    'name'    => 'J 64',
    'length'  => '22',
    'width'   => '15',
    'height'  => '13',
    'price_box_only'   => '699'
  ],
  
  [
    'name'    => 'J 24',
    'length'  => '24',
    'width'   => '18',
    'height'  => '10',
    'price_box_only'   => '925'
  ],
  
  [
    'name'    => '30x24x6',
    'length'  => '30',
    'width'   => '24',
    'height'  => '6',
    'price_box_only'   => '1190'
  ],
  
  [
    'name'    => 'J 70',
    'length'  => '20',
    'width'   => '15',
    'height'  => '15',
    'price_box_only'   => '799'
  ],
  
  [
    'name'    => 'UPS 4',
    'length'  => '22',
    'width'   => '18',
    'height'  => '12',
    'price_box_only'   => '899'
  ],
  
  [
    'name'    => 'ST 10',
    'length'  => '20',
    'width'   => '20',
    'height'  => '12',
    'price_box_only'   => '1099'
  ],
  
  [
    'name'    => '10x10 Lamp',
    'length'  => '10',
    'width'   => '10',
    'height'  => '48',
    'price_box_only'   => '1499'
  ],
  
  [
    'name'    => 'VCR/CD',
    'length'  => '20',
    'width'   => '20',
    'height'  => '12',
    'price_box_only'   => '1099'
  ],
  
  [
    'name'    => '30" mirror',
    'length'  => '30',
    'width'   => '30',
    'height'  => '6',
    'price_box_only'   => '1599'
  ],
  
  [
    'name'    => '18 Cube',
    'length'  => '18',
    'width'   => '18',
    'height'  => '18',
    'price_box_only'   => '850'
  ],
  
  [
    'name'    => 'J 57',
    'length'  => '26',
    'width'   => '18',
    'height'  => '13',
    'price_box_only'   => '975'
  ],
  
  [
    'name'    => 'MG 1',
    'length'  => '32',
    'width'   => '22',
    'height'  => '10',
    'price_box_only'   => '1499'
  ],
  
  [
    'name'    => 'Suitcase',
    'length'  => '24',
    'width'   => '10',
    'height'  => '31',
    'price_box_only'   => '1399'
  ],
  
  [
    'name'    => '36" Mirror',
    'length'  => '36',
    'width'   => '36',
    'height'  => '6',
    'price_box_only'   => '1699'
  ],
  
  [
    'name'    => '20 Cube',
    'length'  => '20',
    'width'   => '20',
    'height'  => '20',
    'price_box_only'   => '999'
  ],
  
  [
    'name'    => 'Guitar',
    'length'  => '20',
    'width'   => '8',
    'height'  => '50',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => '13x13 Lamp',
    'length'  => '13',
    'width'   => '13',
    'height'  => '48',
    'price_box_only'   => '1499'
  ],
  
  [
    'name'    => 'Snowboard',
    'length'  => '16',
    'width'   => '8',
    'height'  => '65',
    'price_box_only'   => '2999'
  ],
  
  [
    'name'    => '42" Mirror',
    'length'  => '42',
    'width'   => '6',
    'height'  => '36',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => 'UPS 2',
    'length'  => '24',
    'width'   => '24',
    'height'  => '16',
    'price_box_only'   => '1799'
  ],
  
  [
    'name'    => '24x24x16',
    'length'  => '24',
    'width'   => '24',
    'height'  => '16',
    'price_box_only'   => '1799'
  ],
  
  [
    'name'    => 'Golf',
    'length'  => '19',
    'width'   => '11',
    'height'  => '46',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => 'TV',
    'length'  => '27',
    'width'   => '20',
    'height'  => '18',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => 'UPS 1',
    'length'  => '20',
    'width'   => '20',
    'height'  => '25',
    'price_box_only'   => '1199'
  ],
  
  [
    'name'    => '24x24x18',
    'length'  => '24',
    'width'   => '24',
    'height'  => '18',
    'price_box_only'   => '1799'
  ],
  
  [
    'name'    => '48" Mirror',
    'length'  => '36',
    'width'   => '48',
    'height'  => '6',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => 'New Golf',
    'length'  => '14',
    'width'   => '14',
    'height'  => '53',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => '22 Cube',
    'length'  => '22',
    'width'   => '22',
    'height'  => '22',
    'price_box_only'   => '1599'
  ],
  
  [
    'name'    => 'UPS 3',
    'length'  => '33',
    'width'   => '18',
    'height'  => '18',
    'price_box_only'   => '1795'
  ],
  
  [
    'name'    => '15x15 Lamp',
    'length'  => '15',
    'width'   => '15',
    'height'  => '48',
    'price_box_only'   => '2499'
  ],
  
  [
    'name'    => 'Bike',
    'length'  => '55',
    'width'   => '8',
    'height'  => '28',
    'price_box_only'   => '3999'
  ],
  
  [
    'name'    => '24 Cube',
    'length'  => '24',
    'width'   => '24',
    'height'  => '24',
    'price_box_only'   => '1799'
  ],
  
  [
    'name'    => '26 Cube',
    'length'  => '26',
    'width'   => '26',
    'height'  => '26',
    'price_box_only'   => '2699'
  ],
  
  [
    'name'    => 'BB 130',
    'length'  => '34',
    'width'   => '22',
    'height'  => '24',
    'price_box_only'   => '1999'
  ],
  
  [
    'name'    => 'Wardrobe',
    'length'  => '25',
    'width'   => '22',
    'height'  => '46',
    'price_box_only'   => '3499'
  ],
  
  [
    'name'    => 'Chair',
    'length'  => '30',
    'width'   => '29',
    'height'  => '34',
    'price_box_only'   => '4999'
  ]
];

foreach($products as $product) {

  pg_query($dbc['read_only'], sprintf("
    INSERT INTO vendor_products (
      vendor_prod_name,
      vendor_prod_type,
      
      vendor_prod_length,
      vendor_prod_width,
      vendor_prod_height,
      
      vendor_prod_price_box_only,
      vendor_prod_price_standard,
      vendor_prod_price_basic,
      vendor_prod_price_fragile,
      vendor_prod_price_custom
    )
    VALUES ('%s', 'ups', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
    ",
    pg_escape_string($dbc['read_only'], $product['name']),
    pg_escape_string($dbc['read_only'], $product['length']),
    pg_escape_string($dbc['read_only'], $product['width']),
    pg_escape_string($dbc['read_only'], $product['height']),
    pg_escape_string($dbc['read_only'], $product['price_box_only']),
    pg_escape_string($dbc['read_only'], $product['price_box_only'] + 200),
    pg_escape_string($dbc['read_only'], $product['price_box_only'] + 400),
    pg_escape_string($dbc['read_only'], $product['price_box_only'] + 600),
    pg_escape_string($dbc['read_only'], $product['price_box_only'] + 1200)
  ));
}

####################################################################################################

pg_query ($dbc['read_write'], 'COMMIT');

$txStatusQ = pg_query ($dbc['read_write'], sprintf ("
  SELECT txid_status ('%s')
  ",
  pg_escape_string ($dbc['read_write'], $txIdR['txid_current'])
));

$txStatusR = pg_fetch_assoc ($txStatusQ);

if ($txStatusR['txid_status'] !== 'committed') {
  
  print_r('TRANSACTION ABORTED');
  
} else {
  
  print_r('SUCCESS');
}


?>
