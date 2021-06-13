<?php

namespace kosuha606\VirtualModel;

use LogicException;

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
        $this->providerType = $providerType;
        $this->adaptingProvider = $adaptingProvider;
        $this->adaptingProviderClass = get_class($this->adaptingProvider);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->providerType;
    }

    /**
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    private function translateMethod($name, $arguments): bool
    {
        if (is_callable($this->adaptingProvider)) {
            $this->adaptingProvider = call_user_func($this->adaptingProvider);
        }

        $callback = [$this->adaptingProvider, $name];

        if (is_callable($callback)) {
            $arguments = array_values($arguments);

            return call_user_func_array($callback, $arguments);
        }

        throw new LogicException("Provider {$this->adaptingProviderClass} has no method {$name}");
    }

    /**
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    public function __call($name, $arguments): bool
    {
        return $this->translateMethod($name, $arguments);
    }

    /**
     * @param string $modelClass
     * @param array $attributes
     * @return false|VirtualModelEntity|mixed
     */
    public function buildModel(string $modelClass, array $attributes = []): VirtualModelEntity
    {
        return $this->translateMethod('buildModel', [
            'modelClass' => $modelClass,
            'attributes' => $attributes
        ]);
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @return false|VirtualModelEntity|mixed
     */
    public function one(string $modelClass, array $config): VirtualModelEntity
    {
        return $this->translateMethod('one', [
            'modelClass' => $modelClass,
            'config' => $config
        ]);
    }

    /**
     * @param string $modelClass
     * @param array $config
     * @param null $indexBy
     * @return array|false|mixed
     */
    public function many(string $modelClass, array $config, $indexBy = null): array
    {
        return $this->translateMethod('many', [
            'modelClass' => $modelClass,
            'config' => $config,
            'indexBy' => $indexBy,
        ]);
    }

    /**
     * @param VirtualModelEntity $model
     * @return false|mixed|void
     */
    public function persist(VirtualModelEntity $model): bool
    {
        return $this->translateMethod('persist', [
            'model' => $model,
        ]);
    }

    /**
     * @return false|mixed|null
     */
    public function flush(): ?bool
    {
        return $this->translateMethod('flush', []);
    }

    /**
     * @param VirtualModelEntity $model
     * @return false|mixed|void
     */
    public function delete(VirtualModelEntity $model): bool
    {
        return $this->translateMethod('delete', [
            'model' => $model,
        ]);
    }

    public function getAvailableModelClasses(): array
    {
        return $this->translateMethod('getAvailableModelClasses', []);
    }
}
