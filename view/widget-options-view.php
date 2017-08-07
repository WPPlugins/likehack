<?php
/* @var $instance */
?>
<!-- Title -->
<p>
    <label for="<?php echo $widget->get_field_id('title'); ?>"><?php _e('Headline:', LikeHack::LANG); ?></label>
    <input type="text"
           class="widefat" id="<?php echo $widget->get_field_id('title'); ?>"
           name="<?php echo $widget->get_field_name('title'); ?>"
           value="<?php echo esc_attr( LikeHack::get($instance, 'title', __('My curated content', LikeHack::LANG), TRUE) ); ?>"/>
</p>

<!-- Links Count -->
<p>
    <label for="<?php echo $widget->get_field_id('links_count'); ?>"><?php _e('Links count:', LikeHack::LANG); ?></label>
    <input type="text"
           size="3"
           id="<?php echo $widget->get_field_id('links_count'); ?>"
           name="<?php echo $widget->get_field_name('links_count'); ?>"
           value="<?php echo esc_attr( LikeHack::get($instance, 'links_count', 5) ); ?>"/>
</p>

<!-- Show Likehack icon -->
<p>
    <label for="<?php echo $widget->get_field_id('show_lh_icon'); ?>">
        <input type="checkbox"
               id="<?php echo $widget->get_field_id('show_lh_icon'); ?>"
               name="<?php echo $widget->get_field_name('show_lh_icon'); ?>"
               <?php checked(!!LikeHack::get($instance, 'show_lh_icon', TRUE), TRUE) ?>
               value="1" />
        <?php _e('Show LikeHack icon', LikeHack::LANG); ?>
    </label>
</p>

<!-- Style -->
<p>
    <label for="<?php echo $widget->get_field_id('style'); ?>"><?php _e('Style:', LikeHack::LANG); ?></label>
    <select id="<?php echo $widget->get_field_id('style'); ?>"
            name="<?php echo $widget->get_field_name('style'); ?>">
        <?php
        $selected = LikeHack::get($instance, 'style', 'dark');
        $options = array('dark' => __('Dark', LikeHack::LANG), 'light' => __('Light', LikeHack::LANG));

        foreach ($options AS $val => $title) : ?>
            <option value="<?php echo $val ?>" <?php selected($selected, $val) ?>><?php echo $title ?></option>
        <?php
        endforeach; ?>
    </select>
</p>

<!-- Icon Size -->
<p>
    <label for="<?php echo $widget->get_field_id('icon_size'); ?>"><?php _e('Icon Size:', LikeHack::LANG); ?></label>
    <select id="<?php echo $widget->get_field_id('icon_size'); ?>"
            name="<?php echo $widget->get_field_name('icon_size'); ?>">
        <?php
        $selected = LikeHack::get($instance, 'icon_size', '60x20');
        $options = array(
            '16'    => __('16x16', LikeHack::LANG),
            '32'    => __('32x32', LikeHack::LANG),
            '60x20' => __('60x20', LikeHack::LANG),
        );

        foreach ($options AS $val => $title) : ?>
            <option value="<?php echo $val ?>" <?php selected($selected, $val) ?>><?php echo $title ?></option>
            <?php
        endforeach; ?>
    </select>
</p>

<!-- Show Follow Links -->
<p>
    <label for="<?php echo $widget->get_field_id('show_follow'); ?>">
        <input type="checkbox"
               id="<?php echo $widget->get_field_id('show_follow'); ?>"
               name="<?php echo $widget->get_field_name('show_follow'); ?>"
               <?php checked(!!LikeHack::get($instance, 'show_follow', FALSE), TRUE) ?>
               value="1" />
        <?php _e('Show follow links', LikeHack::LANG); ?>
    </label>
</p>

<!-- Facebook & Twitter Account -->
<p>
    <label for="<?php echo $widget->get_field_id('facebook_follow_link'); ?>"><?php _e('Facebook:', LikeHack::LANG); ?></label>
    <input type="text"
           class="widefat" id="<?php echo $widget->get_field_id('facebook_follow_link'); ?>"
           name="<?php echo $widget->get_field_name('facebook_follow_link'); ?>"
           value="<?php echo esc_attr( LikeHack::get($instance, 'facebook_follow_link' ,'') ); ?>"/>

    <br />
    
    <label for="<?php echo $widget->get_field_id('facebook_follow_title'); ?>"><?php _e('Message:', LikeHack::LANG); ?></label>
    <input type="text"
           class="widefat" id="<?php echo $widget->get_field_id('facebook_follow_title'); ?>"
           name="<?php echo $widget->get_field_name('facebook_follow_title'); ?>"
           value="<?php echo esc_attr( LikeHack::get($instance, 'facebook_follow_title' ,'Get more on my Facebook!') ); ?>"/>
</p>
<p>
    <label for="<?php echo $widget->get_field_id('twitter_follow_link'); ?>"><?php _e('Twitter:', LikeHack::LANG); ?></label>
    <input type="text"
           class="widefat" id="<?php echo $widget->get_field_id('twitter_follow_link'); ?>"
           name="<?php echo $widget->get_field_name('twitter_follow_link'); ?>"
           value="<?php echo esc_attr( LikeHack::get($instance, 'twitter_follow_link' ,'') ); ?>"/>

    <br />
    
    <label for="<?php echo $widget->get_field_id('twitter_follow_title'); ?>"><?php _e('Message:', LikeHack::LANG); ?></label>
    <input type="text"
           class="widefat" id="<?php echo $widget->get_field_id('twitter_follow_title'); ?>"
           name="<?php echo $widget->get_field_name('twitter_follow_title'); ?>"
           value="<?php echo esc_attr( LikeHack::get($instance, 'twitter_follow_title' ,'Follow me on Twitter!') ); ?>"/>
</p>
