<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use jojoe77777\FormAPI;

class ClaimSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.claim");
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) > 1) {
            return false;
        }
        $name = "";
        if (isset($args[0])) {
            $name = $args[0];
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner != "") {
            if ($plot->owner === $sender->getName()) {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.yourplot"));
            } else {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.alreadyclaimed", [$plot->owner]));
            }
            return true;
        }

        $maxPlots = $this->getPlugin()->getMaxPlotsOfPlayer($player);
        $plotsOfPlayer = count($this->getPlugin()->getProvider()->getPlotsByOwner($player->getName()));
        if ($plotsOfPlayer >= $maxPlots) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.maxplots", [$maxPlots]));
            return true;
        }

        $plotLevel = $this->getPlugin()->getLevelSettings($plot->levelName);
        $economy = $this->getPlugin()->getEconomyProvider();
        if ($economy !== null and !$economy->reduceMoney($player, $plotLevel->claimPrice)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.nomoney"));
            return true;
        }

        $plot->owner = $sender->getName();
        $plot->name = $name;
        if ($this->getPlugin()->getProvider()->savePlot($plot)) {
            $sender->sendMessage($this->translateString("claim.success"));

			$sender->getInventory()->addItem(Item::get(8,0,2));
			$sender->getInventory()->addItem(Item::get(85,0,2));
			$sender->getInventory()->addItem(Item::get(50,0,2));
			$sender->getInventory()->addItem(Item::get(338,0,1));
			$sender->getInventory()->addItem(Item::get(296,0,1));
			$sender->getInventory()->addItem(Item::get(6,5,2));
			$sender->getInventory()->addItem(Item::get(351,15,2));
			$sender->getInventory()->addItem(Item::get(364,0,2));
			$sender->getInventory()->addItem(Item::get(2,0,2));
			$sender->getInventory()->addItem(Item::get(322,0,2));
			$sender->getInventory()->addItem(Item::get(278,0,2));
			$sender->getInventory()->addItem(Item::get(306,0,1));
$sender->getInventory()->addItem(Item::get(307,0,1));
$sender->getInventory()->addItem(Item::get(309,0,1));
$sender->getInventory()->addItem(Item::get(308,0,1));
			$sender->sendMessage("§b[•Cresix•]§aĐã Thêm Đồ Dùng Vào Túi Đồ Của Bạn!");
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
		 $api = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
						$form = $api->createCustomForm(function (Player $player, $data){
                });
                    $form->setTitle("§l§b♦§a SkyBlock §b♦");
                    $form->addLabel("§b§l───────────────────");
                    $form->addLabel("§l§c•chúc các bạn chơi vui vẻ nha");
                    $form->addLabel("§b§l───────────────────");
					$form->sendToPlayer($sender);
        return true;
    }
}