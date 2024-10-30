<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<?php if(is_active_sidebar('sidebar-01')){ ?>

	<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-01')): endif; ?>

<?php } ?>