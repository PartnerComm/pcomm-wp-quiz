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
        '\PComm\WPQuiz\Definitions\QuestionTypeDefinition'
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

    /**
     * Adding meta fields for keywords
     */
    public function add_meta_fields() {
        ?>
        <div class="form-field term-active">
            <label for="active-status"><?php _e('Make Question Active'); ?></label>
            <input id="active_select" name="active_select" type="checkbox" />
            <p class="description">This will make the question active</p>
        </div>
        <?php
    }

    /**
     * Meta fields for keywords
     */
    public static function get_edit_meta_fields() {
        $activeStatus = "";
        if(
        !empty($_GET['tag_ID'])
        ) {
            $getTermMeta = get_term_meta($_GET['tag_ID'], 'active', true);
            if(!empty($getTermMeta)) {
                $activeStatus = "checked";
            }
        }
        ?>
        <tr class="form-field term-sticky-wrap">
            <th scope="row"><label for="Active Status"><?php _e( 'Active' ); ?></label></th>
            <td>
                <input id="active_select" name="active_select" type="checkbox" <?php echo $activeStatus; ?>>
                <p class="description">This will make the question active</p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save the meta fields when creating a new keyword
     */
    public static function save_meta_fields($termId, $ttId) {
        if(empty($termId) || empty($_POST['active_select'])) {
            return;
        }
        update_term_meta(
            (int)$termId,
            'active',
            true
        );
    }

    /**
     * Save the fields that have been added for meta keywords
     */
    public static function edit_meta_fields() {
        if(empty($_POST['tag_ID'])) {
            return;
        }
        if(empty($_POST['active_select'])) {
            delete_term_meta(
                (int)$_POST['tag_ID'],
                'active'
            );
        } else {
            update_term_meta(
                (int)$_POST['tag_ID'],
                'active',
                true
            );
        }
    }

    /**
     * Adding meta fields for editing keywords
     */
    public static function add_meta_columns($columns) {
        $columns['active'] = __('Active');
        return $columns;
    }

    // Retrieving meta field for
    public static function add_meta_column_content($content, $columnName, $termId) {
        if($columnName == "active") {
            $termId = (int)$termId;
            $active = get_term_meta($termId, 'active', true);
            $content .= esc_attr($active);
        }
        return $content;
    }



}

$handler = new \PComm\WPUtils\Post\Handler();
$taxHandler = new \PComm\WPUtils\Taxonomy\Handler();
$tout = new Quiz($handler, $taxHandler);
add_action('init', [$tout, 'initPosts']);
add_action('init', [$tout, 'initTaxonomies']);
add_action('question-type_add_form_fields', [$tout, 'add_meta_fields']);
add_action('question-type_edit_form_fields', [$tout, 'get_edit_meta_fields']);
add_action('edited_question-type', [$tout, 'edit_meta_fields']);
add_action('created_question-type', [$tout, 'save_meta_fields'], 10, 2);
add_filter('manage_edit-question-type_columns', [$tout, 'add_meta_columns']);
add_filter(
    'manage_question-type_custom_column',
    [$tout, 'add_meta_column_content'],
    10,
    3
);