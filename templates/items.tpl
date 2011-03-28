{extends "base.tpl"}

{block name="main"}

<form id="item" method="post" action="items/generator">
<input type="submit" value="generate new item">
</form>

<div class="items">
<h2>Items</h2>

<ul>
{foreach item=item from=$items}
<li>
<div class="marker" style="background-color: #{$item->serial_number};">&nbsp;&nbsp;</div>
<a href="item/{$item->serial_number}">{$item->serial_number}</a>
{if $item->values|@count > 0}({$item->values|@count}){/if}
</li>
{/foreach}
</ul>
</div>

{/block}
