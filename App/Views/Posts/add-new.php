<?php require_once('layouts/header.php'); ?>


<div class="panel-body">

<?php echo output_message(); ?>

	<div class="row">
		<!-- Side navigation -->
		<div class="col-sm-2">
			<p><a href=""></a></p>
			<p><a href="/posts/index">Posts index</a></p>
		</div>

		<div class="col-sm-5">
			<?php $form->render(); ?>
		</div>
	</div>
</div>




<?php require_once('layouts/footer.php'); ?>



