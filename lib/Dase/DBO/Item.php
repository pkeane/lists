<?php

require_once 'Dase/DBO/Autogen/Item.php';

class Dase_DBO_Item extends Dase_DBO_Autogen_Item 
{
    public $list;

    public function getList()
    {
        $list = new Dase_DBO_List($this->db);
        $list->load($this->list_id);
        $this->list = $list;
        return $list;
    }

}
