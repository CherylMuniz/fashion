<?php

$GLOBALS['relatedPostsViaCategories_activate'] = new relatedPostsViaCategories_activate();


class relatedPostsViaCategories_activate
{

	function activate() {
		$option_post = array (
			'auto_display' => 'yes',
			'maximum_number_of_related_posts' => 10,
			'related_posts_title' => 'Related Posts via Categories',
			'before_related_posts_title_and_related_posts' => '',
			'after_related_posts_title_and_related_posts' => '',
			'before_related_posts_title' => '<h2 id="related-posts-via-categories-title">',
			'after_related_posts_title' => '</h2>',
			'before_related_posts' => '<ul id="related-posts-via-categories-list">',
			'after_related_posts' => '</ul>',
			'before_each_related_post' => '<li>',
			'after_each_related_post' => '</li>',
			'order_by' => 'related_scores_high__date_published_new',
			'exclude_posts' => '',
			'exclude_categories' => '',
			'post_types' => array( 'post' ),
			'promotion_link' => '',
			'promotion_link_text' => 'To the official site of Related Posts via Categories.',
			'promotion_link_fontsize' => 12,
			'promotion_link_textalign' => 'right',
			'promotion_link_language' => 'english',
		);
		update_option( "related_posts_via_categories", $option_post );
	}

} // class relatedPostsViaCategories_activate





?>
