<?php
namespace saganov\argparse;

interface IArgument extends IParser
{
    public function __toString();
}