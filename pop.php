<?php
/**
* Plugin Name: Idem Pop Up
* Plugin URI: https://github.com/Imrikmar66/idem-pop-up
* Description: A programmable pop up pluggin
* Version: 1.0
* Author: Pierre MAR
* Author URI: https://github.com/Imrikmar66
**/

/** VERSION SYSTEM **/
require 'plugin-update-checker/plugin-update-checker.php';
$MyUpdateChecker = PucFactory::buildUpdateChecker(
    'https://raw.githubusercontent.com/Imrikmar66/idem-pop-up/master/version.json',
    __FILE__,
    'idem_pop_up'
);

if (!defined('IDEM_POP_UP_VERSION'))
    define('IDEM_POP_UP_VERSION', '1.0');

function idem_pop_up_check_version() {

    if (IDEM_POP_UP_VERSION !== get_option('idem_pop_up_version'))
        idem_pop_up_activation();

}
add_action('plugins_loaded', 'idem_pop_up_check_version');

function idem_pop_up_activation(){
    update_option('idem_pop_up_check_version', IDEM_POP_UP_VERSION);
}

/** POP UP POST TYPE **/
function register_cpt_idem_pop_up() {
 
    $labels = array(
        'name' => _x( 'Idem Pop Up', 'idem_pop_up' ),
        'singular_name' => _x( 'Idem Pop Up', 'idem_pop_up' ),
        'add_new' => _x( 'Add New', 'idem_pop_up' ),
        'add_new_item' => _x( 'Add New Idem Pop Up', 'idem_pop_up' ),
        'edit_item' => _x( 'Edit Idem Pop Up', 'idem_pop_up' ),
        'new_item' => _x( 'New Idem Pop Up', 'idem_pop_up' ),
        'view_item' => _x( 'View Idem Pop Up', 'idem_pop_up' ),
        'search_items' => _x( 'Search Idem Pop Up', 'idem_pop_up' ),
        'not_found' => _x( 'No Idem Pop Up found', 'idem_pop_up' ),
        'not_found_in_trash' => _x( 'No Idem Pop Up found in Trash', 'idem_pop_up' ),
        'parent_item_colon' => _x( 'Parent Idem Pop Up:', 'idem_pop_up' ),
        'menu_name' => _x( 'Idem Pop Up', 'idem_pop_up' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Idem Pop Up',
        'supports' => array( 'title', 'editor', 'revisions' ),
        'taxonomies' => array( '' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-welcome-view-site',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
 
    register_post_type( 'idem_pop_up', $args );
}
 
add_action( 'init', 'register_cpt_idem_pop_up' );

/*** SETTINGS ***/
add_action( 'admin_menu', 'idem_pop_up_settings_init' );
function idem_pop_up_settings_init(){

    add_menu_page( 'Idem Pop Up Settings', 'Idem Pop Up', 'manage_options', 'idem-pop-up', 'idem_pop_up_init', 'dashicons-admin-generic' );
    add_action( 'admin_init', 'update_idem_pop_up_responsive_settings' );
}

function idem_pop_up_init(){ 

    if(isset($_POST["responsive_disable"])) {
        update_option("responsive_disable", $_POST["responsive_disable"]);
    }
    $checked = get_option('responsive_disable') ? "checked" : "";
?>
    <h1>Idem pop up configuration</h1>
    <!-- Form to handle the upload - The enctype value here is very important -->
    <form  method="post" enctype="multipart/form-data">
        <?php settings_fields( 'idem_pop_up_responsive_settings' ); ?>
        <?php do_settings_sections( 'idem_pop_up_responsive_settings' ); ?>
        <label>
            <input type="checkbox" id="responsive_disable_checkbox" name="responsive_disable_checkbox" <?php echo $checked; ?> />
            <span>Désactiver pop up en mode responsive</span>
        </label>
        <input type="hidden" id="responsive_disable" name="responsive_disable" value=0 />
        <?php submit_button('Valider') ?>
    </form>
    <script>
    var $ = jQuery;
        $("#responsive_disable_checkbox").change(function(){
            
            if($(this).is(':checked')){
                $("#responsive_disable").val(1);
            }
            else{
                $("#responsive_disable").val(0);
            }

        });
    </script>
<?php 
}

function update_idem_pop_up_responsive_settings(){
    add_option('responsive_disable', 0);
    register_setting( 'idem_pop_up_responsive_settings', 'responsive_disable' );
}

/*** IN WORDPRESS POP UP POST TYPE COMPORTMENT ***/

/**  META BOXE **/
add_action( 'add_meta_boxes', 'idem_pop_up_inner_add_meta_box' );
add_action( 'save_post', 'idem_pop_up_inner_save_post' );

/**
 * Adds meta box to pages screen
 */
function idem_pop_up_inner_add_meta_box()
{
    add_meta_box(
        'idem-pop-up-inner-meta-box', // id, used as the html id att
        __( 'Idem Pop Up Inner Meta Box' ), // meta box title, like "Page Attributes"
        'idem_pop_up_inner_meta_box_cb', // callback function, spits out the content
        'idem_pop_up', // post type or page. We'll add this to pages only
        'side', // context (where on the screen)
        'low' // priority, where should this go in the context?
    );
}

//Pluggin page info
function idem_pop_up_loaded() {
    $screen = get_current_screen();
    if( $screen->id == "plugins" || $screen->id == "edit-idem_pop_up" ){

        if( !is_plugin_active( 'post-expirator/post-expirator.php' )) {
            echo "<div class='p_suggestion sug_1' > Idem-pop-up is usefull with <a href='http://postexpirator.tuxdocs.net/'>post-expirator pluggin</a> </div>";
        }

        if( !is_plugin_active( 'js_composer/js_composer.php' )) {
            echo "<div class='p_suggestion sug_2' > Idem-pop-up is usefull with <a href='https://vc.wpbakery.com/'> visual composer pluggin </a> </div>";
        }
        
    }
}
add_action( 'wp_after_admin_bar_render', 'idem_pop_up_loaded' );

//Display meta box for default pop up
function idem_pop_up_inner_meta_box_cb( $post )
{   
    $checked = ($post->ID == getDefault() ) ? "checked" : "";
    $check = "<label>Par default</label><input type='checkbox' " . $checked . " name='idem_pop_up_default' value='1' >";
    echo $check;
}

//Save default
function idem_pop_up_inner_save_post( $post_id )
{
    $chk = isset( $_POST['idem_pop_up_default'] ) ? 1 : 0;
    $cnt = getCountDefault_exclude( $post_id ); //Recherche un autre default

    if($chk || !$cnt){ //Si default checkbox posté ou si aucun default checkbox existant
        disableDefault();
        update_post_meta( $post_id, 'idem_pop_up_default', 1 );
    }
    
}

 //Retourne le default pop up post ID
function getDefault(){

    $args = array( 
        'post_type' => 'idem_pop_up',
        'meta_key' => 'idem_pop_up_default',
        'meta_value' => 1,
        'post_status' => ['publish', 'future']
    );
    $posts = get_posts( $args ); //filtré pour le default

    $args = array( 
        'post_type' => 'idem_pop_up',
        'post_status' => ['publish', 'future']
    );
    $unfiltered_posts = get_posts( $args ); //non filtré

    if(count($posts) < 1 ) { //Si aucun default trouvé
        if(count($unfiltered_posts) < 1) //Si aucun post trouvé
            return 0;
        else 
            return $unfiltered_posts[0]; //Sinon, on renvoi le premier en default
    }
    else {
        return $posts[0]->ID; //Sinon, on renvoi le default !
    }

}

//Recherche le default, en excluant $id
function getCountDefault_exclude( $id ) {
    $args = array( 
        'post_type' => 'idem_pop_up',
        'meta_key' => 'idem_pop_up_default',
        'meta_value' => 1,
        'post__not_in' => [  $id ],
        'post_status' => ['publish', 'future']
    );
    $posts = get_posts( $args );

    if(count($posts) < 1) 
        return 0;
    else
        return 1;
    
}

//Désactive tout les defaults (pour en mettre un nouveau)
function disableDefault(){
    $args = array( 
        'post_type' => 'idem_pop_up',
        'meta_key' => 'idem_pop_up_default',
        'meta_value' => 1,
        'post_status' => ['publish', 'future']
    );
    $posts = get_posts( $args );

   foreach( $posts as $post ) {
        update_post_meta( $post->ID, 'idem_pop_up_default', 0 );
    }
}

/*** IN WORDPRESS PAGE ADMIN COMPORTMENT ***/

/** POP UP META BOXE **/
add_action( 'add_meta_boxes', 'idem_pop_up_add_meta_box' );
add_action( 'save_post', 'idem_pop_up_save_post' );
/**
 * Adds meta box to pages screen
 */
function idem_pop_up_add_meta_box()
{
    add_meta_box(
        'idem-pop-up-meta-box', // id, used as the html id att
        __( 'Idem Pop Up' ), // meta box title, like "Page Attributes"
        'idem_pop_up_meta_box_cb', // callback function, spits out the content
        'page', // post type or page. We'll add this to pages only
        'side', // context (where on the screen)
        'high' // priority, where should this go in the context?
    );
}

//Affiche le selecteur sur les pages editors
function idem_pop_up_meta_box_cb( $post )
{
    //Pop up activation
    $checked = get_post_meta( $post->ID, "activate_idem_pop_up", true ) ? "checked" : "";
    $activatePopUp = "<label><input " . $checked . " type='checkbox' id='idem_pop_up_admin_check' name='activate_idem_pop_up' /><span>Activer pop-up</span></label><br />";
    
    //Pop up selection
    $default = getDefault();
    $current = get_post_meta( $post->ID, "idem_pop_up_selected", true );
    $selector = "<select id='idem_pop_up_admin_select' name='idem_pop_up_selected'>";
    $selector .= "<option value=0 > Default </option>";
    
    $args = array( 
        'post_type' => 'idem_pop_up',
        'post_status' => ['publish', 'future']
    );
    $posts = get_posts( $args );

    foreach( $posts as $the_post ) {
        $selected = ( $current == $the_post->ID ) ? "selected" : "";
        $selector .= "<option " . $selected . " value='" . $the_post->ID . "' >" . $the_post->post_title . "</option>";
    }

    $selector .= "</select>";

    //Template Selection
    $tpl_selector = "<br><label><span>Select template</span></label><br><select name='idem_pop_up_template'>";
    $current_tpl = get_post_meta( $post->ID, "idem_pop_up_template", true );
    $current_tpl = $current_tpl ? $current_tpl : 'default';
    $dirs = scandir( __DIR__ . "/templates");
    foreach($dirs as $dir){

        if($dir == '.' || $dir == '..')
            continue;
        
        $dir = str_replace(".php", "", $dir);
        $selected = ($dir == $current_tpl) ? "selected" : "";
        $tpl_selector .= "<option value='" . $dir . "' " . $selected . " >" . $dir . "</option>";
    }
    $tpl_selector .= "</select>";

    //Auto or call-to action
    $current_mode = get_post_meta( $post->ID, "idem_pop_up_action", true );
    $current_mode = $current_mode ? $current_mode : 'auto';
    $current_auto_mode = $current_mode == 'auto' ? 'checked' : '';
    $current_manuel_mode = $current_mode == 'manuel' ? 'checked' : '';

    $current_action_caller = get_post_meta( $post->ID, "idem_pop_up_action_caller", true );
    $current_action_caller = $current_action_caller ? $current_action_caller : '';

    $auto = "<h4>Mode : </h4>";
    $auto .= "<input type='radio' " . $current_auto_mode . " name='idem_pop_up_action' value='auto' /><label>Auto</label>"; 
    $auto .= "<br>";
    $auto .= "<input type='radio' " . $current_manuel_mode . " name='idem_pop_up_action' value='manuel' /><label>Manuel</label>";
    $auto .= "<input placeholder='Action call button class' type='text' name='idem_pop_up_action_caller' value='" .$current_action_caller . "'>";
    
    echo $activatePopUp.$selector.$tpl_selector.$auto;
}

//Sauvegarde des datas on update
function idem_pop_up_save_post( $post_id )
{
    //On sauve la valeur, ou par defaut si deselect
    if( isset( $_POST["idem_pop_up_selected"] ) )
        update_post_meta( $post_id, 'idem_pop_up_selected', $_POST["idem_pop_up_selected"] );
    else {
        $default = getDefault();
        update_post_meta( $post_id, 'idem_pop_up_selected', $default );
    }

    //On sauve le template, ou par defaut si aucun
    if( isset( $_POST["idem_pop_up_template"] ) )
        update_post_meta( $post_id, 'idem_pop_up_template', $_POST["idem_pop_up_template"] );
    else {
        update_post_meta( $post_id, 'idem_pop_up_template', 'default' );
    }

    //On sauve le mode de fonctionnement
    if( isset( $_POST["idem_pop_up_action"] ) )
        update_post_meta( $post_id, 'idem_pop_up_action', $_POST["idem_pop_up_action"] );
    else {
        update_post_meta( $post_id, 'idem_pop_up_action', 'auto' );
    }

    //On sauve l'action caller du mode manuel
    if( isset( $_POST["idem_pop_up_action_caller"] ) )
        update_post_meta( $post_id, 'idem_pop_up_action_caller', $_POST["idem_pop_up_action_caller"] );
    else {
        update_post_meta( $post_id, 'idem_pop_up_action_caller', '' );
    }

    //On active ou desactive la pop up suivant la checkbox
    if( isset( $_POST["activate_idem_pop_up"] ) )
        update_post_meta( $post_id, 'activate_idem_pop_up', 1 );
    else 
        update_post_meta( $post_id, 'activate_idem_pop_up', 0 );
}

function pop_up_admin_assets() {
    wp_enqueue_style( 'pop-admin-styles', plugin_dir_url( __FILE__ ) . '/pop_admin_styles.css' );
	wp_enqueue_script( 'pop-admin-script', plugin_dir_url( __FILE__ ) . '/pop_admin_script.js', array('jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'pop_up_admin_assets' );

/*** IN WORDPRESS PAGE DISPLAY COMPORTMENT ***/
function display($content, $template){
    include('templates/' . $template . '.php');
}
function show_idem_pop_up() {
    global $post;

    //Check si activé
    $pop_up_activated = get_post_meta( $post->ID, "activate_idem_pop_up", true );
    if(!$pop_up_activated)
        return;

    $pop_up_id = get_post_meta( $post->ID, "idem_pop_up_selected", true );
    //Check si on a trouvé une pop up - sinon default id
    $pop_up_id = $pop_up_id ? $pop_up_id : getDefault();

    //Check si l'élément est publish - sinon default id
    $pop_up_statut = get_post_status( $pop_up_id );
    $pop_up_id = $pop_up_statut == "publish" ? $pop_up_id : getDefault();

    //Check si l'id est  ou si la pop up n'existe plus - sinon default
    $pop_up = get_post($pop_up_id);
    if(!$pop_up || !$pop_up_id){
         $pop_up_id = getDefault();
         $pop_up = get_post($pop_up_id);
    }

    //Check si l'id est  ou si la pop up n'existe plus - sinon on est deja en default, donc ne rien afficher comme pop up
    if(!$pop_up || !$pop_up_id)
        return;

    //Get Template
    $pop_up_template = get_post_meta( $post->ID, "idem_pop_up_template", true );
    $pop_up_template = $pop_up_template ? $pop_up_template : "default";

    $pop_up_content = apply_filters( 'the_content', $pop_up->post_content );
    $pop_up_content = do_shortcode( $pop_up_content );

    //display element with template
    display($pop_up_content, $pop_up_template);

    //Get action mode
    $action_mode = get_post_meta( $post->ID, "idem_pop_up_action", true );
    $action_mode = $action_mode ? $action_mode : 'auto';

    //get action caller
    $action_caller = get_post_meta( $post->ID, "idem_pop_up_action_caller", true );
    $action_caller = $action_caller ? $action_caller : '';

    //display javascript needed elements
    echo "<input type='hidden' value='" . $action_mode . "' id='idem-action-mode' />";
    echo "<input type='hidden' value='" . $action_caller . "' id='idem-action-caller' />";

    //Check disabled pop up for responsive
    if( get_option('responsive_disable') ) {
        echo "<input type='hidden' value=1 id='responsive-disabled' />";
    }
    
    //Visual Composer loading styles
    if( class_exists ( "Vc_Manager" ) ) {
        $vcM = Vc_Manager::getInstance();
        $vc = $vcM->vc();
        $vc->addShortcodesCustomCss($pop_up_id);
    }

}

add_action( 'get_footer', 'show_idem_pop_up' );

function pop_up_assets() {
	wp_enqueue_style( 'pop-styles', plugin_dir_url( __FILE__ ) . '/pop_styles.css' );
	wp_enqueue_script( 'pop-script', plugin_dir_url( __FILE__ ) . '/pop_script.js', array('jquery' ), '1.0', true );
}

add_action( 'wp_enqueue_scripts', 'pop_up_assets' );