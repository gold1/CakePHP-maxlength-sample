<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('FormHelper', 'View/Helper');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class Form2Helper extends FormHelper {

	/**
	 * 親クラスの関数の上書き
	 * @param string $fieldName フィールド名
	 * @param array $options オプション
	 */
	public function input($fieldName, $options = array()) {
		list($model_name, $column_name) = $this->getModelAndColumnByFieldName($fieldName);
		// input, textarea の最大文字数があれば設定する。
		if ($options['type'] == 'text' ||
			$options['type'] == 'textarea') {
			if (class_exists($model_name) &&
				$model_name::isMaxLengthJp($column_name)) {
				$options = array_merge(
					array('maxlength' => $model_name::getMaxLengthJp($column_name)),
					$options
				);
			}
		}
		return parent::input($fieldName, $options);
	}

	/**
	 * フィールド名からモデル名とカラム名を取得する。
	 * @param string $field_name フィールド名
	 * @return array (モデル名, カラム名)の配列形式
	 */
	protected function getModelAndColumnByFieldName($field_name)
	{
		$explode_field = explode('.', $field_name);
		if (count($explode_field) == 1 ||
			preg_match("/^[0-9]$/", $explode_field[0], $m)) {
			// field or
			// 0.field
			$model_name = $this->defaultModel;
		} else {
			// Model.field or
			// Model.0.field
			$model_name = $explode_field[0];
		}
		$column = array_pop($explode_field);
		return array($model_name, $column);
	}
}
