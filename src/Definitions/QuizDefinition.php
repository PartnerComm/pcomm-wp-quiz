<?php

namespace Pcomm\WPQuiz\Definitions;

class QuizDefinition extends \PComm\WPUtils\Post\DefaultDefinition {
    protected $slug = 'quiz';
    protected $plural = 'Quizes';
    protected $single = 'Quiz';
    protected $rest = true;
    protected $restFields = [
        'featured_image_url' => ['get' => 'getFeaturedImageUrl'],
        'questions' => ['get' => 'getQuestions']
    ];
    protected $taxonomies = []; //remove defaults

    public function getFeaturedImageUrl($object) {
        // the object here is actually an array, so getting the post object
        $wp_post = get_post($object['id']);
        $post = new \PComm\WPUtils\Post\Post($wp_post);
        return $post->getPostThumbnail();
    }

    public function getQuestions($object) {
        $correctAnswers = get_post_meta($object['id'], 'pc-quiz-correct');
        $wrongAnswers = get_post_meta($object['id'], 'pc-quiz-incorrect');

        return [
            'correct' => $correctAnswers,
            'incorrect' => $wrongAnswers
        ];
    }

}