<?php
	include_once("header.php");
?>
	<div class="py-2">
		<h3>Create New Pipeline</h3>
	</div>
	
	<?php if(isset($_SESSION['message']))echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post">
					<div class="mb-4">
						<label>Title</label>
						<input type="text" name="title" class="form-control" placeholder="Enter list title" required>
					</div>
                    <div id="add_stages"></div>
					<div class="mb-4 mt-2 text-end">
						<button type="button" id="add_stage" class="btn btn-primary">Add Stage</button> 
					</div>
					<div class="mb-4">
						<input type="hidden" name="cmd" value="create_pipeline">
						<input type="submit" value="Create Pipeline" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>
<script>
    $('#add_stage').click(function(){
        $('#add_stages').append(
            `<div class="row my-3 remove_stage">
                <div class="col-md-10">
                    <input type="text" name="stages[]" class="form-control" placeholder="Enter stage title" />
                </div>
                <div class="col-md-2 text-center">
                    <button type="button" class="btn btn-danger remove_field">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>`
        );
    });
    $("#add_stages").on("click", ".remove_field", function (e) {
        e.preventDefault();
        $(this).parents('.remove_stage').remove();
    });
</script>