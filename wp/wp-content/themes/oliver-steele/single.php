<?php get_header(); ?>

	<div id="content" class="widecolumn">
				
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="navigation">
			<div class="alignleft"><?php previous_post('&laquo; %','','yes') ?></div>
			<div class="alignright"><?php next_post(' % &raquo;','','yes') ?></div>
		</div>
	
		<div class="post">
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	
			<div class="entrytext">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>

<div class="postmetadata alt">
<table width="100%"><tr>
  <td class="section utwrelposts">
    <div>Related posts</div>
    <ul><?php UTW_ShowRelatedPostsForCurrentPost("posthtmllist", '', 5) ?></ul>
  </td>
  <td class="section utwtags" id="tags-<?php the_ID(); ?>">
    <div>Tags</div>
<?php $format = array('default'=>'<li>%taglink% %icons%</li>'); ?>
<ul><?php UTW_ShowTagsForCurrentPost("htmllisticons", $format); ?></ul>
    <?php UTW_AddTagToCurrentPost("simplelist") ?>
  </td>
  <td class="section">
    <div>Comments</div><ul>
    <li><a href="#comments">View Comments</a></li>
	<?php if ('open' == $post-> comment_status) { ?>
      <li><a href="#respond">Post Comment</a></li>
    <?php } ?>
    <li><?php comments_rss_link('Comment Feed'); ?>
    <?php if ('open' == $post->ping_status) { ?>
	  <li><a href="<?php trackback_url(display); ?>">Trackback URL</a></li>
    <?php } ?>
    </ul>
  </td>
</table>
				<p>
					<small>
						This entry was posted
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?> 
						on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>.
						
						<?php edit_post_link('Edit this entry.','',''); ?>
						
					</small>
				</p>
</div>
	
			</div>
		</div>
		
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	
<?php endif; ?>
	
	</div>

<?php get_footer(); ?>