<?php

namespace hrustbb2\StrTemplateCompiler;

class Template {

    protected Tokenizer $tokenizer;

    protected Block $main;

    public function __construct()
    {
        $this->tokenizer = new Tokenizer();
        $this->main = new Block();
    }

    public function compile(string $template): void
    {
        $this->tokenizer->tokenize($template);
        foreach($this->tokenizer->getLexeme() as $lexeme){
            $this->main->pushLexeme($lexeme);
        }
    }

    public function loadVars(array $vars): void
    {
        $this->main->loadVars($vars);
    }

    public function toString(): string
    {
        return $this->main->toString();
    }

}