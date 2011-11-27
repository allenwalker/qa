<?php
	
/*
	Question2Answer 1.4.3 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-lang-admin.php
	Version: 1.4.3
	Date: 2011-09-27 18:06:46 GMT
	Description: Language phrases for admin center


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

	return array(
		'active_widgets_explanation' => 'The following widgets are currently active:',
		'add_category_button' => 'Add Category',
		'add_field_button' => 'Add Field',
		'add_link_button' => 'Add Link',
		'add_link_link' => ' - ^1add link^2',
		'add_new_field' => 'Add new field',
		'add_new_title' => 'Add new title',
		'add_page_button' => 'Add Page',
		'add_title_button' => 'Add Title',
		'add_widget_button' => 'Add Widget',
		'add_widget_link' => ' - ^1add widget^2',
		'admin_title' => 'Administration center',
		'after_footer' => 'After links in footer',
		'after_main_menu' => 'After tabs at top',
		'after_x_tab' => 'After "^" tab',
		'after_x' => 'After "^"',
		'basic_editor' => 'Basic Editor',
		'before_main_menu' => 'Before tabs at top',
		'block_ips_note' => 'Use a hyphen for ranges or * to match any number. Examples: 192.168.0.4 , 192.168.0.0-192.168.0.31 , 192.168.0.*',
		'block_words_note' => 'Use a * to match any letters. Examples: doh (will only match exact word doh) , doh* (will match doh or dohno) , do*h (will match doh, dooh, dough).',
		'categories_introduction' => 'To get started with categories, click the \'Add Category\' button.',
		'categories_not_shown' => 'Some questions have categories which will not be displayed.',
		'categories_title' => 'Categories',
		'categories' => 'Categories',
		'category_add_sub' => 'add sub-category',
		'category_added' => 'Category added',
		'category_already_used' => 'This is already being used by a category',
		'category_default_slug' => 'category-^',
		'category_description' => 'Category description (optional):',
		'category_max_depth_x' => 'Some options may be hidden to prevent a category going deeper than ^ levels.',
		'category_move_parent' => 'move to different parent',
		'category_name_first' => 'Name of first category:',
		'category_name' => 'Category name:',
		'category_no_add_subs_x' => 'This category cannot have sub-categories because it is already ^ levels down.',
		'category_no_delete_subs' => 'This category cannot be deleted because it has a sub-category.',
		'category_no_sub_error' => '^q question/s in this category have no sub-category - ^1set sub-category^2',
		'category_no_sub_to' => 'Move questions in ^ with no sub-category to:',
		'category_no_subs' => 'None',
		'category_none_error' => '^q question/s currently have no category - ^1set category^2',
		'category_none_to' => 'Move questions with no category to:',
		'category_parent' => 'Parent category:',
		'category_saved' => 'Category saved',
		'category_slug' => 'Category slug (URL fragment):',
		'category_subs' => 'Sub-categories:',
		'category_top_level' => 'No parent (top level)',
		'click_category_edit' => 'Click a category name to edit:',
		'click_name_edit' => 'Custom pages or links:',
		'database_cleanup' => 'Database clean-up operations',
		'delete_category_reassign' => 'Delete this category and reassign its questions to:',
		'delete_category' => 'Delete this category',
		'delete_field' => 'Delete this field',
		'delete_hidden_complete' => 'All hidden posts without dependents have been deleted',
		'delete_hidden_note' => ' - all hidden questions, answer and comments without dependents',
		'delete_hidden' => 'Delete hidden posts',
		'delete_link' => 'Delete this link',
		'delete_page' => 'Delete this page',
		'delete_stop' => 'Stop deleting',
		'delete_title' => 'Delete this title',
		'delete_widget_position' => 'Delete this widget from this position',
		'edit_custom_page' => 'Edit custom page',
		'edit_field' => ' - ^1edit field^2',
		'edit_link' => ' - ^1edit link^2',
		'edit_page' => ' - ^1edit page^2',
		'edit_title' => ' - ^1edit title^2',
		'emails_title' => 'Emails',
		'feed_link_example' => 'Example feed',
		'feed_link' => 'Feed',
		'feeds_title' => 'RSS feeds',
		'field_link_url' => 'Linked URL',
		'field_multi_line' => 'Multiple lines of text',
		'field_name' => 'Field name for display:',
		'field_single_line' => 'Single line of text',
		'field_type' => 'Field type:',
		'first' => 'First',
		'from_anon' => 'From anonymous:',
		'from_plugin_module' => ' (from plugin module: ^)',
		'from_users' => 'From users:',
		'general_title' => 'General',
		'hidden_answers_deleted' => 'Deleted ^1 of ^2 hidden answers without dependents...',
		'hidden_comments_deleted' => 'Deleted ^1 of ^2 hidden comments...',
		'hidden_questions_deleted' => 'Deleted ^1 of ^2 hidden questions without dependents...',
		'hidden_title' => 'Hidden',
		'installed_plugins' => 'Installed plugins:',
		'ip_address_pages' => 'IP address pages',
		'layout_title' => 'Layout',
		'link_name' => 'Text of link:',
		'link_new_window' => 'Open link in a new window',
		'link_url' => 'URL of link (absolute or relative to QA root):',
		'lists_title' => 'Lists',
		'maintenance_admin_only' => 'Your site is in ^1maintenance^2 and is currently inaccessible to regular users.',
		'maximum_x' => ' (max ^)',
		'mysql_version' => 'MySQL version:',
		'nav_links_explanation' => 'Show the following navigation links on every page:',
		'nav_qa_is_home' => 'Q&A (links to home page)',
		'neat_urls_note' => ' (requires ^1htaccess^2 file)',
		'no_classification' => 'No classification',
		'no_hidden_found' => 'No hidden questions, answers or comments found',
		'no_image_gd' => 'The installed version of PHP was compiled without GD image support, so users cannot upload their avatars directly.',
		'no_link' => 'No link',
		'no_multibyte' => 'The installed version of PHP was compiled without multibyte string support. Searching will be less effective for non-Roman characters.',
		'no_plugin_options' => 'None of your installed plugins have options to display.',
		'no_privileges' => 'Only administrators may access this page.',
		'not_logged_in' => 'Please ^1log in^2 as the administrator to access this page.',
		'opposite_main_menu' => 'Far end of tabs at top',
		'options' => 'options',
		'options_reset' => 'Options reset',
		'options_saved' => 'Options saved',
		'page_already_used' => 'This is already being used by a page',
		'page_content_html' => 'Content to display in page (HTML allowed):',
		'page_default_slug' => 'page-^',
		'page_heading' => 'Heading to display at top of page:',
		'page_name' => 'Name of page (also used for tab or link):',
		'page_slug' => 'Page slug (URL fragment):',
		'pages_explanation' => 'Click the \'Add Page\' button to add custom content to your QA site, or \'Add Link\' to link to any other web page.',
		'pages_title' => 'Pages',
		'permissions_title' => 'Permissions',
		'php_version' => 'PHP version:',
		'plugin_pages_explanation' => 'The following pages are available via plugins:',
		'plugin_widgets_explanation' => 'The following widgets are available via plugins:',
		'plugins_title' => 'Plugins',
		'points_defaults_shown' => 'Defaults shown below but NOT YET APPLIED:',
		'points_required' => 'Points required to receive title:',
		'points_title' => 'Points',
		'points' => 'points',
		'position' => 'Position:',
		'posting_title' => 'Posting',
		'profile_fields' => 'Extra fields on user profile:',
		'q2a_db_version' => 'Q2A database version:',
		'q2a_latest_version' => 'Q2A latest version:',
		'q2a_version' => 'Question2Answer version:',
		'question_lists' => 'Question lists',
		'question_pages' => 'Question pages',
		'recalc_categories_backpaths' => 'Recalculating URL paths for ^1 of ^2 categories...',
		'recalc_categories_complete' => 'All categories were successfully recalculated.',
		'recalc_categories_note' => ' - for post categories and category counts',
		'recalc_categories_recounting' => 'Recounting questions for ^1 of ^2 categories...',
		'recalc_categories_updated' => 'Recalculated for ^1 of ^2 posts...',
		'recalc_categories' => 'Recalculate categories',
		'recalc_points_complete' => 'All user points were successfully recalculated.',
		'recalc_points_note' => ' - for user ranking and points displays',
		'recalc_points_recalced' => 'Recalculated for ^1 of ^2 users...',
		'recalc_points_usercount' => 'Estimating total number of users...',
		'recalc_points' => 'Recalculate user points',
		'recalc_posts_count' => 'Getting total number of questions, answers and comments...',
		'recalc_stop' => 'Stop recalculating',
		'recaptcha_fsockopen' => 'To use reCAPTCHA, the fsockopen() PHP function must be enabled on your server. Please check with your system administrator.',
		'recaptcha_get_keys' => 'To use reCAPTCHA, you must ^1sign up^2 to get these keys.',
		'recent_hidden_title' => 'Questions with recent hidden content',
		'recount_posts_complete' => 'All posts were successfully recounted.',
		'recount_posts_note' => ' - the number of answers, votes, flags and hotness for each post',
		'recount_posts_recounted' => 'Recounted for ^1 of ^2 posts...',
		'recount_posts_stop' => 'Stop recounting',
		'recount_posts' => 'Recount posts',
		'reindex_posts_complete' => 'All posts were successfully reindexed.',
		'reindex_posts_note' => ' - for searching and related question suggestions',
		'reindex_posts_reindexed' => 'Reindexed ^1 of ^2 posts...',
		'reindex_posts_stop' => 'Stop reindexing',
		'reindex_posts_wordcounted' => 'Recounted ^1 of ^2 words...',
		'reindex_posts' => 'Reindex posts',
		'requires_q2a_version' => 'Disabled - requires Question2Answer ^ or later',
		'reset_options_button' => 'Reset to Defaults',
		'save_options_button' => 'Save Options',
		'save_recalc_button' => 'Save and Recalculate',
		'show_defaults_button' => 'Show Defaults',
		'slug_bad_chars' => 'The slug may not contain these characters: ^',
		'slug_reserved' => 'This slug is reserved for use by another page',
		'spam_title' => 'Spam',
		'stats_title' => 'Stats',
		'stop_recalc_warning' => 'A database clean-up operation is running. If you close this page now, the operation will be interrupted.',
		'tag_pages' => 'Tag pages',
		'tags_and_categories' => 'Tags and Categories',
		'tags_not_shown' => 'Some questions have tags which will not be displayed.',
		'tags' => 'Tags',
		'title_already_used' => 'This value is already being used by another title',
		'total_as' => 'Total answers:',
		'total_cs' => 'Total comments:',
		'total_qs' => 'Total questions:',
		'unnamed_plugin' => 'Unnamed Plugin',
		'upgrade_db' => 'Your Question2Answer database needs to be ^1upgraded^2 for this version.',
		'url_format_note' => 'Options with the ^ label are working for your site\'s configuration. For best search engine optimization (SEO), use the first ^ option available.',
		'user_pages' => 'User pages',
		'user_title' => 'User title (HTML allowed):',
		'user_titles' => 'User titles based on points:',
		'users_active' => 'Active users:',
		'users_must_have' => 'Users must have',
		'users_posted' => 'Users who posted:',
		'users_registered' => 'Registered users:',
		'users_title' => 'Users',
		'users_voted' => 'Users who voted:',
		'viewing_title' => 'Viewing',
		'widget_all_pages' => 'Show widget in this position on all available pages',
		'widget_global_options' => ' - ^1edit global options^2',
		'widget_name' => 'Name of widget:',
		'widget_no_positions' => 'This widget has already been added to every available position.',
		'widget_not_available' => 'This widget is not available. This could be because the plugin providing the widget is no longer installed.',
		'widget_pages_explanation' => 'Show widget in this position on the following pages:',
	);
	

/*
	Omit PHP closing tag to help avoid accidental output
*/