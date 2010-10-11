{extends file="base.tpl"}

{block name="title"}{$list->name}{/block}

{block name="main"}
<div id="content">
    <h2>{$list->name} <span class="count">({$list->items|@count} item{if 1 != $list->items|@count}s{/if})</span></h2>
    <form method="post">
        <ul id="list">
            {foreach item=item from=$list->items}
            <li>
            <div {if $item->hidden}class="hidden"{/if}>
                <input type="checkbox" {if $item->hidden}checked{/if} name="hide[]" value="{$item->id}">
                {$item->text|markdown}
            </div>
            </li>
            {/foreach}
        </ul>
        <input type="submit" value="update">
    </form>
    </div>
    <div id="footer">
        <a href="{$app_root}">lists</a> |
        <a href="create">create a list</a> |
        <a href="{$list->uniq_id}/form">add item</a> |
        <a href="{$list->uniq_id}/listbox">add items</a> |
        <a href="{$list->uniq_id}/text">add text</a> |
        <a href="{$list->uniq_id}/update">update</a> |
        <a href="{$list->uniq_id}">view/share</a> 
    </div>
    {/block}

