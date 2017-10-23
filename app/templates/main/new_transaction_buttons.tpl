<span class="navbar-right hidden-sm hidden-xs">
    <a class="btn btn-primary navbar-btn" href="/transaction/form"><span class='glyphicon glyphicon-pencil'></span> New Manual Entry</a>

    <a class="btn btn-success navbar-btn" href="/transaction/image" class="js-show" data-click="#image"><span class='glyphicon glyphicon-camera'></span> New From Image</a>
</span>

<form action="/transaction/image" method="post" enctype="multipart/form-data" class="js-hide js-file-upload" data-progress='#image-upload-progress' style='display:none'>
    <div class="form-group">
        <label for="image">Use Camera or existing file</label>
        <input type="file" accept="image/*" capture="camera" class="form-control" name="image" id="image" value="">
    </div>
    <button type="submit" class="btn btn-primary navbar-btn">Upload</button>
</form>
