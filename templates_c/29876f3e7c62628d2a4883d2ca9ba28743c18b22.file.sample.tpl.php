<?php /* Smarty version Smarty-3.0.6, created on 2011-03-24 17:00:06
         compiled from "/var/www/html/pop/templates/sample.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2210334464d8bbee63a0064-47888143%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29876f3e7c62628d2a4883d2ca9ba28743c18b22' => 
    array (
      0 => '/var/www/html/pop/templates/sample.tpl',
      1 => 1301004003,
      2 => 'file',
    ),
    '2050bf0ec051180bbe7ec533c98b7ac6e910d827' => 
    array (
      0 => '/var/www/html/pop/templates/base.tpl',
      1 => 1300908629,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2210334464d8bbee63a0064-47888143',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!doctype html>
<html lang="en">
	<head>
		<base href="<?php echo $_smarty_tpl->getVariable('app_root')->value;?>
">
		<meta charset="utf-8">

		<title><?php echo $_smarty_tpl->getVariable('main_title')->value;?>
</title>

		<link rel="stylesheet" href="www/css/base.css">
		<link rel="stylesheet" href="www/css/style.css">

		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
		
		<script src="www/js/script.js"></script>

	</head>
	<body>
		<div id="container">
			<div id="header">
</div>
			<div id="main">
<h1>hello sample</h1>

<form method="post">
<label for="meta">meta (key:value)</label>
<input type="text" name="meta">
<input type="submit" value="add">
</form>

<ul>
<?php  $_smarty_tpl->tpl_vars['att'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('atts')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['att']->key => $_smarty_tpl->tpl_vars['att']->value){
?>
<li><?php echo $_smarty_tpl->getVariable('att')->value->ascii_id;?>
 (<?php echo $_smarty_tpl->getVariable('att')->value->id;?>
)
<ul>
<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('att')->value->values; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
<li><?php echo $_smarty_tpl->getVariable('v')->value->text;?>
</li>
<?php }} ?>
</ul>
</li>
<?php }} ?>
</ul>

</div>
			<div class="clear"></div>
			<div id="footer">
</div>
		</div>
	</body>
</html>
