<?php
/**
 * Theme screenshot selection with titles and description template. 
 * 
 * Copy this file into your theme directory and edit away!
 * You can also use $templates array to iterate through your templates.
 */
?>
<?php if (defined('BP_VERSION') && 'bp-default' == get_blog_option(bp_get_root_blog_id(), 'stylesheet')) echo '<br style="clear:both" />'; ?>
<div id="blog_template-selection">
	<h3><?php _e('Personalizza la tua vetrina online', 'blog_templates') ?></h3>
<p style="color: #000000; font-weight: 300;">Per iniziare scegli il tema più adatto alla tua attività tra quelli suggeriti. Potrai comunque personalizzare la tua vetrina in qualsiasi momento</p>
	<?php
		if ( $settings['show-categories-selection'] )
			$templates = nbt_theme_selection_toolbar( $templates );

    ?>
    
	<div class="blog_template-option">
		
		<?php 
		foreach ($templates as $tkey => $template) { 
			nbt_render_theme_selection_item( 'screenshot_plus', $tkey, $template, $settings );
		} 
		?>
		<div style="clear:both;"></div>
	</div>
</div>

