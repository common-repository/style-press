<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
/**
 * footer.php - Footer for common pages.
 *
 * @package Style_Press
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
?>

<div style="clear:both; margin-top:10px;">
    <div class="postbox" style="width:49%; height: 175px; float:left;">
        <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Credits', 'style-press'); ?></h3>
        <div style="padding:8px;">
            <p>
                <?php printf(__('Thank you for trying the %1$s plugin - I hope you find it useful. For the latest updates on this plugin, vist the %2$s. If you have problems with this plugin, please use our %3$s', 'style-press'),
                    $this->pluginName,
                    '<a href="http://stylepress.grandslambert.com/" target="_blank">' . __('official site', 'style-press') . '</a>',
                    '<a href="http://support.grandslambert.com/forum/style-press" target="_blank">' . __('Support Forum', 'style-press') . '</a>'
                ); ?>
            </p>
            <p>
                <?php printf(__('This plugin is &copy; %1$s by %2$s and is released under the %3$s', 'style-press'),
                    '2009-' . date("Y"),
                    '<a href="http://grandslambert.com" target="_blank">GrandSlambert, Inc.</a>',
                    '<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">' . __('GNU General Public License', 'style-press') . '</a>'
                ); ?>
            </p>
        </div>
    </div>
    <div class="postbox" style="width:49%; height: 175px; float:right;">
        <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Donate', 'style-press'); ?></h3>
        <div style="padding:8px">
            <p>
                <?php printf(__('If you find this plugin useful, please consider supporting this and our other great %1$s.', 'style-press'), '<a href="http://wordpress.grandslambert.com/plugins.html" target="_blank">' . __('plugins', 'style-press') . '</a>'); ?>
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZYHR9VVQ4CB94" target="_blank"><?php _e('Donate a few bucks!', 'style-press'); ?></a>
            </p>
            <p style="text-align: center;">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="ZYHR9VVQ4CB94">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </div>
    </div>
</div>