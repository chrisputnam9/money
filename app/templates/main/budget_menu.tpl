{{#budget_menu_data}}
{{#budget}}
<nav class="navbar navbar-inverse navbar-fixed-bottom">
    <div class="container">
    <!--
        <div class="col-sm-3 hidden-xs">
            <p class="navbar-text">{{title}}</p>
        </div>
    -->
        <div class="col-md-offset-2 col-md-5 col-sm-7 hidden-xs" style="padding-top: 15px">
            <div class="progress" style="margin:0">
                <div class="progress-bar progress-bar-{{status}}" role="progressbar" aria-valuenow="{{remaining_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{remaining_percentage}}%; min-width:30px;">
                    <strong><span class='percent'>{{remaining_formatted}}</span></strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-5 hidden-xs">
            <p class="navbar-text navbar-right"><b>{{spending_formatted}}</b> spent of <b>{{limit_formatted}}</b></p>
        </div>
        <div class="col-xs-12 visible-xs clearfix navbar-text">
            <!-- <span class="clearfix"><b>{{title}}</b></span> -->
            <div class="progress" style="margin-bottom:5px">
                <div class="progress-bar progress-bar-{{status}}" role="progressbar" aria-valuenow="{{remaining_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{remaining_percentage}}%; min-width:30px;">
                    <strong><span class='percent'>{{remaining_formatted}}</span></strong>
                </div>
            </div>
            <small class="pull-right"><b>{{spending_formatted}}</b> spent of <b>{{limit_formatted}}</b></small>
        </div>
    </div>
</nav>
{{/budget}}
{{/budget_menu_data}}
