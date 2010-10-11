{extends file="base.tpl"}

{block name="title"}{$list->name}{/block}

{block name="main"}
<div id="header">
    <form method="post">
        <h2>Add "<span class="highlight">{$list->name}</span>" to:</h2>
        <select name="parent_id">
            <option value="">select one:</option>
            {foreach item=parent_list from=$lists}
            <option value="{$parent_list->id}">{$parent_list->name}</option>
            {/foreach}
        </select>
        <input type="submit" value="add">
    </form>
</div>
<div id="footer">
    <a href="{$app_root}">lists</a> |
    <a href="create">create a list</a> |
    <a href="{$list->uniq_id}/form">add item</a> |
    <a href="{$list->uniq_id}/listbox">add items</a> |
    <a href="{$list->uniq_id}/text">add text</a> |
    <a href="{$list->uniq_id}/update">update</a> |
    <a href="{$list->uniq_id}/add_to_list">add to a list</a> |
    <a href="{$list->uniq_id}">view/share</a> 
</div>
{/block}


