<form action="" method="post" enctype="multipart/form-data">

    <input type='hidden' name='id' value='{{id}}'/>

<div class="row">
    <div class='col-sm-12'>  
        <h1>{{form_title}}</h1>
        {{#error}}
            <p class='alert bg-danger'>{{error}}</p>
        {{/error}}
        {{#repeat.is_repeat_child}}
            <p class='alert bg-danger'>
                This is a transaction recurrance.
                You may wish to 
                <a class="btn btn-default btn-xs" href="{{repeat.parent_url}}">Edit the Master</a>
                instead
            </p>
        {{/repeat.is_repeat_child}}
    </div><!-- /col -->
</div><!-- /row -->

<div class="row">
    <div class='col-sm-12'>
        {{> tab_menu }}
    </div>
</div><!-- /row -->

<div class="row">
    <div class='col-sm-12'>
        <div class='tab-container'>
            {{> tab_general }}
        {{#image}}
            {{> tab_image }}
        {{/image}}
        {{^repeat.is_repeat_child}}
            {{> tab_repeat }}
        {{/repeat.is_repeat_child}}
        </div><!-- /tab-container -->
    </div><!-- /col -->
</div><!-- /row -->

<div class="row">
    {{#repeat.is_repeat_parent}}
        <div class='col-sm-12'>  
            <div class="form-group">
                <span class="form-control-static">This is a recurring transaction. View recurrances under Repeat tab.</span>
                <div class="checkbox form-control" style="margin-top:0">
                    <label for="update_recurrances" class="control-label">
                        <input type="checkbox" id="update_recurrances" name="update_recurrances" value="yes" checked="checked" />
                        Update all recurrances with these changes
                    </label>
                </div>
            </div>
        </div>
    {{/repeat.is_repeat_parent}}
</div>


<nav class="navbar navbar-inverse navbar-fixed-bottom" style='padding-top: 9px'>
    <div class="container">
        <div class="col-sm-12">

            <div class='row'>
            {{#id}}<div class='col-xs-2 col-tight'>{{/id}}
            {{^id}}<div class='col-xs-3 col-tight'>{{/id}}
                    <p>
                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="apply">
                            <span class='hidden-xs hidden-sm'>Apply</span>
                            <span class='glyphicon glyphicon-ok'></span>
                            <small class='visible-xs visible-sm'>Apply</small>
                        </button>
                    </p>
                </div><!-- /col -->

                <div class='col-xs-3 col-tight'>  
                    <p>
                        <button type="submit" class="btn btn-success btn-block" name="submit" value="save_new">
                            <span class='hidden-xs hidden-sm'>Save</span>
                            <span class='glyphicon glyphicon-ok'></span>
                            <span class='hidden-xs hidden-sm'>&amp; New</span>
                            <span class='glyphicon glyphicon-plus'></span>
                            <small class='visible-xs visible-sm'>Save &amp; New</small>
                        </button>
                    </p>
                </div><!-- /col -->

                <div class='col-xs-3 col-tight'>  
                    <p>
                        <button type="submit" class="btn btn-info btn-block" name="submit" value="save_close">
                            <span class='hidden-xs hidden-sm'>Save</span>
                            <span class='glyphicon glyphicon-ok'></span>
                            <span class='hidden-xs hidden-sm'>&amp; Close</span>
                            <span class='glyphicon glyphicon-remove'></span>
                            <small class='visible-xs visible-sm'>Save &amp; Close</small>
                        </button>
                    </p>
                </div><!-- /col -->

            {{#id}}<div class='col-xs-2 col-tight'>{{/id}}
            {{^id}}<div class='col-xs-3 col-tight'>{{/id}}
                    <p>
                        <a href="/transaction/list" class="btn btn-warning btn-block">
                            <span class='hidden-xs hidden-sm'>Cancel</span>
                            <span class='glyphicon glyphicon-remove'></span>
                            <small class='visible-xs visible-sm'>Cancel</small>
                        </a>
                    </p>
                </div><!-- /col -->
            {{#id}}
                <div class='col-xs-2 col-tight'>  
                    <p>
                        <a href="/transaction/delete?id={{id}}" class="btn btn-danger btn-block" data-confirm="Are you sure you want to delete this item? {{#repeat.is_repeat_parent}}All recurrances will be deleted as well.{{/repeat.is_repeat_parent}}{{#repeat.is_repeat_child}}This recurrance may be re-created automatically based on the master transaction.{{/repeat.is_repeat_child}}">
                            <span class='hidden-xs hidden-sm'>Delete</span>
                            <span class='glyphicon glyphicon-trash'></span>
                            <small class='visible-xs visible-sm'>Delete</small>
                        </a>
                    </p>
                </div><!-- /col -->
            {{/id}}

            </div><!-- /row -->

        </div>
    </div>
</nav>

</form>
