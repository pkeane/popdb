{extends "base.tpl"}

{block name="main"}

<div class="items">
<h2>Items</h2>

<ul>
{foreach item=item from=$items}
<li>
<a href="item/{$item->serial_number}">{$item->title}</a>
{if $item->values|@count > 0}({$item->values|@count}){/if}
</li>
{/foreach}
</ul>
</div>

{/block}
