<?php
/*
* -------------------------------------------------------------------------------------
* @author: Doothemes
* @author URI: https://doothemes.com/
* @aopyright: (c) 2021 Doothemes. All rights reserved
* -------------------------------------------------------------------------------------
*
* @since 2.5.0
*
*/

// All Postmeta
$classlinks = new DooLinks;
$postmeta = doo_postmeta_movies($post->ID);
$adsingle = doo_compose_ad('_dooplay_adsingle');
// Movies Meta data
$trailer = doo_isset($postmeta,'youtube_id');
$pviews  = doo_isset($postmeta,'dt_views_count');
$player  = doo_isset($postmeta,'players');
$player  = maybe_unserialize($player);
$images  = doo_isset($postmeta,'imagenes');
$tviews  = ($pviews) ? sprintf( __d('%s Views'), $pviews) : __d('0 Views');
//  Image
$dynamicbg  = dbmovies_get_rand_image($images);
// Options
$player_ads = doo_compose_ad('_dooplay_adplayer');
$player_wht = dooplay_get_option('playsize','regular');
// Sidebar
$sidebar = dooplay_get_option('sidebar_position_single','right');
// Dynamic Background
if(dooplay_get_option('dynamicbg') == true) { ?>
<style>
#dt_contenedor {
    background-image: url(<?php echo $dynamicbg; ?>);
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-position: 50% 0%;
}
/* CSS to ensure no gap remains from removed tabs */
.single_tabs { margin-bottom: 0 !important; border: none !important; }
.module_single_ads { margin-top: 10px; margin-bottom: 10px; }
</style>
<?php } ?>
<?php get_template_part('inc/parts/single/report-video'); ?>

<?php DooPlayer::viewer_big($player_wht, $player_ads, $dynamicbg); ?>

<div id="single" class="dtsingle" itemscope itemtype="http://schema.org/Movie">
    <div id="edit_link"></div>
    
    <?php if(have_posts()) :while (have_posts()) : the_post(); ?>
    <div class="content <?php echo $sidebar; ?>">

        <?php DooPlayViews::Meta($post->ID); ?>

        <?php DooPlayer::viewer($post->ID, 'movie', $player, $trailer, $player_wht, $tviews, $player_ads, $dynamicbg); ?>
        
        <div class="sheader">
            <div class="poster">
                <img itemprop="image" src="<?php echo dbmovies_get_poster($post->ID,'medium'); ?>" alt="<?php the_title(); ?>">
            </div>
            <div class="data">
                <h1><?php the_title(); ?></h1>
                <div class="extra">
                <?php
                if($d = doo_isset($postmeta,'tagline')) echo "<span class='tagline'>{$d}</span>";
                if($d = doo_isset($postmeta,'release_date')) echo "<span class='date' itemprop='dateCreated'>".doo_date_compose($d,false)."</span>";
                if($d = doo_isset($postmeta,'Country')) echo "<span class='country'>{$d}</span>";
                if($d = doo_isset($postmeta,'runtime')) echo "<span itemprop='duration' class='runtime'>{$d} ".__d('Min.')."</span>";
                if($d = doo_isset($postmeta,'Rated')) echo "<span itemprop='contentRating' class='C{$d} rated'>{$d}</span>";
                ?>
                </div>
                <?php echo do_shortcode('[starstruck_shortcode]'); ?>
                <div class="sgeneros">
                <?php echo get_the_term_list($post->ID, 'genres', '', '', ''); ?>
                </div>
            </div>
        </div>

        <?php 
        /* Removing the entire Tab section if it's empty to prevent white space/gaps.
           We only show this bar if user control is enabled or Links exist.
        */
        $has_links = doo_here_links($post->ID);
        $user_can_edit = (is_user_logged_in() && doo_is_true('permits','eusr'));

        if($has_links || $user_can_edit): ?>
        <div class="single_tabs">
            <?php if($user_can_edit){ ?>
            <div class="user_control">
                <?php dt_list_button($post->ID); dt_views_button($post->ID); ?>
            </div>
            <?php } ?>
            <ul id="section" class="smenu idTabs">
                <?php if($has_links) echo '<li><a href="#linksx">'.__d('Links').'</a></li>'; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if($adsingle) echo '<div class="module_single_ads">'.$adsingle.'</div>'; ?>

        <?php if(DOO_THEME_DOWNLOAD_MOD) get_template_part('inc/parts/single/links'); ?>

        <?php doo_social_sharelink($post->ID); ?>

        <?php if(DOO_THEME_RELATED) get_template_part('inc/parts/single/relacionados'); ?>

        <?php get_template_part('inc/parts/comments'); ?>

        <?php doo_breadcrumb( $post->ID, 'movies', __d('Movies'), 'breadcrumb_bottom' ); ?>

    </div>
    <?php endwhile; endif; ?>

    <div class="sidebar <?php echo $sidebar; ?> scrolling">
        <?php dynamic_sidebar('sidebar-movies'); ?>
    </div>
</div>