<?php
namespace AndKirby\MageDbHelper;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 *
 * @package AndKirby\MageDbHelper
 */
class Application extends BaseApplication
{
    /**
     * Version
     */
    const VERSION = 'v1.0.1';

    /**
     * Logo
     *
     * @var string
     */
    protected $_logo = <<<LOGO
 __ __   __   __   ___     __| |__    |__   ___  |    __   ___   __
|  )  ) (__( (__| (__/_   (__| |__)   |  ) (__/_ |_, |__) (__/_ |  '
              __/                                        |

LOGO;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct(
            'Mage Db Helper', self::VERSION
        );
    }

    /**
     * Get help
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->_logo . parent::getHelp();
    }
}
