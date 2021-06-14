<?php

namespace kosuha606\VirtualModel;

class VirtualModelProviderDecorator extends VirtualModelProvider
{
    private VirtualModelProvider $adaptingProvider;
    private string $adaptingProviderClass;
    private string $providerType;

    /**
     * @param string $providerType
     * @param mixed $adaptingProvider
     */
    public function __construct(string $providerType, $adaptingProvider)
    {
        parent::__construct();
        $this->providerType = $providerType;
        $this->adaptingProvider = $adaptingProvider;
        $this->adaptingProviderClass = get_class($this->adaptingProvider);
    }

    /**
     * Translate call to this class do, to the adapting provider do method
     * @param string $action
     * @param array $arguments
     * @param bool $exception
     * @return false|mixed|void
     */
    public function do(string $action, array $arguments = [], bool $exception = false)
    {
        if ($action === 'type') {
            return $this->providerType;
        }

        return $this->adaptingProvider->do($action, $arguments, $exception);
    }
}
