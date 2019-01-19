<ul class="nav nav-tabs nav-justified js-tabify">

  <li role="presentation" class="active"><a href="#tab_general" class="active">General</a></li>

{{#image}}
  <li role="presentation"><a href="#tab_image">Image</a></li>
{{/image}}

{{#file}}
  <li role="presentation"><a href="#tab_file">File</a></li>
{{/file}}

{{^repeat.is_repeat_child}}
  <li role="presentation"><a href="#tab_repeat">Repeat</a></li>
{{/repeat.is_repeat_child}}

</ul>
