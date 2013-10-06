<?php
function buildMenu ($items, $resources, $level, $path)
{ 
	foreach($items as $item)
	{
		
		if($item->hasSubitems()) {	
			$href = "#";
			foreach ($resources as $resource) {
				if ($item->getIdentifierref() == $resource->getIdentifier()) {
					$href = $resource->getHref();
				}	
			}
			echo "<li><a class=\"item level$level\" rel=\"".$item->getIdentifier()."\" href=\"$path/$href\" target=\"windowFrame\"><span class='icon icon-share-alt'></span> " . $item->getTitle() . "</a></li>";	
			$level++;	
			buildMenu($item->getSubitems(), $resources, $level, $path);
			$level = 0;
		} else {
			$href = "#";
			foreach ($resources as $resource) {
				if ($item->getIdentifierref() == $resource->getIdentifier()) {
					$href = $resource->getHref();
				}	
			}
			echo "<li><a class=\"item level$level\" rel=\"".$item->getIdentifier()."\" href=\"$path/$href\" target=\"windowFrame\"><span class='icon icon-share-alt'></span> " . $item->getTitle() . "</a></li>";	
		}
	}
}

function getDefault ($items, $resources) {
	foreach ($items as $item) {
		foreach ($resources as $resource) {
			if ($item->getIdentifierref() == $resource->getIdentifier()) {
				$href = $resource->getHref();
				return $href;
			}
		}
	}
}
?>