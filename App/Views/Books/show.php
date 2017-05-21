<?php require_once('layouts/header.php'); ?>

<!-- Content -->
<div id="books-show" class="col-md-8">
	<table class="table">
		<tr>
			<td>
				<img class="img-responsive" src="/uploads/books/<?php echo $book->book_photo; ?>">
			</td>
			<td>
				<ul>
					<h4>2017</h4>
					<h3><strong><?php echo $book->name; ?></strong></h3>
					<h4><?php echo $book->short_info; ?></h4>
				</ul>
			</td>
		</tr>
	</table>

	


</div>

<!-- right sidebar -->
<div class="col-md-1"></div>

<div class="col-md-3">
	<?php require_once('layouts/sidebar.php'); ?>
</div>



<?php require_once('layouts/footer.php'); ?>