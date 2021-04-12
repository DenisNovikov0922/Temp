<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<div id="form_reports_page" class="frm_wrap frm_charts">
	<div class="frm_page_container">
	<?php
	FrmAppHelper::get_admin_header( array(
		'label' => __( 'Reports', 'formidable-pro' ),
		'form'  => $form,
		'close' => remove_query_arg( 'frm-full' ),
	) );

	$class = 'odd';
	?>
	<div class="frm-inner-content wrap">
		<div class="frmcenter">
		<div class="postbox">
			<div class="inside">
				<h3><?php esc_html_e( 'Submissions', 'formidable-pro' ); ?></h3>
				<b><?php echo count( $entries ); ?></b>
			</div>
		</div>
		<?php if ( isset( $submitted_user_ids ) ) { ?>
			<div class="postbox">
				<div class="inside">
					<h3><?php esc_html_e( 'Users Submitted', 'formidable-pro' ); ?></h3>
					<b><?php echo count( $submitted_user_ids ); ?> (<?php echo round( ( count( $submitted_user_ids ) / count( $user_ids ) ) * 100, 2 ); ?>%)</b>
				</div>
			</div>
		<?php } ?>
		<div class="clear"></div>
		</div>

        <?php
		if ( isset( $data['time'] ) ) {
			?>
			<h2 class="frm-h2">
				<?php esc_html_e( 'Responses Over Time', 'formidable-pro' ); ?>
			</h2>
			<?php
			echo $data['time'];
        }

        foreach ( $fields as $field ) {
			if ( ! isset( $data[ $field->id ] ) ) {
                continue;
            }

            $total = FrmProStatisticsController::stats_shortcode( array( 'id' => $field->id, 'type' => 'count' ) );
            if ( ! $total ) {
                continue;
            }
            ?>
			<div class="frm_report_box pg_<?php echo esc_attr( $class ); ?>">
				<h2 class="frm-h2">
					<?php echo esc_html( $field->name ); ?>
				</h2>
				<?php echo $data[ $field->id ]; ?>

				<?php if ( isset( $data[ $field->id . '_table' ] ) ) { ?>
					<br/>
					<?php echo $data[ $field->id . '_table' ]; ?>
				<?php } ?>

				<div class="frmcenter" style="margin-top:20px;">
				<div class="postbox">
					<div class="inside">
						<h3><?php esc_html_e( 'Answered', 'formidable-pro' ); ?></h3>
						<?php echo esc_html( $total ); ?>
						(<?php echo round( ( $total / count( $entries ) ) * 100, 2 ); ?>%)
					</div>
				</div>
			<?php if ( in_array( $field->type, array( 'number', 'hidden', 'scale' ) ) ) { ?>
				<div class="postbox">
					<div class="inside">
						<h3><?php esc_html_e( 'Average', 'formidable-pro' ); ?></h3>
						<?php
						echo FrmProStatisticsController::stats_shortcode( array(
							'id' => $field->id,
							'type' => 'average',
						) );
						?>
					</div>
				</div>

				<div class="postbox">
					<div class="inside">
						<h3><?php esc_html_e( 'Median', 'formidable-pro' ); ?></h3>
						<?php
						echo FrmProStatisticsController::stats_shortcode( array(
							'id' => $field->id,
							'type' => 'median',
						) );
						?>
					</div>
				</div>
            <?php } ?>
			</div>

            <div class="clear"></div>
            </div>
        <?php
			$class = ( $class == 'odd' ) ? 'even' : 'odd';
            unset($field);
        }

        if ( isset($data['month']) ) {
            echo $data['month'];
        }
?>
	</div>
	</div>
</div>
