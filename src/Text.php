<?php

namespace hrustbb2\StrTemplateCompiler;

class Text {

    protected string $content = '';

    protected $container;

    protected $nextNode;

    protected $prevNode;

    public function setMod(int $mod): void
    {
        
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function setContainer($container): void
    {
        $this->container = $container;
    }

    public function setNextNode($node): void
    {
        $this->nextNode = $node;
    }

    public function setPrevNode($node): void
    {
        $this->prevNode = $node;
    }

    public function pushLexeme(Lexeme $lexeme): bool
    {
        $this->content = $lexeme->getContent();
        return true;
    }

    public function toString(): string
    {
        $result = $this->content;
        if($this->prevNode){
            $result = ' ' . $result;
        }
        $firstChar = mb_substr($result, 1, 1);
        if($this->prevNode && in_array($firstChar, [')', ','])){
            return $result;
        }
        return $result;
    }

}