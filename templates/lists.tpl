{extends file="base.tpl"}

{block name="title"}{$list->name}{/block}

{block name="main"}
<div id="content">
    <h2>Lists <span class="count">({$lists|@count} item{if 1 != $lists|@count}s{/if})</span></h2>
    <ul>
        {foreach item=list from=$lists}
        <li><a href="{$list->uniq_id}">{$list->name}</a> <span class="count">({$list->count} item{if 1 != $list->count}s{/if})</span></li>
        {/foreach}
    </ul>
</div>
<div id="footer">
    <a href="{$app_root}">home</a>
</div>
{/block}


