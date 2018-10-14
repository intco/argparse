<?php

namespace saganov\argparse;

class Option extends Argument
{
    protected $required = false;
    protected $short = false;
    protected $long;

    public function __construct($name, array $options = array())
    {
        if (strpos($name, '-') === 0) // Flags in the name
        {
            $flags = preg_split('/\s/', $name);
            if(count($flags) == 1)
            {
                $options['long'] = $flags[0];
            }
            elseif(strlen($flags[0]) > strlen($flags[1]))
            {
                $options['long'] = $flags[0];
                $options['short'] = $flags[1];
            }
            else
            {
                $options['long'] = $flags[1];
                $options['short'] = $flags[0];
            }
            $name = ltrim($options['long'], '-');
        }

        parent::__construct($name, $options);
        if(!isset($this->long)) $this->long = "--{$name}";
        $this->metavar = strtoupper($this->metavar);
    }

    public function key()
    {
        if($this->short) return array($this->short, $this->long);
        else $this->long;
    }

    public function parse($args = NULL)
    {
        array_shift($args);
        return parent::parse($args);
    }

    public function usage($format = '%s')
    {
        $usage = ($this->short ?: $this->long) . str_repeat(" {$this->metavar} ", $this->nargs);
        if(!$this->required)
        {
            $usage = '['.$usage.']';
        }
        return sprintf($format, $usage);
    }

    public function help($format = "\t%s\n%s\n")
    {
        $help = $this->formatText($this->help, "\t\t", 75);
        $name = '';
        if($this->short)
        {
            $name .= $this->short . str_repeat(" {$this->metavar} ", $this->nargs) .', ';
        }
        $name .= $this->long . str_repeat(" {$this->metavar} ", $this->nargs);
        return sprintf($format, $name, $help);
    }
}