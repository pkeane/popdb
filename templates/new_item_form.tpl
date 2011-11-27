{extends "base.tpl"}

{block name="main"}

<div id="targetItemForm">
<form class="add" method="post" action="items/generator">
<label for="title">title</label>
<input type="text"  name="title">
<label for="note">note</label>
<textarea name="note"></textarea>
<input type="submit" value="new item">
<input type="button" value="cancel">
</form>
</div>

{/block}
