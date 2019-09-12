<html>
<head>
	<title>Auto Add Id in Headings</title>
	<meta name="viewport" content= "width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style>
		.ck-editor__editable{
			max-height: 350px;
			min-height: 350px;
			overflow: auto;
		}
	</style>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-7 mx-auto">
				<form method="post" action="preview.php">
					<div class="form-group">
						<textarea name="content" class="form-control" id="editor" cols="30" rows="10"></textarea>
					</div>
					<div class="form-group text-right">
						<input type="submit" name="submit" class="btn btn-primary" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script>
<script>
// CKEditor
ClassicEditor.create(document.querySelector('#editor')).catch( error => {
console.error(error);
});
</script>
</body>
</html>