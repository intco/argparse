<?php
  /**
   * Subparsers of Command line argument parser
   *
   * @author Petr Saganov <saganoff@gmail.com>
   */

namespace saganov\argparse;

class SubParsers extends Parser implements IArgument
{
    protected $parsers = array();
    protected $parser;

    public function __toString()
    {
        return $this->name;
    }

    public function key()
    {
        return null;
    }

    public function addParser(Parser $parser)
    {
        $this->parsers[$parser->_name()] = $parser;
        return $this;
    }

    public function getParser($name)
    {
        return (isset($this->parsers[$name]) ? $this->parsers[$name] : null);
    }

    public function parse($args = null)
    {
        $remainder = array();
        if(empty($args))
        {
            $this->action();
            return $args;
        }

        $arg = array_shift($args);
        $this->parser = $this->getParser($arg);
        if(is_null($this->parser)) throw new UndeclaredSubparserException("Unknown subparser '{$arg}'");
        return $this->parser->parse($args);
    }

    public function value()
    {
        return ($this->parser ? $this->parser->value() : array());
    }

    public function usage($format = '%s')
    {
        return sprintf($format, "{{$this->_name}}");
    }

    public function help($format = "%s\n%s\n")
    {
        return parent::help($format)
            . array_reduce($this->parsers,
                           function($help, $parser) use($format) {
                               return $help .= $parser->help("\t".$format);});
    }
}