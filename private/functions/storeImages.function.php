<?php

function storeImages ($fileTempLocation, $section, $item_id, $coverImage = false, $draftImageItemId = false, $draftImageIndex = false, $maxSizeDimension = 'width') {
  
  global $dbc;
  
#################################################################################################### --- INPUT VALIDATION
  
  if ( ! isEmpty (filter_var ($draftImageItemId, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['any_digits_positive']))))) {
    
    if ($section === 'authors') {
      
      $draftImageSQL = sprintf("
        SELECT da_author_info ->> 'author_image' AS image
        FROM draft_authors
        WHERE da_id = '%s'
        ",
        pg_escape_string($dbc['read_only'], $draftImageItemId)
      );
      
    } elseif ($section === 'publishers') {
      
      $draftImageSQL = sprintf("
        SELECT dp_publisher_info ->> 'publisher_profile_image' AS image
        FROM draft_publishers
        WHERE dp_id = '%s'
        ",
        pg_escape_string($dbc['read_only'], $draftImageItemId)
      );
      
    } elseif ($section === 'books') {
      
      $selectBookImagesQ = pg_query($dbc['read_write'], sprintf("
        SELECT *
        FROM
          draft_books,
          jsonb_to_recordset(draft_books.db_book_info -> 'book_images') AS (image text)
        WHERE draft_books.db_id = '%s'
        ",
        pg_escape_string($dbc['read_write'], $draftImageItemId)
      ));
      
      $selectBookImages = pg_fetch_all($selectBookImagesQ, PGSQL_ASSOC);
    }
    
    $draftImageQ = pg_query($dbc['read_only'], $draftImageSQL);
    
    $draftImageR = pg_fetch_assoc($draftImageQ);
    
    $imageBinaryString = hex2bin($draftImageR['image']);
    
    if ($section === 'books') {
      $imageBinaryString = hex2bin($selectBookImages[$draftImageIndex]['image']);
    }
    
  } else {
    
    $imageBinaryString = getBinaryString ($fileTempLocation);
  }
  
#################################################################################################### --- GET ORIGINAL IMAGE
  
  $image = imagecreatefromstring ($imageBinaryString);
  
  $imgX = imagesx($image);
  $imgY = imagesy($image);
  
#################################################################################################### --- CREATE IMAGE VERSIONS
  
  $finalImages = array();
  
  if ($section === 'users' || $section === 'publishers' || $section === 'authors') {
    
    $imageSizes = [
      30,
      50,
      100,
      150,
      200,
      250,
      300,
      350,
      400,
      450,
      500,
      600
    ];
    
  } else if ($section === 'books') {
    
    $imageSizes = [
      30,
      50,
      100,
      150,
      200,
      250,
      300,
      350,
      400,
      450,
      500,
      600,
      700,
      800,
      900,
      1000
    ];
    
  } elseif ($section == 'blog') {
    
    $imageSizes = [
      50,
      100,
      150,
      200,
      250,
      300,
      350,
      400,
      450,
      500,
      600,
      700,
      800
    ];
  }
  
  // ----------
  
  foreach($imageSizes as $maxSize){
    
    // Fotot ruhen sipas përmasës që është zgjedhur $maxSizeDimension.
    // By deafult ruhen sipas "width".
    
    if ($maxSizeDimension === 'width') {
      
      if ($imgX > $maxSize) {
        
        $thumbX = $maxSize;
        $thumbY = floor ($imgY * ($maxSize / $imgX));
        
      } else {
        
        # If the original image is smaller than this size, copy the original
        
        $finalImages[$maxSize] = $imageBinaryString;
        
        continue;
      }
      
    } elseif ($maxSizeDimension === 'height') {
      
      if ($imgY > $maxSize) {
        
        $thumbY = $maxSize;
        $thumbX = floor ($imgX * ($maxSize / $imgY));
        
      } else {
        
        # If the original image is smaller than this size, copy the original
        
        $finalImages[$maxSize] = $imageBinaryString;
        
        continue;
      }
    }
    
    // ----------
    
    $pageBuffer = ob_get_contents();
    ob_end_clean();
    ob_start();
    
    // ----------
    
    $thumb = imagecreatetruecolor($thumbX, $thumbY);
    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumbX, $thumbY, $imgX, $imgY);
    imagejpeg($thumb, NULL, 90);
    
    // ----------
    
    $resizedImage = ob_get_contents();
    
    ob_end_clean();
    ob_start();
    
    echo $pageBuffer;
    
    // ----------
    
    $finalImages[$maxSize] = $resizedImage;
  }
  
