<?php


namespace kosuha606\VirtualModel;

use LogicException;

/**
 * This trait provides actions what can be used
 * instead of regular class methods, it is useful
 * because of flexibility and reusing and extensibility
 *
 * If you use this trait do not use regular class methods
 */
trait ActionMethodTrait
{
    protected array $actions = [];
    protected array $parentActions = [];
    protected string $lastError = '';

    /**
     * @param array $actions
     * @param bool $merge
     */
    public function specifyActions(array $actions, bool $merge = false): void
    {
        if ($merge) {
            $this->parentActions = $this->actions;
            $this->actions = array_merge($this->actions, $actions);
            return;
        }

        $this->actions = $actions;
    }

    /**
     * @param string $action
     * @return bool
     */
    public function has(string $action): bool
    {
        return isset($this->actions[$action]);
    }

    /**
     * @param string $action
     * @param array $arguments
     * @param bool $exception
     * @return false|mixed
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function do(string $action, array $arguments = [], bool $exception = false)
    {
        $this->lastError = '';

        if (!$this->has($action)) {
            $className = get_class($this);
            $errorMessage = "Action $action is not defined $className";
            $this->lastError = $errorMessage;

            if ($exception) {
                throw new LogicException($errorMessage);
            }

            return false;
        }

        return call_user_func_array($this->actions[$action], $arguments);
    }

    /**
     * @param string $action
     * @param array $arguments
     * @return false
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function doParent(string $action, array $arguments)
    {
        if (!isset($this->parentActions[$action])) {
            return false;
        }

        return call_user_func_array($this->parentActions[$action], $arguments);
    }
}
