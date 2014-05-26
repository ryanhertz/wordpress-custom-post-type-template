<?php
/*
 * CUSTOM POST TYPE ARCHIVE TEMPLATE
 *
 * For more info: http://codex.wordpress.org/Post_Type_Templates
 *
 * This template shows a "Table of Contents" of the posts
 * on this page, grouped by category. The full list of posts
 * is displayed below.
 * 
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<div id="main" class="m-all t-all d-all cf" role="main">

						<h1 class="archive-title">Frequently Asked Questions</h1>

							<?php 

							$faq_query = new WP_Query( array( 'post_type' => 'my_faqs'	)  );

							if ($faq_query->have_posts()) {

								//get all the categories
								$categories = get_categories( array( 'type' => 'my_faqs' ) );

								// make the table of contents at the top
								echo '<div class="faq-contents cf">';
								foreach($categories as $category) {

									echo '<div class="faq-category-group">';
									echo '<span class="faq-category-name">' . $category->name . '</span>';

									$faq_post_list = get_posts(
										array(
											'post_type' => 'my_faqs',
											'category'  => $category->term_id
											)
										);
									
									foreach($faq_post_list as $question) {
										echo '<a href="#'. $question->post_name . '">' . $question->post_title . '</a>';
									}

									echo '</div>';
									
								}
								echo '</div>';


								// output the questions grouped by category
								echo '<div class="faq-list">';
								foreach($categories as $category) {

									echo '<h2 id="'. $category->slug .'">' . $category->name . '</h2>';

									$faq_post_list = get_posts(
										array(
											'post_type' => 'my_faqs',
											'category'  => $category->term_id
											)
										);
															
									// output all of the questions in this category
									foreach($faq_post_list as $question) { 
									?>

										<article id="<?php echo $question->post_name; ?>" class="entry-content cf">

											<header>
												<h3 class="article-header">
													<?php echo $question->post_title; ?>
												</h3>
											</header>

											<section class="cf">
												<?php echo apply_filters('the_content', $question->post_content); ?>
											</section>

										</article>

									<?php 
									}
								}
								echo '</div>';


							} else { ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1>Oops, Post Not Found!</h1>
										</header>
										<section class="entry-content">
											<p>Uh Oh. Something is missing. Try double checking things.</p>
										</section>
									</article>

							<?php } ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>
