{{#date_menu_data}}
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class='col-md-offset-3 col-sm-offset-2 col-sm-1 col-xs-2 text-center'>
            <ul class="nav navbar-nav">
                <li><a href="{{url_prev}}"><span class="glyphicon glyphicon glyphicon-chevron-left"></span></a></li>
            </ul>
        </div>
        <div class='col-md-4 col-sm-6 col-xs-8 navbar-text text-center'>
                {{title}}
                &nbsp;
                <a href="{{period_switch_url}}" title="Switch Period (Month/Year)"><span class="glyphicon glyphicon glyphicon-calendar"></span></a>
        </div>
        <div class='col-xs-2 col-sm-1 text-center'>
            <ul class="nav navbar-nav">
                <li><a href="{{url_next}}"><span class="glyphicon glyphicon glyphicon-chevron-right"></span></a></li>
            </ul>
        </div>
    </div>
</nav>
{{/date_menu_data}}
