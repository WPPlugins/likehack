<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e('LikeHack', LikeHack::LANG) ?></h2>

    <?php LikeHack::try_token(LikeHack::option('api_key')); ?>

    <?php $token_approved = (LikeHack::option('api_key') AND LikeHack::option('token_approved')); ?>

    <?php if (!$token_approved) : ?>
        <div class="updated">
            <p><?php _e('Welcome to Likehack WP Plugin! Be sure, that you have signed up, connected all accounts you need and got access token at <a href="http://likehack.com/?utm_source=wordpress&utm_medium=users&utm_campaign=tools">LikeHack.com</a>', LikeHack::LANG) ?></p>
        </div>
    <?php else : ?>
        <div class="updated">
            <p><?php _e('You are connected now', self::LANG) ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields('likehack'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e('Access token:', LikeHack::LANG) ?><br />
                    <small><?php _e('Get token on:<br />Likehack.com&rarr;Settings&rarr;Tools', LikeHack::LANG) ?></small>
                </th>
                <td>
                    <input type="text" name="likehack_api_key" size="50" placeholder="<?php _e('Paste LikeHack token here', LikeHack::LANG) ?>" value="<?php echo LikeHack::option('api_key'); ?>" />
                </td>
            </tr>

            <?php if ($token_approved) : ?>

                <tr valign="top">
                    <th scope="row">
                        <input type="submit" class="button-primary" value="<?php _e('Reconnect', LikeHack::LANG) ?>" />
                    </th>
                    <td>

                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" colspan="2">
                        <h3>Customize your feed:</h3>
                    </th>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e('Show only:', LikeHack::LANG) ?>
                    </th>
                    <td>
                        <?php 
                          if (LikeHack::option('links_to_show') == 'mine') { 
                            $links_to_show = 'mine';
                          } else if (LikeHack::option('links_to_show') == 'curated') {
                            $links_to_show = 'curated';
                          } else {
                            $links_to_show = 'all';
                          }
                        ?>
                        <label><input type="radio" <?php checked('all', $links_to_show) ?> name="likehack_links_to_show" value="all" /> <?php _e('All (links you shared + new stories)', LikeHack::LANG) ?></label><br />
                        <label><input type="radio" <?php checked('mine', $links_to_show) ?> name="likehack_links_to_show" value="mine" /> <?php _e('Mine (only links you shared)', LikeHack::LANG) ?></label><br />
                        <label><input type="radio" <?php checked('curated', $links_to_show) ?> name="likehack_links_to_show" value="curated" /> <?php _e('Curated (only top new stories)', LikeHack::LANG) ?></label>
                    </td>
                </tr>

            <?php endif ?>

            <tr valign="top">
                <th scope="row">
                    <input type="submit" class="button-primary" value="<?php _e($token_approved ? 'Save Changes' : 'Connect', LikeHack::LANG) ?>" />
                </th>
                <td>

                </td>
            </tr>

        </table>

        <input type="hidden" name="action" value="update" />

    </form>
</div>