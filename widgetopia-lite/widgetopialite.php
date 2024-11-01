<?php

/**
 * @package Widgetopia-Lite
 * @author Bouzid Nazim Zitouni
 * @version 1.15
 */
/*
Plugin Name: Widgetopia-Lite
Plugin URI: http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/
Description: Widgetopia is the best collection of widgets for wordpress, it combines all the most wanted wordpress widgets in one plugin, including the most viewed posts, Top search keywords, "Also in this category", and much more. The full version adds even more widgets, with featured images for selected widgets, and customizable widget settings. More widgets are coming with every update, you can also request new widgets that can be considered for the next update.
Author: Bouzid Nazim Zitouni
Version: 1.15
Author URI: http://angrybyte.com
*/

/*

*/
add_option("widgettopiatable", '0', 'created widgettopia tables', 'yes');
add_filter('the_content', 'widgetopiacounters');





///////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////

class widgetfeatuser extends WP_Widget
{
    function widgetfeatuser()
    {
        $widget_ops = array('classname' => 'widgetfeatuser', 'description' =>
            'Featured User');
        $this->WP_Widget('widgetfeatuser', 'Widgetopia: Featured User', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => 'Featured User'));
        $title = $instance['title'];


?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
   
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;
        extract($args);
        global $post;
        $pfx = $wpdb->prefix;
        $ry=$pres->user_registered;
        $dt = new DateTime($ry);
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT *
FROM {$pfx}users
 order by RAND()
LIMIT 1");

        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $uid = $pres->ID;
            $commcount = $wpdb->get_var("select count(comment_ID) from {$pfx}comments where user_id =$uid ");

            $posttitle = get_the_title($pres->comment_post_ID);
            $postlink = get_permalink($pres->comment_post_ID);
            $ava = get_avatar($uid);
            //$srch= $pres->search;
            $oot .= "<table>
 <tr><td>
  {$ava}</td><td>Name:{$pres->display_name} <br />
 URL: {$pres->user_url}<br />Member since: {$dt}<br />commented on: {$commcount} Posts</td></tr></table>";
        }
        echo $oot ;

        echo  "<font style='font-size:3px;'>widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetfeatuser");'));


////////////////////////////////////////////////////////////////


