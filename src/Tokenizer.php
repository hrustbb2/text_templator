<?php

namespace hrustbb2\StrTemplateCompiler;

class Tokenizer {

    protected array $map = [
        '{%' => Lexeme::VARIABLE_OPEN,
        '{' => Lexeme::OPEN_BRACKET,
        '}' => Lexeme::CLOSE_BRACKET,
        '&' => Lexeme::NOT_COMMA_MODIFICATOR,
    ];

    protected string $strQueue = '';

    protected array $lexeme = [];

    public function tokenize(string $source): void
    {
        $chars = mb_str_split($source);
        for($i = 0; $i <= count($chars) - 1; $i++){
            if($lexeme = $this->buildLexeme($i, $source)){
                $i += mb_strlen($lexeme->getContent()) - 1;
                if($this->strQueue){
                    $l = new Lexeme(Lexeme::STR);
                    $l->setContent($this->strQueue);
                    $this->lexeme[] = $l;
                    $this->strQueue = '';
                }
                $this->lexeme[] = $lexeme;
                continue;
            }
            $this->strQueue .= $chars[$i];
        }
        if($this->strQueue){
            $l = new Lexeme(Lexeme::STR);
            $l->setContent($this->strQueue);
            $this->lexeme[] = $l;
            $this->strQueue = '';
        }
    }

    protected function buildLexeme(int $i, string $source): ?Lexeme
    {
        foreach($this->map as $lexeme=>$type){
            $subStr = mb_substr($source, $i, mb_strlen($lexeme));
            if($subStr == $lexeme){
                $result = new Lexeme($type);
                $result->setContent($subStr);
                return $result;
            }
        }
        return null;
    }

    /**
     * @return Lexeme[]
     */
    public function getLexeme(): array
    {
        return $this->lexeme;
    }

}