<?php
/*
 * Plugin Name: PComm WP Quiz
 * Plugin URI: http://www.partnercomm.net
 * Description: Quiz Post Type Plugin
 * Version: 0.0.1
 * Author: PartnerComm
 * Author URI: http://www.partnercomm.net
*/

namespace PComm\WPQuiz;

class Quiz
{
    /**
     * @var \PComm\WPUtils\Post\Handler
     */
    protected $postHandler;

    /**
     * @var \PComm\WPUtils\Taxonomy\Handler
     */
    protected $taxonomyHandler;

    protected $posts = [
        '\PComm\WPQuiz\Definitions\QuizDefinition'
    ];

    protected $taxonomies = [
    
    ];

    public function __construct(\PComm\WPUtils\Post\Handler $postHandler,
                                \PComm\WPUtils\Taxonomy\Handler $taxonomyHandler)
    {
        $this->postHandler = $postHandler;
        $this->taxonomyHandler = $taxonomyHandler;
    }

    public function initPosts()
    {
        foreach($this->posts as $name) {
            $definition = new $name();
            $this->postHandler->addDefinition($definition);
        }

        $this->postHandler->run();
    }

    public function initTaxonomies()
    {
        foreach($this->taxonomies as $tax) {
            $taxonomy = new $tax();
            $this->taxonomyHandler->addDefinition($taxonomy);
        }
        $this->taxonomyHandler->run();
    }
}

$handler = new \PComm\WPUtils\Post\Handler();
$taxHandler = new \PComm\WPUtils\Taxonomy\Handler();
$tout = new Quiz($handler, $taxHandler);
add_action('init', [$tout, 'initPosts']);
add_action('init', [$tout, 'initTaxonomies']);