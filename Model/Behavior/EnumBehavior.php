<?php
/**
 * This Behavior gives a convenient way to work with ENUM fields
 *
 * @example
 * In your Model :
 * $actsAs = array(
 * 	'Enum.Enum' => array(
 * 		'exemple_field' => array('value_1', 'value_2')
 * 	)
 * );
 * In your controller :
 * $this->set($this->{$this->ModelName}->enumValues());
 *
 * @author Pierre Aboucaya - Asper <p@asper.fr>
 *
 */
class EnumBehavior extends ModelBehavior {

	/**
	 * Setup enum behavior with the specified configuration settings.
	 *
	 * @example $actsAs = array(
	 * 	'Enum.Enum' => array(
	 * 		'exemple_field' => array('value_1', 'value_2')
	 * 	)
	 * );
	 * @param object $Model Model using this behavior
	 * @param array $config Configuration settings for $Model
	 */
	public function setup(Model $Model, $config = array()) {
		$this->settings[$Model->name] = $config;
		$schema = $Model->schema();
		foreach($config as $field => $values){
			$baseRule = array(
				'rule' => array('inList', $values),
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
	 * Returns an array of all enum values for the Model
	 * @example $this->set($this->{$this->ModelName}->enumValues());
	 * @param object $Model Model using this behavior
	 */
	public function enumValues(Model $Model){
		$return = array();
		if(isset($this->settings[$Model->name])){
			foreach($this->settings[$Model->name] as $field => $values){
				if(!empty($values)){
					$return[Inflector::pluralize(Inflector::variable($field))] = array_combine($values, $this->__translate($values));
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
