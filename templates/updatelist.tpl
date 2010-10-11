{extends file="base.tpl"}

{block name="title"}{$list->name}{/block}

{block name="main"}
<div id="content" class="list">
    <h2>{$list->name} <span class="count">({$list->items|@count} item{if 1 != $list->items|@count}s{/if})</span></h2>
    <form method="post" action="{$list->uniq_id}/name">
        <input type="text" name="name" value="{$list->name}">
        <input type="submit" value="update name">
    </form>
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
        <input type="submit" value="hide checked items">
    </form>
    <form method="post" action="{$list->uniq_id}/expunge">
        <input type="submit" value="expunge hidden items">
    </form>
    <form method="post" action="{$list->uniq_id}/color">
        <ul>
            <li>
            <input type="radio" name="color" value="blue" {if 'blue' == $list->color}checked{/if}>
            <a href="{$list->uniq_id}" class="blue">{$list->name}</a>
            </li>
            <li>
            <input type="radio" name="color" value="yellow" {if 'yellow' == $list->color}checked{/if}>
            <a href="{$list->uniq_id}" class="yellow">{$list->name}</a>
            </li>
            <li>
            <input type="radio" name="color" value="green" {if 'green' == $list->color}checked{/if}>
            <a href="{$list->uniq_id}" class="green">{$list->name}</a>
            </li>
            <li>
            <input type="radio" name="color" value="red" {if 'red' == $list->color}checked{/if}>
            <a href="{$list->uniq_id}" class="red">{$list->name}</a>
            </li>
        </ul>
        <input type="submit" value="set color">
    </form>
</div>
<div id="footer">
    <a href="{$app_root}">home/lists</a> |
    <a href="create">create a list</a> |
    <a href="{$list->uniq_id}/form">add item</a> |
    <a href="{$list->uniq_id}/listbox">add items</a> |
    <a href="{$list->uniq_id}/text">add text</a> |
    <a href="{$list->uniq_id}/update">update</a> |
    <a href="{$list->uniq_id}">view/share</a> 
</div>
{/block}


