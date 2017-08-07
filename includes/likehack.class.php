<?php
Class LikeHack
{
    const VERSION = 2.2;
    const LANG = 'likehack-plugin';
    const PAGE = 'likehack';
    const LIKEHACK_API_URL = 'http://likehack.com/api/feed/get_stream?';
    const LIKEHACK_API_PAID_URL = 'http://likehack.com/api/feed/user_paid?';
    const LIKEHACK_API_VALIDATE_URL = 'http://likehack.com/api/feed/validate?';
    const CACHE_EXPIRE = 3600;
    const LIKEHACK_ICONS_URL = 'http://likehack.com/images/widget/';

    function __construct()
    {
        if (is_admin())
        {
            add_action('admin_init', array($this, 'on_admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));

            add_filter('plugin_action_links', array($this, 'in_settings_link'), 10, 2);
        }

        add_action('wp_print_styles', array($this, 'on_wp_print_styles'));

        add_action('widgets_init', array($this, 'register_likehack_widget_callback'));

        load_plugin_textdomain('likehack-plugin', false, LIKEHACK_PLUGIN_DIR . '/languages/');
    }

    function register_likehack_widget_callback() 
    {
        register_widget('LikeHack_Widget');
    }

    function on_admin_init()
    {
        register_setting('likehack', 'likehack_api_key', array($this, 'try_token'));
        register_setting('likehack', 'likehack_links_to_show');
        register_setting('likehack', 'likehack_use_facebook');
        register_setting('likehack', 'likehack_use_twitter');
    }

    function admin_menu()
    {
        add_options_page(__('LikeHack', self::LANG), __('LikeHack', self::LANG), 8, self::PAGE, array(&$this, 'options_page_view'));
    }

    function on_plugin_activate()
    {
        add_option('likehack_api_key', '', false, 'yes');
        add_option('likehack_token_approved', 0, false, 'yes');
        add_option('likehack_links_to_show', 'curated', false, 'yes');
        add_option('likehack_use_facebook', 1, false, 'yes');
        add_option('likehack_use_twitter', 1, false, 'yes');
    }

    function on_plugin_deactivate()
    {
//        delete_option('likehack_api_key');
//        delete_option('likehack_token_approved');
//        delete_option('likehack_links_to_show');
//        delete_option('likehack_use_facebook');
//        delete_option('likehack_use_twitter');
    }

    function in_settings_link($links, $file)
    {
        if ($file == plugin_basename(LIKEHACK_PLUGIN_DIR . '/likehack.php'))
        {
            $links[] = '<a href="options-general.php?page=' . self::PAGE . '">' . __('Settings', self::LANG) . '</a>';
        }

        return $links;
    }

    function on_wp_print_styles()
    {
        if (!is_admin())
        {
            if (!defined('LIKEHACK_CUSTOM_STYLE') OR !LIKEHACK_CUSTOM_STYLE)
            {
                wp_enqueue_style('likehack', LIKEHACK_PLUGIN_URL . 'likehack.css', false, self::VERSION);
            }
        }
    }

    static function url_params(Array $params)
    {
        $pice = array();
        foreach ($params as $k => $v)
        {
            $pice[] = $k . '=' . urlencode($v);
        }

        return implode('&', $pice);
    }

    static function get(Array $array = array(), $key, $default = NULL, $allow_empty = FALSE)
    {
        if (!$allow_empty)
        {
            $return_val = ( isset($array[$key]) AND $array[$key] );
        }
        else
        {
            $return_val = isset($array[$key]);
        }

        return ($return_val) ? $array[$key] : $default;
    }

    static function option($key, $default = NULL, $allow_empty = FALSE)
    {
        $option = get_option("likehack_$key");

        if (!$allow_empty)
        {
            return $option ? $option : $default;
        }
        else
        {
            return $option;
        }
    }

    function try_token($token)
    {
        wp_cache_flush();

        $token = sanitize_text_field($token);

        try
        {
            //'@' - is ugly, but we can refuse cURL
            $response = @file_get_contents(self::LIKEHACK_API_VALIDATE_URL . self::url_params(array('auth_token' => $token, 'tool' => 'wordpress_plugin')));

            if (!$response OR FALSE === strpos($http_response_header[0], '200'))
            {
                throw new Exception(__('We are confused, but we can\'t show you any links', self::LANG));
            }
        }
        catch(Exception $e)
        {
            update_option('likehack_token_approved', 0);

            add_settings_error('likehack', 'likehack_api_key', __('Something went wrong. Try again!', self::LANG), 'error');

            return $token;
        }

        if (!get_option('likehack_token_approved'))
        {
            update_option('likehack_token_approved', 1);
        }

        return $token;
    }

    static function get_user_token()
    {
        return get_option('likehack_api_key');
    }

    static function view($view_name, Array $args = array())
    {
        try
        {
            extract($args, EXTR_SKIP);
            include LIKEHACK_PLUGIN_PATH . 'view/' . $view_name . '-view.php';
        }
        catch (Error $e)
        {
            echo $e->getMessage();
        }
    }

    function options_page_view()
    {
        self::view('options');
    }
    
    static function check_paid()
    {  
      
        $query_args = array('auth_token' => self::get_user_token());

        $cache_grp = 'likehack';
        $cache_key = 'paid';
      
        if ( ! $response = wp_cache_get($cache_key, $cache_grp, self::CACHE_EXPIRE) OR FALSE === ($response = unserialize($response)) )
        {
          
          try
          {
              //'@' - is ugly, but we can refuse cURL
              $response = @file_get_contents(self::LIKEHACK_API_PAID_URL . self::url_params($query_args));

              if (!$response OR FALSE === strpos($http_response_header[0], '200'))
              {
                  throw new Exception(__('We are confused, but we can\'t show you any links...', self::LANG));
              }
          }
          catch(Exception $e)
          {
              $response = new WP_Error('likehack_request', $e->getMessage());

              wp_cache_add($cache_key, serialize($response), $cache_grp, self::CACHE_EXPIRE);

              return $response;
          }
          
          $response = json_decode($response, true);
          wp_cache_add($cache_key, serialize($response), $cache_grp, self::CACHE_EXPIRE);

          return $response;
        }
        else
        {
            return $response;
        }
        
    }
    
    static function get_links($query_args = array())
    {
        $query_args = array_merge(array(
            'limit'          => 5,
            'content_type'   => 'all',
            'feed_type'      => 'all',
            'auth_token'     => self::get_user_token()
        ), $query_args);

        $cache_grp = 'likehack';
        $cache_key = 'links';

        if ( ! $response = wp_cache_get($cache_key, $cache_grp, self::CACHE_EXPIRE) OR FALSE === ($response = unserialize($response)) )
        {
            try
            {
                //'@' - is ugly, but we can refuse cURL
                $response = @file_get_contents(self::LIKEHACK_API_URL . self::url_params($query_args));

                if (!$response OR FALSE === strpos($http_response_header[0], '200'))
                {
                    throw new Exception(__('We are confused, but we can\'t show you any links...', self::LANG));
                }
            }
            catch(Exception $e)
            {
                $response = new WP_Error('likehack_request', $e->getMessage());

                wp_cache_add($cache_key, serialize($response), $cache_grp, self::CACHE_EXPIRE);

                return $response;
            }

            $response = json_decode($response, true);
            wp_cache_add($cache_key, serialize($response), $cache_grp, self::CACHE_EXPIRE);

            return $response;
        }
        else
        {
            return $response;
        }
    }
}