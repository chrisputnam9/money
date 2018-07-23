{{#budget_menu_data}}
{{#budget}}
<nav class="navbar navbar-inverse navbar-fixed-bottom navbar-tight">
    <div class="container">

        {{> filters }}

        <div class="col-xs-11 clearfix navbar-text col-bare pad-left">
            <div class="col-xs-4 col-md-3 col-bare">
                <b>Date</b>
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
                    <div class="progress-bar progress-bar-{{status}}" role="progressbar" aria-valuenow="{{remaining_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{remaining_percentage}}%; min-width:30px;">
                        <strong><span class='percent'>{{remaining_formatted}}</span></strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
</nav>
{{/budget}}
{{/budget_menu_data}}
