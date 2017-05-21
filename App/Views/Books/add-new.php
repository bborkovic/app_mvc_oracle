<?php require_once('layouts/header.php'); ?>

<!-- Content -->
<div class="col-md-8">
	<?php echo output_message(); ?>
	<!-- <h4>This is body of view</h4> -->
			<?php $form->render(); ?>
</div>

<!-- right sidebar -->
<div class="col-md-1"></div>

<div class="col-md-3">
	<?php require_once('layouts/sidebar.php'); ?>
</div>


<?php require_once('layouts/footer.php'); ?>
