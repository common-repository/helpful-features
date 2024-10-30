<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<?php if(is_active_sidebar('sidebar-02')){ ?>

	<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-02')): endif; ?>

<?php } ?>