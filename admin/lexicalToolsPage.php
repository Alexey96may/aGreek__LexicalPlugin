<h1 class="lexical_title"><?php esc_html_e( "Lexical Tools", "lexicalTrainer" ) ?></h1>
<?php settings_errors(); ?>
<div class="lexicalToolsContent">
    <form method="post" action="options.php">
        <?php  
            settings_fields('lexicalSettings');
            do_settings_sections('lexical_tools');
            submit_button();
        ?>
    </form>
</div>