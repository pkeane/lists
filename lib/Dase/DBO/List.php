<?php

require_once 'Dase/DBO/Autogen/List.php';

class Dase_DBO_List extends Dase_DBO_Autogen_List 
{
    public $items = array();
    public $count;

    public function getItems($omit_hidden = false)
    {
        $items = new Dase_DBO_Item($this->db);
        $items->list_id = $this->id;
        $items->orderBy('timestamp DESC');
        if ($omit_hidden) {
            $items->addWhere('hidden',1,'!=');
        }
        $this->items = $items->findAll(1);
        return $this->items;
    }

    public function asJson()
    {
        $set = array();
        $set['name'] = $this->name;
        $set['timestamp'] = $this->timestamp;
        $set['uniq_id'] = $this->uniq_id;
        $set['items'] = array();
        foreach ($this->getItems() as $item) {
            $set['items'][] = $item->text;
        }
        return Dase_Json::get($set);
    }

    public function expunge()
    {
        foreach ($this->getItems() as $item) {
            $item->delete();
        }
        $this->delete();
    }

    public function getCount()
    {
        $items = new Dase_DBO_Item($this->db);
        $items->list_id = $this->id;
        $items->hidden = false;
        $this->count = $items->findCount();
        return $this->count;
    }
}
