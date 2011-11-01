<?php


  $rowPerPage = 30;
  $begin = $_GET['page'] * $rowPerPage;
  $end = ($_GET['page'] + 1) * $rowPerPage; //$item
  $items = array_values($items);
  for ( $i = $begin; $i < $end; $i++ ) {
      $item = $items[$i];
      if ( $item === null )
          break;
      $post['href'] = $item->getHref();
      $post['title'] = "<span style=\"color:red;\">{$item->getWeight()}w</span> ";
      if ( $item->isRated() )
          $post['title'] .= "<span style=\"color:blue;\">{$item->getRating()}r</span> ";
      $post['title'] .= "{$item->getTitle()} ";

      $post['date'] = date( "d/m/Y H:i:s", $item->getDate() );
      $post['summary'] = $item->getContent();
      $post['authorName'] = "<img src=\"{$item->getIcon()}\" /> {$item->getSite()}";
      $post['authorHref'] = "{$item->getSite()}";
      $post['actions'] = 'Оценить: ';
      for ( $r = -5; $r <= 5; $r++ ) {
          $link = '?action=rate_feed_item&label=' . $label . '&id=' . $item->getId() . '&rating=' . $r;
          $post['actions'] .= '<a href="' . $link . '">[' . $r . ']</a> ';
      }
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


  $endPage = ceil( count( $items ) / $rowPerPage );
  for ( $i = 0; $i < $endPage; $i++ ) {
      $j = $i + 1;
      $data['sidebar']['Страницы'] .= "<li><a href=\"?action=$action&page=$i\">Страница $j</a> ";
  }
  $data['sidebar']['Страницы'] = '<ul>'.$data['sidebar']['Страницы'].'</ul>';
  require_once ('template.php');
?>