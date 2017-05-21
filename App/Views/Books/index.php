<?php require_once('layouts/header.php'); ?>

<!-- Content -->
<div class="col-md-8">
	<?php echo output_message(); ?>
	<!-- <h4>This is body of view</h4> -->

	<div id="books-index">
	<!-- loop through rows of books -->
		<?php for ($i=0; $i <= count($books)-1; $i++): ?> 
			<div class="row">
				<!-- loop through columns of books -->
				<?php foreach ($books[$i] as $book): ?>
					<div class="col-md-3">
						<img src="/uploads/books/<?php echo $book->book_photo; ?>">
						<a href="<?php echo $book->id; ?>/show">
							<button type="button" class="btn btn-primary btn-md">Read More</button>
						</a>
						<h4><?php echo $book->name; ?></h4>
					</div>
				<?php endforeach ?>
			</div>
		<?php endfor; ?>
	</div>

	<div class="center-block" text-align="center">
		<?php echo $paginator->displayPagination('') ?>

	</div>

</div>

<!-- right sidebar -->
<div class="col-md-1"></div>

<div class="col-md-3">
	<?php require_once('layouts/sidebar.php'); ?>
</div>



<?php require_once('layouts/footer.php'); ?>