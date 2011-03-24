{extends "base.tpl"}

{block name="title"}{$main_title}{/block}

{block name="header"}
{/block}

{block name="sidebar"}
{/block}

{block name="main"}
<h1>hello sample</h1>

<form method="post">
<label for="meta">meta (key:value)</label>
<input type="text" name="meta">
<input type="submit" value="add">
</form>

<ul>
{foreach item=att from=$atts}
<li>{$att->ascii_id} ({$att->id})
<ul>
{foreach item=v from=$att->values}
<li>{$v->text}</li>
{/foreach}
</ul>
</li>
{/foreach}
</ul>

{/block}

{block name="footer"}
{/block}
