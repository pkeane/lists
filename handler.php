<?php

class Dase_Handler_Default extends Dase_Handler
{
	public $resource_map = array(
		'/' => 'home',
		'all' => 'lists',
		'{id}' => 'list',
		'{id}/form' => 'list_form',
		'{id}/text' => 'list_textform',
		'{id}/listbox' => 'list_listbox',
	);

	protected function setup($r)
	{
	}

	public function getHome($r) 
	{
		$t = new Dase_Template($r);
		$t->assign('uniq',md5(uniqid()));
		$r->renderResponse($t->fetch('home.tpl'));
	}

	public function postToHome($r) 
	{
        $uniq = $r->get('uniq');
        $name = $r->get('name');
        if (!$name || !$uniq) {
            $r->renderError(400,'missing data');
        }
        $list = new Dase_DBO_List($this->db);
        $list->name = $name;
        $list->uniq_id = $uniq;
        $list->insert();
		$r->renderRedirect($uniq.'/form');
	}

	public function getListForm($r) 
    {
        $r->set('show_form',1);
        $this->getList($r);
    }

	public function getListTextform($r) 
    {
        $r->set('show_form',1);
        $r->set('textarea',1);
        $this->getList($r);
    }

	public function postToListTextform($r) 
    {
        $this->postToListForm($r);
    }

	public function getListLinkform($r) 
    {
        $r->set('show_form',1);
        $r->set('textarea',1);
        $r->set('listbox',1);
        $this->getList($r);
    }

	public function getListListbox($r) 
    {
        $r->set('show_form',1);
        $r->set('textarea',1);
        $r->set('listbox',1);
        $this->getList($r);
    }

	public function getList($r) 
	{
		$t = new Dase_Template($r);
        $list = new Dase_DBO_List($this->db);
        if ($r->get('ascii_name')) {
            $list->name = $r->get('ascii_name');
        }
        if ($r->get('id')) {
            $list->uniq_id = $r->get('id');
        }
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        $list->getItems();
		$t->assign('list',$list);
        if ($r->get('show_form')) {
            $t->assign('show_form',1);
        }
        if ($r->get('textarea')) {
            $t->assign('textarea',1);
        }
		$r->renderResponse($t->fetch('list.tpl'));
	}
   
	public function getLists($r) 
	{
		$t = new Dase_Template($r);
        $lists = new Dase_DBO_List($this->db);
        $lists->orderBy('timestamp DESC');
        $set = array();
        foreach ($lists->findAll(1) as $l) {
            $l->getCount();
            $set[] = $l;
        }
		$t->assign('lists',$set);
		$r->renderResponse($t->fetch('lists.tpl'));
	}
   
    public function postToListForm($r)
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        $text = $r->get('text');
        if (!$text) {
            $r->renderError(400,'missing data');
        }
        if ('http' == substr($text,0,4)) {
            $parts = explode('|',$text);
            if (isset($parts[1])) {
                $text = '['.$parts[1].']('.$parts[0].')';
            } else {
                $text = '['.$text.']('.$text.')';
            }
        }
        $item = new Dase_DBO_Item($this->db);
        $item->list_id = $list->id;
        $item->text = $text;
        $item->insert();
		$r->renderRedirect($list->uniq_id.'/form');
    }

    public function postToListListbox($r)
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        $text = $r->get('text');
        if (!$text) {
            $r->renderError(400,'missing data');
        }
        foreach (explode("\n",$text) as $item_text) {
            $item_text = trim($item_text);
            $item = new Dase_DBO_Item($this->db);
            $item->list_id = $list->id;
            $item->text = $item_text;
            $item->insert();
        }
		$r->renderRedirect($list->uniq_id.'/form');
    }
}

