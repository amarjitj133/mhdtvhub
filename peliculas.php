<?php
/*
* -------------------------------------------------------------------------------------
* @author: Doothemes
* @author URI: https://doothemes.com/
* @copyright: (c) 2021 Doothemes. All rights reserved
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

// Reaction Data
$react_like  = get_post_meta($post->ID, '_react_like', true) ?: 0;
$react_love  = get_post_meta($post->ID, '_react_love', true) ?: 0;
$react_wow   = get_post_meta($post->ID, '_react_wow', true) ?: 0;
$react_angry = get_post_meta($post->ID, '_react_angry', true) ?: 0;

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
.single_tabs { margin-bottom: 0 !important; border: none !important; }
.module_single_ads { margin-top: 10px; margin-bottom: 10px; }

/* MOBILE ONLY REACTIONS & PC STAR HIDE */
.mobile-reactions { display: none; } /* Default Chhupa hua */

@media only screen and (max-width: 768px) {
    .starstruck-container, .starstruck-main { display: none !important; } /* Mobile par Star Review hataya */
    .mobile-reactions { 
        display: block; 
        margin: 15px 0; 
        padding: 10px;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
    }
    .reaction-container {
        display: flex;
        flex-direction: row; /* Ek line mein */
        justify-content: space-around;
        align-items: center;
    }
    .react-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
    }
    .react-btn svg {
        width: 35px; /* Perfect size for mobile */
        height: 35px;
        margin-bottom: 5px;
    }
    .react-btn i {
        font-style: normal;
        font-size: 11px;
        color: #bbb;
    }
}
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

                <div class="mobile-reactions">
                    <div class="reaction-container">
                        <div class="react-btn" onclick="hitReaction('like')">
                            <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="24" fill="#3b5998"/><path d="M12 22h6v14h-6zM38 24c0-2.21-1.79-4-4-4h-8.63l1.3-6.24.03-.31c0-.4-.16-.77-.42-1.03L25.26 11 18.63 17.63c-.39.39-.63.93-.63 1.52V34c0 1.1.9 2 2 2h11c.83 0 1.54-.5 1.84-1.22l4.91-11.46c.16-.4.25-.84.25-1.32v-3z" fill="#fff"/></svg>
                            <i id="count-like"><?php echo $react_like; ?></i>
                        </div>
                        <div class="react-btn" onclick="hitReaction('love')">
                            <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="24" fill="#e0245e"/><path d="M24 38.5l-2.15-1.95C14.2 29.35 9 24.64 9 18.88 9 14.16 12.71 10.45 17.43 10.45c2.66 0 5.22 1.24 6.57 3.2 1.35-1.96 3.91-3.2 6.57-3.2 4.72 0 8.43 3.71 8.43 8.43 0 5.76-5.2 10.47-12.85 17.68L24 38.5z" fill="#fff"/></svg>
                            <i id="count-love"><?php echo $react_love; ?></i>
                        </div>
                        <div class="react-btn" onclick="hitReaction('wow')">
                            <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="24" fill="#f7b928"/><circle cx="16.5" cy="19.5" r="2.5" fill="#fff"/><circle cx="31.5" cy="19.5" r="2.5" fill="#fff"/><ellipse cx="24" cy="31" rx="6" ry="8" fill="#fff"/></svg>
                            <i id="count-wow"><?php echo $react_wow; ?></i>
                        </div>
                        <div class="react-btn" onclick="hitReaction('angry')">
                            <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="24" fill="#f14e32"/><path d="M15 21l6 2M33 21l-6 2" stroke="#fff" stroke-width="2" stroke-linecap="round"/><path d="M16 32s4-3 8-3 8 3 8 3" stroke="#fff" stroke-width="2" stroke-linecap="round"/><circle cx="17" cy="24" r="2" fill="#fff"/><circle cx="31" cy="24" r="2" fill="#fff"/></svg>
                            <i id="count-angry"><?php echo $react_angry; ?></i>
                        </div>
                    </div>
                </div>

                <div class="sgeneros">
                <?php echo get_the_term_list($post->ID, 'genres', '', '', ''); ?>
                </div>
            </div>
        </div>

        <script>
        function hitReaction(type) {
            let el = document.getElementById('count-' + type);
            el.innerText = parseInt(el.innerText) + 1;
            // Real-time update feedback
            el.style.color = "#4080ff";
        }
        </script>

        <?php 
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
