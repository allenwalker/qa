<?php

	class qa_html_theme extends qa_html_theme_base
	{
		function body_content()
		{
			$this->body_prefix();
			
			$this->output('<DIV CLASS="qa-body-wrapper">', '');
			
			$this->header();
			$this->output('<DIV CLASS="qa-content-wrapper">', '');	// modxclub
			$this->sidepanel();
			$this->main();
			$this->output('</DIV> <!-- END content-wrapper -->');	// modxclub
			$this->footer();
			
			$this->output('</DIV> <!-- END body-wrapper -->');
			
			$this->body_suffix();
		}
		function attribution()
		{
			// Please see the license at the top of this file before changing this link. Thank you.
				
			qa_html_theme_base::attribution();

			// modxclub [start] Please erase. Use this theme according to license of Question2Answer.
			$this->output(
				'<DIV CLASS="qa-designedby">',
				'Designed by <A HREF="http://www.quickart.jp">QuickArt</A>',
				'</DIV>'
			);
			// modxclub [end]
		}
	}

/*
	Omit PHP closing tag to help avoid accidental output
*/