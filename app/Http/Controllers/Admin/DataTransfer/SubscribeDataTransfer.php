<?php


namespace App\Http\Controllers\Admin\DataTransfer;


class SubscribeDataTransfer
{
    public $customerId;
    public $subscribeId;
    public $startDate;
    public $countDays;
    public $customer;
    public $listIngredientId = [];
    public $listIngredientName = [];
    public $listProteinId = [];
    public $listProteinName = [];
    public $listProteinCuisineId = [];
    public $listProteinCuisineName = [];
    public $listCarbId = [];
    public $listCarbName = [];
    public $listCarbCuisineId = [];
    public $listCarbCuisineName = [];
    public  $listGroupMenu = [];
    public $listPackage = [];

    public $newData = [];

    public $group1 = [];
    public $group2 = [];

    public static function make()
    {
        return new static();
    }

    public function setListIngredientId($IngredientId)
    {
        $this->listIngredientId[] = $IngredientId;
        return $this;
    }

    public function getGroup1()
    {
        return $this->group1;
    }

    public function setGroup1($group1)
    {
        $this->group1[] = $group1;
        return $this;
    }

    public function getGroup2()
    {
        return $this->group2;
    }

    public function setGroup2($group2)
    {
        $this->group2[] = $group2;
        return $this;
    }

    public function getListIngredientId()
    {
        return $this->listIngredientId;
    }

    public function setNewData($newData)
    {
        $this->newData[] = $newData;
        return $this;
    }

    public function getNewData()
    {
        return $this->newData;
    }

    public function setListPackage($package)
    {
        $this->listPackage[] = $package;
        return $this;
    }

    public function getListPackage()
    {
        return $this->listPackage;
    }

    public function setListIngredientName($IngredientName)
    {
        $this->listIngredientName[] = $IngredientName;
        return $this;
    }

    public function getListIngredientName()
    {
        return $this->listIngredientName;
    }


    public function setListProteinId($proteinId)
    {
        $this->listProteinId[] = $proteinId;
        return $this;
    }

    public function getListProteinId()
    {
        return $this->listProteinId;
    }

    public function setListProteinName($proteinName)
    {
        $this->listProteinName[] = $proteinName;
        return $this;
    }

    public function getListProteinName()
    {
        return $this->listProteinName;
    }

    public function setListProteinCuisineId($cuisineId)
    {
        $this->listProteinCuisineId[] = $cuisineId;
        return $this;
    }

    public function getListProteinCuisineId()
    {
        return $this->listProteinCuisineId;
    }

    public function setListProteinCuisineName($cuisineName)
    {
        $this->listProteinCuisineName[] = $cuisineName;
        return $this;
    }

    public function getListProteinCuisineName()
    {
        return $this->listProteinCuisineName;
    }

    public function setListCarbId($carbId)
    {
        $this->listCarbId[] = $carbId;
        return $this;
    }

    public function getListCarbId()
    {
        return $this->listCarbId;
    }

    public function setListCarbName($carbName)
    {
        $this->listCarbName[] = $carbName;
        return $this;
    }

    public function getListCarbName()
    {
        return $this->listCarbName;
    }

    public function setListCarbCuisineId($cuisineId)
    {
        $this->listCarbCuisineId[] = $cuisineId;
        return $this;
    }

    public function getListCarbCuisineId()
    {
        return $this->listCarbCuisineId;
    }

    public function setListCarbCuisineName($cuisineName)
    {
        $this->listCarbCuisineName[] = $cuisineName;
        return $this;
    }

    public function getListCarbCuisineName()
    {
        return $this->listCarbCuisineName;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function setCountDays($count)
    {
        $this->countDays = $count;
        return $this;
    }

    public function setSubscribeId($subscribeId)
    {
        $this->subscribeId = $subscribeId;
        return $this;
    }

    public function getSubscribeId()
    {
        return $this->subscribeId;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getCountDays()
    {
        return $this->countDays;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}
