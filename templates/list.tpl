{extends file="base.tpl"}

{block name="title"}{$list->name}{/block}

{block name="main"}
<div id="header" {if '1' != $show_form}class="hide"{/if}>
    <form method="post">
        {if '1' == $textarea}
        {if '1' == $listbox}
        enter multiple items separated by line breaks
        {/if}
        <textarea name="text"></textarea>
        {else}
        <input type="text" name="text">
        {/if}
        <input type="submit" value="add">
    </form>
    <p>
    <a href="{$list->uniq_id}/form">add item</a> |
    <a href="{$list->uniq_id}/text">add text</a> |
    <a href="{$list->uniq_id}/listbox">add multiple items</a>
    </p>
</div>
<div id="content">
    <h2>{$list->name} <span class="count">({$list->items|@count} item{if 1 != $list->items|@count}s{/if})</span></h2>
    <ul id="list">
        {foreach item=item from=$list->items}
        <li>
        {$item->text|markdown}
        </li>
        {/foreach}
    </ul>
</div>
<div id="footer">
    <a href="{$app_root}">home/lists</a> |
    <a href="create">create a list</a> |
    <a href="{$list->uniq_id}/form">add item</a> |
    <a href="{$list->uniq_id}/update">update</a> |
    <a href="{$list->uniq_id}/add_to_list">add to list</a> |
    <a href="{$list->uniq_id}">view</a> 
</div>
{/block}


