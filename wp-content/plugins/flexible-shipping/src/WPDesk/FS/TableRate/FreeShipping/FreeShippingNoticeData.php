<?php

namespace WPDesk\FS\TableRate\FreeShipping;

/**
 * Can provide free shipping data.
 */
class FreeShippingNoticeData implements \JsonSerializable {

	const SHOW_PROGRESS_BAR       = 'show_progress_bar';
	const PERCENTAGE              = 'percentage';
	const THRESHOLD               = 'threshold';
	const THRESHOLD_DISPLAY_VALUE = 'threshold_display_value';
	const ZERO_DISPLAY_VALUE      = 'zero_display_value';
	const MISSING_AMOUNT          = 'missing_amount';
	const NOTICE_TEXT             = 'notice_text';
	const BUTTON_URL              = 'button_url';
	const BUTTON_LABEL            = 'button_label';
	const META_DATA               = 'meta_data';

	/**
	 * @var bool
	 */
	private $show_progress_bar;

	/**
	 * @var float
	 */
	private $percentage;

	/**
	 * @var float
	 */
	private $threshold;

	/**
	 * @var string
	 */
	private $threshold_display_value;

	/**
	 * @var string
	 */
	private $zero_display_value;

	/**
	 * @var float
	 */
	private $missing_amount;

	/**
	 * @var string
	 */
	private $notice_text;

	/**
	 * @var string
	 */
	private $button_url;

	/**
	 * @var string
	 */
	private $button_label;

	/**
	 * @var array
	 */
	private $meta_data;

	/**
	 * @param bool   $show_progress_bar
	 * @param float  $percentage
	 * @param float  $threshold
	 * @param string $threshold_display_value
	 * @param string $zero_display_value
	 * @param float  $missing_amount
	 * @param string $notice_text
	 * @param string $button_url
	 * @param string $button_label
	 * @param array  $meta_data
	 */
	public function __construct(
		bool $show_progress_bar = false,
		float $percentage = 0.0,
		float $threshold = 0.0,
		string $threshold_display_value = '',
		string $zero_display_value = '',
		float $missing_amount = 0.0,
		string $notice_text = '',
		string $button_url = '',
		string $button_label = '',
		array $meta_data = []
	) {
		$this->show_progress_bar       = $show_progress_bar;
		$this->percentage              = $percentage;
		$this->threshold               = $threshold;
		$this->threshold_display_value = $threshold_display_value;
		$this->zero_display_value      = $zero_display_value;
		$this->missing_amount          = $missing_amount;
		$this->notice_text             = $notice_text;
		$this->button_url              = $button_url;
		$this->button_label            = $button_label;
		$this->meta_data               = $meta_data;
	}

	/**
	 * @return bool
	 */
	public function is_show_progress_bar(): bool {
		return $this->show_progress_bar;
	}

	/**
	 * @return float
	 */
	public function get_percentage(): float {
		return $this->percentage;
	}

	/**
	 * @return float
	 */
	public function get_threshold(): float {
		return $this->threshold;
	}

	/**
	 * @return float
	 */
	public function get_missing_amount(): float {
		return $this->missing_amount;
	}

	/**
	 * @return string
	 */
	public function get_notice_text(): string {
		return $this->notice_text;
	}

	/**
	 * @return string
	 */
	public function get_threshold_display_value(): string {
		return $this->threshold_display_value;
	}

	/**
	 * @return string
	 */
	public function get_zero_display_value(): string {
		return $this->zero_display_value;
	}

	/**
	 * @return string
	 */
	public function get_button_url(): string {
		return $this->button_url;
	}

	/**
	 * @return string
	 */
	public function get_button_label(): string {
		return $this->button_label;
	}

	/**
	 * @return array
	 */
	public function get_meta_data(): array {
		return $this->meta_data;
	}


	#[\ReturnTypeWillChange]
	public function jsonSerialize(): array {
		return [
			self::SHOW_PROGRESS_BAR       => $this->is_show_progress_bar(),
			self::PERCENTAGE              => $this->get_percentage(),
			self::THRESHOLD               => $this->get_threshold(),
			self::THRESHOLD_DISPLAY_VALUE => $this->get_threshold_display_value(),
			self::ZERO_DISPLAY_VALUE      => $this->get_zero_display_value(),
			self::MISSING_AMOUNT          => $this->get_missing_amount(),
			self::NOTICE_TEXT             => $this->get_notice_text(),
			self::BUTTON_URL              => $this->get_button_url(),
			self::BUTTON_LABEL            => $this->get_button_label(),
			self::META_DATA               => $this->get_meta_data(),
		];
	}

	/**
	 * Static factory. Creates notice data from array.
	 *
	 * @param array $data
	 *
	 * @return self
	 */
	public static function create_from_array( array $data ): self {
		return new self(
			$data[ self::SHOW_PROGRESS_BAR ] ?? false,
			$data[ self::PERCENTAGE ] ?? 0.0,
			$data[ self::THRESHOLD ] ?? 0.0,
			$data[ self::THRESHOLD_DISPLAY_VALUE ] ?? '',
			$data[ self::ZERO_DISPLAY_VALUE ] ?? '',
			$data[ self::MISSING_AMOUNT ] ?? '0.0',
			$data[ self::NOTICE_TEXT ] ?? '',
			$data[ self::BUTTON_URL ] ?? '',
			$data[ self::BUTTON_LABEL ] ?? '',
			is_array( $data[ self::META_DATA ] ?? null ) ? $data[ self::META_DATA ] : []
		);
	}

}
