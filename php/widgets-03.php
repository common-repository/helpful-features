<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<?php if(is_active_sidebar('sidebar-03')){ ?>

	<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-03')): endif; ?>

<?php } ?>