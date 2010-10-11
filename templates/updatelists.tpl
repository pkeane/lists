{extends file="base.tpl"}

{block name="title"}lists{/block}

{block name="main"}
<div id="content">
    <h2>Lists <span class="count">({$lists|@count} item{if 1 != $lists|@count}s{/if})</span></h2>
    <form method="post">
        <ul>
            {foreach item=list from=$lists}
            <li>
            <div {if $list->hidden}class="hidden"{/if}>
                <input type="checkbox" {if $list->hidden}checked{/if} name="hide[]" value="{$list->id}">
                <a href="{$list->uniq_id}">{$list->name}</a> 
                <span class="count">({$list->count} item{if 1 != $list->count}s{/if})</span>
            </div>
            </li>
            {/foreach}
        </ul>
        <input type="submit" value="update">
    </form>
</div>
<div id="footer">
    <a href="create">create a list</a>
</div>
{/block}


