<!-- <h4>This is Side</h4> -->

<div>
	<h3>Links:</h3>
	<ul class="list-group">
		<a href="/books/index"><li class="list-group-item">Books</li></a>
		<a href="/publishers/index"><li class="list-group-item">Publishers</li></a>
		<a href="/authors/index"><li class="list-group-item">Authors</li></a>
		<?php foreach ($add_links as $link_name => $link): ?>
			<a href="<?php echo $link; ?>"><li class="list-group-item"><?php echo $link_name; ?></li></a>
		<?php endforeach ?>
	</ul>

</div>