<nav class="navbar navbar-inverse navbar-fixed-top" {{#is_development}}style="background: #7d1414;"{{/is_development}}>
    <div class="container">
        <div class="col-sm-12">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu-collapsible" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                
                    <a class="navbar-brand" href="/" title ="
API Key: ">
                        <span class="glyphicon glyphicon glyphicon-credit-card {{#is_development}}text-danger{{/is_development}}"></span>
                    </a>
                

                <span class="visible-xs">
                    {{#is_development}}
                        <a class="btn btn-danger navbar-btn" href="/">DEV</a>
                    {{/is_development}}

					{{> new_transaction_buttons }}
                </span>

            </div>

            <div class="collapse navbar-collapse" id="main-menu-collapsible">
                <ul class="nav navbar-nav">
                {{#menu}}
                    <li class="{{class}}"><a href="{{url}}">{{title}}</a></li>
                {{/menu}}
                </ul>

				<span class="navbar-right hidden-xs">
					{{> new_transaction_buttons }}
				</span>

            </div>
        </div>
    </div>
</nav>

{{#show_transaction_buttons}}

<div class="container" class="js-hide">
	<div class="alert alert-warning js-hide" role="alert">
		Please enable Javascript for a nicer experience.
	</div>	
</div>

<div class="container well hidden" id="text-entry-form">
	<h4>New From Text</h4>
    <form action="/transaction/text" method="post">
        <div class="form-group">
            <textarea class="form-control" name="text" id="transaction-text-entry" placeholder="Paste text here" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary navbar-btn">Submit</button>
        <button type="submit" class="btn btn-warning navbar-btn js-show" data-clicktoggle="#text-entry-form">Cancel</button>
    </form>
	<br>
</div>

<div class="container hidden">
	<h3>New From Image</h3>
    <form action="/transaction/image" method="post" enctype="multipart/form-data" class="js-file-upload" data-progress='#image-upload-progress'>
        <div class="form-group">
            <label for="image">Use Camera or existing file</label>
            <input type="file" accept="image/*" capture="camera" class="form-control" name="image" id="image" value="">
        </div>
        <button type="submit" class="btn btn-primary navbar-btn">Upload</button>
    </form>
</div>

<div id='image-upload-progress' class="container hidden">
	<div class='col-sm-12'>
		<div class="progress">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%; min-width:30px;">
				<strong><span class='percent'>0%</span></strong>
			</div>
		</div>
	</div>
</div>

{{/show_transaction_buttons}}
