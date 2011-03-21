<?php
class CssHelper extends AppHelper {
	
	private $buffer = '';
	private $parser;
	
	public $helpers = array('Html');
	
	private $settings = array(
		'remove_bslash' => true,
		'compress_colors' => true,
		'compress_font-weight' => true,
		'lowercase_s' => false,
		'optimise_shorthands' => 1,
		'remove_last_;' => true,
		'case_properties' => 1,
		'sort_properties' => false,
		'sort_selectors' => false,
		'merge_selectors' => 2,
		'discard_invalid_properties' => false,
		'css_level' => 'CSS3.0',
	    'preserve_css' => false,
	    'timestamp' => false,
	);
	
	public function __construct() {
		parent::__construct();
		require_once(APP.'vendors/csstidy/class.csstidy.php');
		$this->parser = new csstidy();
		$this->configure();
	}
	
	private function configure() {
		foreach ($this->settings as $key => $val) {
			$this->parser->set_cfg($key, $val);
		}
	}
	
	public function buffer($css, $options=array()) {
		if (isset($options['inline']) && $options['inline'] == true) {
			$parser = new csstidy();
			foreach ($this->settings as $key => $val) {
				$parser->set_cfg($key, $val);
			}
			$parser->parse($css);
			return $this->Html->tag('style', $parser->print->plain());
		} else {
			$css = $this->tidy($css);			
		}
	}
	
	private function tidy($css) {
		$this->buffer .= $css;
	}
	
	public function writeBuffer() {
		$this->parser->parse($this->buffer);
		$this->reset();
		return $this->Html->tag('style', $this->parser->print->plain());
	}
	
	public function scripts_for_layout() {
		return $this->writeBuffer();
	}
	
	private function reset() {
		$this->buffer = '';
	}
}