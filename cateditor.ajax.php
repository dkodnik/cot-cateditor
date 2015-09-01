<?php

/** 
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 */
 
/**
 * plugin Category Editor for Cotonti Siena
 * 
 * @package cateditor
 * @version 1.0.0
 * @author esclkm
 * @copyright 
 * @license BSD
 *  */
// Generated by Cotonti developer tool (littledev.ru)
defined('COT_CODE') or die('Wrong URL.');
define('COT_ADMIN', TRUE);
define('COT_CORE', TRUE);
require_once cot_incfile('cateditor', 'plug');
require_once cot_incfile('cateditor', 'plug', 'structure');
require_once cot_langfile('cateditor', 'plug');

/* === Hook === */
foreach (cot_getextplugins('admin.structure.first') as $pl)
{
	include $pl;
}
/* ===== */
$n = cot_import('n', 'G', 'TXT');
$id = cot_import('id', 'G', 'INT');
//cot_rc_link_file($cfg['plugins_dir'].'/banners/tpl/admin.css');
// Роутер
// Only if the file exists...
if (is_array($extension_structure) && count($extension_structure) == 1 && ((cot_plugin_active($extension_structure[0]) || cot_module_active($extension_structure[0]))))
{
	$n = $extension_structure[0];
}

$sub = (empty($n)) ? 'list' : 'editor';
$status = array();
if(!empty($n))
{
	$cot_structure = new structure($n);
	$is_module = (cot_module_active($n));
	
	if (file_exists(cot_incfile($n, $is_module ? 'module' : 'plug')))
	{
		require_once cot_incfile($n, $is_module ? 'module' : 'plug');
	}
}

if(!empty($a) && file_exists(cot_incfile('cateditor', 'plug', 'admin.'.$sub.'.'.$a)))
{
	require_once cot_incfile('cateditor', 'plug', 'admin.'.$sub.'.'.$a);
}
elseif($id > 0)
{
	require_once cot_incfile('cateditor', 'plug', 'admin.edit');
	$status['editor'] = form_structure_editor($id);
}
else
{
	$parentid = cot_import('parentid', 'G', 'INT');
	require_once cot_incfile('cateditor', 'plug', 'admin.new');
	$status['editor'] = form_structure_new($parentid);
}
/*
if (file_exists(cot_incfile('cateditor', 'plug', 'admin.'.$sub)))
{
	$t = new XTemplate(cot_tplfile('cateditor.admin.'.$sub, 'plug'));
	require_once cot_incfile('cateditor', 'plug', 'admin.'.$sub);
	$t->parse('MAIN');
	$adminmain = $t->text('MAIN');
}
*/
if($status['editor'])
{
	$status['editor'] = preg_replace('#<form\s+[^>]*method=["\']?post["\']?[^>]*>#i', '$0' . cot_xp(), $status['editor']);
}
cot_sendheaders('application/json');

$status['id'] = (int)$id;
$status['x'] = $sys['xk'];
//cot_watch($status, $_GET);
echo json_encode($status);
exit;