<?php


namespace App\ViewOptions;


use Symfony\Component\OptionsResolver\OptionsResolver;

class HeaderOptions
{

    public $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function setSlidered($value = true):self
    {
        $this->options['slidered'] = $value;
        return $this;
    }

    public function setFullInner($value = true):self
    {
        if ($value) {
            $this->options['full_inner'] = true;
            $this->options['short_inner'] = false;
            $this->options['outer'] = false;
        }
        else {
            $this->setOuter();
        }
        return $this;
    }

    public function setShortInner($value = true):self
    {
        if ($value) {
            $this->options['full_inner'] = false;
            $this->options['short_inner'] = true;
            $this->options['outer'] = false;
        }
        else {
            $this->setOuter();
        }

        return $this;
    }

    public function setOuter($value = true):self
    {
        if (!$value) {
            $this->setFullInner(true);
            return $this;
        }
        $this->options['outer'] = true;
        $this->options['full_inner'] = false;
        $this->options['short_inner'] = false;

        return $this;

    }

    public function setOption($optionName, $optionValue):self
    {
        $this->options[$optionName] = $optionValue;
        return $this;
    }

    public function getOption($optionName)
    {
        return $this->options[$optionName];
    }

    public function getClassString():string
    {
        $classList = [];
        $map = [
            'slidered' => 'slidered',
            'full_inner' => 'full inner',
            'short_inner' => 'short inner',
            'outer' => 'outer'

        ];

        foreach (array_keys($map) as $key) {
            if ($this->getOption($key)) {
                $classList[] = $map[$key];
            }
        }

        return implode(' ', $classList);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'slidered' => false,
            'full_inner' => false,
            'short_inner' => false,
            'outer' => true,
            'banner' => false,
            'body_class' => false,
        ]);
    }
}