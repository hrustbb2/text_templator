<?php

namespace hrustbb2\StrTemplateCompiler;

class Block {

    protected int $mod = 0;

    protected int $currentMod = 0;

    protected $container;

    protected $nextNode;

    protected $prevNode;

    protected array $nestedNodes = [];

    protected $currentNode = null;

    protected bool $isEmpty = false;

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
        return $this->isEmpty;
    }

    public function pushLexeme(Lexeme $lexeme): bool
    {
        if(!$this->currentNode){
            if($lexeme->getType() == Lexeme::NOT_COMMA_MODIFICATOR){
                $this->currentMod = Lexeme::NOT_COMMA_MODIFICATOR;
            }
            if($lexeme->getType() == Lexeme::OPEN_BRACKET){
                $this->currentNode = new self();
                $this->currentNode->setContainer($this);
                $this->currentNode->setMod($this->currentMod);
                $this->currentMod = 0;
                return false;
            }
            if($lexeme->getType() == Lexeme::VARIABLE_OPEN){
                $this->currentNode = new Variable();
                $this->currentNode->setContainer($this);
                $this->currentNode->setMod($this->currentMod);
                $this->currentMod = 0;
                return false;
            }
            if($lexeme->getType() == Lexeme::STR){
                $node = new Text();
                $node->setContainer($this);
                $node->pushLexeme($lexeme);
                $this->nestedNodes[] = $node;
                return false;
            }
        }else{
            if($this->currentNode->pushLexeme($lexeme)){
                $lastNode = $this->nestedNodes[count($this->nestedNodes) - 1] ?? null;
                if($lastNode){
                    $this->currentNode->setPrevNode($lastNode);
                    $lastNode->setNextNode($this->currentNode);   
                }
                $this->nestedNodes[] = $this->currentNode;
                $this->currentNode = null;
            }
            return false;
        }
        return !$this->currentNode && $lexeme->getType() == Lexeme::CLOSE_BRACKET;
    }

    public function loadVars(array $vars): void
    {
        foreach($this->nestedNodes as $node){
            if(get_class($node) == Variable::class || get_class($node) == self::class){
                $node->loadVars($vars);
            }
        }
    }

    public function toString(): string
    {
        $result = '';
        if($this->container){
            $this->isEmpty = true;
        }
        foreach($this->nestedNodes as $node){
            $result .= $node->toString();
            if(get_class($node) != Text::class && $this->isEmpty){
                $this->isEmpty = $node->isEmpty();
            }
        }
        if($this->isEmpty){
            return '';
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