class widgetrandcomm extends WP_Widget
{
    function widgetrandcomm()
    {
        $widget_ops = array('classname' => 'widgetrandcomm', 'description' =>
            'Random Comment');
        $this->WP_Widget('widgetrandcomm', 'Widgetopia: Random Comment', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => 'Random Comment'));
        $title = $instance['title'];


?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>

   
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $res = $wpdb->get_results("SELECT comment_author, comment_post_ID, comment_content
FROM {$pfx}comments where comment_approved = 1
 order by RAND()
LIMIT 1");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->comment_post_ID);
            $postlink = get_permalink($pres->comment_post_ID);
            //$srch= $pres->search;
            $oot .= "<strong>$pres->comment_author :</strong> $pres->comment_content .  <small><a  href='$postlink' title='$posttitle' >$posttitle</a></small>";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetrandcomm");'));


///////////////////////////////////////////////////////////////////////////////


class widgetrecentsear extends WP_Widget
{
    function widgetrecentsear()
    {
        $widget_ops = array('classname' => 'widgetrecentsear', 'description' =>
            'Recent Search keywords');
        $this->WP_Widget('widgetrecentsear', 'Widgetopia: Recent keywords', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Recent Search keywords', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
   
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT id, post_id, search
FROM {$pfx}widgetopia where search <>''
 order by id desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $srch = $pres->search;
            $oot .= "<a  href='$postlink' title='$posttitle' >$srch</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetrecentsear");'));


/////////////////////////////////////////////////////
class searchtags4page extends WP_Widget
{
    function searchtags4page()
    {
        $widget_ops = array('classname' => 'searchtags4page', 'description' =>
            'Displays the most common search queries your visitors used to find the current post');
        $this->WP_Widget('searchtags4page',
            'Widgetopia: Post search tags', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => 'Search tags',
            'n' => '10'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
   
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        
        global $wpdb, $post;

        $pfx = $wpdb->prefix;
        $thisid = $post->ID;


        $res = $wpdb->get_results("SELECT post_id, count( id ) , search
FROM {$pfx}widgetopia
WHERE post_id = $thisid AND `search` <>''
GROUP BY search order by 2 desc 
LIMIT {$instance['n']}");

        

        
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = $pres->post_title;
            $srch=$pres->search;
            $oot .= "<b>$srch </b> - ";
            
        }
        if (count($res) > 0){
            echo $before_widget;
            
               echo substr($oot ,0,strlen($oot)- 2) ;

        echo  "<font style='font-size:3px;'>widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
        }
     
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("searchtags4page");'));
//////////////////////////////////////////////////////////////////////////

class widgetsearcloud extends WP_Widget
{
    function widgetsearcloud()
    {
        $widget_ops = array('classname' => 'widgetsearcloud', 'description' =>
            'Widgetopia: Search cloud, looks like the tag cloud but for google search keywords');
        $this->WP_Widget('widgetsearcloud', 'Widgetopia: Search cloud', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => ' Search cloud',
            'n' => '20'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dt->modify("-30 days");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) as zecount , search
FROM {$pfx}widgetopia WHERE search <> '' 
GROUP BY search order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        $maxs = 0;
        shuffle($res);
        foreach ($res as $pres) {
            if ($pres->zecount > $max) {
                $max = $pres->zecount;
            }
        }
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $srch = $pres->search;
            $sz = 24 * $pres->zecount / $max + 8;
            $fs = ($pres->zecount) / $max * 10;
            //$oot .="<a href='index.php?lst=0&gp=1&gtag={$pres->id}'> <font size={$fs}> {$pres->tag}</font></a>";
            $oot .= "<a  href='$postlink' title='$posttitle' ><font style='font-size:{$sz}px;'>$srch</font></a> ";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetsearcloud");'));


/////////////////////////////////////////////////////


class widgettopkeytoday extends WP_Widget
{
    function widgettopkeytoday()
    {
        $widget_ops = array('classname' => 'widgettopkeytoday', 'description' =>
            'Today`s top google search keywords');
        $this->WP_Widget('widgettopkeyweek', 'Widgetopia: today`s top keywords', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Today`s top keywords', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a> 
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");

        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) , search
FROM {$pfx}widgetopia
WHERE date( dtime ) = '$dt' AND `search` <>''
GROUP BY post_id order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $srch = $pres->search;
            $oot .= "<a  href='$postlink' title='$posttitle' >$srch</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgettopkeytoday");'));


/////////////////////////////////////////////////////


class widgettopkeyweek extends WP_Widget
{
    function widgettopkeyweek()
    {
        $widget_ops = array('classname' => 'widgettopkeyweek', 'description' =>
            'Week`s top google search keywords');
        $this->WP_Widget('widgettopkeyweek', 'Widgetopia: week`s top keywords', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Week`s top keywords', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a> 
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dt->modify("-7 days");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) , search
FROM {$pfx}widgetopia
WHERE date( dtime ) >= '$dt' AND `search` <>''
GROUP BY post_id order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $srch = $pres->search;
            $oot .= "<a  href='$postlink' title='$posttitle' >$srch</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgettopkeyweek");'));


//////////////////////////////////////////////////


class widgettopkeymonth extends WP_Widget
{
    function widgettopkeymonth()
    {
        $widget_ops = array('classname' => 'widgettopkeymonth', 'description' =>
            'Month`s top google search keywords');
        $this->WP_Widget('widgettopkeymonth', 'Widgetopia: Month`s top keywords', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Month`s top keywords', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dt->modify("-30 days");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) , search
FROM {$pfx}widgetopia
WHERE date( dtime ) >= '$dt' AND `search` <>''
GROUP BY post_id order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $srch = $pres->search;
            $oot .= "<a  href='$postlink' title='$posttitle' >$srch</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgettopkeymonth");'));


//////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////

class widgetvisitcounter extends WP_Widget
{
    function widgetvisitcounter()
    {
        $widget_ops = array('classname' => 'widgetvisitcounter', 'description' =>
            'Hits counter');
        $this->WP_Widget('widgetvisitcounter', 'Widgetopia: Hits counter', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => 'Hits counter'));
        $title = $instance['title'];
        // $n = $instance['n'];


?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
   
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;
        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dto = $dt->format('Y/m/d');
        $dayvis = $wpdb->get_var("SELECT  count( id )FROM {$pfx}widgetopia WHERE date( dtime ) = '$dto' ");
        $dt->modify("-30 days");
        $dto = $dt->format('Y/m/d');
        $montvis = $wpdb->get_var("SELECT  count( id )FROM {$pfx}widgetopia WHERE date( dtime ) >= '$dto' ");
        $allvis = $wpdb->get_var("SELECT  count( id )FROM {$pfx}widgetopia ");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        //foreach($res as $pres){

        $oot .= "Today: $dayvis<br />This Month: $montvis <br />All time: $allvis";
        //}
        echo $oot;
        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetvisitcounter");'));

/////////////////////////////////////////////////////////////////////////


class widgetmostviewedtoday extends WP_Widget
{
    function widgetmostviewedtoday()
    {
        $widget_ops = array('classname' => 'widgetmostviewedtoday', 'description' =>
            'Most Viewed posts today');
        $this->WP_Widget('widgetmostviewedtoday', 'Widgetopia: Most Viewed today', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Most Viewed posts Today', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a> 
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");

        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) 
FROM {$pfx}widgetopia
WHERE date( dtime ) = '$dt'
GROUP BY post_id order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $oot .= "<a  href='$postlink'>$posttitle</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetmostviewedtoday");'));


////////////////////////////////////////////////////////////////////////////


class widgetmostviewedweek extends WP_Widget
{
    function widgetmostviewedweek()
    {
        $widget_ops = array('classname' => 'widgetmostviewedweek', 'description' =>
            'Most Viewed posts This week');
        $this->WP_Widget('widgetmostviewedweek', 'Widgetopia: Most Viewed week', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Most Viewed posts This Week', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>  
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
       
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dt->modify("-7 days");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) 
FROM {$pfx}widgetopia
WHERE date( dtime ) >= '$dt'
GROUP BY post_id order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $oot .= "<a  href='$postlink'>$posttitle</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetmostviewedweek");'));

//////////////////////////////////////////////////////////////////////////


class widgetmostviewedmonth extends WP_Widget
{
    function widgetmostviewedmonth()
    {
        $widget_ops = array('classname' => 'widgetmostviewedmonth', 'description' =>
            'Most Viewed posts This month');
        $this->WP_Widget('widgetmostviewedmonth', 'Widgetopia: Most Viewed-month', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' =>
            ' Most Viewed posts This month', 'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a> 
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
   
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $dt = new DateTime("now");
        $dt->modify("-30 days");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("SELECT post_id, count( id ) 
FROM {$pfx}widgetopia
WHERE date( dtime ) >= '$dt'
GROUP BY post_id order by 2 desc 
LIMIT {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_id);
            $postlink = get_permalink($pres->post_id);
            $oot .= "<a  href='$postlink'>$posttitle</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("widgetmostviewedmonth");'));


///////////////////////////////////////////////////////////////////////////////////


class randompic extends WP_Widget
{
    function randompic()
    {
        $widget_ops = array('classname' => 'randompic', 'description' =>
            'Returns a random pictures from your posts');
        $this->WP_Widget('randompic', 'Widgetopia: Random Picture', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => 'Random Picture'));
        $title = $instance['title'];

?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a> 
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        

        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;

        $pfx = $wpdb->prefix;
        $res = $wpdb->get_results("select guid,post_parent from {$pfx}posts where post_mime_type = 'image/jpeg' order by RAND() limit 1");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = get_the_title($pres->post_parent);
            $postlink = get_permalink($pres->post_parent);
            $oot .= "<a  href='$postlink'><img style ='width:250px' src='$pres->guid' title='$posttitle' alt='$posttitle' /></a>";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("randompic");'));


//////////////////////////////////////////////////////////////////////////


class randompost extends WP_Widget
{
    function randompost()
    {
        $widget_ops = array('classname' => 'randompost', 'description' =>
            'A list of random posts');
        $this->WP_Widget('randompost', 'Widgetopia: Random Post', $widget_ops);
    }
    function form($instance)
    {
        $instance = wp_parse_args((array )$instance, array('title' => 'Random Posts',
            'n' => '5'));
        $title = $instance['title'];
        $n = $instance['n'];
?>
Settings are only available in the <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>full version</a>
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        global $wpdb;
        //extract($args);
        $pfx = $wpdb->prefix;
        $res = $wpdb->get_results("select ID, post_title, guid from {$pfx}posts where post_status = 'publish' AND post_type='post' order by RAND() limit {$instance['n']}");
        $oot = "{$before_title}{$instance['title']}{$after_title}";
        foreach ($res as $pres) {
            $posttitle = $pres->post_title;
            $postlink = get_permalink($pres->ID);
            $oot .= "<a  href='$postlink'>$posttitle</a><br />";
        }
        echo $oot;

        echo  "<font style='font-size:3px;'><br />widgetopia <a href='http://angrybyte.com/wordpress-plugins/wordpress-widget-plugin-widgetopia/'>wordpress widgets</a></font>" . $after_widget ;
    }
}
add_action('widgets_init', create_function('',
    'return register_widget("randompost");'));


////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////
function googling()
{
    $referer = $_SERVER['HTTP_REFERER'];

    //find the search query from google that brought them here
    $qref = strpos($referer, 'www.google');

    if ($qref != '') {
        $qstart = strpos($referer, 'q=');
        $qend = strpos($referer, '&', $qstart);
        $qtext = substr($referer, $qstart + 2, $qend - $qstart - 2);
        $qtext = urldecode($qtext);
        $qtext = str_replace('+', ' ', $qtext);
        return $qtext;
    }
    //return "";
}


function widgetopiacounters($contents)
{
    global $wpdb, $post;
    // $referer = $_SERVER['HTTP_REFERER'];
    //
    //    //find the search query from google that brought them here
    //    $qref = strpos($referer, 'http://www.google');
    //
    //    if ($qref == 1) {
    //        $qstart = strpos($referer, 'q=');
    //        $qend = strpos($referer, '&', $qstart);
    //        $qtext = substr($referer, $qstart + 2, $qend - $qstart - 2);
    //        $qtext = urldecode($qtext);
    //        $qtext = str_replace('+', ' ', $qtext);
    //
    //    }
    $pfx = $wpdb->prefix;
    if (get_option("widgettopiatable") == 0) {
        $wpdb->query("CREATE TABLE {$pfx}widgetopia (
`id` INT NOT NULL AUTO_INCREMENT ,
`post_id` INT NOT NULL ,
`search` TEXT NOT NULL ,
`dtime` DATETIME NOT NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE = InnoDB");
        update_option('widgettopiatable', 1);
    }

    if (is_single()) {
        $searched = googling(); //update with google search for script
        $dt = new DateTime("now");
        $dt = $dt->format('Y/m/d h:i:s');
        $pid = $post->ID;
        $wpdb->query("INSERT INTO `{$pfx}widgetopia` (
`id` ,
`post_id` ,
`search` ,
`dtime` 
)
VALUES (
NULL , '$pid', '$searched', '$dt'
)");
$cleaner=rand(1,100); //cleanup records older that 1 year to keep things fresh
if($cleaner==50){
     $dt = new DateTime("now");
        $dt->modify("-356 days");
        $dt = $dt->format('Y/m/d');
        $res = $wpdb->get_results("delete
FROM {$pfx}widgetopia
WHERE date( dtime ) < '$dt' ");
}

    }
    return $contents;
}

?>