<?php

  require_once ('Feed.class.php');
  require_once ('FeedItem.class.php');


  if ( $_POST ) {

      $filename = getFilename( $label );
      $feed = getFeed( $filename );
      foreach ( $_POST['items'] as $item ) {
          $feed->getItemById()->rate( $item['rating'] );
      }


      die();
  }

  if ( !$_GET['label'] ) {
      echo '<a href="?label=RusFreelance">RusFreelance</a>';
      die();
  }
  $rowPerPage = 30;

  $label = $_GET['label'];
  $filename = getFilename( $label );
  $feed = getFeed( $filename );
  $feed->sort();
  //var_dump( $feed->items[0]->getWordAndCount() );
  $begin = $_GET['page'] * $rowPerPage;
  $end = ($_GET['page'] + 1) * $rowPerPage; //$item
  for ( $i = $begin; $i < $end; $i++ ) {
      $item = $feed->items[$i];
      if ( $item === null )
          break;
      $post['href'] = $item->getHref();
      $post['title'] = "<span style=\"color:red;\">w{$item->getWeight()}</span> ";
      if ( $item->getRate() )
          $post['title'] .= "<span style=\"color:blue;\">r{$item->getRate()}</span> ";
      $post['title'] .= "{$item->getTitle()} ";

      $post['date'] = date( "d/m/Y H:i:s", $item->getDate() );
      $post['summary'] = $item->getContent();
      $post['authorName'] = "<img src=\"{$item->getIcon()}\" /> {$item->getSite()}";
      $post['authorHref'] = "{$item->getSite()}";
      $post['actions'] = 'Оценить: '.$item->getRadio();
      $data['content'][] = $post;
      /* echo <<<STR
        <div class="item">
        <div class="item_head">
        <img src="{$item->getIcon()}" />
        <a href="{$item->getHref()}">{$item->getTitle()}</a>
        <span style="color:red;">
        {$item->getWeight()}
        </span>
        {$item}

        {$item->getRadio()}
        </div>
        <div class="item_body">
        {$item->getContent()}
        </div>
        </div>

        STR; */
  }


  $endPage = ceil( count( $feed->items ) / $rowPerPage );
  for ( $i = 0; $i < $endPage; $i++ ) {
      $j = $i + 1;
      $data['sidebar']['Страницы'] .= "<li><a href=\"?page=$i&label=$label\">Страница $j</a> ";
  }
  $data['sidebar']['Страницы'] = '<ul>'.$data['sidebar']['Страницы'].'</ul>';
  require_once ('template.php');
?>