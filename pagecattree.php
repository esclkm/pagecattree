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
			if ($has_children)
			{
				$t1->parse("MAIN.CATS.SUB");
			}
			$t1->assign(array(
				"ROW_TITLE" => htmlspecialchars($structure['page'][$row]['title']),
				"ROW_DESC" => $structure['page'][$row]['desc'],
				"ROW_ICON" => $structure['page'][$row]['icon'],
				"ROW_HREF" => cot_url('page', 'c=' . $row),
				"ROW_SELECTED" => in_array($row, $selected) ? 1 : 0,
				"ROW_SUBCAT" => ((int)$cfg['plugin']['pagecattree']['maxlevel'] == 0 || (int)$cfg['plugin']['pagecattree']['maxlevel'] > $level+1) ? cot_build_catstree($row, $selected, $level + 1) : '',
				"ROW_HASCHILD" => $has_children,
				"ROW_LEVEL" => $level,
				"ROW_TYPE" => "cat",
				"ROW_CLASS" => '[class_'.$row.']',
				"ROW_PAGES" => '[pages_'.$row.']',
				"ROW_ODDEVEN" => cot_build_oddeven($jj),
				"ROW_JJ" => $jj
			));
			$t1->parse("MAIN.CATS");
		}
		if ($cfg['plugin']['pagecattree']['addpages'])
		{
			$subquery = (!empty($cfg['plugin']['pagecattree']['query'])) ? ' AND '.$cfg['plugin']['pagecattree']['query'] : '';
			$limit = ((int)$cfg['plugin']['pagecattree']['maxpages'] > 0) ? ' LIMIT '.(int)$cfg['plugin']['pagecattree']['maxpages'] : '';
			$sql_p = $db->query("SELECT * FROM $db_pages WHERE (page_state=0 OR page_state=2) AND page_cat <> 'system' AND page_begin <= {$sys['now']} AND (page_expire = 0 OR page_expire > {$sys['now']}) AND page_cat = '" . $parent . "' $subquery ORDER BY page_title ASC $limit");

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
					"ROW_CLASS" => '[class_page_'.$pag['page_id'].']',
					"ROW_ODDEVEN" => cot_build_oddeven($jj),
					"ROW_JJ" => $jj
				));
				$t1->assign(cot_generate_pagetags($pag, 'ROW_PAGE_'));
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
if (!function_exists(cot_build_catstree_pages))
{

	function cot_build_catstree_pages($parent, $selected, $template='')
	{
		global $structure, $cfg, $db_pages, $db, $sys;
		$t1 = new XTemplate(cot_tplfile(array('pagecattree', $template), 'plug'));
		if ($cfg['plugin']['pagecattree']['addpagesforcurr'] && !$cfg['plugin']['pagecattree']['addpages'])
		{
			$subquery = (!empty($cfg['plugin']['pagecattree']['query'])) ? ' AND '.$cfg['plugin']['pagecattree']['query'] : '';
			$limit = ((int)$cfg['plugin']['pagecattree']['maxpages'] > 0) ? ' LIMIT '.(int)$cfg['plugin']['pagecattree']['maxpages'] : '';
			$sql_p = $db->query("SELECT * FROM $db_pages WHERE (page_state=0 OR page_state=2) AND page_cat <> 'system' AND page_begin <= {$sys['now']} AND (page_expire = 0 OR page_expire > {$sys['now']}) AND page_cat = '" . $parent . "' $subquery ORDER BY page_title ASC $limit");

			foreach ($sql_p->fetchAll() as $pag)
			{
				$jj++;
				$pag['page_pageurl'] = (empty($pag['page_alias'])) ? cot_url('page', 'c=' . $pag['page_cat'] . '&id=' . $pag['page_id']) : cot_url('page', 'c=' . $pag['page_cat'] . '&al=' . $pag['page_alias']);
				$t1->assign(array(
					"ROW_TITLE" => htmlspecialchars($pag['page_title']),
					"ROW_DESC" => htmlspecialchars($pag['page_desc']),
					"ROW_ICON" => "",
					"ROW_HREF" => $pag['page_pageurl'],
					"ROW_SELECTED" => $pag['page_id'] == $selected,
					"ROW_SUBCAT" => false,
					"ROW_HASCHILD" => false,
					"ROW_TYPE" => "page",
					"ROW_CLASS" => '[class_page_'.$pag['page_id'].']',
					"ROW_ODDEVEN" => cot_build_oddeven($jj),
					"ROW_JJ" => $jj
				));
				$t1->assign(cot_generate_pagetags($pag, 'ROW_PAGE_'));
				$t1->parse("PAGES.PAGE");
			}
		}
		if ($jj == 0)
		{
			return '';
		}
		$t1->parse("PAGES");
		return $t1->text("PAGES");
	}

}

$ctree_cat = (isset($c)) ? $c : $pag['page_cat'];
$array_parent = cot_structure_parents('page', $ctree_cat);
$array_parent = (!is_array($array_parent)) ? $array_parent : array();

if (!empty($cfg['plugin']['pagecattree']['defcat']) && $env['ext'] != 'admin' && !$PAGECATTREE)
{

	$cattreecats = explode(",", $cfg['plugin']['pagecattree']['defcat']);
	foreach ($cattreecats as $treecat)
	{
		$PAGECATTREE[strtoupper(trim($treecat))] = cot_build_catstree(trim($treecat), $array_parent, 0, trim($treecat));
	}
	if($cache && $cfg['plugin']['pagecattree']['cache'])
	{
		$cache && $cache->db->store('PAGECATTREE', $PAGECATTREE, 'system', (int)$cfg['plugin']['pagecattree']['cachetime']);
	}
}
if(count($PAGECATTREE) > 0 )
{
	$children_elems = array();
	if($_GET['e'] == 'page')
	{
		global $id, $pag;
		$c = cot_import('c', 'G', 'TXT');

		if(!empty($pag['page_cat']))
		{
			$c = $pag['page_cat'];
		}
		$children_elems = cot_structure_parents('page', $c);
		
		if(!empty($id))
		{
			$children_elems[] = 'page_'.$id;
		}
	}
	foreach ($PAGECATTREE as $k => $v)
	{
		foreach ($children_elems as $children_elem)
		{
			$PAGECATTREE[$k] = str_replace('[class_'.$children_elem.']', $cfg['plugin']['pagecattree']['rselected'], $PAGECATTREE[$k]);
		}
		if($cfg['plugin']['pagecattree']['addpagesforcurr'] && !$cfg['plugin']['pagecattree']['addpages'] && !empty($c))
		{
			$sub_pages = cot_build_catstree_pages($c, $_GET['e'] == 'page' ? $pag['page_id'] : 0, $k);
			$PAGECATTREE[$k] = str_replace('[pages_'.$c.']', $sub_pages, $PAGECATTREE[$k]);
			
		}
		$PAGECATTREE[$k] = preg_replace("/\[class_(.+?)\]/", $cfg['plugin']['pagecattree']['rother'], $PAGECATTREE[$k]);
		$PAGECATTREE[$k] = preg_replace("/\[pages_(.+?)\]/", '', $PAGECATTREE[$k]);

	}
}
?>