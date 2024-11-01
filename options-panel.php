<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * options-panel.php - Settings for the Thesis Footer Tool.
 *
 * @package thesis-tools
 * @subpackage style-press
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div class="wrap">
    <div class="icon32" id="icon-options-general"><br/>
    </div>
    <h2><?php echo $this->pluginName; ?> <?php _e('Settings', 'style-press'); ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields($this->optionsName); ?>

        <div style="width:49%; float:left;">
            <div class="postbox">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Footer Options', 'style-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tr align="top">
                            <th scope="row"><label for="<?php echo $this->optionsName; ?>_post_types"><?php _e('Allow custom styles on:', 'index-press'); ?></label></th>
                            <td>
                                <?php
                                if (function_exists(get_post_types)) {
                                    $types = get_post_types(array('public'=>true));
                                } else {
                                    $types = array('post','page');
                                }
                                ?>

                                <?php foreach ($types as $type) :?>
                                <label class="index-press-post-type"><input type="checkbox" name="<?php echo $this->optionsName; ?>[post_types][]" value="<?php echo $type; ?>" <?php checked(in_array($type, $this->options['post_types']),1); ?> /> <?php echo ucfirst($type); ?></label>
                                <?php endforeach; ?>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <ul id="style_press_tabs">
                <li id="style_press_sitewide"><a href="#top" onclick="tftShowTab('sitewide')"><?php _e('Site Wide', 'style-press'); ?></a></li>

                <?php foreach ($types as $type) : ?>
                <li id="style_press_<?php echo $type; ?>"><a href="#top" onclick="tftShowTab('<?php echo $type; ?>')"><?php echo ucfirst($type); ?></a></li>

                <?php endforeach; ?>

            </ul>
            <div id="style_press_box_sitewide" class="postbox" style="display:block">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php _e('Site Wide CSS', 'style-press'); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tr align="top">
                            <td><textarea class="style-press-textarea" rows="10" cols="50" name="<?php echo $this->optionsName; ?>[sitewide]" id="style_press_sitewide"><?php echo $this->options['sitewide']; ?></textarea></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php foreach ($types as $type) : ?>
            <div id="style_press_box_<?php echo $type; ?>" class="postbox" style="display:none">
                <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
                    <?php printf(__('%1$s CSS', 'style-press'), ucfirst($type)); ?>
                </h3>
                <div class="table">
                    <table class="form-table">
                        <tr align="top">
                            <td><textarea class="style-press-textarea" rows="10" cols="50" name="<?php echo $this->optionsName; ?>[<?php echo $type; ?>]" id="style_press_<?php echo $type; ?>"><?php echo $this->options[$type]; ?></textarea></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>

            <p class="submit" align="center">
                <input type="hidden" name="action" value="update" />
                <?php if (function_exists('wpmu_create_blog')) : ?>
                <input type="hidden" name="option_page" value="<?php echo $this->optionsName; ?>" />
                <?php  else : ?>
                <input type="hidden" name="page_options" value="<?php echo $this->optionsName; ?>" />
                <?php endif; ?>
                <input type="submit" name="Submit" value="<?php _e('Save Changes', 'style-press'); ?>" />
            </p>
        </div>

    </form>
    <div style="width:49%; float:right">
        <div class="postbox">
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;">
                <?php _e('Plugin Information', 'style-press'); ?>
            </h3>
            <div style="padding:5px;">
                <p><?php _e('On this page you can add items before, inside, and after the footer. Whatever is placed in the boxes to the left will be added to every page of your site.', 'style-press'); ?></p>
                <p><span><?php _e('You are using','style-press'); ?> <strong> <a href="http://thesistools.grandslambert.com/the-tools/style-press.html" target="_blank"><?php echo $this->pluginName; ?> <?php echo $this->showVersion(); ?></a></strong> by <a href="http://grandslambert.com" target="_blank">GrandSlambert</a>.</span> </p>
            </div>
        </div>
        <div class="postbox">
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;">
                <?php _e('Usage', 'style-press'); ?>
            </h3>
            <div style="padding:5px;">
                <p><?php _e('Use the boxes to the left to enter text and/or code that you would like added at different parts of the footer. You can also add some custom CSS in the CSS box if you need to style your output.', 'style-press'); ?></p>
                <p><?php _e('You can include your affiliate link in any text box by using the shortcode [link] inside your href tag.', 'style-press'); ?></p>
            </div>
        </div>
        <div class="postbox">
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;">
                <?php _e('Recent Contributors', 'style-press'); ?>
            </h3>
            <div style="padding:5px;">
                <p><?php _e('GrandSlambert would like to thank these wonderful contributors to this plugin!', 'style-press');?></p>
                <?php $this->contributorList(); ?>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
