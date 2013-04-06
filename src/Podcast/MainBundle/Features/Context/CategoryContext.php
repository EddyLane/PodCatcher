<?php

namespace Podcast\MainBundle\Features\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;

/**
 * Description of CategoryContext
 *
 * @author eddy
 */
class CategoryContext extends RawMinkContext {

    private $parameters;

    public function __construct(array $parameters = array()) {
        $this->parameters = $parameters;
    }

    /**
     * @Given /^there are categories:$/
     */
    public function thereAreCategories(TableNode $table) {
        throw new PendingException();
    }

}

?>
