<?php

namespace FSVendor\Octolize\Blocks;

class IntegrationData
{
    private const INTEGRATION_NAME = 'integrationName';
    private const SCRIPT_NAME = 'scriptName';
    private const SCRIPT_PATH = 'scriptPath';
    /**
     * @var array <string, string>
     */
    private array $data = [self::SCRIPT_NAME => 'checkout-blocks-integration', self::SCRIPT_PATH => '/build/'];
    public function get_script_data(): array
    {
        return $this->data;
    }
    public function set_integration_name(string $integration_name): IntegrationData
    {
        $this->data[self::INTEGRATION_NAME] = $integration_name;
        return $this;
    }
    public function get_integration_name(): string
    {
        return $this->data[self::INTEGRATION_NAME];
    }
    public function set_script_name(string $script_name): IntegrationData
    {
        $this->data[self::SCRIPT_NAME] = $script_name;
        return $this;
    }
    public function get_script_name(): string
    {
        return $this->data[self::SCRIPT_NAME];
    }
    public function set_script_path(string $script_name): IntegrationData
    {
        $this->data[self::SCRIPT_PATH] = $script_name;
        return $this;
    }
    public function get_script_path(): string
    {
        return $this->data[self::SCRIPT_PATH];
    }
    /**
     * @param string $key
     * @param string|bool|array  $value
     */
    protected function set_data(string $key, $value)
    {
        $this->data[$key] = $value;
    }
    /**
     * @param string $key
     *
     * @return string|bool|array|null
     */
    protected function get_data(string $key)
    {
        return $this->data[$key] ?? null;
    }
}
