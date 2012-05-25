<?php
/**
 * HTML
 *
 * Плагин обеспечивает визуальное редактирование текстографических страниц
 *
 * @version ${product.version}
 *
 * @copyright 2005, Михаил Красильников
 * @copyright 2007, Eresus Group, http://eresus.ru/
 * @copyright 2010, ООО "Два слона", http://dvaslona.ru/
 * @license http://www.gnu.org/licenses/gpl.txt  GPL License 3
 * @author Михаил Красильников <mk@dvaslona.ru>
 * @author Ghost <ghost@dvaslona.ru>
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
 *
 * $Id: html.php 60 2010-03-01 03:41:02Z ghost $
 */

/**
 * Класс плагина
 *
 * @package HTML
 */
class Html extends ContentPlugin
{
	/**
	 * Версия
	 *
	 * @var string
	 * @since 1.00
	 */
	public $version = '${product.version}';

	/**
	 * Требуемая версия CMS
	 *
	 * @var string
	 */
	public $kernel = '3.00b';

	/**
	 * Название
	 *
	 * @var string
	 * @since 1.00
	 */
	public $title = 'HTML';

	/**
	 * Описание
	 *
	 * @var string
	 * @since 1.00
	 */
	public $description = 'Плагин обеспечивает визуальное редактирование текстографических страниц';

	/**
	 * Обновление контента
	 *
	 * @param string $content  новый контент
	 *
	 * @return void;
	 */
	public function updateContent($content)
	{
		$sections = Eresus_CMS::getLegacyKernel()->sections;
		$item = $sections->get(Eresus_Kernel::app()->getPage()->id);
		$item['content'] = $content;
		$item['options']['disallowPOST'] = arg('disallowPOST', 'int');
		$sections->update($item);
	}
	//------------------------------------------------------------------------------

	/**
	 * Отрисовка административной части
	 *
	 * @return string  Контент
	 */
	public function adminRenderContent()
	{
		if (arg('action') == 'update')
		{
			$this->adminUpdate();
		}

		$item = Eresus_CMS::getLegacyKernel()->sections->get(Eresus_Kernel::app()->getPage()->id);
		$url = Eresus_Kernel::app()->getPage()->clientURL($item['id']);
		$form = array(
			'name' => 'contentEditor',
			'caption' => Eresus_Kernel::app()->getPage()->title,
			'width' => '100%',
			'fields' => array (
				array ('type' => 'hidden', 'name' => 'action', 'value' => 'update'),
				array ('type' => 'html', 'name' => 'content', 'height' => '400px',
					'value'=>$item['content']),
				array ('type' => 'text', 'value' => 'Адрес страницы: <a href="'.$url.'">'.$url.'</a>'),
				array ('type' => 'checkbox', 'name' => 'disallowPOST',
					'label' => 'Запретить передавать аргументы методом POST',
					'value' =>
						isset($item['options']['disallowPOST']) ? $item['options']['disallowPOST'] : false),
				),
			'buttons' => array('apply', 'reset'),
		);

		$result = Eresus_Kernel::app()->getPage()->renderForm($form, $item);
		return $result;
	}
	//------------------------------------------------------------------------------

	/**
	 * Возвращает контент раздела для КИ
	 *
	 * @return string  HTML
	 */
	public function clientRenderContent()
	{
		global $Eresus, $page;

		$extra_GET_arguments = $Eresus->request['url'] != $Eresus->request['path'];
		$is_ARG_request = count($Eresus->request['arg']);
		$POST_requests_disallowed =
			isset($page->options['disallowPOST']) && $page->options['disallowPOST'];

		if ($extra_GET_arguments || ($is_ARG_request && $POST_requests_disallowed))
		{
			$page->httpError(404);
		}

		$result = parent::clientRenderContent();

		return $result;
	}
	//------------------------------------------------------------------------------
}
