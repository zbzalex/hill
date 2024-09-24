<?php

namespace Neon;

class EventDispatcher
{
  private $listeners;

  public function __construct()
  {
    $this->listeners = [];
  }

  public function addListener($eventName, $listener)
  {
    if (!isset($this->listeners[$eventName])) {
      $this->listeners[$eventName] = [];
    }

    $this->listeners[$eventName][] = $listener;
  }
  
  public function removeListener($eventName, $listener)
  {
    $listeners = $this->getListeners($eventName);
    $listeners = array_filter($listeners, function ($it) use ($listener) {
      return $it !== $listener;
    });

    $this->listeners[$eventName] = $listener;
  }

  public function getListeners($eventName)
  {
    return isset($this->listeners[$eventName]) ? $this->listeners[$eventName] : [];
  }

  public function dispatch($eventName, Event $event)
  {
    $listeners = $this->getListeners($eventName);

    foreach ($listeners as $listener) {

      call_user_func($listener, $event);

      if ($event->isPrevented()) {
        break;
      }
    }
  }
}
