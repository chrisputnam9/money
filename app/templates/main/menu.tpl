<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="col-sm-12">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu-collapsible" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="/"><span class="glyphicon glyphicon glyphicon-credit-card" aria-hidden="true"></span></a>

                {{#show_transaction_buttons}}
                    <span class="visible-sm visible-xs">
                        <a class="btn btn-primary navbar-btn" href="/transaction/form"><span class='glyphicon glyphicon-pencil'></span></a>

                        <a class="btn btn-success navbar-btn" href="/transaction/image" class="js-show" data-click="#image"><span class='glyphicon glyphicon-camera'></span></a>
                    </span>
                {{/show_transaction_buttons}}

            </div>

            <div class="collapse navbar-collapse" id="main-menu-collapsible">
                <ul class="nav navbar-nav">
                {{#menu}}
                    <li class="{{class}}"><a href="{{url}}">{{title}}</a></li>
                {{/menu}}
                </ul>

                {{#show_transaction_buttons}}{{> new_transaction_buttons }}{{/show_transaction_buttons}}
            </div>
        </div>
    </div>
</nav>

{{#show_transaction_buttons}}
<div id='image-upload-progress' class='col-sm-12' style='display:none'>
    <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%; min-width:30px;">
            <strong><span class='percent'>0%</span></strong>
        </div>
    </div>
</div>
{{/show_transaction_buttons}}
