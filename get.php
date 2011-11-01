<?php
  //z:\usr\local\php5\php.exe

  require_once ('FeedAnalyser.class.php');


  $_POST['email'] = 'coder24ru';
  $_POST['pass'] = 'fairy24tale';
  $_POST['label'] = 'RusFreelance';
  $_POST['limit'] = '3';

  $login = $_POST['email'];
  $pass = $_POST['pass'];
  $label = $_POST['label'];
  $limit = $_POST['limit'];

  $action = $_GET['action'];
  //$label = $_GET['label'];

  $feedAnalyser = new FeedAnalyser( $login, $pass );
  //$feedAnalyser->cashLabel( $label );
  switch ( $action ) {
      case '':
      case 'index':
          $actions = array( 'index', 'get_init', 'get_labels', 'download_feed', 'get_feed', 'view_feed', 'clear_unrated_feeditems', 'clear_feed', 'rate_feed_item' );
          $actions[] = 'get_weight_words';
          $actions[] = 'recount_words_weight';
          $actions[] = 'count_weight';
          $actions[] = 'get_weight_words';
          
          foreach ( $actions as $act ) {
              echo "<p><a href=\"?action=$act\">$act</a>";
          }
          break;
      case 'get_init':
          $feedAnalyser->save();
          echo 1;
          break;
      case 'get_labels':
          $feedAnalyser->load();
          $labels = $feedAnalyser->getLables();
          var_dump( $labels );
          break;
      case 'download_feed':
          $feedAnalyser->load();
          $feedAnalyser->cashLabel( $label );
          $feedAnalyser->save();
          echo 1;
          break;
      case 'get_feed':
          $feedAnalyser->load();
          $feed = $feedAnalyser->getFeed( $label );
          var_dump( $feedAnalyser );
          $feed->sort();
          var_dump( $feed->getItems() );
          break;
      case 'view_feed':
          $feedAnalyser->load();
          $feed = $feedAnalyser->getFeed( $label );
          $feed->sort();
          $items = $feed->getItems();
          require_once 'view.php';
          die();
          echo '<b>'.count($items).'</b>';
          echo '<table>';
          echo '<tr><th>id<th>weigth<th>rating<th>vote';
          foreach ($items  as $feedItem ) {
              /**
               * $var FeedItem $feedItem
               */
              echo '<div class="feeditem"><tr><td colspan=4><br><br><b>' . $feedItem->getTitle() .'</b> '. $feedItem->getContent();
              echo '<tr><td>' . $feedItem->getId() . '<td>' . $feedItem->getWeight() . '<td>' . $feedItem->getRating() . '<td>';
              for ( $i = -5; $i <= 5; $i++ ) {
                  $link = '?action=rate_feed_item&label=' . $label . '&id=' . $feedItem->getId() . '&rating=' . $i;
                  echo '<a href="' . $link . '">[' . $i . ']</a> ';
              }
          }
          echo '</table>';
          break;
      case 'clear_unrated_feeditems':
          $feedAnalyser->load();
          $feedAnalyser->clearUnratedFeedItems( $label );
          $feedAnalyser->save();
          echo 1;
          break;
      case 'clear_feed':
          $feedAnalyser->load();
          $feedAnalyser->clearFeed( $label );
          $feedAnalyser->save();
          echo 1;
          break;
      case 'rate_feed_item':
          $feedAnalyser->load();
          $id = $_GET['id'];
          $rating = $_GET['rating'];
          echo $feedAnalyser->rateFeedItem( $label, $id, $rating ) ? 1 : 0;
          $feedAnalyser->save();
          break;
      case 'get_weight_words':
          $feedAnalyser->load();
          var_dump($feedAnalyser->getWordsWeight());
          break;
      case 'recount_words_weight':
          $feedAnalyser->load();
          var_dump($feedAnalyser->recountWordsWeight());
          $feedAnalyser->save();
          break;
      case 'count_weight':
          $feedAnalyser->load();
          var_dump($feedAnalyser->countWeight());
          $feedAnalyser->save();
          break;
  }
  exit;
  die();

  /*
    var_dump($_POST);
    if ( $_POST['email'] ) {
    require_once ('GoogleReader.class.php');
    require_once ('Feed.class.php');
    require_once ('FeedItem.class.php');
    //var_dump( $_POST );
    $itemsLimit = $_POST['itemsLimit'];
    $gr = new GoogleReader( $login, $pass );

    $feed = new Feed( );
    $items = $gr->getItems( $label, $feed->timestamp, $itemsLimit);
    if ( count( $items ) != 0 ){
    $feedItems = array_map( 'JsonObj2FeedItem', $items );
    $feed->addItems( $feedItems );
    }

    die();
    } */
?>
<form action="get.php" method="POST">
    <table>
        <tr>
            <td>
                Login
            <td>
                <input name="email">
        <tr>
            <td>
                Pass
            <td>
                <input name="pass" type="password">
        <tr>
            <td>
                Label 
            <td>
                <input  name="label" value="RusFreelance">
        <tr>
            <th colspan="2">
                <input  type="submit">
    </table>
</form>
