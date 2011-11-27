{extends "base.tpl"}

{block name="main"}

<form class="add hide" id="targetMetadataForm" method="post" action="item/{$item->serial_number}/metadata">
<label>add metadata</label>
<select name="ascii_id">
<option value="">select attribute:</option>
{foreach item=att from=$attributes}
<option value="{$att->ascii_id}">{$att->ascii_id}</option>
{/foreach}
</select>
<input type="text" name="value">
<input type="submit" value="add">
<input type="button" value="cancel">
</form>

<h2>Item
<a href="#" class="toggle" id="toggleMetadataForm">[add metadata]</a>
</h2>
{$item->serial_number} <a href="item/{$item->serial_number}" class="delete">[x]</a>
<dl class="metadata">
{foreach item=val_array key=att from=$metadata}
<dt>{$att}</dt>
<dd>
<ul>
{foreach item=val from=$val_array}
<li>{$val.text|nl2br} <a href="{$val.href}" class="edit">[edit]</a></li>
{/foreach}
</ul>
</dd>
{/foreach}
</dl>

{/block}

