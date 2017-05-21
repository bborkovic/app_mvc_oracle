<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Home</title>
</head>
<body>
	<h3>Welcome, from Posts Views</h3>

	<table>
		<?php foreach ($posts as $post) : ?>
			<tr>
				<td><?php echo $post->name; ?></td>
				<td><?php echo $post->details; ?></td>
				<td><a href="<?php echo $post->id; ?>/edit">edit</a></td>
				<td><a href="<?php echo $post->id; ?>/delete">delete</a></td>
			</tr>
		<?php endforeach; ?>	
	</table>


	<a href="<?php echo $link; ?>">New Post</a>


</body>
</html>