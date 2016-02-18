<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ title }}</title>
    {{> styles }}
    {{> scripts_head }}
</head>
<body>
    {{{ render_body }}}
    {{> scripts_foot }}
</body>
</html>
