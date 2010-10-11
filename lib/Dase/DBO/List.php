<?php

require_once 'Dase/DBO/Autogen/List.php';

class Dase_DBO_List extends Dase_DBO_Autogen_List 
{
    public $items = array();
    public $count;

    public function getItems()
    {
        $items = new Dase_DBO_Item($this->db);
        $items->list_id = $this->id;
        $items->orderBy('timestamp DESC');
        $this->items = $items->findAll(1);
        return $this->items;
    }

    public function getCount()
    {
        $items = new Dase_DBO_Item($this->db);
        $items->list_id = $this->id;
        $this->count = $items->findCount();
        return $this->count;
    }
}
