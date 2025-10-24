<?php
namespace SIM\COMMENTS;
use SIM;

add_action('sim_frontend_post_after_content', __NAMESPACE__.'\afterPostContent');
function afterPostContent($frontendcontend){
    $allowedPostTypes     = SIM\getModuleOption(MODULE_SLUG, 'posttypes');

    if(in_array($frontendcontend->postType, $allowedPostTypes)){
        $hidden = '';
    }else{
        $hidden = 'hidden';
    }

    if(comments_open($frontendcontend->postId)){
        $checked    = 'checked';
    }else{
        $checked    = '';
    }

    ?>
    <div id="comments" class="property frontend-form <?php echo $hidden; echo implode(' ', $allowedPostTypes);?>">
        <h4>Comments</h4>
        <label>
            <input type='checkbox' name='comments' value='allow' <?php echo $checked; ?>>
            Allow comments
        </label>
    </div>
    <?php
}

// Allow comments
add_action('sim_after_post_save', __NAMESPACE__.'\afterPostSave', 999, 2);
function afterPostSave($post, $frontEndPost){
    if(
        isset($_POST['comments']) &&        // There is a comment setting
        $_POST['comments'] == 'allow'      // and the value is allow
    ){
        // Only update if the current post is closed for comments
        if($post->comment_status != "open"){     
            wp_update_post(
                array(
                    'ID'                => $post->ID,
                    'comment_status'    => 'open',
                ),
                false,
                false
            );
        }
    }elseif($frontEndPost->update && $post->comment_status == "open"){
        wp_update_post(
            array(
                'ID'                => $post->ID,
                'comment_status'    => 'closed'
            ),
            false,
            false
        );
    }
}