{extends "base.tpl"}

{block name="main"}

<form class="add hide" id="targetAttributeForm" method="post">
<label for="att">new attribute</label>
<input type="text" name="att">
<input type="submit" value="add">
<input type="button" value="cancel">
</form>

<div class="attributes">
<h2>Attributes
<a href="#" id="toggleAttributeForm" class="toggle">[new]</a>
</h2>

<ul>
{foreach item=att from=$atts}
<li><a href="attribute/{$att->ascii_id}">{$att->ascii_id}</a> ({$att->values|@count})</li>
{/foreach}
</ul>
</div>

{/block}
