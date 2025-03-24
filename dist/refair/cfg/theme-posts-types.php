<?php
/**
 * Definition of meta data at theme level.
 *
 * @package refair
 */

defined( 'ABSPATH' ) || exit;

use Pixelscodex\Agent_Meta_Parameters;

/*
------------------------------------------------------------------------------------------------------------------
													POSTS META
------------------------------------------------------------------------------------------------------------------
*/

/* Post Type*/
$agent->post_metas_factory->create( new Agent_Meta_Parameters( 'button', 'external_link', 'Lien extérieur' ) );
