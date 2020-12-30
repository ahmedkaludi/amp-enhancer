<?php
/**
 * Layout Name: Collapsible List
 *
 * @package   PT_Content_Views
 * @author    PT Guy <http://www.contentviewspro.com/>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

$random_id		 = PT_CV_Functions::string_random();
$heading		 = isset( $fields_html[ 'title' ] ) ? $fields_html[ 'title' ] : '';
unset( $fields_html[ 'title' ] );

// Get link
$matches = array();
preg_match( '/href="([^"]+)"/', $heading, $matches );
$href	 = !empty( $matches[ 1 ] ) ? "href='" . esc_url( $matches[ 1 ] ) . "' onclick='event.preventDefault()'" : '';
?>

<h4 class="panel-title panel-heading" style="padding: 10px 15px;">
		<?php
		$tt = tag_escape( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'field-title-tag' ) );
		echo preg_replace( array( '/<(' . $tt . '|a)[^>]*>/i', '/<\/(' . $tt . '|a)>/i' ), '', $heading );
		?>
	<?php
	echo apply_filters( PT_CV_PREFIX_ . 'scrollable_toggle_icon', '' );
	?>
</h4>
<div id="<?php echo esc_attr( $random_id ); ?>" class="panel-collapse">
	<div class="panel-body">
		<?php
		echo implode( "\n", $fields_html );
		?>
	</div>
</div>
