<?php
  if ( $_POST['email'] ) {
      require_once ('GoogleReader.class.php');
      require_once ('Feed.class.php');
      require_once ('FeedItem.class.php');
      //var_dump( $_POST );
      /*$_POST = array(
          "email" => "coder24ru@gmail.com",
          "pass" => "fairy24tale",
          "tags" => "RusFreelance"//,EngFreelance"
      );*/
      $labels = explode( ',', $_POST['tags'] );
      $gr = new GoogleReader( $_POST['email'], $_POST['pass'] );
      foreach ( $labels as $label ) {
          echo $label . ' = ';
          $filename = getFilename( $label );
          $feed = getFeed( $filename );
          $items = $gr->getItems( $label, $feed->timestamp );
          echo count( $items ) . '<br>';
          if ( count( $items ) == 0 )
              continue;
          $feed->addItems( $items );
          setFeed( $filename, $feed );
      }
      echo memory_get_usage ( ).'<br>';
      echo memory_get_usage (1 ).'<br>';
      echo memory_get_peak_usage ( ).'<br>';
      echo memory_get_peak_usage (1 ).'<br>';
      
      die();
  }
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
                Tags
            <td>
                <input  name="tags" value="RusFreelance,EngFreelance">
        <tr>
            <th colspan="2">
                <input  type="submit">
    </table>
</form>
