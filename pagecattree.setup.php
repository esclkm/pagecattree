<?php

/**
 * [BEGIN_COT_EXT]
 * Code=pagecattree
 * Name=pagecattree
 * Description=pagecattree Plugin for Seditio CMS
 * Version=2.6
 * Date=04-Sep-2011
 * Author=esclkm, http://www.littledev.ru
 * Copyright=&copy; 2011 esclkm, http://www.littledev.ru
 * Notes=
 * SQL=
 * Auth_guests=R
 * Lock_guests=W12345A
 * Auth_members=R
 * Lock_members=W12345A
 * [END_COT_EXT]

 * [BEGIN_COT_EXT_CONFIG]
 * defcat=01:string:::Default categories selected, comma sep. Tags like {PHP.PAGECATTREE.CAT}
 * addpages=02:radio::1:Add pages in cat list
 * cache=03:radio::1:Enable cache 
 * maxlevel=04:string::3:Depth
 * query=05:string:::subquery for pages
 * rselected=06:string::selected:Replace for selected
 * rother=07:string:::Replace for other
 * cachetime=07:string::7200:Cache Time in sec
 * [END_COT_EXT_CONFIG]
 */

/**
 * pagecattree Plugin for Cotonti CMF
 *
 * @version 2.5.0
 * @author esclkm, http://www.littledev.ru
 * @copyright (c) 2008-2011 esclkm, http://www.littledev.ru
 */

defined('COT_CODE') or die('Wrong URL.');

?>