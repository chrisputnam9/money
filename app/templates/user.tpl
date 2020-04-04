<div class='row'>
    <div class='col-sm-12'>
        <div class="panel panel-default panel-tight hidden-xs hidden-sm">

            <div class="panel-heading">
                <b>Current User</b>
            </div>

            <div class="panel-body">
                <div class='table-responsive text-left'>
                    <table class='table table-bordered'>
                        {{#user}}
                            <tr>
                                <th>Username:</th>
                                <td>{{name}}</td>
                            </tr>
                            <tr>
                                <th>API_Key:</th>
                                <td><input type='text' readonly='readonly' value='{{api_key}}' size='50' /></td>
                            </tr>
                        {{/user}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
