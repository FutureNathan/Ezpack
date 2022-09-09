<?php

#################################################################################################### --- VALID ASSETS LIST

# List of valid asset types and their associated filename extensions.

const VALID_ASSETS = [
  
  'includes' => [
    
    // assets which are included within the code
    // these must be written in the order they will be included
    
    'text'        =>  ['.text.php'], // TODO: perhaps remove?
    'functions'   =>  ['.function.php'],
    'actions'     =>  ['.action.php'],
    'html'        =>  ['.html.php']
  ],
  
  // ----------
  
  'components' => [
    
    // external components which are linked to from the main page
    
    'css'         =>  ['.css.php'],
    'mo.js'       =>  ['.mo.js.php'],
    'foot.js'     =>  ['.foot.js.php'],
    'head.js'     =>  ['.head.js.php']
  ],
  
  // ----------
  
  'resources' => [
    
    // linked resources
    
    'fonts'       =>  ['.woff', '.woff2', '.ttf', '.otf'],
    'images'      =>  ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
    
    'documents'   =>  [
      
      // OpenDocument
      '.odt',   /*  text processing  */             '.fodt',  /*  text processing uncompressed XML  */
      '.ods',   /*  spreadsheets  */                '.fods',  /*  spreadsheets uncompressed XML  */
      '.odp',   /*  presentations  */               '.fodp',  /*  presentations uncompressed XML  */
      '.odg',   /*  graphics  */                    '.fodg',  /*  graphics uncompressed XML  */
      
      // Microsoft
      '.docx',  /*  Word document  */               '.dotx',  /*  Word template  */
      '.xlsx',  /*  Excel workbook  */              '.xltx',  /*  Excel template  */
      '.pptx',  /*  PowerPoint presentation  */     '.potx',  /*  PowerPoint template  */
      '.ppsx',  /*  PowerPoint slideshow  */        '.pub',   /*  Publisher  */
      
      // Adobe
      '.pdf',   /*  Acrobat or Adobe Reader  */     '.psd',   /*  Photoshop  */
      '.ai',    /*  Illustrator  */                 '.indd',  /*  InDesign  */
      
      // Corel
      '.cdr',   /*  CorelDRAW  */                   '.cpt'    /*  Photo-Paint  */
    ],
    
    'archives'    =>  [
      '.tar',
      '.gz',  '.tar.gz',  '.tgz',
      '.xz',  '.tar.xz',  '.txz',
      '.bz2', '.tar.bz2', '.tbz2',
      
      '.zip', '.rar', '.7z'
    ]
  ],
  
  // ----------
  
  'handlers' => [
    
    // assets which are used on demand
    
    'ajax'        =>  ['.ajax.php']
  ]
];

#################################################################################################### --- ASSET TYPES AND EXTENSIONS

$assetTypes = [];
$assetExtensions = [];

// ----------

foreach (VALID_ASSETS as $group => $list) {
  
  foreach ($list as $type => $extensions) {
    
    $assetTypes[] = $type;
    
    foreach ($extensions as $extension) {
      
      $assetExtensions[$extension] = $type;
    }
  }
}

// ----------

define ('ASSET_TYPES', $assetTypes);
define ('ASSET_EXTENSIONS', $assetExtensions);

// ----------

# We set these as constants, so we don't need them anymore.

unset ($assetTypes);
unset ($assetExtensions);

#################################################################################################### --- VALID MIME TYPES

const VALID_MIME_TYPES = [
  
  'images' => [
    'image/jpeg',
    'image/png',
    'image/gif'
  ],
  
  'documents' => [
    
    'text/plain',
    
    // Adobe
    'application/pdf',    /*  Acrobat or Adobe Reader  */
    
    'application/epub+zip',
    
    // Microsoft
    'application/msword',                                                         /*  Word document  .doc  */
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',    /*  Word document  .docx */
    
  ]
];

#################################################################################################### --- MIME TYPES WITH MIME KEYS

$mimeTypes = [];

// ----------

foreach (VALID_MIME_TYPES as $fileType => $list) {
    
    foreach ($list as $mime) {
      
      $mimeTypes[$mime] = $fileType;
    }
  }

// ----------

define ('MIME_TYPES', $mimeTypes);

// ----------

# We set these as constants, so we don't need them anymore.

unset ($mimeTypes);

?>
