<?php
/**
 * Created by PhpStorm.
 * User: kirby
 * Date: 18.01.2015
 * Time: 17:49
 */

namespace AndKirby\MageDbHelper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CommandAbstract
 *
 * @package PreCommit\Composer\Command
 */
abstract class CommandAbstract extends Command
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this->configureCommand();
        $this->configureInput();
    }

    /**
     * Init input
     *
     * Set name, description, help
     *
     * @return $this
     */
    abstract protected function configureInput();

    /**
     * Init command
     *
     * Set name, description, help
     *
     * @return $this
     */
    abstract protected function configureCommand();

    /**
     * Get dialog helper
     *
     * @return DialogHelper
     */
    protected function getDialog()
    {
        return $this->getHelperSet()->get('dialog');
    }

    /**
     * Is output very verbose
     *
     * @param OutputInterface $output
     * @return bool
     */
    protected function isVeryVerbose(OutputInterface $output)
    {
        return $output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE;
    }

    /**
     * Is output verbose
     *
     * @param OutputInterface $output
     * @return bool
     */
    protected function isVerbose(OutputInterface $output)
    {
        return $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;
    }
}
