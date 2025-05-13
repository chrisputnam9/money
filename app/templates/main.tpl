<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>{{ title }}</title>
    <script
    src="https://js.sentry-cdn.com/9a76d5a2f452ac747dc3333535c4f92e.min.js"
    crossorigin="anonymous"
    ></script>
    {{> favicons }}
    {{> styles }}
    {{> scripts_head }}
</head>
<body>
    {{#show_menu}}{{> menu }}{{/show_menu}}
    {{#show_date_menu}}{{> date_menu }}{{/show_date_menu}}
    <div class="container">
        <div class="template-container--body">
            {{{ render_body }}}
        </div>
        {{> scripts_foot }}
    </div>
    {{#show_budget_menu}}{{> budget_menu }}{{/show_budget_menu}}
</body>
</html>
