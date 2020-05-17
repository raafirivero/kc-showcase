<?php
/*
Plugin Name: KineCommunity Video Showcase
Plugin URI: https://github.com/raafirivero/kc-showcase
Author: Raafi Rivero
Author URI: http://raafirivero.com
Description: Custom post type for video showcase on KineCommunity
Version: 1.3
Textdomain: kinecommunity
License: GPLv2
*/

include "includes/kc-videopost.php";
include "includes/kc-tax.php";
include "includes/kc-save.php";
include "includes/kc-json.php";
include "includes/kc-showroute.php";
include "includes/kc-usersub.php";

# custom post part forked from Dave Rupert 
# credit: https://gist.github.com/davatron5000/848232
# middle parts by me
# on saving posts: https://toolset.com/forums/topic/imposible-to-hook-on-custom-post-type-save-or-update/
# taxonomy parts found online somewhere else