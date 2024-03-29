<?php
	
/*
	Question2Answer 1.4.3 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-page-admin-pages.php
	Version: 1.4.3
	Date: 2011-09-27 18:06:46 GMT
	Description: Controller for admin page for editing custom pages and external links


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'qa-app-admin.php';
	require_once QA_INCLUDE_DIR.'qa-app-format.php';
	require_once QA_INCLUDE_DIR.'qa-db-selects.php';

	
//	Get current list of pages and determine the state of this admin page

	$pageid=qa_post_text('edit');
	if (!isset($pageid))
		$pageid=qa_get('edit');
		
	@list($pages, $editpage)=qa_db_select_with_pending(
		qa_db_pages_selectspec(),
		isset($pageid) ? qa_db_page_full_selectspec($pageid, true) : null
	);
	
	if ((qa_clicked('doaddpage') || qa_clicked('doaddlink') || qa_get('doaddlink') || qa_clicked('dosavepage')) && !isset($editpage)) {
		$editpage=array('title' => qa_get('text'), 'tags' => qa_get('url'), 'nav' => qa_get('nav'), 'position' => 1);
		$isexternal=qa_clicked('doaddlink') || qa_get('doaddlink') || qa_post_text('external');
		
	} elseif (isset($editpage))
		$isexternal=$editpage['flags'] & QA_PAGE_FLAGS_EXTERNAL;
	

//	Check admin privileges (do late to allow one DB query)

	if (!qa_admin_check_privileges($qa_content))
		return $qa_content;
		
		
//	Define an array of navigation settings we can change, option name => language key
	
	$hascustomhome=qa_has_custom_home();
	
	$navoptions=array(
		'nav_home' => 'main/nav_home',
		'nav_activity' => 'main/nav_activity',
		 $hascustomhome ? 'nav_qa_not_home' : 'nav_qa_is_home' => $hascustomhome ? 'main/nav_qa' : 'admin/nav_qa_is_home',
		'nav_questions' => 'main/nav_qs',
		'nav_hot' => 'main/nav_hot',
		'nav_unanswered' => 'main/nav_unanswered',
		'nav_tags' => 'main/nav_tags',
		'nav_categories' => 'main/nav_categories',
		'nav_users' => 'main/nav_users',
	);
	
	$navpaths=array(
		'nav_home' => '',
		'nav_activity' => 'activity',
		'nav_qa_not_home' => 'qa',
		'nav_qa_is_home' => 'qa',
		'nav_questions' => 'questions',
		'nav_hot' => 'hot',
		'nav_unanswered' => 'unanswered',
		'nav_tags' => 'tags',
		'nav_categories' => 'categories',
		'nav_users' => 'users',
	);
	
	if (!qa_opt('show_custom_home'))
		unset($navoptions['nav_home']);
		
	if (!qa_using_categories())
		unset($navoptions['nav_categories']);

	if (!qa_using_tags())
		unset($navoptions['nav_tags']);


//	Process saving an old or new page

	if (qa_clicked('docancel'))
		$editpage=null;

	elseif (qa_clicked('dosaveoptions') || qa_clicked('doaddpage') || qa_clicked('doaddlink')) {
		foreach ($navoptions as $optionname => $langkey)
			qa_set_option($optionname, (int)qa_post_text('option_'.$optionname));

	} elseif (qa_clicked('dosavepage')) {
		require_once QA_INCLUDE_DIR.'qa-db-admin.php';
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
		
		$reloadpages=false;
		
		if (qa_post_text('dodelete')) {
			qa_db_page_delete($editpage['pageid']);
			$editpage=null;
			$reloadpages=true;
		
		} else {
			$inname=qa_post_text('name');
			$inposition=qa_post_text('position');
			$inurl=qa_post_text('url');
			$innewwindow=qa_post_text('newwindow');
			$inheading=qa_post_text('heading');
			$incontent=qa_post_text('content');

			$errors=array();
			
		//	Verify the name (navigation link) is legitimate
		
			if (empty($inname))
				$errors['name']=qa_lang('main/field_required');
			elseif (qa_strlen($inname)>QA_DB_MAX_CAT_PAGE_TITLE_LENGTH)
				$errors['name']=qa_lang_sub('main/max_length_x', QA_DB_MAX_CAT_PAGE_TITLE_LENGTH);
			else
				foreach ($pages as $page)
					if (
						($page['pageid'] != @$editpage['pageid']) &&
						qa_strtolower($page['title']) == qa_strtolower($inname)
					)
						$errors['name']=qa_lang('admin/page_already_used');
						
			if ($isexternal) {
			
			//	Verify the url is legitimate (vaguely)
			
				if (empty($inurl))
					$errors['url']=qa_lang('main/field_required');
				elseif (qa_strlen($inurl)>QA_DB_MAX_CAT_PAGE_TAGS_LENGTH)
					$errors['url']=qa_lang_sub('main/max_length_x', QA_DB_MAX_CAT_PAGE_TAGS_LENGTH);

			} else {
			
			//	Verify the heading is legitimate
			
				if (qa_strlen($inheading)>QA_DB_MAX_TITLE_LENGTH)
					$errors['heading']=qa_lang_sub('main/max_length_x', QA_DB_MAX_TITLE_LENGTH);
			
			//	Verify the slug is legitimate (and try some defaults if we're creating a new page, and it's not)
					
				for ($attempt=0; $attempt<100; $attempt++) {
					switch ($attempt) {
						case 0:
							$inslug=qa_post_text('slug');
							if (!isset($inslug))
								$inslug=implode('-', qa_string_to_words($inname));
							break;
							
						case 1:
							$inslug=qa_lang_sub('admin/page_default_slug', $inslug);
							break;
							
						default:
							$inslug=qa_lang_sub('admin/page_default_slug', $attempt-1);
							break;
					}
					
					list($matchcategoryid, $matchpage)=qa_db_select_with_pending(
						qa_db_slugs_to_category_id_selectspec($inslug),
						qa_db_page_full_selectspec($inslug, false)
					);
					
					if (empty($inslug))
						$errors['slug']=qa_lang('main/field_required');
					elseif (qa_strlen($inslug)>QA_DB_MAX_CAT_PAGE_TAGS_LENGTH)
						$errors['slug']=qa_lang_sub('main/max_length_x', QA_DB_MAX_CAT_PAGE_TAGS_LENGTH);
					elseif (preg_match('/[\\+\\/]/', $inslug))
						$errors['slug']=qa_lang_sub('admin/slug_bad_chars', '+ /');
					elseif (qa_admin_is_slug_reserved($inslug))
						$errors['slug']=qa_lang('admin/slug_reserved');
					elseif (isset($matchpage) && ($matchpage['pageid']!=@$editpage['pageid']))
						$errors['slug']=qa_lang('admin/page_already_used');
					elseif (isset($matchcategoryid))
						$errors['slug']=qa_lang('admin/category_already_used');
					else
						unset($errors['slug']);
					
					if (isset($editpage['pageid']) || !isset($errors['slug'])) // don't try other options if editing existing page
						break;
				}
			}
			
		//	Perform appropriate database action
	
			if (isset($editpage['pageid'])) { // changing existing page
				if ($isexternal)
					qa_db_page_set_fields($editpage['pageid'],
						isset($errors['name']) ? $editpage['title'] : $inname,
						QA_PAGE_FLAGS_EXTERNAL | ($innewwindow ? QA_PAGE_FLAGS_NEW_WINDOW : 0),
						isset($errors['url']) ? $editpage['tags'] : $inurl,
						null, null);

				else
					qa_db_page_set_fields($editpage['pageid'],
						isset($errors['name']) ? $editpage['title'] : $inname,
						0,
						isset($errors['slug']) ? $editpage['tags'] : $inslug,
						isset($errors['heading']) ? $editpage['heading'] : $inheading,
						isset($errors['content']) ? $editpage['content'] : $incontent);
				
				qa_db_page_move($editpage['pageid'], substr($inposition, 0, 1), substr($inposition, 1));
				
				$reloadpages=true;
	
				if (empty($errors))
					$editpage=null;
				else
					$editpage=@$pages[$editpage['pageid']];
	
			} else { // creating a new one
				if (empty($errors)) {
					if ($isexternal)
						$pageid=qa_db_page_create($inname, QA_PAGE_FLAGS_EXTERNAL | ($innewwindow ? QA_PAGE_FLAGS_NEW_WINDOW : 0), $inurl, null, null);
					else
						$pageid=qa_db_page_create($inname, 0, $inslug, $inheading, $incontent);
						
					qa_db_page_move($pageid, substr($inposition, 0, 1), substr($inposition, 1));

					$editpage=null;
					$reloadpages=true;
				}
			}
		}
		
		if ($reloadpages) {
			unset($qa_nav_pages_cached);
			$pages=qa_db_select_with_pending(qa_db_pages_selectspec());
		}
	}
		
		
//	Prepare content for theme
	
	$qa_content=qa_content_prepare();

	$qa_content['title']=qa_lang_html('admin/admin_title').' - '.qa_lang_html('admin/pages_title');
	
	$qa_content['error']=qa_admin_page_error();

	if (isset($editpage)) {
		$positionoptions=array();
		
		if (!$isexternal)
			$positionoptions['_'.max(1, @$editpage['position'])]=qa_lang_html('admin/no_link');
		
		$navlangkey=array(
			'B' => 'admin/before_main_menu',
			'M' => 'admin/after_main_menu',
			'O' => 'admin/opposite_main_menu',
			'F' => 'admin/after_footer',
		);
		
		foreach ($navlangkey as $nav => $langkey) {
			$previous=null;
			$passedself=false;
			$maxposition=0;
			
			foreach ($pages as $key => $page)
				if ($page['nav']==$nav) {
					if (isset($previous))
						$positionhtml=qa_lang_html_sub('admin/after_x_tab', qa_html($passedself ? $page['title'] : $previous['title']));
					else
						$positionhtml=qa_lang_html($langkey);
						
					if ($page['pageid']==@$editpage['pageid'])
						$passedself=true;
		
					$maxposition=max($maxposition, $page['position']);
					$positionoptions[$nav.$page['position']]=$positionhtml;
						
					$previous=$page;
				}
				
			if ((!isset($editpage['pageid'])) || $nav!=@$editpage['nav']) {
				$positionvalue=isset($previous) ? qa_lang_html_sub('admin/after_x_tab', qa_html($previous['title'])) : qa_lang_html($langkey);
				$positionoptions[$nav.(isset($previous) ? (1+$maxposition) : 1)]=$positionvalue;
			}
		}
		
		$positionvalue=@$positionoptions[$editpage['nav'].$editpage['position']];
		
		$qa_content['form']=array(
			'tags' => 'METHOD="POST" ACTION="'.qa_path_html($qa_request).'"',
			
			'style' => 'tall',
			
			'fields' => array(
				'name' => array(
					'tags' => 'NAME="name" ID="name"',
					'label' => qa_lang_html($isexternal ? 'admin/link_name' : 'admin/page_name'),
					'value' => qa_html(isset($inname) ? $inname : @$editpage['title']),
					'error' => qa_html(@$errors['name']),
				),
				
				'delete' => array(
					'tags' => 'NAME="dodelete" ID="dodelete"',
					'label' => qa_lang_html($isexternal ? 'admin/delete_link' : 'admin/delete_page'),
					'value' => 0,
					'type' => 'checkbox',
				),
				
				'position' => array(
					'id' => 'position_display',
					'tags' => 'NAME="position"',
					'label' => qa_lang_html('admin/position'),
					'type' => 'select',
					'options' => $positionoptions,
					'value' => $positionvalue,
				),
				
				'slug' => array(
					'id' => 'slug_display',
					'tags' => 'NAME="slug"',
					'label' => qa_lang_html('admin/page_slug'),
					'value' => qa_html(isset($inslug) ? $inslug : @$editpage['tags']),
					'error' => qa_html(@$errors['slug']),
				),
				
				'url' => array(
					'id' => 'url_display',
					'tags' => 'NAME="url"',
					'label' => qa_lang_html('admin/link_url'),
					'value' => qa_html(isset($inurl) ? $inurl : @$editpage['tags']),
					'error' => qa_html(@$errors['url']),
				),
				
				'newwindow' => array(
					'id' => 'newwindow_display',
					'tags' => 'NAME="newwindow"',
					'label' => qa_lang_html('admin/link_new_window'),
					'value' => (isset($innewwindow) ? $innewwindow : (@$editpage['flags'] & QA_PAGE_FLAGS_NEW_WINDOW)) ? 1 : 0,
					'type' => 'checkbox',
				),
				
				'heading' => array(
					'id' => 'heading_display',
					'tags' => 'NAME="heading"',
					'label' => qa_lang_html('admin/page_heading'),
					'value' => qa_html(isset($inheading) ? $inheading : @$editpage['heading']),
					'error' => qa_html(@$errors['heading']),
				),
				
				'content' => array(
					'id' => 'content_display',
					'tags' => 'NAME="content"',
					'label' => qa_lang_html('admin/page_content_html'),
					'value' => qa_html(isset($incontent) ? $incontent : @$editpage['content']),
					'error' => qa_html(@$errors['content']),
					'rows' => 16,
				),
			),

			'buttons' => array(
				'save' => array(
					'label' => qa_lang_html(isset($editpage['pageid']) ? 'main/save_button' : ($isexternal ? 'admin/add_link_button' : 'admin/add_page_button')),
				),
				
				'cancel' => array(
					'tags' => 'NAME="docancel"',
					'label' => qa_lang_html('main/cancel_button'),
				),
			),
			
			'hidden' => array(
				'dosavepage' => '1', // for IE
				'edit' => @$editpage['pageid'],
				'external' => (int)$isexternal,
			),
		);
		
		if ($isexternal) {
			unset($qa_content['form']['fields']['slug']);
			unset($qa_content['form']['fields']['heading']);
			unset($qa_content['form']['fields']['content']);
		
		} else {
			unset($qa_content['form']['fields']['url']);
			unset($qa_content['form']['fields']['newwindow']);
		}
		
		if (isset($editpage['pageid']))
			qa_set_display_rules($qa_content, array(
				'position_display' => '!dodelete',
				($isexternal ? 'url_display' : 'slug_display') => '!dodelete',
				($isexternal ? 'newwindow_display' : 'heading_display') => '!dodelete',
				'content_display' => '!dodelete',
			));
		
		else {
			unset($qa_content['form']['fields']['slug']);
			unset($qa_content['form']['fields']['delete']);
		}
		
		$qa_content['focusid']='name';
	
	} else {

	//	List of standard navigation links

		$qa_content['form']=array(
			'tags' => 'METHOD="POST" ACTION="'.qa_self_html().'"',
			
			'style' => 'tall',
			
			'fields' => array(),

			'buttons' => array(
				'save' => array(
					'tags' => 'NAME="dosaveoptions"',
					'label' => qa_lang_html('main/save_button'),
				),

				'addpage' => array(
					'tags' => 'NAME="doaddpage"',
					'label' => qa_lang_html('admin/add_page_button'),
				),

				'addlink' => array(
					'tags' => 'NAME="doaddlink"',
					'label' => qa_lang_html('admin/add_link_button'),
				),
			),
		);
		
		$qa_content['form']['fields']['navlinks']=array(
			'label' => qa_lang_html('admin/nav_links_explanation'),
			'type' => 'static',
			'tight' => true,
		);

		foreach ($navoptions as $optionname => $langkey) {
			$qa_content['form']['fields'][$optionname]=array(
				'label' => '<A HREF="'.qa_path_html($navpaths[$optionname]).'">'.qa_lang_html($langkey).'</A>',
				'tags' => 'NAME="option_'.$optionname.'"',
				'type' => 'checkbox',
				'value' => qa_opt($optionname),
			);
		}
		
	//	List of suggested plugin pages

		$listhtml='';
		
		$modulenames=qa_list_modules('page');
		
		foreach ($modulenames as $tryname) {
			$trypage=qa_load_module('page', $tryname);
			
			if (method_exists($trypage, 'suggest_requests')) {
				$suggestrequests=$trypage->suggest_requests();
			
				foreach ($suggestrequests as $suggestrequest) {
					$listhtml.='<LI><B><A HREF="'.qa_path_html($suggestrequest['request']).'">'.qa_html($suggestrequest['title']).'</A></B>';
					
					$listhtml.=qa_lang_html_sub('admin/from_plugin_module', qa_html($tryname));

					$listhtml.=strtr(qa_lang_html('admin/add_link_link'), array(
						'^1' => '<A HREF="'.qa_path_html($qa_request, array('doaddlink' => 1, 'text' => $suggestrequest['title'], 'url' => $suggestrequest['request'], 'nav' => @$suggestrequest['nav'])).'">',
						'^2' => '</A>',
					));
						
					$listhtml.='</LI>';
				}
			}
		}

		if (strlen($listhtml))
			$qa_content['form']['fields']['plugins']=array(
				'label' => qa_lang_html('admin/plugin_pages_explanation'),
				'type' => 'custom',
				'html' => '<UL STYLE="margin-bottom:0;">'.$listhtml.'</UL>',
			);
		
	//	List of custom pages or links

		$listhtml='';
		
		foreach ($pages as $page) {
			$listhtml.='<LI><B><A HREF="'.qa_custom_page_url($page).'">'.qa_html($page['title']).'</A></B>';
			
			$listhtml.=strtr(qa_lang_html(($page['flags'] & QA_PAGE_FLAGS_EXTERNAL) ? 'admin/edit_link' : 'admin/edit_page'), array(
				'^1' => '<A HREF="'.qa_path_html('admin/pages', array('edit' => $page['pageid'])).'">',
				'^2' => '</A>',
			));
								
			$listhtml.='</LI>';
		}
		
		$qa_content['form']['fields']['pages']=array(
			'label' => strlen($listhtml) ? qa_lang_html('admin/click_name_edit') : qa_lang_html('admin/pages_explanation'),
			'type' => 'custom',
			'html' => strlen($listhtml) ? '<UL STYLE="margin-bottom:0;">'.$listhtml.'</UL>' : null,
		);
	}


	$qa_content['navigation']['sub']=qa_admin_sub_navigation();
	
	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/