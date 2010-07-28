<?php
/**
 * This Behavior gives a convenient way to work with ENUM fields
 * 
 * @example
 * In your model : 
 * $actsAs = array(
 * 	'Enum.Enum' => array(
 * 		'exemple_field' => array('value_1', 'value_2')
 * 	)
 * );
 * In your controller :
 * $this->set($this->Enum->enumValues());
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
	 * @param object $model Model using this behavior
	 * @param array $config Configuration settings for $model
	 */
	public function setup(&$model, $config = array()) { 
		$this->settings[$model->name] = $config;
		foreach($config as $field => $values){
			$model->validate[$field]['allowedValues'] = array(
		  		'rule' => array('inList', $values),
		  		'message' => sprintf(__('Please choose ont of the following values : %s', true), join(', ', $this->__translate($values)))
		  	);
		}
	}
	
	/**
	 * Returns an array of all enum values for the model
	 * @example $this->set($this->Enum->enumValues());
	 * @param object $model Model using this behavior
	 */
	public function enumValues(&$model){
		$return = array();
		if(isset($this->settings[$model->name])){
			foreach($this->settings[$model->name] as $field => $values){
				if(!empty($values)){
					$return[Inflector::pluralize(Inflector::underscore($field))] = array_combine($values, $this->__translate($values));
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
			$return[] = __($value, true);
		}
		return $return;
	}
	
}