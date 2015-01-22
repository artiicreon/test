<?php
/**
 * @file
 * Template file for the album block.
 */
//print '<pre>';print_r($items);print '</pre>';
?>

	<?php foreach ($items as $item):?>
					<div class="nid"><?php print $item['nid']; ?></div>		
					<div class="title"><?php print $item['title']; ?></div>				
          <div class="title"><?php print $item['created']; ?></div>				
          <div class="title"><?php print $item['news_image']; ?></div>				
          <div class="title"><?php print $item['body']; ?></div>				
          <div class="title"><?php print $item['news_logo']; ?></div>				
	<?php endforeach; ?>		
