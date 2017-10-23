<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>{{ title }}</title>
    {{> favicons }}
    {{> styles }}
    {{> scripts_head }}
</head>
<body>
    <div class="container">
        <div class="template-container--body">
            {{#show_menu}}{{> menu }}{{/show_menu}}
            {{{ render_body }}}
        </div>
        {{> scripts_foot }}
    </div>
</body>
</html>
