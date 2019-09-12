<?php 
include 'functions.php';

if (isset($_POST['submit'])) {
	$content = $_POST['content'];
	$content = auto_add_id_headings($content);
}
?>

<html>
<head>
	<title>Auto Add Id in Headings</title>
	<meta name="viewport" content= "width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-7 mx-auto">
				<?php echo $content; ?>
			</div>
		</div>
	</div>
<?php // } ?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>