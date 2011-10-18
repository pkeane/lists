<?php

class Dase_Handler_Default extends Dase_Handler
{
	public $resource_map = array(
		'test' => 'test',
		'create' => 'form',
		'/' => 'lists',
        'update' => 'updateform',
        'search' => 'search',
        'item/{id}/edit' => 'item_edit_form',
		'{id}' => 'list',
		'{id}/add_to_list' => 'add_to_list_form',
		'{id}/form' => 'list_form',
		'{id}/name' => 'list_name',
		'{id}/expunge' => 'expunge_hidden_items',
		'{id}/color' => 'list_color',
		'{id}/access' => 'list_access',
		'{id}/text' => 'list_textform',
		'{id}/update' => 'list_updateform',
		'{id}/listbox' => 'list_listbox',
	);

	protected function setup($r)
	{
        if ($r->method != 'get' || $r->resource != 'list') {
            //require login
            $r->getUser('http');
        }
	}

    public function getTest($r)
    {
		$t = new Dase_Template($r);
		$r->renderResponse($t->fetch('test.tpl'));
    }

    public function getSearch($r)
    {
		$t = new Dase_Template($r);
        $q = $r->get('q');
        $dbh = $this->db->getDbh();
        $lists = array();
        $texts = array();
        $sth = $dbh->prepare('select list.name,item.list_id, item.text from item, list where item.list_id = list.id  and text like ?');
        $sth->execute(array('%'.$q.'%'));
        while ($row = $sth->fetch()) { 
            if (!isset($texts[$row['list_id']])) {
                $texts[$row['list_id']] = array();
            }
            $texts[$row['list_id']][] = $row['text'];
            $list = new Dase_DBO_List($this->db);
            $list->load($row['list_id']);
            $list->getCount();
            $lists[$row['list_id']] = $list;
        }
        $sth2 = $dbh->prepare("select id,name from list where name like ?");
        $sth2->execute(array('%'.$q.'%'));
        while ($row = $sth2->fetch()) { 
            $list = new Dase_DBO_List($this->db);
            $list->load($row['id']);
            $list->getCount();
            $lists[$row['id']] = $list;
        }
		$t->assign('lists',$lists);
		$t->assign('texts',$texts);
		$t->assign('q',$q);
		$r->renderResponse($t->fetch('search.tpl'));
    }

	public function getForm($r) 
	{
		$t = new Dase_Template($r);
		$t->assign('uniq',md5(uniqid()));
		$r->renderResponse($t->fetch('home.tpl'));
	}

	public function postToForm($r) 
	{
        $uniq = $r->get('uniq');
        $name = $r->get('name');
        if (!$name || !$uniq) {
            $r->renderError(400,'missing data');
        }
        $list = new Dase_DBO_List($this->db);
        $list->name = $name;
        $list->uniq_id = $uniq;
        $list->hidden = false;
        $list->color = 'blue';
        $list->is_public = true;
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

    public function deleteList($r)
    {
        $list = new Dase_DBO_List($this->db);
        if ($r->get('id')) {
            $list->uniq_id = $r->get('id');
        }
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        $list->expunge();
        $r->renderResponse('list deleted');
    }

    public function getAddToListForm($r) 
	{
		$t = new Dase_Template($r);
        $list = new Dase_DBO_List($this->db);
        if ($r->get('id')) {
            $list->uniq_id = $r->get('id');
        }
        if (!$list->findOne()) {
            $r->renderError(404);
        }
		$t->assign('list',$list);
        $lists = new Dase_DBO_List($this->db);
        $lists->orderBy('timestamp DESC');
        $lists->hidden = false;
        $set = array();
        foreach ($lists->findAll(1) as $l) {
            $l->getCount();
            $set[] = $l;
        }
		$t->assign('lists',$set);
		$r->renderResponse($t->fetch('add_to_list.tpl'));
	}
   
	public function postToAddToListForm($r) 
	{
        $child_list = new Dase_DBO_List($this->db);
        if ($r->get('id')) {
            $child_list->uniq_id = $r->get('id');
        }
        if (!$child_list->findOne()) {
            $r->renderError(404);
        }
        $parent_list = new Dase_DBO_List($this->db);
        if (!$parent_list->load($r->get('parent_id'))) {
            $r->renderError(404);
        }
        $url = $r->app_root.'/'.$child_list->uniq_id;
        $text = '['.$child_list->name.']('.$url.')';
        $item = new Dase_DBO_Item($this->db);
        $item->list_id = $parent_list->id;
        $item->text = $text;
        $item->hidden = false;
        $item->insert();
		$r->renderRedirect($parent_list->uniq_id);
	}
   
	public function getList($r) 
	{
		$t = new Dase_Template($r);
        $list = new Dase_DBO_List($this->db);
        if ($r->get('id')) {
            $list->uniq_id = $r->get('id');
        }
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        if (!$list->is_public) {
            $r->getUser('http');
        }
        $list->getItems(1);
		$t->assign('list',$list);
        if ($r->get('show_form')) {
            $t->assign('show_form',1);
        }
        if ($r->get('textarea')) {
            $t->assign('textarea',1);
        }
        if ($r->get('listbox')) {
            $t->assign('listbox',1);
        }
		$r->renderResponse($t->fetch('list.tpl'));
	}
   
	public function getListJson($r) 
	{
        $list = new Dase_DBO_List($this->db);
        if ($r->get('id')) {
            $list->uniq_id = $r->get('id');
        }
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        if($r->get('callback')){
            $r->renderResponse($r->get('callback').'('.$list->asJson().');');
        }
		$r->renderResponse($list->asJson());
	}
   
	public function postToListUpdateform($r) 
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        if (isset($_POST['hide'])) {
            $hide_set = $_POST['hide'];
        } else {
            $hide_set = array();
        }
        foreach ($list->getItems() as $item) {
            if (in_array($item->id,$hide_set)) {
                $item->hidden = true;
            } else {
                $item->hidden = false;
            }
            $item->update();
        }
		$r->renderRedirect($list->uniq_id);
    }

