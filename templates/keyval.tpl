{extends "base.tpl"}

{block name="main"}

<div class="controls">
<a href="item/{$item->serial_number}">back to item</a>
</div>

<h2>{$kv->attribute->ascii_id}</h2>
<form action="item/{$item->serial_number}/keyval/{$kv->id}" method="post">
<textarea name="text">{$kv->text}</textarea>
<input type="submit" value="update">
</form>

{/block}

