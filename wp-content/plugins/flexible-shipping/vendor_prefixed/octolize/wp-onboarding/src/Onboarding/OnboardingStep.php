<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

use FSVendor\WPDesk\Forms\Field;
/**
 * Onboarding step.
 */
class OnboardingStep implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id = 'step_0';
    /**
     * @var int
     */
    private $step = 0;
    /**
     * @var bool
     */
    private $logo = \false;
    /**
     * @var string
     */
    private $title = '';
    /**
     * @var bool
     */
    private $show = \false;
    /**
     * @var string
     */
    private $image = '';
    /**
     * @var string
     */
    private $heading = '';
    /**
     * @var string
     */
    private $sub_heading = '';
    /**
     * @var Field[]
     */
    private $content = [];
    /**
     * @var OnboardingButton[]
     */
    private $buttons = [];
    /**
     * @param string $id
     * @param int $step
     * @param string $title
     * @param Field[] $content
     * @param OnboardingButton[] $buttons
     */
    public function __construct(string $id, int $step, string $title, array $content, array $buttons)
    {
        $this->id = $id;
        $this->step = $step;
        $this->title = $title;
        $this->content = $content;
        $this->buttons = $buttons;
    }
    /**
     * @return string
     */
    public function get_id(): string
    {
        return $this->id;
    }
    /**
     * @return int
     */
    public function get_step(): int
    {
        return $this->step;
    }
    /**
     * @return bool
     */
    public function is_logo(): bool
    {
        return $this->logo;
    }
    /**
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }
    /**
     * @return bool
     */
    public function is_show(): bool
    {
        return $this->show;
    }
    /**
     * @return string
     */
    public function get_image(): string
    {
        return $this->image;
    }
    /**
     * @return string
     */
    public function get_heading(): string
    {
        return $this->heading;
    }
    /**
     * @return string
     */
    public function get_sub_heading(): string
    {
        return $this->sub_heading;
    }
    /**
     * @return OnboardingButton[]
     */
    public function get_buttons(): array
    {
        return $this->buttons;
    }
    /**
     * @param string $id
     * @return OnboardingStep
     */
    public function set_id(string $id): OnboardingStep
    {
        $this->id = $id;
        return $this;
    }
    /**
     * @param int $step
     * @return OnboardingStep
     */
    public function set_step(int $step): OnboardingStep
    {
        $this->step = $step;
        return $this;
    }
    /**
     * @param bool $logo
     * @return OnboardingStep
     */
    public function set_logo(bool $logo): OnboardingStep
    {
        $this->logo = $logo;
        return $this;
    }
    /**
     * @param string $title
     * @return OnboardingStep
     */
    public function set_title(string $title): OnboardingStep
    {
        $this->title = $title;
        return $this;
    }
    /**
     * @param bool $show
     * @return OnboardingStep
     */
    public function set_show(bool $show): OnboardingStep
    {
        $this->show = $show;
        return $this;
    }
    /**
     * @param string $image
     * @return OnboardingStep
     */
    public function set_image(string $image): OnboardingStep
    {
        $this->image = $image;
        return $this;
    }
    /**
     * @param string $heading
     * @return OnboardingStep
     */
    public function set_heading(string $heading): OnboardingStep
    {
        $this->heading = $heading;
        return $this;
    }
    /**
     * @param string $sub_heading
     * @return OnboardingStep
     */
    public function set_sub_heading(string $sub_heading): OnboardingStep
    {
        $this->sub_heading = $sub_heading;
        return $this;
    }
    /**
     * @param OnboardingButton[] $buttons
     * @return OnboardingStep
     */
    public function set_buttons(array $buttons): OnboardingStep
    {
        $this->buttons = $buttons;
        return $this;
    }
    /**
     * @return Field[]
     */
    public function get_content(): array
    {
        return $this->content;
    }
    /**
     * @param Field[] $content
     * @return OnboardingStep
     */
    public function set_content(array $content): OnboardingStep
    {
        $this->content = $content;
        return $this;
    }
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['id' => $this->get_id(), 'logo' => $this->is_logo(), 'title' => $this->get_title(), 'step' => $this->get_step(), 'show' => $this->is_show(), 'image' => $this->get_image(), 'heading' => $this->get_heading(), 'sub_heading' => $this->get_sub_heading(), 'content' => $this->serializeContent(), 'buttons' => $this->get_buttons()];
    }
    private function serializeContent(): array
    {
        $serialized = [];
        foreach ($this->content as $field) {
            $serialized_field = ['type' => $field->get_type(), 'name' => $field->get_name(), 'id' => $field->get_id(), 'class' => $field->get_classes(), 'value' => $field->get_default_value(), 'label' => $field->get_label(), 'autocomplete' => $field->is_attribute_set('autocomplete') ? $field->get_attribute('autocomplete', '') : ''];
            if ($field instanceof Field\CheckboxField) {
                $serialized_field['sublabel'] = $field->get_sublabel();
            }
            $serialized[] = $serialized_field;
        }
        return $serialized;
    }
}
