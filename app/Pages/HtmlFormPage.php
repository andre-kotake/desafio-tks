<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;

class HtmlFormPage extends BasePage
{
    private $formElement;

    private function getForm()
    {
        if ($this->formElement == null) {
            $this->formElement = $this->findElement('//form[@id="HTMLFormElements"]');
        }

        return $this->formElement;
    }

    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/basic-html-form-test.html';
    }

    function fillFormAndSubmit()
    {
        $this->sendKeysToElement($this->getInputByName('username'), 'kotake');
        $this->sendKeysToElement($this->getInputByName('password'), '123');
        $this->sendKeysToElement($this->getTextAreaByName('comments'), 'comentario');
        $this->uploadFile($this->getInputByName('filename'), storage_path('app/files') . '/formFileInput.txt');
        $this->selectCheckboxOptions($this->getCheckboxes(),  ['cb1', 'cb2']);
        $this->selectRadioButtonValue($this->getRadios(), 'rd1');
        $this->setSelectOptions($this->getSelectByName('multipleselect[]'), ['ms1', 'ms2', 'ms3']);
        $this->setSelectOptions($this->getSelectByName('dropdown'), ['dd1']);

        $this->findChildElement($this->getForm(), './/input[@value="submit"]')->click();

        return new HtmlFormProcessorPage();
    }

    private function getInputByName($name)
    {
        return $this->findChildElement($this->getForm(), './/input[@name="' . $name . '"]');
    }

    private function getTextAreaByName($name)
    {
        return $this->findChildElement($this->getForm(), './/textarea[@name="' . $name . '"]');
    }

    private function getCheckboxes()
    {
        return $this->findChildElement($this->getForm(), './/input[@name="checkboxes[]"]');
    }

    private function getRadios()
    {
        return $this->findChildElement($this->getForm(), './/input[@name="radioval"]');
    }

    private function getSelectByName($name)
    {
        return $this->findChildElement($this->getForm(), './/select[@name="' . $name . '"]');
    }
}
