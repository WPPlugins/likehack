<?php
class LikeHack_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'likehack',
            __('LikeHack', LikeHack::LANG),
            array(
                'description' => __('A LikeHack Widget', LikeHack::LANG),
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        LikeHack::view('widget-frontend', $args + array(
            'instance' => $instance
        ));
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance                         = array();
        $instance['title']                = strip_tags($new_instance['title']);
        $instance['style']                = strip_tags($new_instance['style']);
        $instance['icon_size']            = strip_tags($new_instance['icon_size']);
        $instance['links_count']          = intval($new_instance['links_count']);
        $instance['links_count']          = max(1, $instance['links_count']);
        $instance['links_count']          = min(50, $instance['links_count']);
        $instance['show_follow']          = strip_tags($new_instance['show_follow']);
        $instance['show_lh_icon']         = strip_tags($new_instance['show_lh_icon']);
        $instance['facebook_follow_link'] = $this->validate_facebook_link($new_instance['facebook_follow_link']);
        $instance['twitter_follow_link']  = $this->validate_twitter_link($new_instance['twitter_follow_link']);
        $instance['facebook_follow_title'] = $this->validate_facebook_link($new_instance['facebook_follow_title']);
        $instance['twitter_follow_title']  = $this->validate_twitter_link($new_instance['twitter_follow_title']);

        wp_cache_flush();

        return $instance;
    }

    protected function validate_twitter_link($link)
    {
        $link = trim($link);
        $link = strip_tags($link);

        $matches = array();
        if (preg_match('~twitter.com/([^/#]+)~iu', $link, $matches))
        {
            return $matches[1];
        }
        elseif (preg_match('~@([^/#]+)~iu', $link, $matches))
        {
            return $matches[1];
        }
        else
        {
            return $link;
        }
    }

    protected function validate_facebook_link($link)
    {
        $link = trim($link);
        $link = strip_tags($link);

        $matches = array();
        if (preg_match('~(?:facebook|fb).com/([^/#]+)~iu', $link, $matches))
        {
            return $matches[1];
        }
        else
        {
            return $link;
        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        LikeHack::view('widget-options', array(
            'widget'   => &$this,
            'instance' => $instance
        ));
    }

}