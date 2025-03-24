<?php
/**
 *  Define Agent_Meta_Parameters Class.
 *
 * @package pixelscodex
 */

namespace Pixelscodex;

/**
 * Class defining meta parameters for instanciation.
 */
class Agent_Meta_Parameters {
	/**
	 * Name of the meta sanitized to get the slug/id.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Title / description of the meta.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Type of the meta ( text / image / etc. )
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Options used to create the meta edit control.
	 *
	 * @var array
	 */
	public $options;

	/**
	 * Initialize internal variables.
	 *
	 * @param  string $type Type of meta.
	 * @param  string $name Name of the meta.
	 * @param  string $title Title/ description of the Meta.
	 * @param  array  $options Options of the meta.
	 */
	public function __construct(
		$type,
		$name,
		$title,
		$options = array()
	) {
		$this->name    = $name;
		$this->title   = $title;
		$this->type    = $type;
		$this->options = $options;
	}
}
