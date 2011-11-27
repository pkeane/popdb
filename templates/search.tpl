{extends "base.tpl"}

{block name="main"}

<div class="items">
<h2>Items matching {if $ascii_id}{$ascii_id}: {/if}"{$q}"</h2>

<ul>
{foreach item=item from=$items}
<li>
<a href="item/{$item->serial_number}">{$item->title}</a>
</li>
{/foreach}
</ul>
</div>

{/block}
