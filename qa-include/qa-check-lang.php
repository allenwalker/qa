<?php

/*
	Question2Answer 1.4.3 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-check-lang.php
	Version: 1.4.3
	Date: 2011-09-27 18:06:46 GMT
	Description: Development tool to see which language phrases are missing or unused


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

	define('QA_BASE_DIR', dirname(dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME'])).'/');

	require 'qa-base.php';
	
	header('Content-type: text/html; charset=utf-8');
?>
<HTML>
	<HEAD>
		<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8"/>
		<TITLE>Question2Answer Language Check</TITLE>
		<STYLE>
			code {font-size:125%;}
		</STYLE>
	</HEAD>
	<BODY STYLE="font-family:arial; font-size:12px;">
<?php

	function get_phrase_substitutions($phrase)
	{
		$substitutions=array();

		if (preg_match_all('/\^(([0-9]+)|([a-z_]+)|)/', $phrase, $matches))
			foreach ($matches[0] as $match)
				@$substitutions[$match]++;
				
		return $substitutions;
	}
	
	echo '<font color="#c00"><code>Dark red = important to review.</code></font><br>';
	echo '<font color="#c99"><code>Light red = probably safe to ignore.</code></font>';
	
	echo '<H1>Checking US English files in <code>qa-include</code>...</H1>';
	
	$includefiles=array_merge(
		glob(QA_INCLUDE_DIR.'qa-*.php'),
		glob(QA_PLUGIN_DIR.'*/qa-*.php')
	);
	
	$definite=array();
	$probable=array();
	$possible=array();
	$defined=array();
	$english=array();
	$backmap=array();
	$substitutions=array();
	
	output_start_includes();
	
	foreach ($includefiles as $includefile) {
		$contents=file_get_contents($includefile);
		
		preg_match_all('/qa_lang[a-z_]*\s*\(\s*[\'\"]([a-z]+)\/([0-9a-z_]+)[\'\"]/', $contents, $matches, PREG_SET_ORDER);
		
		foreach ($matches as $matchparts)
			@$definite[$matchparts[1]][$matchparts[2]]++;
			
		preg_match_all('/[\'\"]([a-z]+)\/([0-9a-z_]+)[\'\"]/', $contents, $matches, PREG_SET_ORDER);

		foreach ($matches as $matchparts)
			@$probable[$matchparts[1]][$matchparts[2]]++;

		if (preg_match('|/qa-include/qa-lang-([a-z]+)\.php$|', $includefile, $matches)) { // it's a lang file
			$prefix=$matches[1];
		
			output_reading_include($includefile);
			$phrases=@include $includefile;
			
			foreach ($phrases as $key => $value) {
				@$defined[$prefix][$key]++;
				$english[$prefix][$key]=$value;
				$backmap[$value][]=array('prefix' => $prefix, 'key' => $key);
				$substitutions[$prefix][$key]=get_phrase_substitutions($value);
			}

		} else { // it's a different file
			preg_match_all('/[\'\"\/]([0-9a-z_]+)[\'\"]/', $contents, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $matchparts)
				@$possible[$matchparts[1]]++;
		}
	}
	
	output_finish_includes();
	
	foreach ($definite as $key => $valuecount)
		foreach ($valuecount as $value => $count)
			if (!@$defined[$key][$value])
				output_lang_issue($key, $value, 'used by '.$count.' file/s but not defined');
				
	foreach ($defined as $key => $valuecount)
		foreach ($valuecount as $value => $count)
			if ( (!@$definite[$key][$value]) && (!@$probable[$key][$value]) && (!@$possible[$value]) )
				output_lang_issue($key, $value, 'defined but apparently not used');
	
	foreach ($backmap as $phrase => $where)
		if (count($where)>1)
			foreach ($where as $onewhere)
				output_lang_issue($onewhere['prefix'], $onewhere['key'], 'contains the shared phrase "'.$phrase.'"', false);
	
	require_once QA_INCLUDE_DIR.'qa-app-admin.php';
	
	$languages=qa_admin_language_options();
	unset($languages['']);
	
	foreach ($languages as $code => $language) {
		echo '<H1>Checking '.$language.' files in <code>qa-lang/'.$code.'</code>...</H1>';
		
		$langdefined=array();
		$langdifferent=array();
		$langsubstitutions=array();
		$langincludefiles=glob(QA_LANG_DIR.$code.'/qa-*.php');
		$langnewphrases=array();
		
		output_start_includes();
		
		foreach ($langincludefiles as $langincludefile)
			if (preg_match('/qa-lang-([a-z]+)\.php$/', $langincludefile, $matches)) { // it's a lang file
				$prefix=$matches[1];

				output_reading_include($langincludefile);
				$phrases=@include $langincludefile;
				
				foreach ($phrases as $key => $value) {
					@$langdefined[$prefix][$key]++;
					$langdifferent[$prefix][$key]=($value!=@$english[$prefix][$key]);
					$langsubstitutions[$prefix][$key]=get_phrase_substitutions($value);
				}
			}
			
		output_finish_includes();
			
		foreach ($langdefined as $key => $valuecount)
			foreach ($valuecount as $value => $count) {
				if (!@$defined[$key][$value])
					output_lang_issue($key, $value, 'defined but not in US English files');
				
				elseif (!$langdifferent[$key][$value])
					output_lang_issue($key, $value, 'identical to US English files', false);
				
				else
					foreach ($substitutions[$key][$value] as $substitution => $subcount)
						if (!@$langsubstitutions[$key][$value][$substitution])
							output_lang_issue($key, $value, 'omitted the substitution '.$substitution);
						elseif ($subcount > @$langsubstitutions[$key][$value][$substitution])
							output_lang_issue($key, $value, 'has fewer of the substitution '.$substitution);
			}
					
		foreach ($defined as $key => $valuecount) {
			$showaserror=!(($key=='admin') || ($key=='options') || ($code=='en-GB'));
			
			if (@$langdefined[$key]) {
				if (count($langdefined[$key]) < (count($valuecount)/2)) { // only a few phrases defined
					output_lang_issue($key, null, 'few translations provided so will use US English defaults', $showaserror);

				} else
					foreach ($valuecount as $value => $count)
						if (!@$langdefined[$key][$value]) {
							output_lang_issue($key, $value, 'undefined so will use US English defaults', $showaserror);
							$langnewphrases[$key][$value]=$english[$key][$value];
						}
			} else
				output_lang_issue($key, null, 'no translations provided so will use US English defaults', $showaserror);
		}
		
		foreach ($langnewphrases as $prefix => $phrases) {
			echo '<H2>'.$language.' phrases to add to <code>qa-lang/'.$code.'/qa-lang-'.$prefix.'.php</code>:</H2>';
			
			echo 'Copy and paste this into the middle of <code>qa-lang/'.$code.'/qa-lang-'.$prefix.'.php</code> then translate the right-hand sides after the <code>=></code> symbol.';
			
			echo '<PRE>';
			
			foreach ($phrases as $key => $value)
				echo '<SPAN STYLE="font-size:25%;">'."\t\t</SPAN>'".$key."' => \"".strtr($value, array('\\' => '\\\\', '"' => '\"', '$' => '\$', "\n" => '\n', "\t" => '\t'))."\",\n";
				
			echo '</PRE>';
		}
	}
	
	function output_lang_issue($prefix, $key, $issue, $error=true)
	{
		echo '<font color="'.($error ? '#c00' : '#c99').'"><code>';

		echo 'qa-lang-<B>'.qa_html($prefix).'</B>.php:';

		if (strlen($key))
			echo "'<B>".qa_html($key)."</B>'";
		
		echo '</code></font> &nbsp; '.qa_html($issue).'<BR>';
	}

	function output_start_includes()
	{
		global $oneread;
		
		$oneread=false;
		
		echo '<P STYLE="font-size:80%; color:#999;">Reading: ';
	}
	
	function output_reading_include($file)
	{
		global $oneread;
		
		echo ($oneread ? ', ' : '').htmlspecialchars(basename($file));
		flush();
		
		$oneread=true;
	}
	
	function output_finish_includes()
	{
		echo '</P>';
	}
	
	echo '<H1>Finished scanning for problems!</H1>';

?>

	</BODY>
</HTML>