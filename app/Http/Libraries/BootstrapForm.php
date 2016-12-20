<?php
namespace App\Libraries;

use App\Libraries\HtmlBuilder;
use Collective\Html\FormBuilder as IlluminateFormBuilder;
use Watson\BootstrapForm\BootstrapForm as OriginalBootstrapForm;

# Override stupid stuff from bootstrap form builder
class BootstrapForm extends OriginalBootstrapForm
{
    # Support passing 'div' option to specify classes for div

    # Functions pretty much the same except passing options to form-group
    protected function getFormGroupWithLabel($name, $value, $element,$divoptions=[])
    {
        if(is_string($divoptions)) { $divoptions = ['class'=>$divoptions]; } # Convert string to class
        $options = $this->getFormGroupOptions($name,$divoptions); 

        $labelString = ($value === false) ? '' : $this->label($name, $value);

        return '<div' . $this->html->attributes($options) . '>' . $labelString . $element . '</div>';
    }

    protected function getFormGroupOptions($name = null, array $options = [])
    {
        $class = 'form-group';

        if ($name) {
            $class .= ' ' . $this->getFieldErrorClass($name);
        }

        if(!empty($options['class']))
        {
            $class .= " ".$options['class']; # Append, not overwrite
            unset($options['class']);
        }

        return array_merge(['class' => $class], $options);
    }

    public function getFormGroup($name = null, $element, $divoptions = [])
    {
        if(is_string($divoptions)) { $divoptions = ['class'=>$divoptions]; } # Convert string to class
        $options = $this->getFormGroupOptions($name,$divoptions); 

        return '<div' . $this->html->attributes($options) . '>' . $element . '</div>';
    }

    #######################
    # XXX TODO FILE
    public function file($name, $label = null, array $options = [])
    {
        $divoptions = isset($options['div']) ? $options['div'] : []; unset($options['div']);
        $label = $this->getLabelTitle($label, $name);

        $options = array_merge(['class' => 'filestyle', 'data-buttonBefore' => 'true'], $options);

        $options = $this->getFieldOptions($options, $name);
        $inputElement = $this->form->input('file', $name, null, $options);
        if($divoptions === false) { return $inputElement; }

        $wrapperOptions = $this->isHorizontal() ? ['class' => $this->getRightColumnClass()] : [];
        $wrapperElement = '<div' . $this->html->attributes($wrapperOptions) . '>' . $inputElement . $this->getFieldError($name) . $this->getHelpText($name, $options) . '</div>';

        return $this->getFormGroupWithLabel($name, $label, $wrapperElement, $divoptions); 
    }

    public function input($type, $name, $label = null, $value = null, array $options = [])
    {
        $divoptions = isset($options['div']) ? $options['div'] : []; unset($options['div']);
        $label = $this->getLabelTitle($label, $name);

        $options = $this->getFieldOptions($options, $name);
        $inputElement = $type === 'password' ? $this->form->password($name, $options) : $this->form->{$type}($name, $value, $options);
        if($divoptions === false) { return $inputElement; }

        $wrapperOptions = $this->isHorizontal() ? ['class' => $this->getRightColumnClass()] : [];
        $wrapperElement = '<div' . $this->html->attributes($wrapperOptions) . '>' . $inputElement . $this->getFieldError($name) . $this->getHelpText($name, $options) . '</div>';

        return $this->getFormGroupWithLabel($name, $label, $wrapperElement, $divoptions); 
    }

    public function select($name, $label = null, $list = [], $selected = null, array $options = [])
    {
        $divoptions = isset($options['div']) ? $options['div'] : []; unset($options['div']);
        $label = $this->getLabelTitle($label, $name);

        $options = $this->getFieldOptions($options, $name);
        $inputElement = $this->form->select($name, $list, $selected, $options);
        if($divoptions === false) { return $inputElement; }

        $wrapperOptions = $this->isHorizontal() ? ['class' => $this->getRightColumnClass()] : [];
        $wrapperElement = '<div' . $this->html->attributes($wrapperOptions) . '>' . $inputElement . $this->getFieldError($name) . $this->getHelpText($name, $options) . '</div>';

        return $this->getFormGroupWithLabel($name, $label, $wrapperElement, $divoptions);
    }

    public function checkbox($name, $label = null, $value = 1, $checked = null, array $options = [])
    {
        $divoptions = isset($options['div']) ? $options['div'] : []; unset($options['div']);
        $inputElement = $this->checkboxElement($name, $label, $value, $checked, false, $options);
        if($divoptions === false) { return $inputElement; }

        $wrapperOptions = $this->isHorizontal() ? ['class' => implode(' ', [$this->getLeftColumnOffsetClass(), $this->getRightColumnClass()])] : [];
        $wrapperElement = '<div' . $this->html->attributes($wrapperOptions) . '>' . $inputElement . '</div>';

        return $this->getFormGroup(null, $wrapperElement, $divoptions);
    }

    public function radio($name, $label = null, $value = null, $checked = null, array $options = [])
    {
        $divoptions = isset($options['div']) ? $options['div'] : []; unset($options['div']);
        $inputElement = $this->radioElement($name, $label, $value, $checked, false, $options);
        if($divoptions === false) { return $inputElement; }

        $wrapperOptions = $this->isHorizontal() ? ['class' => implode(' ', [$this->getLeftColumnOffsetClass(), $this->getRightColumnClass()])] : [];
        $wrapperElement = '<div' . $this->html->attributes($wrapperOptions) . '>' . $inputElement . '</div>';

        return $this->getFormGroup(null, $wrapperElement, $divoptions);
    }

    public function submit($value = null, array $options = [])
    {
        $divoptions = isset($options['div']) ? $options['div'] : []; unset($options['div']);
        $options = array_merge(['class' => 'btn btn-primary'], $options);

        $inputElement = $this->form->submit($value, $options);
        if($divoptions === false) { return $inputElement; }

        $wrapperOptions = $this->isHorizontal() ? ['class' => implode(' ', [$this->getLeftColumnOffsetClass(), $this->getRightColumnClass()])] : [];
        $wrapperElement = '<div' . $this->html->attributes($wrapperOptions) . '>'. $inputElement . '</div>';

        return $this->getFormGroup(null, $wrapperElement, $divoptions);
    }

    function getLabelTitle($label,$name)
    {
        if($label === false) { return false; }
        return parent::getLabelTitle($label,$name);
    }


    # ANY MORE???? TODO







}
