<div class='row'>

    <div class='col-md-offset-3 col-sm-offset-2 col-xs-2 col-sm-1 text-center'>
        <h1><a class="btn btn-primary" href="{{prev_month_url}}"><span class="glyphicon glyphicon glyphicon-chevron-left"></span></a></h1>
    </div>
    <div class='col-md-4 col-sm-6 col-xs-8 text-center'>
        <h1 class='text-center'>{{month_title}}</h1>
    </div>
    <div class='col-xs-2 col-sm-1 text-center'>
        <h1><a class="btn btn-primary" href="{{next_month_url}}"><span class="glyphicon glyphicon glyphicon-chevron-right"></span></a></h1>
    </div>

{{#month_budgeted_length}}
<!-- 
    <div id='image-upload-progress' class='col-sm-12 col-md-6 col-lg-3'>
        <p class="label label-default">Category <small>(Monthly)</small></p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%; min-width:30px;">
                <strong><span class='percent'>10%</span></strong>
            </div>
        </div>
    </div>

    <div id='image-upload-progress' class='col-sm-12 col-md-6 col-lg-3'>
        <p class="label label-default">Category <small>(Monthly)</small></p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%; min-width:30px;">
                <strong><span class='percent'>40%</span></strong>
            </div>
        </div>
    </div>
-->
{{/month_budgeted_length}}
</div>

{{#year_budgeted_length}}
<div class='row'>
    <br/>
    <div class='col-sm-offset-2 col-sm-8 col-xs-12'>
        <h1 class='text-center'>{{year_title}}</h1>
    </div>
</div>
{{/year_budgeted_length}}

{{#month_unbudgeted_length}}
<div class='row'>
    <br/>
    <div class='col-sm-offset-2 col-sm-8 col-xs-12'>
        <h2 class='text-center'>Unbudgeted Spending {{month_title}}</h2>
        <div class='table-responsive text-left'>
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                {{#month_unbudgeted}}
                    <tr>
                        <td>{{category_value}}</td>
                        <td align="right">{{amount_formatted}}</td>
                    </tr>
                {{/month_unbudgeted}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{/month_unbudgeted_length}}

{{#year_unbudgeted_length}}
<div class='row'>
    <br/>
    <div class='col-sm-offset-2 col-sm-8 col-xs-12'>
        <h2 class='text-center'>Unbudgeted Spending {{year_title}}</h2>
        <div class='table-responsive text-left'>
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                {{#year_unbudgeted}}
                    <tr>
                        <td>{{category_value}}</td>
                        <td align="right">{{amount_formatted}}</td>
                    </tr>
                {{/year_unbudgeted}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{/year_unbudgeted_length}}
