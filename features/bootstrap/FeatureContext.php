<?php

require(__DIR__.'/../../vendor/autoload.php');

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Inflador\Inflador;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use org\bovigo\vfs\vfsStream;

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    private $sourceDir;
    private $destinationDir;

    private $inflador;
    private $output;
    private $statusCode;

    /**
     * @BeforeScenario
     */
    public function generateSourceDir()
    {
        vfsStream::setup('root');

        $this->sourceDir = vfsStream::url('root').'/source'.uniqid();
        mkdir($this->sourceDir);

        $this->destinationDir = $this->sourceDir.'/'.Inflador::DEFAULT_DESTINATION_DIR;
    }

    /**
     * @Given /^I have the inflador config file:$/
     */
    public function iHaveTheInfladorConfigFile(PyStringNode $string)
    {
        file_put_contents($this->getConfigFile(), $string->getRaw());
    }

    private function getConfigFile()
    {
        return $this->sourceDir.'/'.Inflador::CONFIG_FILE_NAME;
    }

    /**
     * @When /^I run the inflador command "([^"]*)"$/
     */
    public function iRunTheInfladorCommand($command)
    {
        $this->inflador = $this->createInflador();

        $input = $this->createInfladorInput($command);
        $output = $this->createInfladorOutput();

        $this->statusCode = $this->inflador->run($input, $output);
        $this->output = $this->getInfladorOutputContents($output);
    }

    private function createInflador()
    {
        $inflador = new Inflador();
        $inflador->setAutoExit(false);

        return $inflador;
    }

    private function createInfladorInput($command)
    {
        $commandWithSourceDir = $this->addSourceDirToCommand($command);

        return new StringInput($commandWithSourceDir);
    }

    private function addSourceDirToCommand($command)
    {
        return sprintf('%s --%s=%s', $command, Inflador::SOURCE_DIR_OPTION, $this->sourceDir);
    }

    private function createInfladorOutput()
    {
        $stream = fopen('php://memory', 'r+');

        return new StreamOutput($stream);
    }

    private function getInfladorOutputContents(OutputInterface $output)
    {
        $stream = $output->getStream();

        $contents = stream_get_contents($stream, -1, 0);
        fclose($stream);

        return $contents;
    }

    /**
     * @Given /^I have the source file "([^"]*)" that contains "([^"]*)"$/
     */
    public function iHaveTheSourceFileThatContains($file, $content)
    {
        file_put_contents($this->sourceDir.'/'.$file, $content);
    }

    /**
     * @Then /^The command should be successful$/
     */
    public function theCommandShouldBeSuccessful()
    {
        if ($this->statusCode !== 0) {
            throw new \Exception(sprintf('Code: %s. Output: %s', $this->statusCode, $this->output));
        }
    }

    /**
     * @Then /^The command should fail$/
     */
    public function theCommandShouldFail()
    {
        if ($this->statusCode === 0) {
            throw new \Exception(sprintf('Code: %s. Output: %s', $this->statusCode, $this->output));
        }
    }

    /**
     * @Then /^The output should match "(.*)"$/
     */
    public function theOutputShouldMatch($pattern)
    {
        if (!preg_match($pattern, $this->output)) {
            throw new \Exception(sprintf('Output: %s', $this->output));
        }
    }

    /**
     * @Then /^I should see the destination file "([^"]*)" that contains "([^"]*)"$/
     */
    public function iShouldSeeTheDestinationFileThatContains($file, $content)
    {
        $destinationFile = $this->destinationFile($file);

        if (!file_exists($destinationFile)) {
            throw new \Exception(sprintf('The destination file "%s" does not exist.', $file));
        }

        if (file_get_contents($destinationFile) !== $content) {
            throw new \Exception(sprintf('The content of the destination file is not "%s".', $content));
        }
    }

    /**
     * @Then /^I should not see the destination file "([^"]*)"$/
     */
    public function iShouldNotSeeTheDestinationFile($file)
    {
        $destinationFile = $this->destinationFile($file);

        if (file_exists($destinationFile)) {
            throw new \Exception(sprintf('The destination file "%s" exists.', $file));
        }
    }

    private function destinationFile($file)
    {
        return $this->pathFile($this->destinationDir, $file);
    }

    private function pathFile($dir, $file)
    {
        return sprintf('%s/%s', $this->destinationDir, $file);
    }
}
