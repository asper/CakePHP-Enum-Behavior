<?php
/**
 * This Behavior gives a convenient way to work with ENUM fields
 *
 * @example
 * In your Model :
 * $actsAs = array(
 *  'CakePHP-Enum-Behavior.Enum' => array(
 *      'exemple_field' => array(1 => 'value_1', 'key' => 'value_2')
 *  )
 * );
 * In your controller :
 * $this->set($this->{$this->ModelName}->enumValues());
 *
 * @author Created: Pierre Aboucaya - Asper <p@asper.fr>
 * @author Updated: Tomasz Mazur <cakephp@tomaszmazur.eu>
 *
 */
class EnumBehavior extends ModelBehavior {

	/**
	 * Setup enum behavior with the specified configuration settings.
	 *
	 * @example $actsAs = array(
	 *  'CakePHP-Enum-Behavior.Enum' => array(
	 *      'exemple_field' => array(1 => 'value_1', 'key' => 'value_2')
	 *  )
	 * );
	 * @param object $Model Model using this behavior
	 * @param array $config Configuration settings for $Model
	 */
	public function setup(Model $Model, $config = array()) {
		$this->settings[$Model->name] = $config;
		$schema = $Model->schema();
		foreach($config as $field => $values){
			$baseRule = array(
				/* All types to string conversion */
				'rule' => array('inList', array_map(function($v){ return (string)$v; }, array_keys($values)), false), 
				'message' => __('Please choose one of the following values : %s', join(', ', $this->__translate($values))),
				'allowEmpty' => in_array(null, $values) || in_array('', $values),
				'required' => false
			);
			if(
				isset($schema[$field])
				&& isset($schema[$field]['null']) 
				&& !$schema[$field]['null']
			){
				$Model->validate[$field]['allowedValuesCreate'] = array_merge(
					$baseRule,
					array(
						'required' => true,
						'on' => 'create'
					)
				);
				$Model->validate[$field]['allowedValuesUpdate'] = array_merge(
					$baseRule,
					array(
						'on' => 'update'
					)
				);
			}
			else {
				$Model->validate[$field]['allowedValues'] = $baseRule;
			}
		}
	}


	/**
	 * convert given $field $value (array value) to enum key (array key)
	 * @param object $Model Model using this behavior
	 * @param  string $field requested field
	 * @param  string $value enum value
	 * @return false/int        enum key, or false if not found 
	 */
	public function enumValueToKey(Model $Model, $field, $value) {
		$enums = $this->enumValues($Model);
		if(array_key_exists($field, $enums)) {
			$enum = $enums[$field];
			return array_search($value, $enum);
		}
		return false;
	}

	/**
	 * Convert given $field $key (array key) to value (array value)
	 * @param object $Model Model using this behavior
	 * @param  string $field requested enum field
	 * @param  int $key enum array key
	 * @return false/int        enum value, or false if not found
	 */
	public function enumKeyToValue(Model $Model, $field, $key) {
		$enums = $this->enumValues($Model);
		if(array_key_exists($field, $enums)) {
			$enum = $enums[$field];
			if(array_key_exists($key, $enum))
				return $enum[$key];
		}
		return false;
	}

	/**
	 * Returns an array of all enum values for the Model
	 * @example $this->set($this->{$this->ModelName}->enumValues());
	 * @param object $Model Model using this behavior
	 */
	public function enumValues(Model $Model){
		$return = array();
		if(isset($this->settings[$Model->name])){
			foreach($this->settings[$Model->name] as $field => $values){
				if(!empty($values)){
					foreach($values as $key => $value)
						$values[$key] =  __(Inflector::humanize($value));
					$return[Inflector::pluralize(Inflector::variable($field))] = $values;
				}
			}
		}
		return $return;
	}

	/**
	 * Translates the values
	 * @param array $values Values of the ENUM field
	 */
	private function __translate($values = array()){
		$return = array();
		foreach($values as $value){
			$return[] = __(Inflector::humanize($value));
		}
		return $return;
	}

}
