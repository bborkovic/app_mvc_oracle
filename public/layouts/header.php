<?php use Core\Session; use App\Models\User; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Bookstore</title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <!-- Bootstrap -->
  <script src="/javascripts/jquery-3.1.0.min.js"></script>
  <script src="/javascripts/bootstrap.min.js"></script>
  <link href="/stylesheets/bootstrap.min.css" rel="stylesheet">
  <link href="/stylesheets/aplication.css" rel="stylesheet">
</head>

<!--   <header>
    <div class="container"></div>
    <h1>The BookStore, <small>Wellcome!</small></h1>
  </header> -->


<body>
	<div class="container">
		<div class="row">
		<?php require 'navbar.php'; ?>