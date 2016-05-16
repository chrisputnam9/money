<div class='col-sm-4 col-sm-offset-4'>  
    <h1>Upload Receipt</h1>
    {{#error}}
    <p class='alert bg-danger'>{{error}}</p>
    {{/error}}
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Use Camera or existing file</label>
            <input type="file" accept="image/*" capture="camera" class="form-control js-click" name="image" id="image" value="">
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Upload</button>
    </form>
</div>
