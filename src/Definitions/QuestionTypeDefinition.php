<?php
namespace Pcomm\WPQuiz\Definitions;

class QuestionTypeDefinition extends \PComm\WPUtils\Taxonomy\DefaultDefinition {
    protected $slug = 'question-type';
    protected $single = 'Question Type';
    protected $plural = 'Question Types';
    protected $rest = true;
    protected $hierarchical = true;
    protected $postType = ['quiz'];

    
}

