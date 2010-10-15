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
    <h2>Search: "{$q}" <span class="count">({$lists|@count} item{if 1 != $lists|@count}s{/if})</span></h2>
    <ul id="lists">
        {foreach key=list_id item=list from=$lists}
        <li>
        <a class="{$list->color}" href="{$list->uniq_id}">{$list->name}</a> 
        <span class="count">({$list->count} item{if 1 != $list->count}s{/if})</span>
        <ul class="searchsub">
            {foreach item=txt from=$texts.$list_id}
            <li>{$txt|markdown}</li>
            {/foreach}
        </ul>
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


