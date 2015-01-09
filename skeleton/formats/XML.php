<?php
class XML {

	public function __construct() {}

	public static function out($data) {
		header('Content-type: text/xml');
		$xml = self::xml($data);
		echo $xml;
		return $xml;
	}

	private static function xml($data = null, $structure = null, $basenode = 'xml') {
		if ($data === null and ! func_num_args()) {
			$data = $this->_data;
		}
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1) {
			ini_set('zend.ze1_compatibility_mode', 0);
		}
		if ($structure === null) {
			$structure = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
		}
		// Force it to be something useful
		if (!is_array($data) AND !is_object($data)) {
			$data = (array) $data;
			// $data = json_decode(json_encode($data));
		}
		foreach ($data as $key => $value) {
			//change false/true to 0/1
			if(is_bool($value)) {
				$value = (int) $value;
			}
			// no numeric keys in our xml please!
			if (is_numeric($key)) {
				// make string key...
				$key = (Inflector_Helper::singular($basenode) != $basenode) ? Inflector_Helper::singular($basenode) : 'item';
			}
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z_\-0-9]/i', '', $key);
			if ($key === '_attributes' && (is_array($value) || is_object($value))) {
				$attributes = $value;
				if (is_object($attributes)) $attributes = get_object_vars($attributes);
				foreach ($attributes as $attributeName => $attributeValue) {
					$structure->addAttribute($attributeName, $attributeValue);
				}
			} else if (is_array($value) || is_object($value)) {
				// if there is another array found recursively call this function
				$node = $structure->addChild($key);
				// recursive call.
				self::xml($value, $node, $key);
			} else {
				// add single node.
				$value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, "UTF-8");
				$structure->addChild($key, $value);
			}
		}
		return $structure->asXML();
	}
}