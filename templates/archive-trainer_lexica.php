
<?php get_header(); 
$options = get_option( 'lexicalSettingsOptions' );
$instance = new LexicalPlugin();
$templates = new LP_Templater();
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
                <select name="location_lt">
                    <option value=""><?php esc_html_e( 'Select Location', 'lexical_trainers' ); ?></option>

                    <?php echo $instance->getTermsHierarcical('location', $_POST['location_lt']); ?>
                </select>
                <select name="type_lt">
                    <option value=""><?php esc_html_e( 'Select Type', 'lexical_trainers' ); ?></option>

                    <?php echo $instance->getTermsHierarcical('type', $_POST['type_lt']); ?>
                </select>

                <input type="submit" name="submit" value="<?php esc_html_e( 'Filter', 'lexical_trainers' );?>">
            </form>
        </div>
    </section>

    <section class="category-search">
        
        <?php 

            $tlArgs = [
                'post_type'=>'trainer_lexica',
                'posts_per_page'=>-1,
                'tax_query'=>array('relation'=>'AND'),
            ];
            if ($options['posts_per_page']) {
                $tlArgs['posts_per_page'] = esc_attr($options['posts_per_page']);
            }

            if (isset($_POST['location_lt']) && $_POST['location_lt'] !== '') {
                array_push($tlArgs['tax_query'], array(
                    'taxonomy' => 'location',
                    'terms' => $_POST['location_lt'],
                ));
            }
            if (isset($_POST['type_lt']) && $_POST['type_lt'] !== '') {
                array_push($tlArgs['tax_query'], array(
                    'taxonomy' => 'type',
                    'terms' => $_POST['type_lt'],
                ));
            }

            if (!empty($_POST['submit'])) {
                $searchTrainersListing = new WP_Query($tlArgs);

                if ($searchTrainersListing->have_posts()) {
                    while ($searchTrainersListing->have_posts()) { $searchTrainersListing->the_post(); // Начало цикла
                        
                        $templates->get_template_part( 'lp_archive','content' );
                        
                    } 
                    
                    $big_serch = 999999999; // need an unlikely integer

                    $paginationSArgs = [
                        'base'         => str_replace( $big_serch, '%#%', esc_url( get_pagenum_link( $big_serch ) ) ),
                        'format'       => '?paged=%#%',
                        'total'        => $searchTrainersListing->max_num_pages,
                        'current'      => max( 1, get_query_var('paged') ),
                    ];
                    echo paginate_links($paginationSArgs);
                } else {
                    echo esc_html__( 'No posts', 'lexicalPlugin' );
                }
            } else {
                $paged = 1;
                if (get_query_var('paged')) $paged = get_query_var('paged');
                if (get_query_var('page')) $paged = get_query_var('page');

                $defaultTlArgs = [
                    'post_type'=>'trainer_lexica',
                    'posts_per_page'=>-1,
                    'paged'=>$paged,
                ];
                if ($options['posts_per_page']) {
                    $defaultTlArgs['posts_per_page'] = esc_attr($options['posts_per_page']);
                }

                $trainersListing = new WP_Query($defaultTlArgs);

                if ($trainersListing->have_posts()) {
                    while ($trainersListing->have_posts()) { $trainersListing->the_post(); // Начало цикла
                        $templates->get_template_part( 'lp_archive','content' );
                    }

                    $big = 999999999; // need an unlikely integer

                    $paginationArgs = [
                        'base'         => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'       => '?paged=%#%',
                        'total'        => $trainersListing->max_num_pages,
                        'current'      => max( 1, get_query_var('page') ),
                    ];
                    echo paginate_links($paginationArgs);
                } else {
                    echo esc_html__( 'No posts', 'lexicalPlugin' );
                }
            
            }
        ?>
    </section>

</main>
<?php get_template_part( 'template-parts/view-more', null );?> 
		
<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

</div>
</div>

<?php get_footer(); ?>