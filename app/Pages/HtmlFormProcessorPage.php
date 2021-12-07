<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;

class HtmlFormProcessorPage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/the_form_processor.php';
    }

    function getValues()
    {
        $this->waitUntilUrl();

        $divSubmittedValues = $this->getDivSubmittedValues();
        $divsValues = $divSubmittedValues->findElements(WebDriverBy::xpath('./div[@id and not(@id= \'_submitbutton\')]'));

        $values = array_map(
            fn ($value) => $this->getValue($value),
            $divsValues
        );

        return $values;
    }

    private function getDivSubmittedValues()
    {
        return $this->findElement('//div[contains(@class, \'form-results\')]');
    }

    private function getValue($divValues)
    {
        $values = $divValues->findElements(WebDriverBy::xpath('./ul/child::*'));
        return array($divValues->getAttribute('id') => $this->formatValues($values));
    }

    private function formatValues($values)
    {
        $valuesCount = count($values);

        if ($valuesCount > 1) {
            return array_map(
                fn ($value) => $value->getText(),
                $values
            );
        } else if ($valuesCount == 1) {
            return $values[0]->getText();
        } else {
            return null;
        }
    }
}
