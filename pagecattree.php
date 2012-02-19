<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=header.tags
 * [END_COT_EXT]
 */
/**
 * pagecattree Plugin for Cotonti CMF
 *
 * @version 2.0.0
 * @author esclkm, http://www.littledev.ru
 * @copyright (c) 2008-2011 esclkm, http://www.littledev.ru
 */
defined('COT_CODE') or die('Wrong URL.');
if (!function_exists(cot_build_catstree))
{

	function cot_build_catstree($parent, $selected = array(), $level = 0, $template='')
	{
		global $structure, $cfg, $db_pages, $db, $sys;
		$t1 = new XTemplate(cot_tplfile(array('pagecattree', $template), 'plug'));
		$children = cot_structure_children('page', $parent, false, false);
		if (count($children) == 0 && !$cfg['plugin']['pagecattree']['addpages'])
		{
			return false;
		}
		$jj = 0;
		foreach ($children as $row)
		{
			$jj++;
			$has_children = count(cot_structure_children('page', $row, false, false));
			$t1->assign(array(
				"ROW_TITLE" => htmlspecialchars($structure['page'][$row]['title']),
				"ROW_DESC" => $structure['page'][$row]['desc'],
				"ROW_ICON" => $structure['page'][$row]['icon'],
				"ROW_HREF" => cot_url('page', 'c=' . $row),
				"ROW_SELECTED" => in_array($row, $selected) ? 1 : 0,
				"ROW_SUBCAT" => cot_build_catstree($row, $selected, $level + 1),
				"ROW_HASCHILD" => $has_children,
				"ROW_LEVEL" => $level,
				"ROW_TYPE" => "cat",
				"ROW_ODDEVEN" => cot_build_oddeven($jj),
				"ROW_JJ" => $jj
			));
			$t1->parse("MAIN.CATS");
		}
		if ($cfg['plugin']['pagecattree']['addpages'])
		{
			$sql_p = $db->query("SELECT * FROM $db_pages WHERE page_state = 0 AND page_cat <> 'system' AND page_begin <= {$sys['now']} AND (page_expire = 0 OR page_expire > {$sys['now']}) AND page_cat = '" . $parent . "' ORDER BY page_title ASC");
			foreach ($sql_p->fetchAll() as $pag)
			{
				$jj++;
				$pag['page_pageurl'] = (empty($pag['page_alias'])) ? cot_url('page', 'c=' . $pag['page_cat'] . '&id=' . $pag['page_id']) : cot_url('page', 'c=' . $pag['page_cat'] . '&al=' . $pag['page_alias']);
				$t1->assign(array(
					"ROW_TITLE" => htmlspecialchars($pag['page_title']),
					"ROW_DESC" => htmlspecialchars($pag['page_desc']),
					"ROW_ICON" => "",
					"ROW_HREF" => $pag['page_pageurl'],
					"ROW_SELECTED" => 0,
					"ROW_SUBCAT" => false,
					"ROW_HASCHILD" => false,
					"ROW_LEVEL" => $level,
					"ROW_TYPE" => "page",
					"ROW_ODDEVEN" => cot_build_oddeven($jj),
					"ROW_JJ" => $jj
				));
				$t1->parse("MAIN.CATS");
			}
		}
		if ($jj == 0)
		{
			return false;
		}
		$t1->parse("MAIN");
		return $t1->text("MAIN");
	}

}

$ctree_cat = (isset($c)) ? $c : $pag['page_cat'];
$array_parent = cot_structure_parents('page', $ctree_cat);
$array_parent = (!is_array($array_parent)) ? $array_parent : array();

if (!empty($cfg['plugin']['pagecattree']['defcat']))
{
	$cattreecats = explode(",", $cfg['plugin']['pagecattree']['defcat']);
	foreach ($cattreecats as $treecat)
	{
		$PAGECATTREE[strtoupper(trim($treecat))] = cot_build_catstree(trim($treecat), $array_parent, 0, trim($treecat));
	}
}
?>