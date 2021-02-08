{{#budget_menu_data}}
{{#budget}}
<nav class="navbar navbar-inverse navbar-fixed-bottom navbar-tight">
    <div class="container">

<!--
        <div class="col-sm-12">
            <div id="filter-menu-collapsible">
                <div class="clearfix navbar-text col-bare">
                    Filter inputs will go here<br>
                    Filter inputs will go here<br>
                    Filter inputs will go here<br>
                    Filter inputs will go here<br>
                    Filter inputs will go here<br>
                    Filter inputs will go here<br>
                </div>
            </div>
        </div>
-->

        <div class="col-xs-12 clearfix navbar-text col-bare pad-right">
            <div class="col-xs-4 col-md-3 col-bare">
        <!--
                <b>&lt; Date &gt;</b>
        -->
            </div>
            <div class="col-xs-8 col-md-3 col-bare pad-left">
                <div class="hidden-xs hidden-sm">
                    <small>{{spending_formatted}} spent of {{limit_formatted}}</small>
                </div>
                <div class="visible-xs-block visible-sm-block text-right">
                    <small>{{spending_formatted}} spent of {{limit_formatted}}</small>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-bare">
                <div class="progress">
                    <div class="progress-bar progress-bar-{{status}}" role="progressbar" aria-valuenow="{{remaining_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{remaining_percentage}}%;">
                        <strong><span class='percent'>{{remaining_formatted}}</span></strong>
                    </div>
                </div>
            </div>
        </div>

<!--
        <div class="col-xs-1 clearfix navbar-text col-bare text-right">
            <a class="btn btn-primary js-toggle" href="#filter-menu-collapsible" title="Toggle filters">
                <span class="sr-only">Toggle filters</span>
                <span class="glyphicon glyphicon-filter"></span>
            </a>
        </div>
-->

    </div>
</nav>
{{/budget}}
{{/budget_menu_data}}
