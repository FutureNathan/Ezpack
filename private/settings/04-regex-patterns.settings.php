<?php

#################################################################################################### --- SYSTEM REGEX PATTERNS

# Standard regex patterns, some of which are used by the system.

const SYSTEM_REGEX = [
  
  'single_digit'            =>  '~^[0-9]$~',
  'single_digit_positive'   =>  '~^[1-9]$~',
  
  'any_digits'              =>  '~^[0-9]+$~',
  'any_digits_positive'     =>  '~^[1-9][0-9]*$~',
  'any_digits_optional'     => '~^[0-9]*$~',
  
  'latin_letters_lower'     =>  '~^[a-z]+$~',
  'latin_letters_upper'     =>  '~^[A-Z]+$~',
  'latin_letters_all'       =>  '~^[A-Za-z]+$~',
  
  'alphanumeric_lower'      =>  '~^[a-z0-9]+$~',
  'alphanumeric_upper'      =>  '~^[A-Z0-9]+$~',
  'alphanumeric_all'        =>  '~^[A-Za-z0-9]+$~',
  
  'alphanumeric_all_40'     =>  '~^[A-Za-z0-9]{40}$~',
  'alphanumeric_all_80'     =>  '~^[A-Za-z0-9]{80}$~',
  
  'crc32b'                  =>  '~^[a-f0-9]{8}$~',
  'md5'                     =>  '~^[a-f0-9]{32}$~',
  'sha1'                    =>  '~^[a-f0-9]{40}$~',
  'sha256'                  =>  '~^[a-f0-9]{64}$~',
  
  'email_address'           =>  '~^[A-Za-z0-9_.+-]+@[A-Za-z0-9-.]+\.[A-Za-z0-9]+$~',
  
  'view_asset_path'         =>  '~^(?<fullPath>' . PATH_PRIVATE_VIEWS . '(?<viewName>[a-z0-9\-]+)/(?<relativePath>(?:(?<directoryType>[a-z.]+)/)?(?<fileName>[A-Za-z0-9_\-]+)(?<fileExtension>\.[a-z0-9.]+)))$~'
];

#################################################################################################### --- APPLICATION REGEX PATTERNS

# Regex patterns that are relevant only to the specific application.

const APPLICATION_REGEX = [
  
  'contact_field_name'             => '~^[\p{L}\p{M}\p{N} \'.-]+$~u',
  'contact_field_subject'          => '~^[\p{L}\p{M}\p{N}\p{P} \'.-]+$~u',
  'contact_field_message'          => '~^[\p{L}\p{M}\p{N}\p{P}\p{S}\p{Z}\s]+$~u',
  
  'safe'                           => '~^[\p{L}\p{M}\p{N}\p{P}\p{S}\p{Z}\s]+$~u',
  'safe_optional'                  => '~^[\p{L}\p{M}\p{N}\p{P}\p{Sm}\p{Sc}\s]*$~u',
  
  'latin_letters_optional'         =>  '~^[A-Za-z]*$~',
  'sq_letters_lower'               =>  '~^[a-zëç]*$~',
  
  'date'                           => '~^[0-9]{4}-[0-9]{2}-[0-9]{2}$~',
  'date_optional'                  => '~^(?:[0-9]{4}-[0-9]{2}-[0-9]{2})*$~',
  'time_optional'                  => '~^(?:[0-9]{2}:[0-9]{2})*$~',
  
  'zip_code_optional'              => '~^[A-Za-z0-9 \'().-]*$~',
  'registration_number_optional'   => '~^[A-Za-z0-9 -]*$~',
  'phone_number'                   => '~^[0-9 +()/-]+$~',
  'phone_number_optional'          => '~^[0-9 +()/-]*$~',
  'isbn'                           => '~^[0-9- ]+$~',
  'isbn_optional'                  => '~^[0-9- ]*$~',
  
  'orderLetter'                    => '~^[a-zA-Z]*$~',
  'review_score'                   => '~^[1-5]$~',
  'sort_by'                        => '~^(ASC|DESC)$~',
  'reviews_order_by'               => '~^(review_date_time|review_likes|review_dislikes|review_score|review_likes\+review_dislikes)$~',
  'users_order_by'                 => '~^(user_first_name|user_registration_date)$~',
  'boolean'                        => '~^yes|no$~',
  'favourites'                     => '~^(yes|no|except)$~',
  'status'                         => '~^(draft|ne-pritje-per-miratim|publikuar|refuzuar|joaktiv|arkivuar)$~',
  'user_status'                    => '~^(registered|confirmed|deactivated)$~',
  'book_status_download'           => '~^(draft|publikuar|blere|falas)$~',
  'format'                         => '~^(epub|pdf|source)$~',
  'price_optional'                 => '~^[0-9-><]*$~',
  'image_size'                     => '~^(original|30|50|100|150|200|250|300|350|400|450|500|600|700|800|900|1000)$~',
  
  'true'                           => '~^true$~',
  
  'blog_sub_heading'               => '~<h2>(?<title>.+?)</h2>~',
  
  'book_search_parameters'         => '~^/libra(/s/(?<searchString>[a-z0-9-]+))?(/c/(?<categories>[a-z-_]+))?(/p/(?<publishers>[a-z-_]+))?(/pr/(?<prices>[a-z0-9-_]+))?$~',
  
  'contains_number'                => '~[0-9]~',
  
  'contains_letter_lower'          => '~[a-z]~',
  'contains_unicode_letter_lower'  => '~[\p{Ll}]~u',
  
  'contains_letter_upper'          => '~[A-Z]~',
  'contains_unicode_letter_upper'  => '~[\p{Lu}]~u',
  
  'contains_unicode_symbol'        => '~[\p{S}\p{P}]~u'
];

?>
