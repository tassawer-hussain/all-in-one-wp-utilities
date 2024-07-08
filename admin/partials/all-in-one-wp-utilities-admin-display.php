<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com
 * @since      1.0.0
 *
 * @package    All_In_One_Utilities
 * @subpackage All_In_One_Utilities/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap aiowpu-manager">
	<h1><?php esc_html_e( 'All-in-One Utilities Modules', 'all-in-one-utilities' ); ?></h1>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<div class="tablenav">

							<div class="alignleft">
								<?php
								$active_link = add_query_arg(
									array(
										'_wpnonce' => wp_create_nonce(),
										'filter'   => 'active',
									),
									$page_link
								);

								$inactive_link = add_query_arg(
									array(
										'_wpnonce' => wp_create_nonce(),
										'filter'   => 'inactive',
									),
									$page_link
								);

								$num_modules          = 0;
								$num_active_modules   = 0;
								$num_inactive_modules = 0;

								if ( $this->modules ) {

									foreach ( $this->modules as $key => $module ) {

										if ( ! $module['public'] ) {
											continue;
										}

										$num_modules++;

										if ( aiowpu_module_enabled( $module['slug'] ) ) {
											$num_active_modules++;
										} else {
											$num_inactive_modules++;
										}
									}
								}
								?>

								<ul class="subsubsub">
									<li class="all">
										<a href="<?php echo esc_url( $page_link ); ?>" class="<?php echo esc_attr( ( ! isset( $filter ) ) ? 'current' : '' ); ?>">
											<?php esc_html_e( 'All', 'all-in-one-utilities' ); ?> <span class="count">(<?php echo esc_attr( $num_modules ); ?>)</span>
										</a> |
									</li>
									<li class="active">
										<a href="<?php echo esc_url( $active_link ); ?>" class="<?php echo esc_attr( ( isset( $filter ) && 'active' === $filter ) ? 'current' : '' ); ?>">
											<?php esc_html_e( 'Active', 'all-in-one-utilities' ); ?> <span class="count">(<?php echo esc_attr( $num_active_modules ); ?>)</span>
										</a> |
									</li>
									<li class="inactive">
										<a href="<?php echo esc_url( $inactive_link ); ?>" class="<?php echo esc_attr( ( isset( $filter ) && 'inactive' === $filter ) ? 'current' : '' ); ?>">
											<?php esc_html_e( 'Inactive', 'all-in-one-utilities' ); ?> <span class="count">(<?php echo esc_attr( $num_inactive_modules ); ?>)</span>
										</a>
									</li>
								</ul>

							</div>

							<div class="tablenav top">
								<div class="alignleft actions bulkactions">
									<label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e( 'Select bulk action', 'all-in-one-utilities' ); ?></label>
									<select name="action" id="bulk-action-selector-top">
										<option value="-1"><?php esc_html_e( 'Bulk Actions', 'all-in-one-utilities' ); ?></option>
										<option value="activate-selected"><?php esc_html_e( 'Activate', 'all-in-one-utilities' ); ?></option>
										<option value="deactivate-selected"><?php esc_html_e( 'Deactivate', 'all-in-one-utilities' ); ?></option>
									</select>
									<input type="submit" id="doaction" class="button action" value="Apply">
								</div>

								<div class="tablenav-pages one-page">
									<span class="displaying-num"><?php echo esc_attr( $num_modules ); ?> <?php esc_html_e( 'items', 'all-in-one-utilities' ); ?></span>
								</div>
							</div>
						</div>

						<table class="wp-list-table widefat plugins">

							<thead>
								<tr>
									<th id="cb" class="manage-column column-cb check-column">
										<input id="cb-select-all-1" type="checkbox">
									</th>
									<th scope="col" class="manage-column column-name column-primary"><?php esc_html_e( 'Module', 'all-in-one-utilities' ); ?></th>
									<th scope="col" class="manage-column column-description"><?php esc_html_e( 'Description', 'all-in-one-utilities' ); ?></th>
									<th scope="col" class="manage-column"></th>
								</tr>
							</thead>

							<tbody id="the-list">
								<?php
								$counter = 0;
								// Loop modules.
								if ( $this->modules ) {
									foreach ( $this->modules as $key => $module ) {

										// Public module.
										if ( ! $module['public'] ) {
											continue;
										}

										// Check module enabled.
										$module_enabled = aiowpu_module_enabled( $module['slug'] );

										// Filter list.
										if ( isset( $filter ) ) {
											if ( 'active' === $filter && ! $module_enabled ) {
												continue;
											}
											if ( 'inactive' === $filter && $module_enabled ) {
												continue;
											}
										}

										$activate_link = add_query_arg(
											array(
												'_wpnonce' => wp_create_nonce(),
												'slug'     => $module['slug'],
												'action'   => 'activate',
											),
											$page_link
										);

										$deactivate_link = add_query_arg(
											array(
												'_wpnonce' => wp_create_nonce(),
												'slug'     => $module['slug'],
												'action'   => 'deactivate',
											),
											$page_link
										);

										$counter++;
										?>

										<tr class="<?php echo esc_attr( $module_enabled ? 'active' : 'inactive' ); ?>">
											<th scope="row" class="check-column">
												<input type="checkbox" name="checked[]" value="<?php echo esc_attr( $module['slug'] ); ?>">
											</th>
											<td scope="col" class="plugin-title column-primary">
												<strong><?php echo esc_html( $module['name'] ); ?></strong>

												<div class="actions">
													<?php if ( 'default' === $module['type'] ) { ?>
														<?php if ( $module_enabled ) { ?>
															<span class="edit">
																<a href="<?php echo esc_url( $deactivate_link ); ?>" role="button"><?php esc_html_e( 'Deactivate', 'all-in-one-utilities' ); ?></a>
															</span>
														<?php } else { ?>
															<span class="edit">
																<a href="<?php echo esc_url( $activate_link ); ?>" role="button"><?php esc_html_e( 'Activate', 'all-in-one-utilities' ); ?></a>
															</span>
														<?php } ?>
													<?php } ?>
												</div>
											</td>
											<td scope="col" class="column-description desc">
												<div class="plugin-description">
													<p><?php echo esc_html( $module['desc'] ); ?></p>
												</div>

												<?php
												if ( $module_enabled && $module['links'] ) {
													$counter = 0;
													foreach ( $module['links'] as $module_link ) {
														$counter++;
														if ( ! isset( $module_link['name'] ) ) {
															continue;
														}

														// Target of link.
														$target = isset( $module_link['target'] ) ? $module_link['target'] : '_self';

														// Output separator.
														echo 1 < $counter ? '|' : '';
														?>
														<span class="edit">
															<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_url( isset( $module_link['url'] ) ? $module_link['url'] : '' ); ?>" role="button">
																<?php echo esc_html( $module_link['name'] ); ?>
															</a>
														</span>
														<?php
													}
												}
												?>
											</td>
											<td scope="col" class="manage-column">
												<?php if ( $module['badge'] ) { ?>
													<div class="pk-badge pk-badge-primary"><?php echo esc_attr( $module['badge'] ); ?></div>
												<?php } ?>
											</td>
										</tr>
										<?php
									}
								}

								if ( ! $counter ) {
									?>
										<tr>
											<td scope="col" colspan="4"><?php esc_html_e( 'No modules avaliable.', 'all-in-one-utilities' ); ?></td>
										</tr>
									<?php
								}
								?>
							</tbody>
						</table>
						<?php wp_nonce_field(); ?>
					</form>

					<script>
					if ( window.history.replaceState ) {
						if ( window.location.href.indexOf( 'action=' ) >= 0 ) {
							window.history.pushState( null, '', '<?php echo esc_url( $page_link ); ?>' );
						}
					}
					</script>
				</div>
			</div>
		</div>
	</div>
</div>
