<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

/**
 * Onboarding button.
 */
class OnboardingButton implements \JsonSerializable
{
    const BTN_SUCCESS = 'btn-success';
    const BTN_SECONDARY = 'btn-secondary';
    const BTN_LINK = 'btn-link';
    const TYPE_STEP = 'step';
    const TYPE_AJAX = 'ajax';
    const TYPE_CLOSE = 'close';
    /**
     * @var string
     */
    private $label = 'button';
    /**
     * @var string
     */
    private $type = self::TYPE_STEP;
    /**
     * @var string
     */
    private $popup = 'step_0';
    /**
     * @var string
     */
    private $classes = self::BTN_SUCCESS;
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
    /**
     * @param string $label
     * @return OnboardingButton
     */
    public function set_label(string $label): OnboardingButton
    {
        $this->label = $label;
        return $this;
    }
    /**
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }
    /**
     * @param string $type
     * @return OnboardingButton
     */
    public function set_type(string $type): OnboardingButton
    {
        $this->type = $type;
        return $this;
    }
    /**
     * @return string
     */
    public function get_popup(): string
    {
        return $this->popup;
    }
    /**
     * @param string $popup
     * @return OnboardingButton
     */
    public function set_popup(string $popup): OnboardingButton
    {
        $this->popup = $popup;
        return $this;
    }
    /**
     * @return string
     */
    public function get_classes(): string
    {
        return $this->classes;
    }
    /**
     * @param string $classes
     * @return OnboardingButton
     */
    public function set_classes(string $classes): OnboardingButton
    {
        $this->classes = $classes;
        return $this;
    }
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['label' => $this->getLabel(), 'type' => $this->get_type(), 'popup' => $this->get_popup(), 'classes' => $this->get_classes()];
    }
}
