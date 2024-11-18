
<?php get_header(); 
$options = get_option( 'lexicalSettingsOptions' );
$instance = new LexicalPlugin();
function optionsSet($options) {
    if (isset($options["titleForLexicalTrainers"]) && $options["titleForLexicalTrainers"] !== "") {
        return $options["titleForLexicalTrainers"];
    } else {
        return "Задайте заголовок в настройках!";
    }
}
?>

<?php if ( have_posts() ) : ?> 

<main>
    <hr class="hr_title_page" size="3">
    <h1 class="title_page"><?php echo optionsSet($options); ?></h1>
    <hr class="hr_title_page" size="3">

    <section>
        <div class="filter">
            <form action="<?php echo get_post_type_archive_link( 'trainer_lexica' ) ?>" method="post">
                <select name="location">
                    <option value=""><?php esc_html_e( 'Select Location', 'lexical_trainers' ); ?></option>

                    <?php echo $instance->getTermsHierarcical('location'); ?>
                </select>
                <select name="type">
                    <option value=""><?php esc_html_e( 'Select Type', 'lexical_trainers' ); ?></option>

                    <?php echo $instance->getTermsHierarcical('type'); ?>
                </select>

                <input type="submit" name="submit" value="<?php esc_html_e( 'Filter', 'lexical_trainers' );?>">
            </form>
        </div>
    </section>

    <section class="category-search">
        <?php if ( have_posts() ) while ( have_posts() ) : the_post(); // Начало цикла ?>
        <h2 class="search-header"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="search-date"><?php the_time('F j, Y'); // Дата создания поста ?>
        </div>
        <p class="author_link">Автор: <?php the_author_posts_link(); ?></p>
        <div>
            <?php 
                $locations = get_the_terms( get_the_ID(), 'location');
                if (!empty($locations)) {
                    echo esc_html_e( 'Location: ', 'lexical_trainers' );
                    foreach ($locations as $location) {
                        echo $location->name . ' ';
                    }
                    echo '<br>';
                }

                $types = get_the_terms( get_the_ID(), 'type');
                if (!empty($types)) {
                    echo esc_html_e( 'Type: ', 'lexical_trainers' );
                    foreach ($types as $type) {
                        echo $type->name . ' ';
                    }
                    echo '<br>';
                }
            
            ?>
        </div>
        <div class="search-img"><a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } // Проверяем наличие миниатюры, если есть показываем ?></a>
            <div id="search_stat_topic" class="search_stat_topic" name="<?php echo get_the_ID();?>">
                <img src="<?php echo bloginfo('template_url'); ?>/assets/img/viewers.png" alt="просмотры"> <span class="views_counter"><?php 
                    $vieweredPost = $post->post_viewers;
                    if ($vieweredPost >= 1000000) {
                        echo round($vieweredPost/1000000) . "М";
                    } else if ($vieweredPost >= 1000) {
                        echo round($vieweredPost/1000) . "К";
                    } else {
                        echo $vieweredPost;
                    }
                    ?></span>
            </div>
        </div>
        <div class="search-text"><?php the_excerpt(); // Содержимое страницы ?></div>
        <hr class="hr-text">
        <?php endwhile; // Конец цикла ?>
    </section>

    <div class="pagination">
        <?php echo paginate_links();?>
    </div> 

</main>
<?php get_template_part( 'template-parts/view-more', null );?> 
		
<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

</div>
</div>

<?php get_footer(); ?>