{extends file="base.tpl"}

{block name="title"}lists{/block}

{block name="main"}
<div id="content">
    <div class="search">
        <form action="search" method="get">
            <input type="text" name="q">
            <input type="submit" value="search">
        </form>
    </div>
    <h2>Lists <span class="count">({$lists|@count} item{if 1 != $lists|@count}s{/if})</span></h2>
    <ul id="lists">
        {foreach item=list from=$lists}
        <li>
        <a class="{$list->color}" href="{$list->uniq_id}">{$list->name}</a> 
        <span class="count">({$list->count} item{if 1 != $list->count}s{/if})</span>
        </li>
        {/foreach}
    </ul>
</div>
<div id="footer">
    <a href="{$app_root}">home/lists</a> |
    <a href="create">create a list</a> |
    <a href="update">update</a>
</div>
{/block}


