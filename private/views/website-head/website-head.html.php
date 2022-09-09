<!DOCTYPE html>

<html lang="<?php echo $_SESSION['locale']; ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="content-language" content="<?php echo $_SESSION['locale']; ?>">
  
  <title></title>
  <!--<link rel="icon" type="image/png" href="<?php echo getPubUrl ('application-common', 'images/favicon-40x40.png'); ?>">-->
  
  <meta name="description" content="">
  <meta name="keywords" content="">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- START HEAD PRELOAD --><!-- END HEAD PRELOAD -->
  
  <!-- START HEAD CSS --><!-- END HEAD CSS -->
  
  <!-- START HEAD JS --><!-- END HEAD JS -->
  
  <!-- START HEAD LINKED DATA --><!-- END HEAD LINKED DATA -->
  
  <?php
    foreach (array_keys (LOCALES) as $localeTag) {
      
      if ($localeTag !== $_SESSION['locale']) {
        
        echo '<link rel="alternate" hreflang="' . $localeTag . '" href="' . WEBSITE_BASE_URL . $localeTag . '">' . "\n";
      }
    }
  ?>
  
  <meta property="og:site_name" content="">
  <meta property="og:title" content="">
  <meta property="og:description" content="">
  <meta property="og:image" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  
  
</head>

<?php
/**
 * The value of $viewOptions is passed as a second parameter on insertView() calls, and is used to request
 * specific, customised versions of the requested view.
 * 
 * The logic utilising $viewOptions should be placed within the requested view's own files.
 */
?>

<body class="<?php echo $viewOptions['version']; ?>">
