{extends "base.tpl"}

{block name="main"}

<div class="items">
<h2>Items matching {if $ascii_id}{$ascii_id}: {/if}"{$q}"</h2>

<ul>
{foreach item=sn from=$serial_numbers}
<li>
<div class="marker" style="background-color: #{$sn};">&nbsp;&nbsp;</div>
<a href="item/{$sn}">{$sn}</a>
</li>
{/foreach}
</ul>
</div>

{/block}
