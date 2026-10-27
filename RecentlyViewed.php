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

use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Module\BaseModule;
use TheliaSmarty\Template\AbstractSmartyPlugin;

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

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([
                __DIR__.'/I18n',
                __DIR__.'/I18n/*',
                __DIR__.'/I18n/**/*',
                __DIR__.'/Smarty',
            ])
            ->autowire(true)
            ->autoconfigure(true);

        // The Smarty plugin is an optional integration: it needs TheliaSmarty, which Thelia 3 no longer ships by default.
        if (class_exists(AbstractSmartyPlugin::class)) {
            $servicesConfigurator->load(self::getModuleCode().'\\Smarty\\', __DIR__.'/Smarty')
                ->autowire(true)
                ->autoconfigure(true);
        }
    }

}
