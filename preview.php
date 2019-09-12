<?php 
include 'functions.php';

if (isset($_POST['submit'])) {
	$content = $_POST['content'];
	$content = add_id_to_header_tags($content);
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
			<div class="col-md-7 mx-auto" id="toc-container">
				<?php echo $content; ?>
			</div>
			<div class="col-md-3">
				<div class="card card-small">
					<div class="card-header">Table Of Contents</div>
					<div class="card-body p-2">
						<ol class="list-group" id="toc-main"></ol>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php // } ?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>

$(document).ready(function toc() {
	var children = $("#toc-container").children(".toc-item");

	for (var i = 0; i < children.length; i++) {
		$(children[i]).prop("id", "toc-item-"+i);
		html += "<li class='list-group-item'><a href='#toc-item-"+ i +"'>" + $(children[i]).html() + "</a></li>";
	}

	$("#toc-main").html(html);
});

</script>

</body>
</html>