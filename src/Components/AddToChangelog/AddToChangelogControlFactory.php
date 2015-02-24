<?php

/**
 * This file is part of the DbChangelog package
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lovec\DbChangelog\Components\AddToChangelog;


interface AddToChangelogControlFactory
{

	/**
	 * @return AddToChangelogControl
	 */
	function create();

}
