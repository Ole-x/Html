<?php
/**
 * Модульные тесты
 *
 * @version ${product.version}
 *
 * @copyright 2012, ООО «Два слона», http://dvaslona.ru/
 * @license http://www.gnu.org/licenses/gpl.txt	GPL License 3
 * @author Михаил Красильников <mk@dvaslona.ru>
 *
 * Данная программа является свободным программным обеспечением. Вы
 * вправе распространять ее и/или модифицировать в соответствии с
 * условиями версии 3 либо (по вашему выбору) с условиями более поздней
 * версии Стандартной Общественной Лицензии GNU, опубликованной Free
 * Software Foundation.
 *
 * Мы распространяем эту программу в надежде на то, что она будет вам
 * полезной, однако НЕ ПРЕДОСТАВЛЯЕМ НА НЕЕ НИКАКИХ ГАРАНТИЙ, в том
 * числе ГАРАНТИИ ТОВАРНОГО СОСТОЯНИЯ ПРИ ПРОДАЖЕ и ПРИГОДНОСТИ ДЛЯ
 * ИСПОЛЬЗОВАНИЯ В КОНКРЕТНЫХ ЦЕЛЯХ. Для получения более подробной
 * информации ознакомьтесь со Стандартной Общественной Лицензией GNU.
 *
 * Вы должны были получить копию Стандартной Общественной Лицензии
 * GNU с этой программой. Если Вы ее не получили, смотрите документ на
 * <http://www.gnu.org/licenses/>
 *
 * @package HTML
 * @subpackage Tests
 *
 * $Id: bootstrap.php 2173 2012-05-18 14:45:27Z mk $
 */

define('TESTS_SRC_DIR', realpath(__DIR__ . '/../../src'));

/**
 * Универсальная заглушка
 *
 * @package HTML
 * @subpackage Tests
 */
class UniversalStub implements ArrayAccess
{
	public function __get($a)
	{
		return $this;
	}
	//-----------------------------------------------------------------------------

	public function __call($a, $b)
	{
		return $this;
	}
	//-----------------------------------------------------------------------------

	public function offsetExists($offset)
	{
		return true;
	}
	//-----------------------------------------------------------------------------

	public function offsetGet($offset)
	{
		return $this;
	}
	//-----------------------------------------------------------------------------

	public function offsetSet($offset, $value)
	{
		;
	}
	//-----------------------------------------------------------------------------

	public function offsetUnset($offset)
	{
		;
	}
	//-----------------------------------------------------------------------------

	public function __toString()
	{
		return '';
	}
	//-----------------------------------------------------------------------------
}



/**
 * Фасад к моку для эмуляции статичных методов
 *
 * @package HTML
 * @subpackage Tests
 */
class MockFacade
{
	/**
	 * Мок
	 *
	 * @var array
	 */
	private static $mocks = array();

	/**
	 * Возвращает имя класса
	 *
	 * @return string
	 */
	public static function getName()
	{
		return __CLASS__;
	}
	//-----------------------------------------------------------------------------

	/**
	 * Устанавливает мок
	 *
	 * @param object $mock
	 *
	 * @return void
	 */
	public static function setMock($mock)
	{
		$name = static::getName();
		self::$mocks[$name] = $mock;
	}
	//-----------------------------------------------------------------------------

	/**
	 * Вызывает метод мока
	 *
	 * @param string $method
	 * @param array  $args
	 *
	 * @return mixed
	 */
	public static function __callstatic($method, $args)
	{
		$name = static::getName();
		if (isset(self::$mocks[$name]) && method_exists(self::$mocks[$name], $method))
		{
			return call_user_func_array(array(self::$mocks[$name], $method), $args);
		}

		return new UniversalStub();
	}
	//-----------------------------------------------------------------------------
}


/**
 * Заглушка для класса Plugin
 *
 * @package HTML
 * @subpackage Tests
 */
class Plugin extends UniversalStub {}

/**
 * Заглушка для класса ContentPlugin
 *
 * @package HTML
 * @subpackage Tests
 */
class ContentPlugin extends Plugin {}

class Eresus_CMS extends MockFacade
{
	public static function getName()
	{
		return __CLASS__;
	}
}
class Eresus_Kernel extends MockFacade
{
	public static function getName()
	{
		return __CLASS__;
	}
}