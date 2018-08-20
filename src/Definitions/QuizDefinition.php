<?php

namespace Pcomm\WPQuiz\Definitions;

class QuizDefinition extends \PComm\WPUtils\Post\DefaultDefinition {
    protected $slug = 'quiz';
    protected $plural = 'Quizes';
    protected $single = 'Quiz';
    protected $rest = true;
    protected $restFields = [
        'featured_image_url' => ['get' => 'getFeaturedImageUrl'],
        'answers' => ['get' => 'getAnswers'],
        'question_menu_order' => ['get' => 'getMenuOrder'],
        'hint' => ['get' => 'getHints'],
        'question_types' => ['get' => 'getQuestionTypes']
    ];
    protected $taxonomies = []; //remove defaults

    public function getFeaturedImageUrl($object) {
        // the object here is actually an array, so getting the post object
        $wp_post = get_post($object['id']);
        $post = new \PComm\WPUtils\Post\Post($wp_post);
        return $post->getPostThumbnail();
    }

    public function getMenuOrder($object) {
        // the object here is actually an array, so getting the post object
        $wp_post = get_post($object['id']);
        return $wp_post->menu_order;
    }

    public function getAnswers($object) {
        $correctAnswers = get_post_meta($object['id'], 'pc-quiz-correct');
        $wrongAnswers = get_post_meta($object['id'], 'pc-quiz-incorrect');

        return [
            'correct' => $correctAnswers,
            'incorrect' => $wrongAnswers
        ];
    }

    public function getHints($object) {
        $hintLabel = get_post_meta($object['id'], 'pc-hint-label');
        $hintUrl = get_post_meta($object['id'], 'pc-hint-url');

        return [
            'hintlabel' => $hintLabel,
            'hinturl' => $hintUrl
        ];
    }

    public function getQuestionTypes() {
        $questiontypes = get_the_terms( $post->ID, 'question-type' );
        $questiontypes_links = array();
        $questiontypes_active = $taxonomies;
        $questiontypes_active_array = array();

        foreach ( $questiontypes as $questiontype ) {
            $questiontypes_links[] = $questiontype->name;
            if (get_term_meta($questiontype->term_id)) {
                $questiontypes_active_array[] = 1;
            } else {
                $questiontypes_active_array[] = 0;
            }

        }



        return [
            'question_types' => $questiontypes_links,
            'question_types_active' => $questiontypes_active_array
        ];
    }

}