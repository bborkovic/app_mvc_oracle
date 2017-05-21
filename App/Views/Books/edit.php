<?php require_once('layouts/header.php'); ?>

<div class="col-md-1"></div>

<!-- Content -->
<div class="col-md-5">
	<?php echo output_message(); ?>
	<!-- <h4>This is body of view</h4> -->
			<?php $form->render(); ?>
</div>


<div id="books-show" class="col-md-6">
	
	<img src="/uploads/books/<?php echo $form->model_class->book_photo; ?>">

	<form role="form" action="edit" method="post" enctype="multipart/form-data">
		<p>
			<label for="filename">Select Image:</label>
			<input type="file" name="filename" id="filename" multiple>
		</p>
		<p>
			<input type="submit" name="upload" value="Upload File">
		</p>
	</form>


</div>


<?php require_once('layouts/footer.php'); ?>