	public function postToUpdateform($r) 
    {
        if (isset($_POST['hide'])) {
            $hide_set = $_POST['hide'];
        } else {
            $hide_set = array();
        }
        $lists = new Dase_DBO_List($this->db);
        foreach ($lists->findAll(1) as $l) {
            if (in_array($l->id,$hide_set)) {
                $l->hidden = true;
            } else {
                $l->hidden = false;
            }
            $l->update();
        }
		$r->renderRedirect('/');
    }

	public function getListUpdateform($r) 
	{
		$t = new Dase_Template($r);
        $list = new Dase_DBO_List($this->db);
        if ($r->get('id')) {
            $list->uniq_id = $r->get('id');
        }
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        $list->getItems();
		$t->assign('list',$list);
		$r->renderResponse($t->fetch('updatelist.tpl'));
	}
   
	public function getLists($r) 
	{
		$t = new Dase_Template($r);
        $lists = new Dase_DBO_List($this->db);
        $lists->orderBy('color DESC, name');
        $lists->hidden = false;
        $set = array();
        foreach ($lists->findAll(1) as $l) {
            $l->getCount();
            $set[] = $l;
        }
		$t->assign('lists',$set);
		$r->renderResponse($t->fetch('lists.tpl'));
	}
   
	public function getUpdateform($r) 
	{
		$t = new Dase_Template($r);
        $lists = new Dase_DBO_List($this->db);
        $lists->orderBy('color DESC, name');
        $set = array();
        foreach ($lists->findAll(1) as $l) {
            $l->getCount();
            $set[] = $l;
        }
		$t->assign('lists',$set);
		$r->renderResponse($t->fetch('updatelists.tpl'));
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
                $title = $text;
                $input = @file_get_contents(trim($text)) or die("Could not access file: $text");
                $regexp = "<title>(.*)<\/title>"; 
                if(preg_match("/$regexp/siU", $input, $matches)) { 
                    $title = $matches[1];
                }
                $text = '['.$title.']('.$text.')';
            }
        }
        $item = new Dase_DBO_Item($this->db);
        $item->list_id = $list->id;
        $item->text = $text;
        $item->hidden = false;
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
            $item->hidden = false;
            $item->insert();
        }
		$r->renderRedirect($list->uniq_id.'/form');
    }

    public function postToListColor($r)
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        $list->color = $r->get('color');
        $list->update();
		$r->renderRedirect('/');
    }

    public function postToExpungeHiddenItems($r)
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        foreach ($list->getItems() as $item) {
            if ($item->hidden) {
                $item->delete();
            }
        }
		$r->renderRedirect($list->uniq_id);
    }

    public function postToListName($r)
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        if ($r->get('name')) {
            $list->name = $r->get('name');
            $list->update();
        }
		$r->renderRedirect($list->uniq_id);
    }

    public function postToListAccess($r)
    {
        $list = new Dase_DBO_List($this->db);
        $list->uniq_id = $r->get('id');
        if (!$list->findOne()) {
            $r->renderError(404);
        }
        if ('public' == $r->get('access')) {
            $list->is_public = 1;
            $list->update();
        }
        if ('private' == $r->get('access')) {
            $list->is_public = 0;
            $list->update();
        }
		$r->renderRedirect($list->uniq_id);
    }

    public function getItemEditForm($r) 
    {
		$t = new Dase_Template($r);
        $item = new Dase_DBO_Item($this->db);
        $item->load($r->get('id'));
		$t->assign('item',$item);
		$r->renderResponse($t->fetch('item_edit_form.tpl'));
    }

    public function postToItemEditForm($r) 
    {
        $item = new Dase_DBO_Item($this->db);
        $item->load($r->get('id'));
        $item->text = $r->get('text');
        $item->update();
        $list = $item->getList();
		$r->renderRedirect($list->uniq_id);
    }

}

