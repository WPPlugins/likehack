<?php
/* @var $before_widget */
/* @var $after_widget */
/* @var $before_title */
/* @var $after_title */
/* @var $instance */

$links = LikeHack::get_links(array(
    'limit'          => LikeHack::get($instance, 'links_count', 5),
    'content_type'   => LikeHack::option('content_type'),
    'feed_type'      => LikeHack::option('links_to_show')
));

$paid = LikeHack::check_paid();

$show_message_for_admin = false;

if (is_wp_error($links) OR !$links)
{
    global $current_user;
    foreach($current_user->roles AS $role)
    {
        if ($role == 'administrator')
        {
            $show_message_for_admin = true;
            break;
        }
    }

    if (!$show_message_for_admin)
    {
        return;
    }
}

$title = apply_filters('widget_title', $instance['title']);

echo $before_widget;

echo $before_title; ?>

<?php if (LikeHack::get($instance, 'show_lh_icon')) : ?>

        <?php
        $likehack_icon_src = LIKEHACK_PLUGIN_URL . 'images/likehack_'
            . LikeHack::get($instance, 'style', 'dark') . '_'
            . LikeHack::get($instance, 'icon_size', '60x20') . '.png';

        $likehack_icon_class = 'likehack-icon likehack-icon-style_' . LikeHack::get($instance, 'style', 'dark') . ' likehack-icon-size_' . LikeHack::get($instance, 'icon_size', '16');
        ?>

        <a class='likehack-site-link'
           target="_blank"
           href='http://likehack.com/?utm_source=wordpress&utm_medium=visitors&utm_campaign=tools'><img
                src='<?php echo $likehack_icon_src ?>'
                class='<?php echo $likehack_icon_class ?>' /><img src='<?php echo $likehack_icon_src ?>'
                                                                  class='likehack-icon-placeholder <?php echo $likehack_icon_class ?>' /></a><?php echo $title ?>
<?php else : ?>
    <?php echo $title ?>
<?php endif; ?>

<?php echo $after_title ?>

<?php
if ($show_message_for_admin)
{
        if (is_wp_error($links))
        {
            echo $links->get_error_message();
        }
}
else
{
?>
        <?php $links_list_class = (LikeHack::get($instance, 'icon_size') == 32) ? 'add-offset-top' : false ?>
        <ul class="likehack-links <?php echo $links_list_class ?>">
            <?php foreach($links AS $link) : ?>
                <?php
                  if ($paid['paid'] == true) 
                  {
                      $url = $link['original_url'];
                  }
                  else
                  {
                      $url = $link['url'];
                  }
                  if (!$link['original_url'] && !$link['url'])
                  {
                      continue;
                  }

                  if (!$name = esc_attr($link['name']))
                  {
                      $name = parse_url($link['original_url'], PHP_URL_HOST);
                      if ($url_path = parse_url($link['original_url'], PHP_URL_PATH))
                      {
                          $url_path = preg_replace('~\W*([\w]{0,6})[\s\S]*~', '$1', $url_path) . '&hellip;';
                          $name .= "<span class='link-fade'>/$url_path</span>";
                      }
                  }
                ?>
                <li class="likehack-link-item"><a class="likehack-link" target="_blank" href="<?php echo $url ?>"><?php echo $name ?></a></li>
              
            <?php endforeach; ?>
        </ul>
<?php
}
?>

<?php if (LikeHack::get($instance, 'show_follow')) : ?>

        <?php
        $follow_icons_size = (LikeHack::get($instance, 'icon_size', '60x20') == 32) ? 32 : 16;
        ?>

        <?php if ($facebook_follow_link = LikeHack::get($instance, 'facebook_follow_link')) : ?>
            <a class="likehack-follow-link likehack-follow-link_facebook" target="_blank" href="https://facebook.com/<?php echo $facebook_follow_link ?>">
                <img class="icon-follow icon-follow_facebook" src="<?php echo LIKEHACK_PLUGIN_URL, "images/facebook_$follow_icons_size.png" ?>" />
                  <?php echo ($facebook_follow_title = LikeHack::get($instance, 'facebook_follow_title')) ? $facebook_follow_title : 'Get more on my Facebook!' ?>
            </a>
        <?php endif; ?>

        <?php if ($twitter_follow_link = LikeHack::get($instance, 'twitter_follow_link')) : ?>
            <a class="likehack-follow-link likehack-follow-link_facebook" target="_blank" href="https://twitter.com/<?php echo $twitter_follow_link ?>">
                <img class="icon-follow icon-follow_twitter" src="<?php echo LIKEHACK_PLUGIN_URL, "images/twitter_$follow_icons_size.png" ?>" />
                  <?php echo ($twitter_follow_title = LikeHack::get($instance, 'twitter_follow_title')) ? $twitter_follow_title : 'Follow me on Twitter!' ?>
            </a>
        <?php endif; ?>

<?php endif ?>

<?php echo $after_widget;