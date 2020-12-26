<?php
header("access-control-allow-credentials:true");
header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
 $siteUrl = parse_url(get_site_url());
header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
header("Content-Type:application/json;charset=utf-8");

$total_stars = is_numeric(get_option('kksr_stars')) ? get_option('kksr_stars') : 5;
$stars = is_numeric($_POST['rating']) && ((int)$_POST['rating']>0) && ((int)$_POST['rating']<=$total_stars) ? $_POST['rating']: 0;
$ip = $_SERVER['REMOTE_ADDR'];
$Ids = explode(',', $_POST['post_id']);

foreach($Ids as $pid) :
    $ratings = get_post_meta($pid, '_kksr_ratings', true) ? get_post_meta($pid, '_kksr_ratings', true) : 0;
    $casts = get_post_meta($pid, '_kksr_casts', true) ? get_post_meta($pid, '_kksr_casts', true) : 0;

    if($stars==0 && $ratings==0){
        $Response[$pid]['legend'] = get_option('kksr_init_msg');
        $Response[$pid]['disable'] = 'false';
        $Response[$pid]['fuel'] = '0';
        do_action('kksr_init', $pid, false, false);
    }else{
        $nratings = $ratings + ($stars/($total_stars/5));
        $ncasts = $casts + ($stars>0);
        $avg = $nratings && $ncasts ? number_format((float)($nratings/$ncasts), 2, '.', '') : 0;
        $per = $nratings && $ncasts ? number_format((float)((($nratings/$ncasts)/5)*100), 2, '.', '') : 0;
        $Response[$pid]['disable'] = 'false';
        if($stars)
        {
            $Ips = get_post_meta($pid, '_kksr_ips', true) ? unserialize(base64_decode(get_post_meta($pid, '_kksr_ips', true))) : array();
            if(!in_array($ip, $Ips))
            {
                $Ips[] = $ip;
            }
            $ips = base64_encode(serialize($Ips));
            update_post_meta($pid, '_kksr_ratings', $nratings);
            update_post_meta($pid, '_kksr_casts', $ncasts);
            update_post_meta($pid, '_kksr_ips', $ips);
            update_post_meta($pid, '_kksr_avg', $avg);
            $Response[$pid]['disable'] = get_option('kksr_unique') ? 'true' : 'false';
            do_action('kksr_rate', $pid, $stars, $ip);
        }
        else
        {
            do_action('kksr_init', $pid, number_format((float)($avg*($total_stars/5)), 2, '.', '').'/'.$total_stars, $ncasts);
        }
        
    }
    //$Response[$pid]['success'] = true;
    $best = (int) get_option('kksr_stars');
    $score = get_post_meta($pid, '_kksr_ratings', true) ? ((int) get_post_meta($pid, '_kksr_ratings', true)) : 0;
    $votes = get_post_meta($pid, '_kksr_casts', true) ? ((int) get_post_meta($pid, '_kksr_casts', true)) : 0;
    $avg = $score && $votes ? round((float)(($score/$votes)*($best/5)), 1) : 0;
    $per = $score && $votes ? round((float)((($score/$votes)/5)*100), 2) : 0;
    $ratings = compact('best', 'score', 'votes', 'avg', 'per');
    $leg = '';
    if ($ratings['score']) {         
        $leg = str_replace('[total]', '<span itemprop="ratingCount">'.$ratings['votes'].'</span>', get_option('kksr_legend'));
        $leg = str_replace('[avg]', '<span itemprop="ratingValue">'.$ratings['avg'].'</span>', $leg);
        $leg = str_replace('[per]',  $ratings['per'] .'%', $leg);
        $leg = str_replace('[s]', $ratings['votes'] == 1 ? '' : 's', $leg);
        $leg = str_replace('[best]', $ratings['best'], $leg);
    }
endforeach;
$rating = $_POST['rating'];
$ratings = array(
    'rating' => $rating,
    'average' => $ratings['avg'],
    'votes' =>  $ratings['votes'],
    'best' => $ratings['best'],
    'percent' => $ratings['per'].'%'
);
echo json_encode($ratings);
wp_die();
