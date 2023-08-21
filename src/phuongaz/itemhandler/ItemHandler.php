<?php

declare(strict_types=1);

namespace phuongaz\itemhandler;

use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use ReflectionException;

class ItemHandler {

    /**
     * @param PluginBase $plugin
     * @param Item $item
     * @param Handler[] $handlers
     * @throws ReflectionException
     */
    public static function create(PluginBase $plugin, Item $item, array $handlers) : void {
        foreach ($handlers as $handler) {
            $callback = $handler->getClosure();
            Server::getInstance()->getPluginManager()->registerEvent(
                $handler->getEventClass(),
                function($event) use ($callback, $item)  {
                    $filterItem = false;
                    if(method_exists($event, "getPlayer")) {
                        $handItem = $event->getPlayer()->getInventory()->getItemInHand();
                        if ($handItem->equals($item)) {
                            $filterItem = true;
                        }
                    }
                    if(method_exists($event, "getItem")) {
                        $eventItem = $event->getItem();
                        if ($item->equals($eventItem)) {
                            $filterItem = true;
                        }
                    }
                    if ($filterItem) {
                        ($callback)($event, $item);
                    }
                },
                $handler->getPriority(),
                $plugin,
                $handler->isIgnoreCancelled()
            );
        }
    }

}