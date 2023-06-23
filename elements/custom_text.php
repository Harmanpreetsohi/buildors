<div class="page_content_container">
    <div class="page_content_outer">
        <h6>Page content</h6>
        <h5>Introduction</h5>
    </div>
    <div class="btn_outer">
        <button class="btn btn-primary ">View Page</button>
    </div>
</div>
<div class="text_container">

    <textarea name="editor" id="editor"></textarea>

</div>
<script src="node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.error(error);
    });
</script>
