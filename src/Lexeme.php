<?php

namespace hrustbb2\StrTemplateCompiler;

class Lexeme {

    const OPEN_BRACKET = 10;

    const CLOSE_BRACKET = 20;

    const VARIABLE_OPEN = 30;

    const NOT_COMMA_MODIFICATOR = 40;

    const STR = 50;

    protected int $type;

    protected string $content = '';

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

}