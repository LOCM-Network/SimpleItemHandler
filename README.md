# SimpleItemHandler
Simple library for handling item events

`Before` and `After` are examples of how to handle events with this library

```php
class MyPlugin extends PluginBase implements Listener {
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getId() === Item::DIAMOND_SWORD) {
            $player->sendMessage("You can't hold this item");
            $event->setCancelled();
        }
    }
    
    public function onBlockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getId() === Item::DIAMOND_SWORD) {
            $player->sendMessage("You can't break blocks with this item");
            $event->setCancelled();
        }
    }
}
```

```php
class MyPlugin extend PluginBase {

    public function onEnable() {
        ItemHandler::create($this, VanillaItems::DIAMOND_SWORD, [
            new Handler(PlayerInteractEvent::class, function(PlayerInteractEvent $event, Item $item) {
                $event->getPlayer()->sendMessage("You can't hold this item");
                return false;
            },
            new Handler(BlockBreakEvent::class, function(BlockBreakEvent $event, Item $item) {
                $event->getPlayer()->sendMessage("You can't break blocks with this item");
                return false;
            }
        ]);
    }
}
```