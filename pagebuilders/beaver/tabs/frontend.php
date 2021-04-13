<?php global $wp_embed; ?>
<div class="fl-tabs fl-tabs-<?php echo $settings->layout; ?> fl-clearfix">

	<div class="fl-tabs-labels fl-clearfix" role="tablist">
		<?php
		for ( $i = 0; $i < count( $settings->items ); $i++ ) :
			if ( ! is_object( $settings->items[ $i ] ) ) {
				continue;
			}

			$tab_label_id = 'fl-tabs-' . $module->node . '-label-' . $i;
			$id_in_label  = apply_filters( 'fl_tabs_id_in_label', false, $settings, $i );

			if ( $id_in_label && ! empty( $settings->id ) ) {
				$tab_label_id = $settings->id . '-label-' . $i;
			}
			?>
			<label for="amp-fl-tab-<?php echo esc_attr( $i ); ?>" class="tab-bullet">
			<a  class="fl-tabs-label" id="<?php echo $tab_label_id; ?>" data-index="<?php echo $i; ?>" aria-selected="<?php echo ($i > 0) ? 'false' : 'true';?>" aria-controls="<?php echo 'fl-tabs-' . $module->node . '-panel-' . $i; ?>" aria-expanded="<?php echo ( $i > 0 ) ? 'false' : 'true'; ?>" role="button" tabindex="0"><?php // @codingStandardsIgnoreLine ?>
				<?php echo $settings->items[ $i ]->label; ?>
			</a>
		 </label>
		<?php endfor; ?>
	</div>

	<div class="fl-tabs-panels fl-clearfix">
		<?php
		for ( $i = 0; $i < count( $settings->items ); $i++ ) :
			if ( ! is_object( $settings->items[ $i ] ) ) {
				continue;
			}
			$tab_check = '';
          if($i == 0){
          	$tab_check = 'checked="checked"';
          }
			?>
		<div class="fl-tabs-panel"<?php echo ( ! empty( $settings->id ) ) ? ' id="' . sanitize_html_class( $settings->id ) . '-' . $i . '"' : ''; ?>>
			<label for="amp-fl-tab-<?php echo esc_attr( $i ); ?>" class="tab-bullet">
			<div class="fl-tabs-label fl-tabs-panel-label" data-index="<?php echo $i; ?>" tabindex="0">
				<span><?php echo $settings->items[ $i ]->label; ?></span>
				<i class="fas<?php echo ( $i > 0 ) ? ' fa-plus' : ''; ?>"></i>
			</div>
			</label>
			<input class="tab-fl-open" type="radio" id="amp-fl-tab-<?php echo esc_attr( $i ); ?>" name="tabs" aria-hidden="true" hidden=""  <?php echo  esc_attr($tab_check); ?> >
			<div class="fl-tabs-panel-content tab-hide-item fl-clearfix" id="<?php echo 'fl-tabs-' . $module->node . '-panel-' . $i; ?>" data-index="<?php echo $i; ?>"<?php if ( $i > 0 ) { echo ' aria-hidden="true"';} ?> aria-labelledby="<?php echo 'fl-tabs-' . $module->node . '-label-' . $i; ?>" role="tabpanel" aria-live="polite"><?php // @codingStandardsIgnoreLine ?>
				<?php echo wpautop( $wp_embed->autoembed( $settings->items[ $i ]->content ) ); ?>
			</div>
		</div>
		<?php endfor; ?>
	</div>

</div>
