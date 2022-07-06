<?php

#################################################################################################### --- DESCRIPTION

/**
 * Sets the application to the specified locale.
 * 
 * Since there are no common internationalisation (i18n) and localisation (l10n) standards, we use
 * a combination of them to best achieve our goals.
 * 
 * We wish to use the more robust language references of RFC5646 and RFC4647, but at the same time
 * we wish to use the application "gettext" for translation and localisation.
 * 
 * To this end, we maintain an array constant called LOCALES, which contains references to the
 * available languages for the application, and links the RFC5646/RFC4647 language codes (as keys)
 * to their gettext equivalents (as values).
 * 
 * In URLs we use RFC5646 and RFC4647.
 * For translations we use gettext, ISO639 and ISO3166.
 * 
 * Since gettext is a system application, BOTH IT AND THE LOCALES USED WITH IT have to be installed
 * in the system, in order for translations to work.
 * 
 * ***
 * 
 * RFC5646
 * Tags for Identifying Languages
 * https://tools.ietf.org/html/rfc5646
 * 
 * RFC4647
 * Matching of Language Tags
 * https://tools.ietf.org/html/rfc4647
 * 
 * For the commonly used languages, the ISO 639-1 standard defines two-letter codes.
 * For rarely used languages, the ISO 639-2 standard defines three-letter codes.
 * 
 * The ISO 3166 standard defines two character codes for many countries and territories.
 * 
 * @param   $locale   string    The locale to be set.
 */

#################################################################################################### --- FUNCTION DECLARATION

function assignLocale (string $locale = NULL) {
  
#################################################################################################### --- DETERMINE LOCALE
  
  if (array_key_exists ($locale, LOCALES) === true) {
    
    # If a valid locale was passed as a parameter.
    
    $_SESSION['locale'] = $locale;
    
  } else if (array_key_exists ($_GET['locale'], LOCALES) === true) {
    
    # If a valid locale is set in the URL.
    
    $_SESSION['locale'] = $_GET['locale'];
    
  } else if ( ! array_key_exists ($_SESSION['locale'], LOCALES)) {
    
    # If there is no valid locale already set in $_SESSION['locale'].
    
    $_SESSION['locale'] = WEBSITE_DEFAULT_LOCALE;
  }
  
#################################################################################################### --- SET LOCALE
  
  # $_SESSION['locale'] contains an RFC5646/RFC4647 language code. We need to convert it to the
  # gettext-equivalent locale, and pass it to setlocale().
  
  # The gettext locale is the key within LOCALES[$_SESSION['locale']]. This array actually contains
  # only one entry. To quickly get its key, we use:
  
  # array_key_first()
  # Get the first key of the given array without affecting the internal array pointer.
  
  # array_key_first() needs PHP version 7.3.0 or later.
  # An alternative for versions of PHP lower than 7.3.0 is:
  
  /*
  if ( ! function_exists ('array_key_first')) {
    
    function array_key_first (array $input) {
      
      foreach ($input as $key => $unused) {
        
        return $key;
      }
      
      return NULL;
    }
  }
  */
  
  // ----------
  
  setlocale (
    
    LC_ALL,
    
    array_key_first (LOCALES[$_SESSION['locale']]) . '.utf8'
  );
  
}

?>
