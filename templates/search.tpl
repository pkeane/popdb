{extends "base.tpl"}

{block name="main"}

<div class="items">
<h2>Items matching {if $ascii_id}{$ascii_id}: {/if}"{$q}"</h2>

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
