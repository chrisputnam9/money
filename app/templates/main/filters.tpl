<div class="col-sm-12 col-bare clearfix pad-top" id="filter-menu-collapsible2" style="border-bottom: 1px solid #aaa;">
    <form action="" method="get">

        <div class='col-xs-12 col-sm-6 col-tight pad-bottom'>  
            <div class="form-group" style="margin:0">
                <select class="form-control" name="category" id="category">
                    <option value="">- Category -</option>
                {{#category_options}}
                    <option value="{{id}}" {{selected}}>{{title}}</option>
                {{/category_options}}
                </select>

            </div>
        </div>

        <div class='col-xs-12 col-sm-6 col-bare pad-bottom'>
            <div class='col-xs-6 col-tight'>  
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="apply">
                    <span>Apply</span>
                    <span class='glyphicon glyphicon-ok'></span>
                </button>
            </div>  
            <div class='col-xs-6 col-tight'>  
                <button type="submit" class="btn btn-danger btn-block" name="submit" value="reset">
                    <span>Reset</span>
                    <span class='glyphicon glyphicon-remove'></span>
                </button>
            </div>  
        </div>

    </form>
</div>

<div class="col-xs-1 clearfix navbar-text col-bare text-left">
    <a class="btn btn-primary js-toggle" href="#filter-menu-collapsible" title="Toggle filters">
        <span class="sr-only">Toggle filters</span>
        <span class="glyphicon glyphicon-filter"></span>
    </a>
</div>
