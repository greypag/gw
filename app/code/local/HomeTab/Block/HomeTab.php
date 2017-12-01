<?php
class Hometab_Block_Hometab extends Mage_Catalog_Block_Product_Abstract
{
    public function getBestValuebProducts() {
        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('is_best_value', array(1))
            ->addAttributeToFilter('status', array(1));
        return $products;
    }

    public function getWeddingandPartyProducts() {
        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('is_wedding_party', array(1))
            ->addAttributeToFilter('status', array(1));
        return $products;
    }

    public function getPremiumProducts() {
        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('is_premium', array(1))
            ->addAttributeToFilter('status', array(1));
        return $products;
    }

    public function getNewArrivalProducts() {
        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('is_new_arrival', array(1))
            ->addAttributeToFilter('status', array(1));
        return $products;
    }
}
