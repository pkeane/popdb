{extends "base.tpl"}

{block name="main"}

<form id="attribute" method="post">
<label for="att">new attribute</label>
<input type="text" name="att">
<input type="submit" value="add">
</form>

<div class="attributes">
<h2>Attributes</h2>

<ul>
{foreach item=att from=$atts}
<li><a href="attribute/{$att->ascii_id}">{$att->ascii_id}</a> ({$att->values|@count})</li>
{/foreach}
</ul>
</div>

{/block}
