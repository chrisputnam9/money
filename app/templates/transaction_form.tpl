<form action="" method="post" enctype="multipart/form-data">

    <input type='hidden' name='id' value='{{id}}'/>

    <div class='col-sm-12'>  
        <h1>{{form_title}}</h1>
        {{#error}}
        <p class='alert bg-danger'>{{error}}</p>
        {{/error}}
    </div>

    {{> tab_menu }}

    <div class='tab-container'>
        <div class='col-sm-12'>
            {{> tab_0 }}
            {{> tab_1 }}
        {{#image}}
            {{> tab_2 }}
        {{/image}}
        </div>
    </div>


{{#id}}
    <div class='col-sm-2'>  
{{/id}}
{{^id}}
    <div class='col-sm-3'>  
{{/id}}
        <p>
            <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit" value="apply">Apply</button>
        </p>
    </div>
    <div class='col-sm-3'>  
        <p>
            <button type="submit" class="btn btn-success btn-lg btn-block" name="submit" value="save_new">Save &amp; New</button>
        </p>
    </div>
    <div class='col-sm-3'>  
        <p>
            <button type="submit" class="btn btn-info btn-lg btn-block" name="submit" value="save_close">Save &amp; Close</button>
        </p>
    </div>
{{#id}}
    <div class='col-sm-2'>  
{{/id}}
{{^id}}
    <div class='col-sm-3'>  
{{/id}}
        <p>
            <a href="/" class="btn btn-warning btn-lg btn-block">Cancel</a>
        </p>
    </div>
{{#id}}
    <div class='col-sm-2'>  
        <p>
            <a href="/transaction/delete?id={{id}}" class="btn btn-danger btn-lg btn-block" data-confirm="Are you sure you want to delete this item?">Delete</a>
        </p>
    </div>
{{/id}}

</form>
