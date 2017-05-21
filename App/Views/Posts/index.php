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
			<table class="table table-striped">
				<thead>
					<tr><th>name</th><th>details</th><th>Edit</th><th>Delete</th></tr>
				</thead>
				<tbody>
					<?php foreach ($posts as $post): ?>
						<tr>
							<td><?php echo $post->name; ?></td>
							<td><?php echo $post->details; ?></td>
							<td><a href="<?php echo $post->id;?>/edit">Edit</a></td>
							<td><a href="<?php echo $post->id;?>/delete">Delete</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<a href="add-new" class="btn btn-default">Create</a>
		</div>
	</div>
</div>




<?php require_once('layouts/footer.php'); ?>