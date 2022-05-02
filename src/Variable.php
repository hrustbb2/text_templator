<?php

namespace hrustbb2\StrTemplateCompiler;

class Variable {

    protected int $mod = 0;

    protected string $name;

    protected $value = '';

    protected $container;

    protected $nextNode;

    protected $prevNode;

    public function setMod(int $mod): void
    {
        $this->mod = $mod;
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

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function pushLexeme(Lexeme $lexeme): bool
    {
        if($lexeme->getType() == Lexeme::CLOSE_BRACKET){
            return true;
        }
        if($lexeme->getType() == Lexeme::STR){
            $this->name = $lexeme->getContent();
        }
        return false;
    }

    public function loadVars(array $vars): void
    {
        $this->value = $vars[$this->name] ?? '';
    }

    public function toString(): string
    {
        $result = $this->value;
        if(is_array($this->value)){
            $result = join(', ', $this->value);
        }
        if($this->prevNode && get_class($this->prevNode) == Text::class){
            $str = $this->prevNode->toString();
            if(mb_substr($str, -1) == '('){
                return $result;
            }
        }
        if($this->mod != Lexeme::NOT_COMMA_MODIFICATOR && $this->prevNode){
            $result = ', ' . $result;
        }
        if($this->mod == Lexeme::NOT_COMMA_MODIFICATOR && $this->prevNode){
            $result = ' ' . $result;
        }
        return $result;
    }

}