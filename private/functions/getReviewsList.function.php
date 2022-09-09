<?php

#################################################################################################### --- DESCRIPTION

/**
 * TODO: funksioni mund të fshihet dhe reviews të shfaqen me pagination dhe search-in e ri
 */

#################################################################################################### --- FUNCTION DECLARATION

function getReviewsList($bookId, $limit, $offset, $orderBy, $sortBy, $type = 'ajax'){
  
  global $dbc;
  
  if ($_SESSION['userRole'] === 'registered') {
    $where = "AND book_reviews.review_user_id <> '" . pg_escape_string($dbc['read_write'], $_SESSION['user_id']) . "'";
  } else {
    $where = '';
  }
  
  $selectBookReviewsQ = pg_query($dbc['read_write'], sprintf("
    SELECT
      book_reviews.*,
      users.*,
      (
        SELECT COUNT(brl_like)
        FROM book_review_likes
        WHERE book_review_likes.brl_review_id = book_reviews.review_id
        AND brl_like = 'true'
      ) AS review_likes,
      (
        SELECT COUNT(brl_like)
        FROM book_review_likes
        WHERE book_review_likes.brl_review_id = book_reviews.review_id
        AND brl_like = 'false'
      ) AS review_dislikes
    
    FROM book_reviews
      LEFT JOIN users
      ON book_reviews.review_user_id = users.user_id
    
    WHERE book_reviews.review_book_id = '%s'
    $where
    
    ORDER BY %s %s
    
    LIMIT %s OFFSET %s
    ",
    pg_escape_string($dbc['read_write'], $bookId),
    pg_escape_string($dbc['read_write'], $orderBy),
    pg_escape_string($dbc['read_write'], $sortBy),
    pg_escape_string($dbc['read_write'], $limit),
    pg_escape_string($dbc['read_write'], $offset)
  ));
  
  $selectBookReviews = pg_fetch_all($selectBookReviewsQ, PGSQL_ASSOC);
  
  //check if user liked review
  foreach ($selectBookReviews AS $review){
    
    $checkUserLikedReviewQ = pg_query($dbc['read_write'], sprintf("
      SELECT brl_like
      FROM book_review_likes
      WHERE brl_user_id = '%s'
      AND brl_review_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_SESSION['user_id']),
      pg_escape_string($dbc['read_write'], $review['review_id'])
    ));
    
    $checkUserLikedReviewR = pg_fetch_assoc($checkUserLikedReviewQ);
    
    // ----------
    
    $reviewLikesQ = pg_query($dbc['read_write'], sprintf("
      SELECT COUNT (brl_like) AS \"totalLikes\"
      FROM book_review_likes
      WHERE brl_like = 'true'
      AND brl_review_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $review['review_id'])
    ));
    
    $reviewLikes = pg_fetch_assoc($reviewLikesQ);
    
    // ----------
    
    $reviewDislikesQ = pg_query($dbc['read_write'], sprintf("
      SELECT COUNT (brl_like) AS \"totalDislikes\"
      FROM book_review_likes
      WHERE brl_like = 'false'
      AND brl_review_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $review['review_id'])
    ));
    
    $reviewDislikes = pg_fetch_assoc($reviewDislikesQ);
    
    // ----------
    
    $reviewData[] = [
      'id'                => $_POST['book_id'],
      'user_display_name' => $review['user_display_name'],
      'user_first_name'   => $review['user_first_name'],
      'user_last_name'    => $review['user_last_name'],
      'review_id'         => $review['review_id'],
      'review_score'      => $review['review_score'],
      'review_title'      => (isEmpty($review['review_title']) ? '' : $review['review_title']),
      'review_content'    => (isEmpty($review['review_content']) ? '' : $review['review_content']),
      'review_likes'      => $reviewLikes['totalLikes'],
      'review_dislikes'   => $reviewDislikes['totalDislikes'],
      'user_liked_review' => $checkUserLikedReviewR['brl_like']
    ];
    
  }
  
  if ($type === 'ajax') {
    
    return $reviewData;
    
  } else {
    
    return $selectBookReviews;
  }
}

?>
