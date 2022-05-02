<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use hrustbb2\StrTemplateCompiler\Template;

class TreeTest extends TestCase {

    public function test1()
    {
        $template = new Template();
        $tmp = "Список телевизоров&{с диагональю&{от&{%from}'}&{до&{%to}'}}";
        $vars = [
            'from' => 15,
            'to' => 20,
        ];
        $template->compile($tmp);
        $template->loadVars($vars);
        $actual = $template->toString();
        $expected = "Список телевизоров с диагональю от 15' до 20'";
        $this->assertEquals($expected, $actual);
    }

    public function test2()
    {
        $template = new Template();
        $tmp = "Список телевизоров&{с диагональю&{от&{%from}'}&{до&{%to}'}}";
        $vars = [
            'to' => 20,
        ];
        $template->compile($tmp);
        $template->loadVars($vars);
        $actual = $template->toString();
        $expected = "Список телевизоров с диагональю до 20'";
        $this->assertEquals($expected, $actual);
    }

    public function test3()
    {
        $template = new Template();
        $tmp = "Список телевизоров&{с диагональю&{от&{%from}'}&{до&{%to}'}}";
        $vars = [
            
        ];
        $template->compile($tmp);
        $template->loadVars($vars);
        $actual = $template->toString();
        $expected = "Список телевизоров";
        $this->assertEquals($expected, $actual);
    }

}