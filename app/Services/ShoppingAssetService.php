<?php

namespace App\Services;

use App\Models\ShoppingAsset;
use App\Repositories\ShoppingAssetRepository;

class ShoppingAssetService
{
    public function __construct(
        protected ShoppingAssetRepository $shoppingAssetRepository,
    ) {

    }

    public function updateActionShoppingAssets($shoppingAssets)
    {
        $idShoppingAssetNew      = [];
        $idShoppingAssetRotation = [];
        foreach ($shoppingAssets as $shoppingAsset) {
            if (ShoppingAsset::ACTION_NEW === $shoppingAsset['action'] || is_null($shoppingAsset['action'])) {
                $idShoppingAssetNew[] = $shoppingAsset['id'];
            } else {
                $idShoppingAssetRotation[] = $shoppingAsset['id'];
            }
        }

        if (!empty($idShoppingAssetNew)) {
            $this->shoppingAssetRepository->updateShoppingAsset(['id' => $idShoppingAssetNew], ['action' => ShoppingAsset::ACTION_NEW]);
        }

        if (!empty($idShoppingAssetRotation)) {
            $this->shoppingAssetRepository->updateShoppingAsset(['id' => $idShoppingAssetRotation], ['action' => ShoppingAsset::ACTION_ROTATION]);
        }
    }
}
