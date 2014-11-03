<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	// カラムの最大文字数
	protected static $maxLengthJp = array();

	/**
	 * バリデートをおこなう
	 * 親クラスの関数の上書き
	 * @param array $options オプション
	 * @return boolean true : 問題なし
	 */
	public function validates($options = array()) {
		$this->modifyValidateRure();
		return parent::validates($options);
	}

	/**
	 * バリデートルールを変更する
	 */
	public function modifyValidateRure() {
		foreach ($this->validate as $column => $validates) {
			foreach ($validates as $number => $settings) {
				if (is_string($settings['rule'])) {
					$rule = $settings['rule'];
				} elseif (is_array($settings['rule'])) {
					$rule = $settings['rule'][0];
				}
				if ($rule == 'ruleMaxLengthJp') {
					// ルール変更をおこなって文字数を引数に設定する
					$length = static::getMaxLengthJp($column);
					$this->validate[$column][$number]['rule'] = array($rule, $length);
				}
			}
		}
	}

	/** 
	 * 日本語の最大文字数があるか判定する
	 * @param string $column カラム名
	 * @return boolean true : 存在する
	 */
	public static function isMaxLengthJp($column) {
		if (isset(static::$maxLengthJp) &&
			isset(static::$maxLengthJp[$column])) {
			return true;
		} else {
			return false;
		}
	}

	/** 
	 * 日本語の最大文字数を取得する
	 * @param string $column カラム名
	 * @return int 文字数
	 */
	public static function getMaxLengthJp($column) {
		if (!isset(static::$maxLengthJp[$column])) {
			throw new Exception("");
		}
		return static::$maxLengthJp[$column];
	}

	/** 
	 * 日本語の文字数チェック
	 * @param array $data 該当するデータ
	 * @param string $length 長さ
	 * @return boolean
	 */
	public function ruleMaxLengthJp($data, $length) {
		$encoding = Configure::read('App.encoding');
		$value = array_shift($data);
		return (mb_strlen($value, $encoding) <= $length);
	}
}