#################################################################################################### --- BEGIN TRANSACTION
  
  pg_query ($dbc['read_write'], 'BEGIN');
  
  $txIdQ = pg_query ($dbc['read_write'], "
    SELECT txid_current()
  ");
  
  $txIdR = pg_fetch_assoc ($txIdQ);
  
#################################################################################################### --- STORE IMAGES IN THE DATABASE
  
  if ($section === 'users') {
    
    $imagesTableName = 'image_sets_profile';
    
  } else if ($section === 'publishers') {
    
    $imagesTableName = 'image_sets_publisher';
    
  } else if ($section === 'authors') {
    
    $imagesTableName = 'image_sets_author';
    
  }
  
  if ($section === 'users' || $section === 'publishers' || $section === 'authors') {
    
    $insertImgQ = pg_query($dbc['read_write'], sprintf("
      
      INSERT INTO $imagesTableName (
        img_original,
        img_30,
        img_50,
        img_100,
        img_150,
        img_200,
        img_250,
        img_300,
        img_350,
        img_400,
        img_450,
        img_500,
        img_600
      )
      
      VALUES (
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex')
      )
      
      RETURNING img_id
      ",
      
      bin2hex($imageBinaryString),
      
      bin2hex($finalImages['30']),
      bin2hex($finalImages['50']),
      bin2hex($finalImages['100']),
      bin2hex($finalImages['150']),
      bin2hex($finalImages['200']),
      bin2hex($finalImages['250']),
      bin2hex($finalImages['300']),
      bin2hex($finalImages['350']),
      bin2hex($finalImages['400']),
      bin2hex($finalImages['450']),
      bin2hex($finalImages['500']),
      bin2hex($finalImages['600'])
    ));
    
  } else if ($section === 'books') {
    
    $insertImgQ = pg_query($dbc['read_write'], sprintf("
      
      INSERT INTO image_sets_books (
        img_original,
        img_30,
        img_50,
        img_100,
        img_150,
        img_200,
        img_250,
        img_300,
        img_350,
        img_400,
        img_450,
        img_500,
        img_600,
        img_700,
        img_800,
        img_900,
        img_1000
      )
      
      VALUES (
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex')
      )
      
      RETURNING img_id
      ",
      
      bin2hex($imageBinaryString),
      
      bin2hex($finalImages['30']),
      bin2hex($finalImages['50']),
      bin2hex($finalImages['100']),
      bin2hex($finalImages['150']),
      bin2hex($finalImages['200']),
      bin2hex($finalImages['250']),
      bin2hex($finalImages['300']),
      bin2hex($finalImages['350']),
      bin2hex($finalImages['400']),
      bin2hex($finalImages['450']),
      bin2hex($finalImages['500']),
      bin2hex($finalImages['600']),
      bin2hex($finalImages['700']),
      bin2hex($finalImages['800']),
      bin2hex($finalImages['900']),
      bin2hex($finalImages['1000'])
    ));
    
  } else if ($section === 'blog') {
    
    $insertImgQ = pg_query($dbc['read_write'], sprintf("
      
      INSERT INTO image_sets_blog (
        img_original,
        img_50,
        img_100,
        img_150,
        img_200,
        img_250,
        img_300,
        img_350,
        img_400,
        img_450,
        img_500,
        img_600,
        img_700,
        img_800
      )
      
      VALUES (
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex'),
        DECODE('%s', 'hex')
      )
      
      RETURNING img_id
      ",
      
      bin2hex($imageBinaryString),
      
      bin2hex($finalImages['50']),
      bin2hex($finalImages['100']),
      bin2hex($finalImages['150']),
      bin2hex($finalImages['200']),
      bin2hex($finalImages['250']),
      bin2hex($finalImages['300']),
      bin2hex($finalImages['350']),
      bin2hex($finalImages['400']),
      bin2hex($finalImages['450']),
      bin2hex($finalImages['500']),
      bin2hex($finalImages['600']),
      bin2hex($finalImages['700']),
      bin2hex($finalImages['800'])
    ));
  }
  
  // ----------
  
  $insertImgR = pg_fetch_assoc($insertImgQ);
  
  $image_id = $insertImgR['img_id'];
  
  if($section == 'books'){
    $dbName = 'book_image';
    $itemName = 'book_id';
  }elseif($section == 'draft_books'){
    $dbName = 'draft_book_image';
    $itemName = 'book_id';
  }
  
  // ----------
  
  if ($section == 'draft_books' || $section == 'books') {
    
    $si_query = sprintf("
      INSERT INTO $dbName ($itemName, image_id)
      VALUES ('%s', '%s')
      ",
      
      pg_escape_string($dbc['read_write'], $item_id),
      pg_escape_string($dbc['read_write'], $image_id)
    );
    
    pg_query($dbc['read_write'], $si_query);
    
    // ----------
    
    if ($coverImage === true) {
      
      $mainImgQ = sprintf("
        UPDATE books
        SET book_cover_image_id = '%s'
        WHERE book_id = '%s'
        ",
        
        pg_escape_string($dbc['read_write'], $image_id),
        pg_escape_string($dbc['read_write'], $item_id)
      );
      
      pg_query($dbc['read_write'], $mainImgQ);
    }
  }
  
  // ----------
  
  if ($section == 'authors') {
    
    $updateImageIdQ = pg_query($dbc['read_write'], sprintf("
      UPDATE $section
      SET author_image_id = '%s'
      WHERE author_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $image_id),
      pg_escape_string($dbc['read_write'], $item_id)
    ));
    
  }
  
  // ----------
  
  if ($section == 'publishers') {
    $updateImageIdQ = pg_query($dbc['read_write'], sprintf("
      UPDATE $section
      SET publisher_profile_image_id = '%s'
      WHERE publisher_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $image_id),
      pg_escape_string($dbc['read_write'], $item_id)
    ));
  }
  
  // ----------
  
  if ($section == 'users') {
    $updateImageIdQ = pg_query($dbc['read_write'], sprintf("
      UPDATE users
      SET user_photo_id = '%s'
      WHERE user_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $image_id),
      pg_escape_string($dbc['read_write'], $item_id)
    ));
  }
  
  // ----------
  
  if ($section == 'blog') {
    
    $blogImage = pg_query($dbc['read_write'], sprintf("
      INSERT INTO blog_image (blog_id, image_id)
      VALUES ('%s', '%s')
      ",
      
      pg_escape_string($dbc['read_write'], $item_id),
      pg_escape_string($dbc['read_write'], $image_id)
    ));
    
    if ($coverImage === true) {
      
      $updateCoverImageQ = pg_query($dbc['read_write'], sprintf("
        UPDATE blog
        SET blog_main_image_id = '%s'
        WHERE blog_id = '%s'
        ",
        pg_escape_string($dbc['read_write'], $image_id),
        pg_escape_string($dbc['read_write'], $item_id)
      ));
    }
  }
  
  // ----------
  
  pg_query ($dbc['read_write'], 'COMMIT');
  
#################################################################################################### --- HANDLE FEEDBACK
  
  $txStatusQ = pg_query ($dbc['read_write'], sprintf ("
    SELECT txid_status ('%s')
    ",
    pg_escape_string ($dbc['read_write'], $txIdR['txid_current'])
  ));
  
  $txStatusR = pg_fetch_assoc ($txStatusQ);
  
  if ($txStatusR['txid_status'] === 'committed') {
    
    if (pg_affected_rows($insertImgQ) === 1) {
      
      return true;
    }
  }
  
}

?>
