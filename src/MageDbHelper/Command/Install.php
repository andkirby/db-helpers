<?php
namespace AndKirby\MageDbHelper\Command;

use AndKirby\MageDbHelper\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * CommitHooks files installer
 *
 * @package AndKirby\MageDbHelper\Command
 */
class Install extends CommandAbstract
{
    /**
     * MySQL import command
     *
     * @var string
     */
    protected $mySqlImportCommand;

    /**
     * User directory path
     *
     * @var string
     */
    protected $userDir;

    /**
     * Source directory path
     *
     * @var string
     */
    protected $srcDir;

    /**
     * Construct
     *
     * @param string $userDir
     * @param string $srcRoot
     */
    public function __construct($userDir, $srcRoot)
    {
        $this->userDir = $userDir;
        $this->srcDir  = $srcRoot;
        parent::__construct();
    }

    /**
     * Init default helpers
     *
     * @return $this
     */
    protected function configureCommand()
    {
        $this->setName('install');
        $this->setHelp(
            'This command can install available DB helpers into your MySQL database.'
        );
        $this->setDescription(
            'This command can install available DB helpers into your MySQL database.'
        );
        return $this;
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->testMysql($input, $output);
            $this->importScripts($input, $output);
        } catch (Exception $e) {
            if ($this->isVeryVerbose($output)) {
                throw $e;
            } else {
                $output->writeln($e->getMessage());
                return 1;
            }
        }

        $database = $input->getOption('database');
        $output->writeln(
            "Script files have been imported into MySQL database '$database'."
        );
        return 0;
    }

    /**
     * Init input definitions
     *
     * @return $this
     */
    protected function configureInput()
    {
        $this->addOption(
            'user', '-u', InputOption::VALUE_REQUIRED,
            'MySQL username to connect.'
        );
        $this->addOption(
            'password', '-p', InputOption::VALUE_OPTIONAL,
            'MySQL username to connect.'
        );
        $this->addOption(
            'no-password', '-o', InputOption::VALUE_NONE,
            'Skip using MySQL password.'
        );
        $this->addOption(
            'host', '-t', InputOption::VALUE_OPTIONAL,
            'MySQL DB host to connect.'
        );
        $this->addOption(
            'database', '-d', InputOption::VALUE_REQUIRED,
            'MySQL DB host to connect.'
        );
        return $this;
    }

    /**
     * Test connection
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return $this
     * @throws Exception
     */
    protected function testMysql(InputInterface $input, OutputInterface $output)
    {
        $scriptName = 'Stub';
        //test connection
        try {
            $this->importSqlFile($scriptName, $input, $output);
        } catch (MySqlException $e) {
            if ($this->isVerbose($output)) {
                throw $e;
            }
            throw new Exception('Could not connect to MySQL properly. Please check your parameters.');
        }
        return $this;
    }

    /**
     * Import SQL scripts
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return $this
     */
    protected function importScripts(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->procedures() as $name) {
            $this->importSqlFile($name, $input, $output);
        }
        return $this;
    }

    /**
     * Get procedures list
     *
     * @return array
     */
    protected function procedures()
    {
        return [
            'DeleteAllTables',
            'ResetBaseUrl',
            'ResetAdmin',
        ];
    }

    /**
     * Get MySQL import command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return string
     */
    protected function getMySqlImportCommand(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->mySqlImportCommand) {
            $command = 'mysql';

            $username = $input->getOption('user');
            $command .= " -u$username ";

            $password = $this->askMySqlPassword($input, $output);
            if ($password) {
                $command .= " -p$password ";
            }

            $host = $input->getOption('host');
            if ($host) {
                $command .= " -h$host ";
            }

            $database = $input->getOption('database');
            $command .= " $database < ";
            $this->mySqlImportCommand = $command;
        }
        return $this->mySqlImportCommand;
    }

    /**
     * Ask MySQL password
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return bool|mixed|string
     */
    protected function askMySqlPassword(InputInterface $input, OutputInterface $output)
    {
        $password = false;
        if (!$input->getOption('no-password')) {
            $password = $input->getOption('password');
            if (!$password) {
                $question = new Question('MySQL password: ');
                $question->setHidden(true);
                $password = $this->getDialog()->ask(
                    $input, $output, $question
                );
            }
        }
        return $password;
    }

    /**
     * Get SQL script file
     *
     * @param string $name
     * @return string
     * @throws Exception
     */
    protected function getScriptFile($name)
    {
        $file = rtrim($this->srcDir, '\\/') . '/helper/' . $name . '.sql';
        if (!is_file($file)) {
            throw new Exception("File '$file' not found.");
        }
        return $file;
    }

    /**
     * Check error in result of query
     *
     * @param OutputInterface $output
     * @param string          $result
     * @param string          $mySqlCommand
     * @param string          $file
     * @return $this
     * @throws Exception
     */
    protected function checkErrorInQueryResult(OutputInterface $output, $result, $mySqlCommand, $file)
    {
        if (false !== stripos($result, 'error')) {
            if ($this->isVeryVerbose($output)) {
                throw new MySqlException('Command: ' . "$mySqlCommand $file" . PHP_EOL . $result);
            } elseif ($this->isVerbose($output)) {
                throw new MySqlException($result);
            }
            throw new Exception("An error occurred on importing script '$file'.");
        }
        return $this;
    }

    /**
     * Import SQL file
     *
     * @param string                                            $scriptName
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return $this
     * @throws Exception
     */
    protected function importSqlFile($scriptName, InputInterface $input, OutputInterface $output)
    {
        $mySqlCommand = $this->getMySqlImportCommand($input, $output);
        $fileStub     = $this->getScriptFile($scriptName);
        $result       = `$mySqlCommand $fileStub 2>&1`;
        if ($this->isVeryVerbose($output)) {
            $output->write($result);
        } elseif ($this->isVerbose($output)) {
            $output->write("Script '$scriptName' has been imported.");
        }
        $this->checkErrorInQueryResult($output, $result, $mySqlCommand, $fileStub);
        return $this;
    }
}
