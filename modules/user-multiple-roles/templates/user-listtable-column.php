<?php
/**
 * Output a list of roles belonging to the current user.
 *
 * @var $roles array All applicable roles in name => label pairs.
 *
 * @package    All_In_One_Utilities/modules/user-multiple-roles
 * @subpackage All_In_One_Utilities/modules/user-multiple-roles/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="aiowpu-multiple-roles">

	<?php if ( ! empty( $roles ) ) : ?>

		<?php $it = new CachingIterator( new ArrayIterator( $roles ) ); ?>

		<?php foreach ( $it as $name => $label ) : ?>
			<a href="users.php?role=<?php echo esc_attr( $name ); ?>">
				<?php echo esc_html( translate_user_role( $label ) ); ?>
			</a>
			<?php echo $it->hasNext() ? ',' : ''; ?>
		<?php endforeach; ?>

	<?php else : ?>
		<span class="aiowpu-multiple-roles-no-role"><?php esc_html_e( 'None', 'all-in-one-utilities' ); ?></span>
	<?php endif; ?>

</div><!-- .aiowpu-multiple-roles -->
