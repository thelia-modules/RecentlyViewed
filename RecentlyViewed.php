<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace RecentlyViewed;

use Thelia\Module\BaseModule;

/**
 * Class RecentlyViewed
 *
 * @package RecentlyViewed
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class RecentlyViewed extends BaseModule
{
    /** maximun product save in recently viewed SESSION */
    const MAX = 8;
}
